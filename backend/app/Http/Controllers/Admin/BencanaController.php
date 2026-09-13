<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Bencana;
use App\Models\BencanaPending;
use App\Models\Bpbd;
use App\Models\Posko;
use App\Models\StokPosko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BencanaController extends Controller
{
    public function index()
    {
        $regions = Bpbd::pluck('nama_kabupaten_kota')->filter()->toArray();

        $pendingQuery = BencanaPending::where('status', 'pending');
        if (!empty($regions)) {
            $pendingQuery->where(function ($query) use ($regions) {
                foreach ($regions as $region) {
                    $query->orWhereRaw('LOWER(wilayah) LIKE ?', ['%' . strtolower(trim($region)) . '%']);
                }
            });
        }
        $pendingDisasters = $pendingQuery->orderBy('waktu_kejadian', 'desc')->get();

        $todayQuery = BencanaPending::whereDate('created_at', today());
        if (!empty($regions)) {
            $todayQuery->where(function ($query) use ($regions) {
                foreach ($regions as $region) {
                    $query->orWhereRaw('LOWER(wilayah) LIKE ?', ['%' . strtolower(trim($region)) . '%']);
                }
            });
        }

        $activeDisasters = Bencana::where('status', 'sedang_berjalan')
            ->orderBy('tanggal_aktivasi', 'desc')
            ->get();

        $completedDisasters = Bencana::where('status', 'selesai')
            ->orderBy('tanggal_selesai', 'desc')
            ->get();

        $stats = [
            'terdeteksi_hari_ini' => $todayQuery->count(), 
            'perlu_validasi'      => $pendingDisasters->count(),
            'sedang_berjalan'     => $activeDisasters->count(),
            'selesai'             => $completedDisasters->count(),
        ];

        return view('dashboard.admin.bencana.index', compact(
            'pendingDisasters', 
            'activeDisasters', 
            'completedDisasters', 
            'stats'
        ));
    }

    /**
     * Langkah 1: Validasi TRC & SK Darurat + Autoinject Rekomendasi Stok Awal
     */
    public function validateAndActivate(Request $request, $pendingId)
    {
        $request->validate([
            'estimasi_pengungsi_awal' => 'required|integer|min:1',
            'sk_status_darurat'       => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $pending = BencanaPending::findOrFail($pendingId);

        DB::beginTransaction();
        try {
            $skPath = $request->file('sk_status_darurat')->store('sk_darurat', 'public');

            $bencana = Bencana::create([
                'jenis_bencana'             => $pending->jenis_bencana,
                'lokasi_bencana'            => $pending->wilayah,
                'koordinat_operasional_lat' => $pending->latitude,
                'koordinat_operasional_lng' => $pending->longitude,
                'estimasi_pengungsi_awal'   => $request->estimasi_pengungsi_awal,
                'sk_status_darurat_path'    => $skPath,
                'tanggal_aktivasi'          => now(),
                'status'                    => 'menunggu_posko',
            ]);

            $pending->update(['status' => 'validated']);

            // --- OTOMATIS GENERATE STOK REKOMENDASI KE POSKO KOMANDO ---
            $totalPengungsi = $request->estimasi_pengungsi_awal;
            $rekomendasiStok = [
                'Beras'           => ceil($totalPengungsi * 0.4 * 7),  // 0.4kg/hari x 7 hari
                'Air Minum'       => ceil($totalPengungsi * 0.5),      // Dus
                'Makanan Kaleng'  => ceil($totalPengungsi * 2),        // Pack
                'Makanan Bayi'    => ceil($totalPengungsi * 0.2),
                'Minyak Goreng'   => ceil($totalPengungsi * 0.1),      // Liter
                'Popok Bayi'      => ceil($totalPengungsi * 0.5),
                'Popok Dewasa'    => ceil($totalPengungsi * 0.2),
                'Pembalut Wanita' => ceil($totalPengungsi * 0.3),
                'Hygiene Kit'     => ceil($totalPengungsi * 0.25),
                'Selimut'         => ceil($totalPengungsi * 0.8),
                'Matras Terpal'   => ceil($totalPengungsi * 0.5),
                'Obat P3K'        => ceil($totalPengungsi * 0.15),
            ];

            // Cari Seluruh Posko Komando
            $poskosKomando = Posko::where('tipe_posko', 'komando')->get();

            foreach ($poskosKomando as $posko) {
                foreach ($rekomendasiStok as $namaBarang => $jumlah) {
                    $barang = Barang::firstOrCreate(['nama_barang' => $namaBarang]);
                    
                    StokPosko::updateOrCreate(
                        [
                            'posko_id'  => $posko->id,
                            'barang_id' => $barang->id,
                        ],
                        [
                            'jumlah_stok' => DB::raw("COALESCE(jumlah_stok, 0) + {$jumlah}")
                        ]
                    );
                }
            }

            DB::commit();

            return redirect()->route('admin.posko.create', ['bencana_id' => $bencana->id])
                ->with('success', 'Data kaji TRC & SK Darurat divalidasi. Rekomendasi stok awal otomatis disiapkan untuk Posko Komando.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses validasi: ' . $e->getMessage());
        }
    }

    public function rejectPending($pendingId)
    {
        $pending = BencanaPending::findOrFail($pendingId);
        $pending->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Deteksi bencana berhasil diabaikan.');
    }

    public function finish($id)
    {
        $bencana = Bencana::findOrFail($id);

        DB::beginTransaction();
        try {
            $bencana->update([
                'status'          => 'selesai',
                'tanggal_selesai' => now(),
            ]);

            Posko::komando()
                ->where('bencana_id', $bencana->id)
                ->update([
                    'status'     => 'terdaftar_nonaktif',
                    'bencana_id' => null,
                ]);

            Posko::subPosko()
                ->where('bencana_id', $bencana->id)
                ->update([
                    'status' => 'ditutup',
                ]);

            DB::commit();

            return redirect()->back()->with('success', 'Operasi bencana diselesaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyelesaikan bencana: ' . $e->getMessage());
        }
    }
}
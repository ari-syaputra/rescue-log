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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BencanaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userBpbd = $user->bpbd;
        $regionName = $userBpbd ? strtolower(trim($userBpbd->nama_kabupaten_kota)) : null;

        // Ambil semua bencana pending
        $pendingQuery = BencanaPending::where('status', 'pending');

        if ($regionName) {
            $cleanRegion = str_replace(['kabupaten ', 'kota '], '', $regionName);

            $pendingQuery->where(function ($query) use ($regionName, $cleanRegion) {
                $query->orWhereRaw('LOWER(wilayah) LIKE ?', ['%' . $regionName . '%'])
                      ->orWhereRaw('LOWER(wilayah) LIKE ?', ['%' . $cleanRegion . '%'])
                      ->orWhere('external_id', 'LIKE', 'MANUAL-%');
            });
        }

        $pendingDisasters = $pendingQuery->orderBy('waktu_kejadian', 'desc')->get();

        $todayQuery = BencanaPending::whereDate('created_at', today());

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
        ], [
            'estimasi_pengungsi_awal.required' => 'Estimasi pengungsi awal wajib diisi.',
            'estimasi_pengungsi_awal.integer'  => 'Estimasi pengungsi harus berupa angka.',
            'sk_status_darurat.required'       => 'Dokumen SK Status Darurat wajib diunggah.',
            'sk_status_darurat.mimes'          => 'Dokumen SK harus berformat PDF, JPG, JPEG, atau PNG.',
            'sk_status_darurat.max'            => 'Ukuran file SK Status Darurat maksimal 5MB.',
        ]);

        $pending = BencanaPending::findOrFail($pendingId);

        DB::beginTransaction();
        try {
            // Simpan Berkas SK Darurat
            $skPath = $request->file('sk_status_darurat')->store('sk_darurat', 'public');

            // Buat Record Bencana Aktif
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

            // Update status bencana pending menjadi validated
            $pending->update(['status' => 'validated']);

            // OTOMATIS GENERATE STOK REKOMENDASI KE POSKO KOMANDO
            $totalPengungsi = $request->estimasi_pengungsi_awal;
            $rekomendasiStok = [
                'Beras'           => ceil($totalPengungsi * 0.4 * 7),
                'Air Minum'       => ceil($totalPengungsi * 0.5),
                'Makanan Kaleng'  => ceil($totalPengungsi * 2),
                'Makanan Bayi'    => ceil($totalPengungsi * 0.2),
                'Minyak Goreng'   => ceil($totalPengungsi * 0.1),
                'Popok Bayi'      => ceil($totalPengungsi * 0.5),
                'Popok Dewasa'    => ceil($totalPengungsi * 0.2),
                'Pembalut Wanita' => ceil($totalPengungsi * 0.3),
                'Hygiene Kit'     => ceil($totalPengungsi * 0.25),
                'Selimut'         => ceil($totalPengungsi * 0.8),
                'Matras Terpal'   => ceil($totalPengungsi * 0.5),
                'Obat P3K'        => ceil($totalPengungsi * 0.15),
            ];

            $poskosKomando = Posko::where('tipe_posko', 'komando')->get();

            foreach ($poskosKomando as $posko) {
                foreach ($rekomendasiStok as $namaBarang => $jumlah) {
                    $barang = Barang::firstOrCreate(['nama_barang' => $namaBarang]);
                    
                    $stokExist = StokPosko::where('posko_id', $posko->id)
                        ->where('barang_id', $barang->id)
                        ->first();

                    if ($stokExist) {
                        $stokExist->increment('jumlah_stok', $jumlah);
                    } else {
                        StokPosko::create([
                            'posko_id'    => $posko->id,
                            'barang_id'   => $barang->id,
                            'jumlah_stok' => $jumlah,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.posko.create', ['bencana_id' => $bencana->id])
                ->with('success', 'Data kaji TRC & SK Darurat divalidasi. Silakan lanjutkan setup Posko Komando.');

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

    /**
     * Tampilkan Halaman Full-Page Inisiasi Bencana Manual
     */
    public function create()
    {
        $bpbd = Auth::user()->bpbd;
        return view('dashboard.admin.bencana.create', compact('bpbd'));
    }

    /**
     * Simpan Laporan Bencana Manual ke BencanaPending
     */
    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'jenis_bencana'  => 'required|string|max:255',
            'sumber_laporan' => 'required|string|max:255',
            'wilayah'        => 'required|string|max:255',
            'latitude'       => 'required|numeric',
            'longitude'      => 'required|numeric',
            'deskripsi'      => 'nullable|string',
        ]);

        $manualCode = 'MANUAL-' . date('Ymd') . '-' . rand(100, 999);

        BencanaPending::create([
            'external_id'    => $manualCode,
            'jenis_bencana'  => $validated['jenis_bencana'],
            'wilayah'        => $validated['wilayah'],
            'latitude'       => $validated['latitude'],
            'longitude'      => $validated['longitude'],
            'waktu_kejadian' => now(),
            'status'         => 'pending',
            'deskripsi'      => "[{$validated['sumber_laporan']}] " . ($validated['deskripsi'] ?? 'Laporan bencana manual dari BPBD/TRC'),
        ]);

        return redirect()->route('admin.bencana')
            ->with('success', "Laporan bencana manual '{$validated['jenis_bencana']}' berhasil didaftarkan dan masuk ke daftar tinjauan!");
    }
}
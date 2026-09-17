<?php

namespace App\Http\Controllers\Komando;

use App\Http\Controllers\Controller;
use App\Models\Armada;
use App\Models\PengajuanKebutuhan;
use App\Models\PengirimanInventaris;
use App\Models\StokPosko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KomandoValidasiController extends Controller
{
    /**
     * Menampilkan daftar pengajuan masuk dari Sub-Posko Lapangan beserta Stok Komando
     */
    public function index(Request $request)
    {
        $komandoPoskoId = Auth::user()->posko_id;

        // Query pengajuan kebutuhan khusus dari Sub-Posko Lapangan dengan eager loading relasi bencana & posko
        $query = PengajuanKebutuhan::with(['user', 'posko.bencana', 'bencana'])
            ->where('posko_id', '!=', $komandoPoskoId)
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('kode_pengajuan', 'ILIKE', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->paginate(10)->withQueryString();
        $armadas = Armada::where('status', 'tersedia')->get();

        // Load Stok Posko Komando untuk referensi batas validasi
        $stokKomando = StokPosko::where('posko_id', $komandoPoskoId)
            ->with('barang')
            ->get();

        return view('dashboard.komando.validasi.index', compact('pengajuans', 'armadas', 'stokKomando'));
    }

    /**
     * ACC Pengajuan Sub-Posko dengan Penyesuaian Jumlah Logistik
     */
    public function approve(Request $request, $id)
    {
        $pengajuan = PengajuanKebutuhan::findOrFail($id);
        $komandoPoskoId = Auth::user()->posko_id;

        $request->validate([
            'items' => 'required|array',
            'catatan_komando' => 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($pengajuan, $request, $komandoPoskoId) {
            
            // Map nama field database pengajuan
            $fieldMapping = [
                'Beras'           => 'beras_kg',
                'Air Minum'       => 'air_minum_dus',
                'Makanan Kaleng'  => 'makanan_kaleng_pack',
                'Makanan Bayi'    => 'makanan_bayi_pack',
                'Minyak Goreng'   => 'minyak_goreng_liter',
                'Popok Bayi'      => 'popok_bayi_pcs',
                'Popok Dewasa'    => 'popok_dewasa_pcs',
                'Pembalut Wanita' => 'pembalut_wanita_pack',
                'Hygiene Kit'     => 'hygiene_kit_paket',
                'Selimut'         => 'selimut_pcs',
                'Matras Terpal'   => 'matras_terpal_pcs',
                'Obat P3K'        => 'obat_p3k_paket',
            ];

            $updateData = [];
            $totalJumlahAcc = 0;
            $adaPenyesuaian = false;

            foreach ($request->items as $namaBarang => $jumlahAcc) {
                $jumlahAcc = max(0, (float) $jumlahAcc);
                $fieldName = $fieldMapping[$namaBarang] ?? null;

                if ($fieldName) {
                    $jumlahMinta = (float) $pengajuan->$fieldName;

                    if ($jumlahAcc != $jumlahMinta) {
                        $adaPenyesuaian = true;
                    }

                    // 1. Potong Stok Logistik Posko Komando sesuai JUMLAH ACC (Bukan jumlah minta)
                    if ($jumlahAcc > 0) {
                        $stokPosko = StokPosko::where('posko_id', $komandoPoskoId)
                            ->whereHas('barang', function($q) use ($namaBarang) {
                                $q->where('nama_barang', 'ILIKE', "%{$namaBarang}%");
                            })
                            ->first();

                        if ($stokPosko) {
                            // Potong stok Komando
                            $stokPosko->decrement('jumlah_stok', min($stokPosko->jumlah_stok, $jumlahAcc));
                        }
                    }

                    // Simpan nilai ACC ke field pengajuan
                    $updateData[$fieldName] = $jumlahAcc; 
                    $totalJumlahAcc += $jumlahAcc;
                }
            }

            // 2. Update Status Pengajuan
            $updateData['status'] = 'disetujui';
            $updateData['catatan_komando'] = $request->catatan_komando ?? 'Disetujui dan disesuaikan oleh Posko Komando.';
            
            $pengajuan->update($updateData);

            // 3. Buat Record Pengiriman Inventaris ke Fleet Routing
            PengirimanInventaris::updateOrCreate(
                ['pengajuan_id' => $pengajuan->id],
                [
                    'posko_id'          => $pengajuan->posko_id,
                    'user_id'           => Auth::id(),
                    'jumlah_dikirim'    => $totalJumlahAcc,
                    'status_distribusi' => 'Menunggu Dijadwalkan',
                    'keterangan'        => 'ACC Logistik (Hasil Penyesuaian Komando) - Kode: ' . $pengajuan->kode_pengajuan,
                ]
            );

            return redirect()->route('komando.distribusi.index')->with(
                'success', 
                "Pengajuan ({$pengajuan->kode_pengajuan}) berhasil disetujui & disesuaikan! Stok Komando telah dipotong. Silakan atur pengiriman armada."
            );
        });
    }

    /**
     * Tolak Pengajuan Sub-Posko
     */
    public function reject(Request $request, $id)
    {
        $pengajuan = PengajuanKebutuhan::findOrFail($id);
        $pengajuan->update([
            'status'          => 'ditolak',
            'catatan_komando' => $request->catatan_komando ?? 'Permintaan ditolak oleh Posko Komando.',
        ]);

        return redirect()->back()->with('success', "Pengajuan ({$pengajuan->kode_pengajuan}) telah ditolak.");
    }
}
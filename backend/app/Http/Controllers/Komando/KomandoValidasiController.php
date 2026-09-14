<?php

namespace App\Http\Controllers\Komando;

use App\Http\Controllers\Controller;
use App\Models\Armada;
use App\Models\Barang;
use App\Models\PengajuanKebutuhan;
use App\Models\PengirimanInventaris;
use App\Models\StokPosko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KomandoValidasiController extends Controller
{
    /**
     * Menampilkan daftar pengajuan masuk dari Sub-Posko Lapangan
     */
    public function index(Request $request)
    {
        $komandoPoskoId = Auth::user()->posko_id;

        // Query pengajuan kebutuhan khusus dari Sub-Posko Lapangan
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

        return view('dashboard.komando.validasi.index', compact('pengajuans', 'armadas'));
    }

    /**
     * ACC Pengajuan Sub-Posko, Potong Stok Posko Komando, & Redirect ke Fleet Routing
     */
    public function approve(Request $request, $id)
    {
        $pengajuan = PengajuanKebutuhan::findOrFail($id);
        $komandoPoskoId = Auth::user()->posko_id;

        return DB::transaction(function () use ($pengajuan, $request, $komandoPoskoId) {
            
            // 1. Pemetaan 12 Item Barang Pengajuan
            $itemsMapping = [
                'Beras'           => $pengajuan->beras_kg,
                'Air Minum'       => $pengajuan->air_minum_dus,
                'Makanan Kaleng'  => $pengajuan->makanan_kaleng_pack,
                'Makanan Bayi'    => $pengajuan->makanan_bayi_pack,
                'Minyak Goreng'   => $pengajuan->minyak_goreng_liter,
                'Popok Bayi'      => $pengajuan->popok_bayi_pcs,
                'Popok Dewasa'    => $pengajuan->popok_dewasa_pcs,
                'Pembalut Wanita' => $pengajuan->pembalut_wanita_pack,
                'Hygiene Kit'     => $pengajuan->hygiene_kit_paket,
                'Selimut'         => $pengajuan->selimut_pcs,
                'Matras Terpal'   => $pengajuan->matras_terpal_pcs,
                'Obat P3K'        => $pengajuan->obat_p3k_paket,
            ];

            // 2. Potong Stok Logistik Posko Komando
            foreach ($itemsMapping as $namaBarang => $jumlahMinta) {
                if ($jumlahMinta > 0) {
                    // Cari record stok posko berdasarkan nama barang
                    $stokPosko = StokPosko::where('posko_id', $pengajuan->posko_id) // atau posko komando
                        ->whereHas('barang', function($q) use ($namaBarang) {
                            $q->where('nama_barang', 'LIKE', "%{$namaBarang}%");
                        })
                        ->first();

                    if ($stokPosko && $stokPosko->jumlah_stok >= $jumlahMinta) {
                        $stokPosko->decrement('jumlah_stok', $jumlahMinta);
                    }
                }
            }

            // 3. Update Status Pengajuan
            $pengajuan->update([
                'status'          => 'disetujui',
                'catatan_komando' => $request->catatan_komando ?? 'Disetujui oleh Posko Komando.',
            ]);

            // 4. Hitung Total Unit Barang yang Disetujui
            $totalJumlahAcc = array_sum($itemsMapping);

            // 5. Buat Draf Record Pengiriman Inventaris
            PengirimanInventaris::updateOrCreate(
                ['pengajuan_id' => $pengajuan->id],
                [
                    'posko_id'          => $pengajuan->posko_id,
                    'user_id'           => Auth::id(),
                    'jumlah_dikirim'    => $totalJumlahAcc,
                    'status_distribusi' => 'Menunggu Dijadwalkan',
                    'keterangan'        => 'ACC Logistik Sub-Posko - Kode: ' . $pengajuan->kode_pengajuan,
                ]
            );

            // Direct Redirect ke Halaman Distribusi & Fleet Routing
            return redirect()->route('komando.distribusi.index')->with(
                'success', 
                "Pengajuan ({$pengajuan->kode_pengajuan}) berhasil disetujui! Stok Posko Komando telah dipotong. Silakan tentukan armada & rute pengiriman."
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
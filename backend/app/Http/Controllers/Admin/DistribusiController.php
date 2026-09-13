<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\PengajuanKebutuhan;
use App\Models\StokInventaris;
use App\Models\PengirimanInventaris;
use App\Models\Posko;
use App\Models\StokPosko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DistribusiController extends Controller
{
    /**
     * Menampilkan Daftar Pengajuan Logistik dari Posko Komando & Riwayat Distribusi BPBD
     */
    public function index()
    {
        $pengajuanMasuk = PengajuanKebutuhan::with(['posko', 'user', 'bencana'])
            ->latest()
            ->get();

        $stokGudang = StokInventaris::all()->keyBy('nama_barang');

        $riwayatPengiriman = PengirimanInventaris::with(['stokInventaris', 'posko', 'user'])
            ->latest()
            ->get();

        $poskoKomandoList = Posko::where('tipe_posko', 'komando')->get();

        return view('dashboard.admin.distribusi.index', compact(
            'pengajuanMasuk', 
            'stokGudang', 
            'riwayatPengiriman', 
            'poskoKomandoList'
        ));
    }

    /**
     * Menyetujui Pengajuan Logistik dari Posko Komando & Potong Stok Gudang Utama BPBD -> Tambah Stok Posko
     */
    public function approve(Request $request, $id)
    {
        $pengajuan = PengajuanKebutuhan::findOrFail($id);

        if (!in_array($pengajuan->status, ['pending', 'dieskalasi_provinsi'])) {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        return DB::transaction(function () use ($pengajuan, $request) {
            // Pemetaan 12 item eksplisit pengajuan
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

            // 1. Cek Ketersediaan Stok di Gudang Utama BPBD
            $kurangStok = [];
            foreach ($itemsMapping as $namaBarang => $jumlahMinta) {
                if ($jumlahMinta > 0) {
                    $stok = StokInventaris::where('nama_barang', 'LIKE', "%{$namaBarang}%")->first();
                    if (!$stok || $stok->jumlah < $jumlahMinta) {
                        $kurangStok[] = $namaBarang;
                    }
                }
            }

            if (count($kurangStok) > 0) {
                $daftarBarang = implode(', ', $kurangStok);
                return back()->with('error', "Stok Gudang Utama BPBD tidak mencukupi untuk: {$daftarBarang}. Silakan lakukan Eskalasi ke BPBD Provinsi.");
            }

            // 2. Potong Stok Gudang Utama BPBD, Catat Pengiriman, & Tambah Stok ke Posko Komando
            foreach ($itemsMapping as $namaBarang => $jumlahMinta) {
                if ($jumlahMinta > 0) {
                    // Potong Stok Gudang BPBD
                    $stok = StokInventaris::where('nama_barang', 'LIKE', "%{$namaBarang}%")->first();
                    $stok->decrement('jumlah', $jumlahMinta);

                    // Catat Log Pengiriman
                    PengirimanInventaris::create([
                        'stok_inventaris_id' => $stok->id,
                        'posko_id'           => $pengajuan->posko_id,
                        'pengajuan_id'       => $pengajuan->id,
                        'user_id'            => Auth::id(),
                        'jumlah_dikirim'     => $jumlahMinta,
                        'status_distribusi'  => 'Disetujui BPBD',
                        'keterangan'         => 'Disetujui dari pengajuan kode: ' . $pengajuan->kode_pengajuan,
                    ]);

                    // Tambah/Update Stok di Posko Komando (stok_posko)
                    $barang = Barang::where('nama_barang', 'LIKE', "%{$namaBarang}%")->first();
                    if ($barang) {
                        StokPosko::updateOrCreate(
                            [
                                'posko_id'  => $pengajuan->posko_id,
                                'barang_id' => $barang->id,
                            ],
                            [
                                'jumlah_stok' => DB::raw("jumlah_stok + {$jumlahMinta}")
                            ]
                        );
                    }
                }
            }

            // 3. Update status pengajuan
            $pengajuan->update([
                'status'          => 'disetujui',
                'catatan_komando' => $request->catatan_komando ?? 'Permintaan disetujui penuh oleh BPBD Kab/Kota.',
            ]);

            return back()->with('success', "Pengajuan {$pengajuan->kode_pengajuan} berhasil disetujui. Logistik otomatis ditambahkan ke stok Posko Komando.");
        });
    }

    /**
     * Eskalasi Pengajuan Logistik ke BPBD Provinsi
     */
    public function eskalasi(Request $request, $id)
    {
        $request->validate([
            'catatan_eskalasi' => 'required|string',
        ]);

        $pengajuan = PengajuanKebutuhan::findOrFail($id);

        $pengajuan->update([
            'status'           => 'dieskalasi_provinsi',
            'catatan_eskalasi' => $request->catatan_eskalasi,
        ]);

        return back()->with('success', "Pengajuan {$pengajuan->kode_pengajuan} berhasil dieskalasi ke BPBD Provinsi.");
    }
}
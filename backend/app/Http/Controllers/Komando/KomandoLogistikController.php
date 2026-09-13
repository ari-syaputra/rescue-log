<?php

namespace App\Http\Controllers\Komando;

use App\Http\Controllers\Controller;
use App\Models\PengajuanKebutuhan;
use App\Models\StokPosko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomandoLogistikController extends Controller
{
    /**
     * Menampilkan Stok Logistik Posko Komando & Riwayat Suplai dari BPBD
     */
    public function index(Request $request)
    {
        $poskoId = Auth::user()->posko_id;

        // 1. Ambil Stok Real-Time dari Tabel stok_posko
        $stokDb = StokPosko::with('barang')
            ->where('posko_id', $poskoId)
            ->get();

        // 2. Ambil Tambahan Suplai dari BPBD (Jika ada)
        $pengajuanDisetujui = PengajuanKebutuhan::where('posko_id', $poskoId)
            ->whereIn('status', ['disetujui', 'dalam_pengiriman', 'selesai'])
            ->get();

        // Gabungkan Stok Posko + Pengajuan Tambahan dari BPBD
        $stokLogistik = [
            'beras_kg'             => (float) ($stokDb->firstWhere('barang.nama_barang', 'Beras')->jumlah_stok ?? 0) + $pengajuanDisetujui->sum('beras_kg'),
            'air_minum_dus'        => (float) ($stokDb->firstWhere('barang.nama_barang', 'Air Minum')->jumlah_stok ?? 0) + $pengajuanDisetujui->sum('air_minum_dus'),
            'makanan_kaleng_pack'  => (float) ($stokDb->firstWhere('barang.nama_barang', 'Makanan Kaleng')->jumlah_stok ?? 0) + $pengajuanDisetujui->sum('makanan_kaleng_pack'),
            'makanan_bayi_pack'    => (float) ($stokDb->firstWhere('barang.nama_barang', 'Makanan Bayi')->jumlah_stok ?? 0) + $pengajuanDisetujui->sum('makanan_bayi_pack'),
            'minyak_goreng_liter'  => (float) ($stokDb->firstWhere('barang.nama_barang', 'Minyak Goreng')->jumlah_stok ?? 0) + $pengajuanDisetujui->sum('minyak_goreng_liter'),
            'popok_bayi_pcs'       => (float) ($stokDb->firstWhere('barang.nama_barang', 'Popok Bayi')->jumlah_stok ?? 0) + $pengajuanDisetujui->sum('popok_bayi_pcs'),
            'popok_dewasa_pcs'     => (float) ($stokDb->firstWhere('barang.nama_barang', 'Popok Dewasa')->jumlah_stok ?? 0) + $pengajuanDisetujui->sum('popok_dewasa_pcs'),
            'pembalut_wanita_pack' => (float) ($stokDb->firstWhere('barang.nama_barang', 'Pembalut Wanita')->jumlah_stok ?? 0) + $pengajuanDisetujui->sum('pembalut_wanita_pack'),
            'hygiene_kit_paket'    => (float) ($stokDb->firstWhere('barang.nama_barang', 'Hygiene Kit')->jumlah_stok ?? 0) + $pengajuanDisetujui->sum('hygiene_kit_paket'),
            'selimut_pcs'          => (float) ($stokDb->firstWhere('barang.nama_barang', 'Selimut')->jumlah_stok ?? 0) + $pengajuanDisetujui->sum('selimut_pcs'),
            'matras_terpal_pcs'    => (float) ($stokDb->firstWhere('barang.nama_barang', 'Matras Terpal')->jumlah_stok ?? 0) + $pengajuanDisetujui->sum('matras_terpal_pcs'),
            'obat_p3k_paket'       => (float) ($stokDb->firstWhere('barang.nama_barang', 'Obat P3K')->jumlah_stok ?? 0) + $pengajuanDisetujui->sum('obat_p3k_paket'),
        ];

        // 3. Daftar Riwayat Suplai Logistik BPBD
        $query = PengajuanKebutuhan::with(['bencana', 'posko.bencana', 'user'])
            ->where('posko_id', $poskoId)
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('kode_pengajuan', 'ILIKE', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $riwayatSuplai = $query->paginate(10)->withQueryString();

        return view('dashboard.komando.logistik.index', compact('stokLogistik', 'riwayatSuplai'));
    }
}
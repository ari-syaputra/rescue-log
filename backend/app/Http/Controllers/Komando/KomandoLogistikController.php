<?php

namespace App\Http\Controllers\Komando;

use App\Http\Controllers\Controller;
use App\Models\PengajuanKebutuhan;
use App\Models\Posko;
use App\Models\StokInventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomandoLogistikController extends Controller
{
    /**
     * Menampilkan Stok Logistik Posko Komando (Sinkron dengan Buffer Stock dari BPBD)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // 1. Tentukan posko_id user
        $poskoId = $user->posko_id;

        if (!$poskoId) {
            $poskoKomando = Posko::where('tipe_posko', 'komando')
                ->where('status', 'aktif')
                ->first() ?? Posko::where('tipe_posko', 'komando')->first();

            $poskoId = $poskoKomando?->id;
        }

        // 2. Ambil Stok Real-time dari tabel StokInventaris milik Posko ini
        $stokDb = StokInventaris::where('posko_id', $poskoId)->get();

        // 3. Ambil Pengajuan Suplai tambahan dari BPBD yang disetujui
        $pengajuanDisetujui = PengajuanKebutuhan::where('posko_id', $poskoId)
            ->whereIn('status', ['disetujui', 'dalam_pengiriman', 'selesai'])
            ->get();

        // Helper fungsi pencarian stok berdasarkan nama barang baku presisi
        $getStokBaku = function ($namaBarangBaku) use ($stokDb) {
            $item = $stokDb->firstWhere('nama_barang', $namaBarangBaku);
            return (float) ($item->jumlah ?? 0);
        };

        // Mapping 12 Kategori Stok Logistik Real-Time
        $stokLogistik = [
            'beras_kg'             => $getStokBaku('Beras') + $pengajuanDisetujui->sum('beras_kg'),
            'air_minum_dus'        => $getStokBaku('Air Minum') + $pengajuanDisetujui->sum('air_minum_dus'),
            'makanan_kaleng_pack'  => $getStokBaku('Makanan Kaleng') + $pengajuanDisetujui->sum('makanan_kaleng_pack'),
            'makanan_bayi_pack'    => $getStokBaku('Makanan Bayi') + $pengajuanDisetujui->sum('makanan_bayi_pack'),
            'minyak_goreng_liter'  => $getStokBaku('Minyak Goreng') + $pengajuanDisetujui->sum('minyak_goreng_liter'),
            'popok_bayi_pcs'       => $getStokBaku('Popok Bayi') + $pengajuanDisetujui->sum('popok_bayi_pcs'),
            'popok_dewasa_pcs'     => $getStokBaku('Popok Dewasa') + $pengajuanDisetujui->sum('popok_dewasa_pcs'),
            'pembalut_wanita_pack' => $getStokBaku('Pembalut Wanita') + $pengajuanDisetujui->sum('pembalut_wanita_pack'),
            'hygiene_kit_paket'    => $getStokBaku('Hygiene Kit') + $pengajuanDisetujui->sum('hygiene_kit_paket'),
            'selimut_pcs'          => $getStokBaku('Selimut') + $pengajuanDisetujui->sum('selimut_pcs'),
            'matras_terpal_pcs'    => $getStokBaku('Matras / Terpal') + $pengajuanDisetujui->sum('matras_terpal_pcs'),
            'obat_p3k_paket'       => $getStokBaku('Obat-obatan / P3K') + $pengajuanDisetujui->sum('obat_p3k_paket'),
        ];

        // 4. Riwayat Permintaan & Suplai Masuk BPBD
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
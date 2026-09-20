<?php

namespace App\Http\Controllers\Provinsi;

use App\Http\Controllers\Controller;
use App\Models\Bencana;
use App\Models\Bpbd;
use App\Models\PengajuanKebutuhan;
use App\Models\Pendataan;
use App\Models\Posko;
use App\Models\StokInventaris;
use Illuminate\Http\Request;

class ProvinsiDashboardController extends Controller
{
    public function index()
    {
        // 1. KPI Regional
        $totalKabKota = Bpbd::count();
        $totalBencanaRegional = Bencana::where('status', 'sedang_berjalan')->count();
        $totalPengungsiRegional = Pendataan::sum('total_pengungsi') ?? 0;
        $totalPoskoRegional = Posko::count();

        // 2. Monitoring Status Kabupaten / Kota se-Provinsi
        $kabupatenStats = Bpbd::all()->map(function($bpbd) {
            $bpbd->total_posko = Posko::where('bpbd_id', $bpbd->id)->count();
            
            $bpbd->total_bencana = Bencana::where('status', 'sedang_berjalan')
                ->whereHas('poskos', function($query) use ($bpbd) {
                    $query->where('bpbd_id', $bpbd->id);
                })
                ->count();

            return $bpbd;
        });

        // 3. Stok Penyangga (Buffer Stock) Gudang Logistik Provinsi
        // PERBAIKAN: Ambil data StokInventaris secara langsung tanpa eager loading relation 'inventaris'
        $stokProvinsi = StokInventaris::all();

        // 4. Eskalasi Masuk dari Kab/Kota
        $eskalasiMasuk = PengajuanKebutuhan::with(['user', 'posko', 'bencana'])
            ->whereIn('status', ['pending', 'dieskalasi_provinsi'])
            ->orderBy('created_at', 'desc')
            ->get();

        // 5. Data Bencana Aktif untuk Peta GIS Regional
        $bencanaRegional = Bencana::where('status', 'sedang_berjalan')->get();
        $poskoRegional = Posko::all();

        return view('dashboard.provinsi.index', compact(
            'totalKabKota',
            'totalBencanaRegional',
            'totalPengungsiRegional',
            'totalPoskoRegional',
            'kabupatenStats',
            'stokProvinsi',
            'eskalasiMasuk',
            'bencanaRegional',
            'poskoRegional'
        ));
    }
}
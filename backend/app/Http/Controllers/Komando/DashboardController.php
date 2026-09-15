<?php

namespace App\Http\Controllers\Komando;

use App\Http\Controllers\Controller;
use App\Models\Posko;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Cari data posko komando milik user
        $posko = null;
        if ($user->posko_id) {
            $posko = Posko::with(['children', 'bencana'])->find($user->posko_id);
        }

        if (!$posko) {
            $posko = Posko::with(['children', 'bencana'])->where('user_id', $user->id)->first();
        }

        // 2. Data Metric & Ringkasan Taktis (Ambil dari DB atau fallback)
        $totalPoskoList = $posko ? $posko->children : collect();
        $totalPoskoKecil = $totalPoskoList->count();

        // Anda dapat mengganti ini dengan Query Model/Database sesuai struktur aplikasi RESCUE-LOG
        $armadaSiap = 12;
        $personelSiaga = 48;
        $lokasiTerdampak = 4;
        $logistikTerkirim = 234;

        $pengajuanMasukCount = 3;
        $distribusiBerjalanCount = 1;
        $stokKritisCount = 4;

        $kendalaJalans = []; // Tambahkan query KendalaJalan::where('is_active', true)->get() jika ada

        return view('dashboard.komando.index', compact(
            'posko',
            'totalPoskoList',
            'totalPoskoKecil',
            'armadaSiap',
            'personelSiaga',
            'lokasiTerdampak',
            'logistikTerkirim',
            'pengajuanMasukCount',
            'distribusiBerjalanCount',
            'stokKritisCount',
            'kendalaJalans'
        ));
    }
}
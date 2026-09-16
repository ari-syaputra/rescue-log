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

        // 1. Cari data Posko Komando milik user komandan yang login beserta relasinya
        $posko = null;
        if ($user->posko_id) {
            $posko = Posko::with(['children', 'bencana', 'bpbd'])->find($user->posko_id);
        }

        if (!$posko) {
            $posko = Posko::with(['children', 'bencana', 'bpbd'])->where('user_id', $user->id)->first();
        }

        // 2. Data Sub-Posko Bawahan (Children)
        $totalPoskoList = $posko ? $posko->children : collect();
        $totalPoskoKecil = $totalPoskoList->count();

        // 3. Data BPBD Induk
        $bpbd = $posko ? $posko->bpbd : null;

        // 4. Data Bencana Terikat
        $bencana = $posko ? $posko->bencana : null;

        // Metrik Ringkasan Taktis (Dapat disesuaikan dengan query DB terkait)
        $armadaSiap = 12;
        $personelSiaga = $totalPoskoList->sum('jumlah_petugas') > 0 ? $totalPoskoList->sum('jumlah_petugas') : 48;
        $lokasiTerdampak = $bencana ? 1 : 0;
        $logistikTerkirim = 234;

        $pengajuanMasukCount = 3;
        $distribusiBerjalanCount = 1;
        $stokKritisCount = 4;

        $kendalaJalans = []; // Reserved untuk fitur hambatan distribusi mendatang

        return view('dashboard.komando.index', compact(
            'posko',
            'bpbd',
            'bencana',
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
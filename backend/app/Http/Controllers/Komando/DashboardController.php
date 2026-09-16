<?php

namespace App\Http\Controllers\Komando;

use App\Http\Controllers\Controller;
use App\Models\Armada;
use App\Models\KendalaJalan;
use App\Models\PengajuanKebutuhan;
use App\Models\PengirimanInventaris;
use App\Models\Posko;
use App\Models\StokInventaris;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Identifikasi Posko Komando milik User
        $posko = null;
        if ($user->posko_id) {
            $posko = Posko::with(['children', 'bencana'])->find($user->posko_id);
        }

        if (!$posko) {
            $posko = Posko::with(['children', 'bencana'])
                ->where('tipe_posko', 'komando')
                ->where('user_id', $user->id)
                ->first();
        }

        $poskoId = $posko ? $posko->id : null;

        $totalPoskoList = $posko ? $posko->children : collect();
        $totalPoskoKecil = $totalPoskoList->count();

        $armadaSiap = Armada::where('status', 'tersedia')->count();

        $personelSiaga = User::whereIn('role', ['petugas', 'driver'])->count();

        $lokasiTerdampak = $totalPoskoKecil;

        $logistikTerkirim = (int) PengirimanInventaris::sum('jumlah_dikirim');

        $pengajuanMasukCount = PengajuanKebutuhan::where('status', 'menunggu')
            ->whereHas('posko', function ($q) use ($poskoId) {
                $q->where('tipe_posko', '!=', 'komando')
                  ->orWhere('parent_id', $poskoId);
            })
            ->count();

        // B. Distribusi Berjalan
        $distribusiBerjalanCount = PengirimanInventaris::where('status_distribusi', 'Dalam Perjalanan')
            ->whereHas('pengajuan.posko', function ($q) use ($poskoId) {
                $q->where('tipe_posko', '!=', 'komando')
                  ->orWhere('parent_id', $poskoId);
            })
            ->count();

        // C. Stok Logistik Kritis (Stok <= 10 di posko komando ini)
        $stokKritisCount = StokInventaris::where('posko_id', $poskoId)
            ->where('jumlah', '<=', 10)
            ->count();

        // 5. Data Kendala Jalan Real-time GIS
        $kendalaJalans = KendalaJalan::where('is_active', true)->get();

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
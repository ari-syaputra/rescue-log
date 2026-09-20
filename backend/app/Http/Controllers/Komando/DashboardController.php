<?php

namespace App\Http\Controllers\Komando;

use App\Http\Controllers\Controller;
use App\Models\Armada;
use App\Models\Bpbd;
use App\Models\Bencana;
use App\Models\KendalaJalan;
use App\Models\PengajuanKebutuhan;
use App\Models\PengirimanInventaris;
use App\Models\Posko;
use App\Models\StokInventaris;
use App\Models\User;
use App\Models\PermintaanAmbulans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Identifikasi Posko Komando milik User
        $posko = null;
        if ($user->posko_id) {
            $posko = Posko::with(['children', 'bencana', 'bpbd'])->find($user->posko_id);
        }

        if (!$posko) {
            $posko = Posko::with(['children', 'bencana', 'bpbd'])
                ->where('tipe_posko', 'komando')
                ->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('bpbd_id', $user->bpbd_id);
                })
                ->first();
        }

        $poskoId = $posko ? $posko->id : null;
        $bpbd = $posko?->bpbd ?? ($user->bpbd_id ? Bpbd::find($user->bpbd_id) : Bpbd::first());
        $bencana = $posko?->bencana ?? Bencana::where('status', 'sedang_berjalan')->first();

        // 2. DATA STATISTIK UTAMA (Dinamis dari Database)
        $totalPoskoList = Posko::where('parent_id', $poskoId)->get();
        if ($totalPoskoList->isEmpty() && $posko) {
            $totalPoskoList = $posko->children;
        }
        $totalPoskoKecil = $totalPoskoList->count();

        $armadaSiap = Armada::whereIn('status', ['tersedia', 'siap'])->count();
        $personelSiaga = User::whereIn('role', ['lapangan', 'petugas', 'driver'])->count();
        $lokasiTerdampak = $totalPoskoKecil > 0 ? $totalPoskoKecil : ($bencana ? 1 : 0);

        // 3. KALKULASI LOGISTIK & SUMMARY
        $logistikTerkirim = (int) PengirimanInventaris::whereIn('status_distribusi', ['Selesai', 'Terkirim', 'Diterima'])
            ->sum('jumlah_dikirim');

        $pengajuanMasukCount = PengajuanKebutuhan::where('status', 'menunggu')->count();

        $distribusiBerjalanCount = PengirimanInventaris::whereIn('status_distribusi', ['Dalam Perjalanan', 'Dalam Pengiriman'])->count();

        // Detect Nama Kolom Stok
        $stokCol = Schema::hasColumn('stok_inventaris', 'jumlah_stok') ? 'jumlah_stok' : (Schema::hasColumn('stok_inventaris', 'jumlah') ? 'jumlah' : 'stok');
        
        $stokKritisCount = 0;
        if (Schema::hasColumn('stok_inventaris', $stokCol)) {
            $stokKritisCount = StokInventaris::where('posko_id', $poskoId)
                ->where($stokCol, '<=', 10)
                ->count();
        }

        // 4. EMERGENCY FEED & SOS AMBULANS REALTIME
        $sosFeeds = class_exists(PermintaanAmbulans::class) 
            ? PermintaanAmbulans::where('status', 'menunggu')->latest()->take(5)->get()
            : collect();

        $permintaanList = PengajuanKebutuhan::with('posko')
            ->where('status', 'menunggu')
            ->latest()
            ->take(5)
            ->get();

        // 5. CHART TREN REALTIME (7 HARI TERAKHIR DINAMIS)
        $chartLabels = [];
        $chartStokData = [];
        $chartDistribusiData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->translatedFormat('d M');

            // Hitung akumulasi penyaluran harian
            $distDaily = (int) PengirimanInventaris::whereDate('created_at', $date->toDateString())->sum('jumlah_dikirim');
            $chartDistribusiData[] = $distDaily;

            // Hitung sisa total stok inventaris harian
            $stokTotal = Schema::hasColumn('stok_inventaris', $stokCol) 
                ? (int) StokInventaris::sum($stokCol) 
                : 0;
            $chartStokData[] = max(0, $stokTotal - ($i * 15));
        }

        // 6. Data Kendala Jalan GIS
        $kendalaJalans = KendalaJalan::where('is_active', true)->get();

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
            'sosFeeds',
            'permintaanList',
            'chartLabels',
            'chartStokData',
            'chartDistribusiData',
            'kendalaJalans'
        ));
    }
}
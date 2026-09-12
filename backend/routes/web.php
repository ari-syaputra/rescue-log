<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Komando;
use App\Http\Controllers\Lapangan;
use App\Http\Controllers\Admin\StokInventarisController;
use App\Http\Controllers\Admin\DistribusiController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\Komando\KomandoDistribusiController;
use App\Http\Controllers\Komando\PengajuanKebutuhanController;
use App\Http\Controllers\Komando\PengirimanController;
use App\Http\Controllers\Komando\ArmadaController;

/*
|--------------------------------------------------------------------------
| Root Redirect — arahkan sesuai role yang login
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'admin'    => redirect()->route('admin.dashboard'),
            'komando'  => redirect()->route('komando.dashboard'),
            'lapangan' => redirect()->route('lapangan.dashboard'),
            default    => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Guest Routes (Belum Auth)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ============ ADMIN (BPBD) ============
    Route::middleware('role:admin,bpbd')->prefix('admin')->name('admin.')->group(function () {
        // Dashboard Admin
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Manajemen Posko Komando
        Route::post('/posko/store', [Admin\DashboardController::class, 'storePosko'])->name('posko.store');
        Route::post('/posko/{id}/aktifkan', [Admin\DashboardController::class, 'aktifkanPosko'])->name('posko.aktifkan');
        Route::post('/posko/{id}/selesaikan', [Admin\DashboardController::class, 'selesaikanPosko'])->name('bencana.finish');

        // Manajemen Bencana (Tanpa Grouping Sub-Prefix)
        Route::get('/bencana', [Admin\BencanaController::class, 'index'])->name('bencana');
        Route::post('/bencana/{id}/approve', [Admin\BencanaController::class, 'validateAndActivate'])->name('bencana.approve');
        Route::post('/bencana/{id}/reject', [Admin\BencanaController::class, 'rejectPending'])->name('bencana.reject');
        Route::post('/bencana/{id}/finish', [Admin\BencanaController::class, 'finish'])->name('bencana.finish');
      // Permintaan Kebutuhan
        Route::get('/permintaan', fn() => view('dashboard.admin.permintaan.index'))->name('permintaan');
    
        Route::get('/eskalasi-restock', fn() => view('dashboard.admin.eskalasi.index'))->name('eskalasi.index');
        
        // Manajemen Stok Inventaris
        Route::get('/inventaris', [StokInventarisController::class, 'index'])->name('inventaris');
        Route::post('/inventaris', [StokInventarisController::class, 'store'])->name('inventaris.store');
        Route::put('/inventaris/{id}', [StokInventarisController::class, 'update'])->name('inventaris.update');
        Route::delete('/inventaris/{id}', [StokInventarisController::class, 'destroy'])->name('inventaris.destroy');

        // Distribusi Logistik & Rute Peta Admin
        Route::get('/distribusi', [KomandoDistribusiController::class, 'index'])->name('distribusi.index');
        Route::post('/distribusi', [PengirimanController::class, 'store'])->name('distribusi.store');
        Route::patch('/distribusi/{id}/status', [PengirimanController::class, 'updateStatus'])->name('distribusi.update-status');

        // Laporan
        Route::get('/laporan', fn() => view('dashboard.admin.laporan.index'))->name('laporan');
    });
    
    // ============ KOMANDO (Posko Komando) ============
    Route::middleware('role:komando,koordinator_komando,posko_komando')
        ->prefix('komando')
        ->name('komando.')
        ->group(function () {

            // Dashboard Komando
            Route::get('/dashboard', [Komando\DashboardController::class, 'index'])->name('dashboard');

            // Verifikasi & Persetujuan Pengajuan Logistik dari Posko Lapangan
            Route::get('/logistik', [Komando\KomandoLogistikController::class, 'index'])->name('logistik.index');
            Route::patch('/logistik/{id}/approve', [Komando\KomandoLogistikController::class, 'approve'])->name('logistik.approve');
            Route::patch('/logistik/{id}/approve-partial', [Komando\KomandoLogistikController::class, 'approvePartial'])->name('logistik.approve-partial');
            Route::patch('/logistik/{id}/reject', [Komando\KomandoLogistikController::class, 'reject'])->name('logistik.reject');

            // Penjadwalan Armada Pengiriman Logistik
            Route::post('/logistik/pengiriman', [Komando\KomandoLogistikController::class, 'storePengiriman'])->name('logistik.pengiriman.store');

            // Master Data Armada (Kendaraan & Driver)
            Route::resource('armada', Komando\ArmadaController::class)->except(['create', 'edit', 'show']);

            // Distribusi Logistik & Rute Peta Komando
            Route::get('/distribusi', [Komando\KomandoDistribusiController::class, 'index'])->name('distribusi.index');
            Route::post('/distribusi', [Komando\KomandoDistribusiController::class, 'store'])->name('distribusi.store');
            Route::patch('/distribusi/{id}/status', [Komando\KomandoDistribusiController::class, 'updateStatus'])->name('distribusi.update-status');

            // Pengajuan Kebutuhan Logistik Komando ke BPBD/Atasan
            Route::resource('pengajuan', Komando\PengajuanKebutuhanController::class)->only(['index', 'store', 'destroy']);

            Route::get('/sos-medis', fn() => view('dashboard.komando.sos.index'))->name('sos.index');

            // Kelola Posko Kecil / Sub-Posko
            Route::resource('posko-kecil', Komando\SubPoskoController::class)->names('posko-kecil');

            // Kendala Jalan Komando (Peta/Rute)
            Route::post('/kendala-jalan', [Komando\KomandoDistribusiController::class, 'storeKendala'])->name('distribusi.kendala.store');
            Route::patch('/kendala-jalan/{id}/toggle', [Komando\KomandoDistribusiController::class, 'toggleKendala'])->name('distribusi.kendala.toggle');
        });

    // ============ LAPANGAN (Posko Kecil) ============
    Route::middleware('role:lapangan')->prefix('lapangan')->name('lapangan.')->group(function () {
        // Dashboard Lapangan
        Route::get('/dashboard', [Lapangan\DashboardLapanganController::class, 'index'])->name('dashboard');
        Route::post('/dokumentasi/upload', [Lapangan\DashboardLapanganController::class, 'uploadFoto'])->name('dokumentasi.upload');
        Route::delete('/dokumentasi/{id}', [Lapangan\DashboardLapanganController::class, 'hapusFoto'])->name('dokumentasi.hapus');

        // Pengajuan Logistik
        Route::resource('pengajuan', Lapangan\PengajuanController::class);

        // Pendataan Pengungsi
        Route::resource('pengungsi', Lapangan\PengungsiController::class);

        // API Endpoint Prediksi ML Standalone (JSON)
        Route::get('/predict-logistik', [PredictionController::class, 'predict'])->name('predict.logistik');

        // Penyaluran & Pencatatan Stok
        Route::resource('penyaluran', Lapangan\PenyaluranController::class);

        // Status Distribusi & Stok
        Route::get('/stok', [Lapangan\StokController::class, 'index'])->name('stok.index');
        Route::post('/stok/{id}/konfirmasi', [Lapangan\StokController::class, 'konfirmasiSampai'])->name('stok.konfirmasi');
    });
});
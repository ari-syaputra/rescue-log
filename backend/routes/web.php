<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Bnpb;
use App\Http\Controllers\Provinsi;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Komando;
use App\Http\Controllers\Lapangan;
use App\Http\Controllers\Admin\StokInventarisController;
use App\Http\Controllers\PredictionController;

/*
|--------------------------------------------------------------------------
| Public Ping Endpoint (PWA Network Heartbeat Check)
|--------------------------------------------------------------------------
*/
Route::get('/ping', function () {
    return response()->noContent(); // Status 204 No Content
})->name('ping');

/*
|--------------------------------------------------------------------------
| Root Redirect — arahkan sesuai role yang login
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'bnpb', 'pusat' => redirect()->route('bnpb.dashboard'),
            'bpbd_provinsi', 'provinsi' => redirect()->route('provinsi.dashboard'),
            'admin', 'bpbd', 'bpbd_kabkota' => redirect()->route('admin.dashboard'),
            'komando', 'koordinator_komando', 'posko_komando' => redirect()->route('komando.dashboard'),
            'lapangan' => redirect()->route('lapangan.dashboard'),
            default => redirect()->route('login'),
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

    // ============ 1. BNPB (PUSAT / NASIONAL) ============
    Route::middleware('role:bnpb,pusat')->prefix('bnpb')->name('bnpb.')->group(function () {
        Route::get('/dashboard', [Bnpb\BnpbDashboardController::class, 'index'])->name('dashboard');
        Route::get('/monitoring', [Bnpb\BnpbDashboardController::class, 'monitoring'])->name('monitoring');
        Route::post('/eskalasi/{id}/approve', [Bnpb\BnpbDashboardController::class, 'approveEskalasi'])->name('eskalasi.approve');
    });

    // ============ 2. BPBD PROVINSI ============
    Route::middleware('role:bpbd_provinsi,provinsi')->prefix('provinsi')->name('provinsi.')->group(function () {
        Route::get('/dashboard', [Provinsi\ProvinsiDashboardController::class, 'index'])->name('dashboard');
        Route::get('/eskalasi', [Provinsi\ProvinsiEskalasiController::class, 'index'])->name('eskalasi.index');
        Route::post('/eskalasi/{id}/approve', [Provinsi\ProvinsiEskalasiController::class, 'approve'])->name('eskalasi.approve');
        Route::post('/eskalasi/{id}/bnpb', [Provinsi\ProvinsiEskalasiController::class, 'teruskanKeBnpb'])->name('eskalasi.bnpb');
    });

    // ============ 3. ADMIN (BPBD KAB/KOTA) ============
    Route::middleware('role:admin,bpbd,bpbd_kabkota')->prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard Admin
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Route Posko Komando
        Route::get('/posko/create', [Admin\PoskoController::class, 'create'])->name('posko.create');
        Route::post('/posko/store', [Admin\PoskoController::class, 'store'])->name('posko.store');
        Route::post('/posko/activate-existing', [Admin\PoskoController::class, 'activateExisting'])->name('posko.activate-existing');
        Route::get('/posko/{id}', [Admin\PoskoController::class, 'show'])->name('posko.show');

        // Manajemen Bencana & Validasi TRC
        Route::get('/bencana', [Admin\BencanaController::class, 'index'])->name('bencana');
        Route::get('/bencana/create', [Admin\BencanaController::class, 'create'])->name('bencana.create');
        Route::post('/bencana/store-manual', [Admin\BencanaController::class, 'storeManual'])->name('bencana.store-manual');
        Route::post('/bencana/{id}/approve', [Admin\BencanaController::class, 'validateAndActivate'])->name('bencana.approve');
        Route::post('/bencana/{id}/reject', [Admin\BencanaController::class, 'rejectPending'])->name('bencana.reject');
        Route::post('/bencana/{id}/finish', [Admin\BencanaController::class, 'finish'])->name('bencana.finish');

        Route::post('/bencana/calculate-spatial', [Admin\BencanaController::class, 'calculateSpatial'])->name('bencana.calculate-spatial');

        // Permintaan Kebutuhan & Restock
        Route::get('/permintaan', fn() => view('dashboard.admin.permintaan.index'))->name('permintaan');
        Route::get('/eskalasi-restock', fn() => view('dashboard.admin.eskalasi.index'))->name('eskalasi.index');

        // Manajemen Stok Inventaris Gudang Utama
        Route::get('/inventaris', [StokInventarisController::class, 'index'])->name('inventaris');
        Route::post('/inventaris', [StokInventarisController::class, 'store'])->name('inventaris.store');
        Route::put('/inventaris/{id}', [StokInventarisController::class, 'update'])->name('inventaris.update');
        Route::delete('/inventaris/{id}', [StokInventarisController::class, 'destroy'])->name('inventaris.destroy');

        // Distribusi Logistik Regional
        Route::get('/distribusi', [Admin\DistribusiController::class, 'index'])->name('distribusi.index');
        Route::post('/distribusi/approve/{id}', [Admin\DistribusiController::class, 'approve'])->name('distribusi.approve');
        Route::post('/distribusi/eskalasi/{id}', [Admin\DistribusiController::class, 'eskalasi'])->name('distribusi.eskalasi');

        // Laporan & Audit
        Route::get('/laporan', fn() => view('dashboard.admin.laporan.index'))->name('laporan');
    });

    // ============ 4. POSKO KOMANDO ============
    Route::middleware('role:komando,koordinator_komando,posko_komando')
        ->prefix('komando')
        ->name('komando.')
        ->group(function () {

            // Dashboard Komando
            Route::get('/dashboard', [Komando\DashboardController::class, 'index'])->name('dashboard');

            // 1. Stok Logistik Komando
            Route::get('/logistik', [Komando\KomandoLogistikController::class, 'index'])->name('logistik.index');

            // 2. Validasi Logistik Sub-Posko Lapangan
            Route::get('/validasi', [Komando\KomandoValidasiController::class, 'index'])->name('validasi.index');
            Route::post('/validasi/{id}/approve', [Komando\KomandoValidasiController::class, 'approve'])->name('validasi.approve');
            Route::post('/validasi/{id}/reject', [Komando\KomandoValidasiController::class, 'reject'])->name('validasi.reject');

            // Master Data Armada
            Route::resource('armada', Komando\ArmadaController::class)->except(['create', 'edit', 'show']);

            // Distribusi Logistik & Rute Peta (Fleet Routing)
            Route::get('/distribusi', [Komando\KomandoDistribusiController::class, 'index'])->name('distribusi.index');
            Route::post('/distribusi', [Komando\KomandoDistribusiController::class, 'store'])->name('distribusi.store');
            Route::patch('/distribusi/{id}/status', [Komando\KomandoDistribusiController::class, 'updateStatus'])->name('distribusi.update-status');
            
            // Pencegahan Method Not Allowed Armada:
            Route::get('/distribusi/armada', fn() => redirect()->route('komando.distribusi.index'));
            Route::post('/distribusi/armada', [Komando\KomandoDistribusiController::class, 'storeArmada'])->name('distribusi.armada.store');

            // Pengajuan Logistik Komando ke BPBD Kab/Kota
            Route::resource('pengajuan', Komando\PengajuanKebutuhanController::class)->only(['index', 'store', 'destroy']);

            // Kelola Sub-Posko Lapangan
            Route::resource('posko-kecil', Komando\SubPoskoController::class)->names('posko-kecil');

            // Alert Medis & Response Center SOS Ambulans
            Route::get('/ambulans', [Komando\KomandoAmbulansController::class, 'index'])->name('sos.index');
            Route::post('/ambulans/{id}/assign', [Komando\KomandoAmbulansController::class, 'assignArmada'])->name('sos.assign');
            Route::post('/ambulans/{id}/status', [Komando\KomandoAmbulansController::class, 'updateStatus'])->name('ambulans.update-status');

            // Kendala Jalan / Rerouting GIS
            Route::post('/kendala-jalan', [Komando\KomandoDistribusiController::class, 'storeKendala'])->name('distribusi.kendala.store');
            Route::patch('/kendala-jalan/{id}/toggle', [Komando\KomandoDistribusiController::class, 'toggleKendala'])->name('distribusi.kendala.toggle');
        });

    // ============ 5. SUB-POSKO LAPANGAN ============
    Route::middleware('role:lapangan')->prefix('lapangan')->name('lapangan.')->group(function () {
        
        // Dashboard Lapangan
        Route::get('/dashboard', [Lapangan\DashboardLapanganController::class, 'index'])->name('dashboard');
        Route::post('/dokumentasi/upload', [Lapangan\DashboardLapanganController::class, 'uploadFoto'])->name('dokumentasi.upload');
        Route::delete('/dokumentasi/{id}', [Lapangan\DashboardLapanganController::class, 'hapusFoto'])->name('dokumentasi.hapus');

        // Pengajuan Logistik ke Posko Komando
        Route::resource('pengajuan', Lapangan\PengajuanController::class);

        // Pendataan Pengungsi Agregat
        Route::resource('pengungsi', Lapangan\PengungsiController::class);

        // Endpoint Prediksi ML
        Route::get('/predict-logistik', [PredictionController::class, 'predict'])->name('predict.logistik');

        // Penyaluran ke Pengungsi
        Route::resource('penyaluran', Lapangan\PenyaluranController::class);

        // Stok Lapangan & Konfirmasi Penerimaan Logistik
        Route::get('/stok', [Lapangan\StokController::class, 'index'])->name('stok.index');
        Route::post('/stok/{id}/konfirmasi', [Lapangan\StokController::class, 'konfirmasiSampai'])->name('stok.konfirmasi');

        // Manajemen Panggilan Ambulans Lapangan
        Route::get('/ambulans', [Lapangan\LapanganAmbulansController::class, 'index'])->name('ambulans.index');
        Route::post('/ambulans', [Lapangan\LapanganAmbulansController::class, 'store'])->name('ambulans.store');
        Route::post('/ambulans/{id}/konfirmasi', [Lapangan\LapanganAmbulansController::class, 'konfirmasiSelesai'])->name('ambulans.konfirmasi');
    });
});
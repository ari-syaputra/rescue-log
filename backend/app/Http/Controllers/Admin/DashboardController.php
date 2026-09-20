<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Posko;
use App\Models\Bencana;
use App\Models\PengajuanKebutuhan;
use App\Models\Pengiriman;
use App\Models\PengirimanInventaris;
use App\Models\StokInventaris;
use App\Models\Pendataan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $bpbd = $user->bpbd;

        // 1. Ambil data Posko Komando milik BPBD user
        $posko = Posko::where('tipe_posko', 'komando')
            ->where('bpbd_id', $user->bpbd_id)
            ->first();

        // 2. Ambil data Bencana Aktif
        $bencanaAktif = null;
        if ($posko && $posko->bencana_id) {
            $bencanaAktif = $posko->bencana;
        } else {
            $bencanaAktif = Bencana::where('status', 'sedang_berjalan')->first();
        }

        // 3. STATISTIK UTAMA
        $permintaanMasukCount = PengajuanKebutuhan::where('status', 'menunggu')->count();
        $distribusiBerjalanCount = Pengiriman::where('status_pengiriman', 'dalam_perjalanan')->count();

        // 4. CHART TREN BENCANA (7 Hari Terakhir)
        $chartLabels = [];
        $chartBencanaData = [];
        $chartDitanganiData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->translatedFormat('d M');

            $countTotal = Bencana::whereDate('created_at', $date->toDateString())->count();
            $countSelesai = Bencana::whereDate('created_at', $date->toDateString())
                ->where('status', 'selesai')
                ->count();

            $chartBencanaData[] = $countTotal;
            $chartDitanganiData[] = $countSelesai;
        }

        // 5. STOK LOGISTIK (Mengambil dari Tabel stok_inventaris)
        $totalBarang = class_exists(StokInventaris::class) ? StokInventaris::count() : 0;
        
        $stokColumn = 'jumlah_stok';
        if (!Schema::hasColumn('stok_inventaris', 'jumlah_stok')) {
            $stokColumn = Schema::hasColumn('stok_inventaris', 'stok') ? 'stok' : 'jumlah';
        }

        if (class_exists(StokInventaris::class) && Schema::hasColumn('stok_inventaris', $stokColumn)) {
            $stokTersedia = StokInventaris::where($stokColumn, '>', 50)->count();
            $stokMenipis  = StokInventaris::whereBetween($stokColumn, [1, 50])->count();
            $stokHabis    = StokInventaris::where($stokColumn, '<=', 0)->count();
        } else {
            $stokTersedia = $totalBarang;
            $stokMenipis  = 0;
            $stokHabis    = 0;
        }

        $persenTersedia = $totalBarang > 0 ? round(($stokTersedia / $totalBarang) * 100) : 0;

        // 6. PERMINTAAN MASUK (5 Pengajuan Terbaru)
        $permintaanTerbaru = PengajuanKebutuhan::with('posko')
            ->latest()
            ->take(5)
            ->get();

        // 7. TABEL DISTRIBUSI TERAKHIR (5 Pengiriman Terbaru)
        $distribusiTerakhir = Pengiriman::with(['poskoTujuan', 'armada'])
            ->latest()
            ->take(5)
            ->get();

        // 8. RINGKASAN LAPORAN
        $totalBencanaDitangani = Bencana::where('status', 'selesai')->count();

        if (class_exists(PengirimanInventaris::class)) {
            $totalPaketTersalurkan = (int) PengirimanInventaris::whereIn('status_distribusi', ['Selesai', 'Terkirim', 'Diterima'])
                ->sum('jumlah_dikirim');
        } else {
            $totalPaketTersalurkan = 0;
        }

        // Deteksi Otomatis Kolom Jumlah Jiwa di Tabel Pendataans
        $jiwaColumn = null;
        if (Schema::hasColumn('pendataans', 'total_jiwa')) {
            $jiwaColumn = 'total_jiwa';
        } elseif (Schema::hasColumn('pendataans', 'jumlah_pengungsi')) {
            $jiwaColumn = 'jumlah_pengungsi';
        } elseif (Schema::hasColumn('pendataans', 'total_pengungsi')) {
            $jiwaColumn = 'total_pengungsi';
        } elseif (Schema::hasColumn('pendataans', 'jiwa')) {
            $jiwaColumn = 'jiwa';
        } elseif (Schema::hasColumn('pendataans', 'jumlah')) {
            $jiwaColumn = 'jumlah';
        } elseif (Schema::hasColumn('pendataans', 'jumlah_jiwa')) {
            $jiwaColumn = 'jumlah_jiwa';
        }

        if ($jiwaColumn) {
            $totalPengungsiTerlayani = Pendataan::sum($jiwaColumn) ?? 0;
        } else {
            // Fallback hitung jumlah baris record pendataan
            $totalPengungsiTerlayani = Pendataan::count();
        }

        return view('dashboard.admin.index', compact(
            'posko', 
            'bencanaAktif', 
            'bpbd',
            'permintaanMasukCount',
            'distribusiBerjalanCount',
            'chartLabels',
            'chartBencanaData',
            'chartDitanganiData',
            'totalBarang',
            'stokTersedia',
            'stokMenipis',
            'stokHabis',
            'persenTersedia',
            'permintaanTerbaru',
            'distribusiTerakhir',
            'totalBencanaDitangani',
            'totalPaketTersalurkan',
            'totalPengungsiTerlayani'
        ));
    }

    public function storePosko(Request $request)
    {
        $request->validate([
            'nama_posko'       => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'kontak_hp'        => 'required|string|max:20',
        ]);

        $user = auth()->user();
        $bpbd = $user->bpbd;

        $posko = Posko::create([
            'nama_posko'       => $request->nama_posko,
            'penanggung_jawab' => $request->penanggung_jawab,
            'kontak_hp'        => $request->kontak_hp,
            'tipe_posko'       => 'komando',
            'status'           => 'terdaftar_nonaktif',
            'bpbd_id'          => $user->bpbd_id ?? 1,
            'alamat'           => $bpbd->alamat_kantor ?? null,
            'latitude'         => $bpbd->latitude ?? null,
            'longitude'        => $bpbd->longitude ?? null,
        ]);

        return redirect()->back()->with('success', 'Posko Komando "' . $posko->nama_posko . '" berhasil didaftarkan!');
    }

    public function aktifkanPosko(Request $request, $id)
    {
        $posko = Posko::findOrFail($id);
        $bencanaAktif = Bencana::where('status', 'sedang_berjalan')->first();

        if (!$bencanaAktif) {
            return redirect()->back()->with('error', 'Gagal mengaktifkan posko: Tidak ada kejadian bencana aktif di sistem!');
        }

        $posko->update([
            'status'     => 'aktif',
            'bencana_id' => $bencanaAktif->id,
        ]);

        return redirect()->back()->with('success', 'Posko Komando berhasil diaktifkan untuk bencana ' . $bencanaAktif->jenis_bencana . '!');
    }

    public function selesaikanPosko(Request $request, $id)
    {
        $posko = Posko::findOrFail($id);

        if ($posko->bencana_id) {
            Bencana::where('id', $posko->bencana_id)->update([
                'status' => 'selesai'
            ]);
        }

        $posko->update([
            'status'     => 'nonaktif',
            'bencana_id' => null,
        ]);

        return redirect()->back()->with('success', 'Posko Komando telah dinonaktifkan & tanggap darurat dinyatakan selesai.');
    }
}
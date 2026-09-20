<?php

namespace App\Http\Controllers\Bnpb;

use App\Http\Controllers\Controller;
use App\Models\Bencana;
use App\Models\Bpbd;
use App\Models\PengajuanKebutuhan;
use App\Models\Pendataan;
use App\Models\Posko;
use App\Models\StokInventaris;
use Illuminate\Http\Request;

class BnpbDashboardController extends Controller
{
    /**
     * Dashboard Utama National Command Center BNPB Pusat
     */
    public function index()
    {
        // 1. KPI Agregat Nasional
        $totalBencanaAktif = Bencana::where('status', 'sedang_berjalan')->count();
        $totalPengungsiNasional = Pendataan::sum('total_pengungsi') ?? 0;
        $totalPoskoOperasional = Posko::count();
        $totalEskalasiBantuan = PengajuanKebutuhan::whereIn('status', ['pending', 'dieskalasi_provinsi'])->count();
        $totalStokNasional = StokInventaris::sum('jumlah') ?? 0;

        // 2. Bencana Utama / Highlight Nasional (Ambil bencana terbaru atau paling banyak dampak)
        $bencanaUtama = Bencana::where('status', 'sedang_berjalan')
            ->orderBy('total_jiwa_terdampak', 'desc')
            ->first();

        // 3. Daftar Bencana Aktif Seluruh Indonesia
        $bencanaList = Bencana::where('status', 'sedang_berjalan')
            ->orderBy('created_at', 'desc')
            ->get();

        // 4. Agregat Status Bencana per Provinsi
        $provinsiStats = Bpbd::all()->map(function($bpbd) {
            $bpbd->total_bencana = Bencana::where('status', 'sedang_berjalan')
                ->whereHas('poskos', function($q) use ($bpbd) {
                    $q->where('bpbd_id', $bpbd->id);
                })->count();
            
            $bpbd->total_pengungsi = Pendataan::whereHas('posko', function($q) use ($bpbd) {
                $q->where('bpbd_id', $bpbd->id);
            })->sum('total_pengungsi');

            return $bpbd;
        });

        // 5. Permintaan Eskalasi Bantuan Nasional yang Menunggu ACC
        $eskalasiNasional = PengajuanKebutuhan::with(['user', 'posko', 'bencana'])
            ->whereIn('status', ['pending', 'dieskalasi_provinsi'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.bnpb.index', compact(
            'totalBencanaAktif',
            'totalPengungsiNasional',
            'totalPoskoOperasional',
            'totalEskalasiBantuan',
            'totalStokNasional',
            'bencanaUtama',
            'bencanaList',
            'provinsiStats',
            'eskalasiNasional'
        ));
    }

    /**
     * Monitoring Sebaran GIS Bencana Nasional
     */
    public function monitoring()
    {
        $bencanaList = Bencana::all();
        $poskoList = Posko::all();

        return view('dashboard.bnpb.monitoring', compact('bencanaList', 'poskoList'));
    }

    /**
     * Persetujuan Eskalasi Bantuan Bencana oleh BNPB Pusat
     */
    public function approveEskalasi(Request $request, $id)
    {
        $pengajuan = PengajuanKebutuhan::findOrFail($id);
        $pengajuan->status = 'disetujui';
        $pengajuan->catatan_eskalasi = $request->input('catatan', 'Disetujui oleh BNPB Pusat (Dana Hibah / Logistik Nasional)');
        $pengajuan->save();

        return redirect()->back()->with('success', 'Eskalasi bantuan nasional berhasil disetujui oleh BNPB Pusat!');
    }
}
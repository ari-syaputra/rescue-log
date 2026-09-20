<?php

namespace App\Http\Controllers\Provinsi;

use App\Http\Controllers\Controller;
use App\Models\PengajuanKebutuhan;
use Illuminate\Http\Request;

class ProvinsiEskalasiController extends Controller
{
    /**
     * Menampilkan Halaman Kelola Eskalasi Bantuan Kab/Kota
     */
    public function index()
    {
        $eskalasiList = PengajuanKebutuhan::with(['user', 'posko', 'bencana'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.provinsi.eskalasi.index', compact('eskalasiList'));
    }

    /**
     * Persetujuan Bantuan Logistik dari Stok Penyangga Provinsi
     */
    public function approve(Request $request, $id)
    {
        $pengajuan = PengajuanKebutuhan::findOrFail($id);
        $pengajuan->status = 'disetujui';
        $pengajuan->catatan_eskalasi = $request->input('catatan', 'Disetujui oleh BPBD Provinsi (Suplai dari Gudang Penyangga Provinsi)');
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan logistik berhasil disetujui & disuport dari Gudang Provinsi!');
    }

    /**
     * Meneruskan Eskalasi Bantuan ke BNPB Pusat
     */
    public function diteruskanKeBnpb(Request $request, $id)
    {
        $pengajuan = PengajuanKebutuhan::findOrFail($id);
        $pengajuan->status = 'dieskalasi_provinsi'; // Status diteruskan ke BNPB
        $pengajuan->catatan_eskalasi = $request->input('catatan', 'Diteruskan ke BNPB Pusat karena cadangan logistik provinsi kritis.');
        $pengajuan->save();

        return redirect()->back()->with('info', 'Permintaan logistik telah berhasil diteruskan ke BNPB Pusat!');
    }
}
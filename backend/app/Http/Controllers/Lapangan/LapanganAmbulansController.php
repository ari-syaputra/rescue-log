<?php

namespace App\Http\Controllers\Lapangan;

use App\Http\Controllers\Controller;
use App\Models\PermintaanAmbulans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LapanganAmbulansController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $poskoId = $user->posko_id;

        // Ambil data permintaan ambulans dari Sub-Posko ini
        $requests = PermintaanAmbulans::with(['armada', 'posko'])
            ->where('posko_id', $poskoId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Cek jika ada permintaan yang sedang aktif / berlangsung
        $activeRequest = $requests->whereIn('status', ['menunggu_penanganan', 'ambulans_meluncur', 'proses_evakuasi'])->first();

        return view('dashboard.lapangan.ambulans.index', compact('requests', 'activeRequest'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pasien'      => 'required|string|max:255',
            'kategori_darurat' => 'required|in:kritis_nyawa,berat,sedang',
            'kondisi_medis'    => 'required|string',
        ]);

        $user = Auth::user();

        // Cegah pengajuan ganda jika masih ada yang aktif
        $hasActive = PermintaanAmbulans::where('posko_id', $user->posko_id)
            ->whereIn('status', ['menunggu_penanganan', 'ambulans_meluncur', 'proses_evakuasi'])
            ->exists();

        if ($hasActive) {
            return redirect()->back()->with('error', 'Posko Anda masih memiliki panggilan ambulans darurat yang sedang aktif!');
        }

        // Buat Kode SOS unik
        $kodeSos = 'SOS-' . date('Ymd') . '-' . rand(1000, 9999);

        PermintaanAmbulans::create([
            'kode_sos'         => $kodeSos,
            'posko_id'         => $user->posko_id,
            'bencana_id'       => $user->posko->bencana_id ?? null,
            'user_id'          => $user->id,
            'nama_pasien'      => $request->nama_pasien,
            'kategori_darurat' => $request->kategori_darurat,
            'kondisi_medis'    => $request->kondisi_medis,
            'status'           => 'menunggu_penanganan',
            'waktu_request'    => now(),
        ]);

        return redirect()->back()->with('success', 'Sinyal SOS Panggilan Ambulans Darurat berhasil terkirim ke Posko Komando!');
    }

    public function konfirmasiSelesai($id)
    {
        $permintaan = PermintaanAmbulans::findOrFail($id);

        $permintaan->update([
            'status'        => 'selesai',
            'waktu_selesai' => now(),
        ]);

        // Bebaskan status armada jika ada yang diplot
        if ($permintaan->armada) {
            $permintaan->armada->update(['status' => 'tersedia']);
        }

        return redirect()->back()->with('success', 'Evakuasi pasien dikonfirmasi selesai.');
    }
}
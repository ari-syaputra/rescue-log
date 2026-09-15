<?php

namespace App\Http\Controllers\Lapangan;

use App\Http\Controllers\Controller;
use App\Models\Pendataan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengungsiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil posko_id dari user yang sedang login
        $poskoId = $user->posko_id;

        // Query berdasarkan posko_id
        $pendataan_terakhir = Pendataan::where('posko_id', $poskoId)
            ->latest()
            ->first();

        $riwayat_pendataan = Pendataan::where('posko_id', $poskoId)
            ->latest()
            ->get();

        $isFirstTime = $riwayat_pendataan->isEmpty();

        return view('dashboard.lapangan.pengungsi.index', compact(
            'pendataan_terakhir',
            'riwayat_pendataan',
            'isFirstTime'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validasi seluruh inputan dari Modal Pendataan
        $validated = $request->validate([
            'total_pengungsi'  => 'required|integer|min:0',
            'balita'           => 'required|integer|min:0',
            'dewasa'           => 'required|integer|min:0',
            'lansia'           => 'required|integer|min:0',
            'ibu_hamil'        => 'required|integer|min:0',
            'disabilitas'      => 'required|integer|min:0',
            'tipe_tempat'      => 'required|string',
            'akses_air'        => 'required|string',
            'akses_jalan'      => 'required|string',
            'lama_pengungsian' => 'required|integer|min:1',
            'cuaca'            => 'nullable|string',
            'suhu_celcius'     => 'nullable|numeric',
            'catatan'          => 'nullable|string',
        ]);

        // Masukkan posko_id milik user yang sedang login
        $validated['posko_id'] = $user->posko_id;

        // Validasi pengaman jika akun lapangan belum terikat posko
        if (!$validated['posko_id']) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Akun lapangan belum terikat ke Posko manapun.'], 422);
            }
            return redirect()->back()->with('error', 'Gagal: Akun lapangan Anda belum terikat ke Posko manapun.');
        }

        // Simpan data pendataan baru ke database
        $pendataan = Pendataan::create($validated);

        // Jika request berasal dari sinkronisasi latar belakang offline (AJAX/JSON)
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data pendataan offline berhasil disinkronkan.',
                'data' => $pendataan
            ]);
        }

        // REDIRECT LANGSUNG KE HALAMAN PENGAJUAN LOGISTIK
        return redirect()->route('lapangan.pengajuan.index')
            ->with('success', 'Data pengungsi berhasil diperbarui! Hasil kalkulasi rekomendasi logistik AI telah disesuaikan.');
    }
}
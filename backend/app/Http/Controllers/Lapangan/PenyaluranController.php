<?php

namespace App\Http\Controllers\Lapangan;

use App\Http\Controllers\Controller;
use App\Models\StokInventaris;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenyaluranController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil stok barang yang tersedia di posko ini
        $stoks = StokInventaris::where('posko_id', $user->posko_id)
            ->where('jumlah', '>', 0)
            ->get();

        // Ambil riwayat penyaluran keluar
        $riwayatPenyaluran = RiwayatStok::with('barang')
            ->where('posko_id', $user->posko_id)
            ->where('jenis', 'keluar')
            ->latest()
            ->paginate(10);

        return view('dashboard.lapangan.penyaluran.index', compact('stoks', 'riwayatPenyaluran'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'stok_inventaris_id' => 'required|exists:stok_inventaris,id',
            'jumlah_keluar'      => 'required|numeric|min:0.01',
            'target_penerima'    => 'required|string|max:255',
            'keterangan'         => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $stokItem = StokInventaris::where('id', $validated['stok_inventaris_id'])
                ->where('posko_id', $user->posko_id)
                ->firstOrFail();

            // 1. Cek Ketersediaan Stok
            if ($stokItem->jumlah < $validated['jumlah_keluar']) {
                return redirect()->back()
                    ->with('error', "Stok {$stokItem->nama_barang} tidak cukup! Sisa: {$stokItem->jumlah} {$stokItem->satuan}")
                    ->withInput();
            }

            // 2. Kurangi Stok di Sub-Posko
            $stokItem->decrement('jumlah', $validated['jumlah_keluar']);

            // 3. Catat ke Tabel riwayat_stok
            RiwayatStok::create([
                'posko_id'     => $user->posko_id,
                'barang_id'    => $stokItem->id,
                'jenis'        => 'keluar',
                'jumlah'       => $validated['jumlah_keluar'],
                'referensi'    => 'distribusi_lapangan',
                'keterangan'   => "Penyaluran ke {$validated['target_penerima']}. " . ($validated['keterangan'] ?? ''),
            ]);

            DB::commit();

            return redirect()->route('lapangan.stok.index')
                ->with('success', "Berhasil menyalurkan {$validated['jumlah_keluar']} {$stokItem->satuan} {$stokItem->nama_barang} ke {$validated['target_penerima']}. Stok berkurang!");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal mencatat penyaluran: ' . $e->getMessage())
                ->withInput();
        }
    }
}
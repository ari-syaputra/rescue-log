<?php

namespace App\Http\Controllers\Komando;

use App\Http\Controllers\Controller;
use App\Models\Armada;
use App\Models\KendalaJalan;
use App\Models\PengajuanKebutuhan;
use App\Models\PengirimanInventaris;
use App\Models\StokInventaris;
use App\Models\Posko;
use Illuminate\Http\Request;

class KomandoDistribusiController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Ambil posko milik user
        $posko = null;
        if ($user->posko_id) {
            $posko = Posko::find($user->posko_id);
        }

        if (!$posko) {
            $posko = Posko::where('tipe_posko', 'komando')
                ->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('bpbd_id', $user->bpbd_id);
                })
                ->first();
        }

        $poskoId = $posko ? $posko->id : null;

        // 2. Data Pengajuan Masuk dari Sub-Posko
        $pengajuans = PengajuanKebutuhan::with(['posko', 'items.inventaris'])
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Data Pengajuan Siap Kirim
        $pengajuanSiapKirim = PengajuanKebutuhan::whereIn('status', ['disetujui', 'disetujui_komando'])->get();

        // 4. Data Armada Siaga
        $armadas = Armada::orderBy('created_at', 'desc')->get();

        // 5. Data Riwayat / Proses Pengiriman
        $pengirimans = PengirimanInventaris::with(['pengajuan.posko', 'armada'])
            ->orderBy('created_at', 'desc')
            ->get();

        // 6. Data Stok Inventaris Komando
        $stoks = StokInventaris::with('inventaris')
            ->where('posko_id', $poskoId)
            ->get();

        // 7. Data Kendala Jalan
        $kendalaJalans = KendalaJalan::orderBy('created_at', 'desc')->get();

        return view('dashboard.komando.distribusi.index', compact(
            'posko',
            'pengajuans',
            'pengajuanSiapKirim',
            'armadas',
            'pengirimans',
            'stoks',
            'kendalaJalans'
        ));
    }

    /**
     * Menyimpan data Armada baru ke Database
     */
    public function storeArmada(Request $request)
    {
        $validated = $request->validate([
            'nama_armada'   => 'required|string|max:255',
            'jenis_armada'  => 'required|string|max:100',
            'plat_nomor'    => 'nullable|string|max:50',
            'nama_driver'   => 'nullable|string|max:255',
            'kontak_driver' => 'nullable|string|max:50',
            'kapasitas_kg'  => 'nullable|numeric|min:0',
            'status'        => 'required|string|in:tersedia,siap,dalam_perjalanan,pemeliharaan',
        ]);

        $user = auth()->user();
        $posko = $user->posko_id ? Posko::find($user->posko_id) : Posko::where('tipe_posko', 'komando')->first();

        Armada::create([
            'nama_armada'   => $validated['nama_armada'],
            'jenis_armada'  => $validated['jenis_armada'],
            'plat_nomor'    => $validated['plat_nomor'] ?? null,
            'nama_driver'   => $validated['nama_driver'] ?? null,
            'kontak_driver' => $validated['kontak_driver'] ?? null,
            'kapasitas_kg'  => $validated['kapasitas_kg'] ?? 0,
            'status'        => $validated['status'],
            'posko_id'      => $posko?->id,
            'bpbd_id'       => $user->bpbd_id,
        ]);

        return redirect()->route('komando.distribusi.index')->with('success', 'Armada pengiriman berhasil ditambahkan!');
    }

    /**
     * Menyimpan data Laporan Kendala Jalan baru ke Database (Smart Routing GIS)
     */
    public function storeKendala(Request $request)
    {
        $validated = $request->validate([
            'nama_lokasi'   => 'required|string|max:255',
            'jenis_kendala' => 'required|string|max:100',
            'latitude'      => 'required|numeric',
            'longitude'     => 'required|numeric',
            'deskripsi'     => 'nullable|string',
        ]);

        $user = auth()->user();

        KendalaJalan::create([
            'nama_lokasi'   => $validated['nama_lokasi'],
            'jenis_kendala' => $validated['jenis_kendala'],
            'latitude'      => $validated['latitude'],
            'longitude'     => $validated['longitude'],
            'deskripsi'     => $validated['deskripsi'] ?? null,
            'is_active'     => true,
            'user_id'       => $user->id,
            'bpbd_id'       => $user->bpbd_id,
        ]);

        return redirect()->route('komando.distribusi.index')->with('success', 'Laporan kendala jalan berhasil ditambahkan ke sistem rute!');
    }

    /**
     * Mengubah status aktif / non-aktif Kendala Jalan (Selesai/Aktif)
     */
    public function toggleKendala($id)
    {
        $kendala = KendalaJalan::findOrFail($id);
        $kendala->is_active = !$kendala->is_active;
        $kendala->save();

        $statusMessage = $kendala->is_active ? 'diaktifkan kembali' : 'ditandai selesai / dapat dilalui';

        return redirect()->route('komando.distribusi.index')->with('success', "Status kendala jalan berhasil {$statusMessage}!");
    }
}
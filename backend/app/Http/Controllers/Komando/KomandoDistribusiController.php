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
                ->where('user_id', $user->id)
                ->first();
        }

        $poskoId = $posko ? $posko->id : null;

        // 2. Data Pengajuan Masuk dari Sub-Posko
        $pengajuans = PengajuanKebutuhan::with(['posko', 'items.inventaris'])
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Data Pengajuan Siap Kirim (TAMBAHKAN INI)
        // Sesuaikan 'disetujui' dengan value status di database Anda (misal: 'disetujui' / 'siap_kirim')
        $pengajuanSiapKirim = PengajuanKebutuhan::where('status', 'disetujui')->get();

        // 4. Data Armada Siaga
        $armadas = Armada::where('status', 'tersedia')->get();

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
            'pengajuanSiapKirim', // <--- MENGATASI ERROR: Variabel ditambahkan di sini
            'armadas',
            'pengirimans',
            'stoks',
            'kendalaJalans'
        ));
    }
}
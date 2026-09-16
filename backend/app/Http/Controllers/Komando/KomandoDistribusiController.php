<?php

namespace App\Http\Controllers\Komando;

use App\Http\Controllers\Controller;
use App\Models\Armada;
use App\Models\KendalaJalan;
use App\Models\PengajuanKebutuhan;
use App\Models\PengirimanInventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Posko;

class KomandoDistribusiController extends Controller
{
    /**
     * Menampilkan Dashboard Distribusi & Fleet Tracking Khusus Pengiriman Posko Komando -> Sub-Posko
     */
    public function index()
    {
        $user = Auth::user();
        $komandoPoskoId = $user->posko_id;

        // 1. Load Data Posko Komando milik komandan yang login
        $posko = Posko::with(['children', 'bencana', 'bpbd', 'user.bpbd'])->find($komandoPoskoId);
        if (!$posko) {
            $posko = Posko::with(['children', 'bencana', 'bpbd', 'user.bpbd'])
                ->where('user_id', $user->id)
                ->first();
        }

        // 2. Ambil BPBD Induk (Prioritas: Posko -> User BPBD -> Fallback DB BPBD Pertama)
        $bpbd = $posko?->bpbd 
            ?? $user->bpbd 
            ?? \App\Models\Bpbd::first();

        // Pastikan koordinat BPBD ada fallback default (Bantul)
        if ($bpbd) {
            $bpbd->latitude = $bpbd->latitude ?? -7.8893;
            $bpbd->longitude = $bpbd->longitude ?? 110.3288;
            $bpbd->nama_kabupaten_kota = $bpbd->nama_kabupaten_kota ?? 'BPBD Kabupaten Bantul';
            $bpbd->alamat_kantor = $bpbd->alamat_kantor ?? 'Jl. Jend. A. Yani No. 1, Badegan, Bantul';
        }

        $bencana = $posko ? $posko->bencana : null;

        // 3. Ambil seluruh Sub-Posko (baik children dari posko ini maupun sub-posko bencana terkait)
        $subPoskoList = Posko::where('tipe_posko', '!=', 'komando')
            ->where(function ($q) use ($posko) {
                if ($posko) {
                    $q->where('parent_id', $posko->id)
                    ->orWhere('bencana_id', $posko->bencana_id);
                }
            })
            ->get();

        // 4. Pengajuan & Pengiriman
        $pengajuanSiapKirim = PengajuanKebutuhan::with(['posko.bencana', 'user', 'bencana'])
            ->where('status', 'disetujui')
            ->whereHas('posko', function ($q) use ($komandoPoskoId) {
                $q->where('tipe_posko', '!=', 'komando')
                ->orWhere('parent_id', $komandoPoskoId);
            })
            ->latest()
            ->get();

        $pengirimans = PengirimanInventaris::with(['pengajuan.posko', 'pengajuan.user', 'user', 'armada', 'posko'])
            ->whereHas('pengajuan.posko', function ($q) use ($komandoPoskoId) {
                $q->where('tipe_posko', '!=', 'komando')
                ->orWhere('parent_id', $komandoPoskoId);
            })
            ->latest()
            ->get();

        $armadas = Armada::where('status', 'tersedia')->get();
        $kendalaJalans = KendalaJalan::latest()->get();

        return view('dashboard.komando.distribusi.index', compact(
            'posko',
            'bpbd',
            'bencana',
            'subPoskoList',
            'pengajuanSiapKirim',
            'pengirimans',
            'armadas',
            'kendalaJalans'
        ));
    }

    /**
     * Menugaskan Armada & Memulai Pengiriman Logistik ke Sub-Posko
     */
    public function store(Request $request)
    {
        $request->validate([
            'pengajuan_id' => 'required|exists:pengajuan_kebutuhan,id',
            'armada_id'    => 'required|exists:armadas,id',
        ]);

        return DB::transaction(function () use ($request) {
            $pengajuan = PengajuanKebutuhan::findOrFail($request->pengajuan_id);
            $armada = Armada::findOrFail($request->armada_id);

            // Update Status Pengajuan
            $pengajuan->update([
                'status' => 'dalam_pengiriman'
            ]);

            // Update Status Armada menjadi Dalam Tugas
            $armada->update([
                'status' => 'dalam_tugas'
            ]);

            // Total unit barang yang dikirim
            $totalJumlah = ($pengajuan->beras_kg ?? 0) +
                           ($pengajuan->air_minum_dus ?? 0) +
                           ($pengajuan->makanan_kaleng_pack ?? 0) +
                           ($pengajuan->makanan_bayi_pack ?? 0) +
                           ($pengajuan->minyak_goreng_liter ?? 0) +
                           ($pengajuan->popok_bayi_pcs ?? 0) +
                           ($pengajuan->popok_dewasa_pcs ?? 0) +
                           ($pengajuan->pembalut_wanita_pack ?? 0) +
                           ($pengajuan->hygiene_kit_paket ?? 0) +
                           ($pengajuan->selimut_pcs ?? 0) +
                           ($pengajuan->matras_terpal_pcs ?? 0) +
                           ($pengajuan->obat_p3k_paket ?? 0);

            // Buat / Update Record Pengiriman Inventaris
            PengirimanInventaris::updateOrCreate(
                ['pengajuan_id' => $pengajuan->id],
                [
                    'posko_id'          => $pengajuan->posko_id,
                    'user_id'           => Auth::id(),
                    'jumlah_dikirim'    => $totalJumlah,
                    'status_distribusi' => 'Dalam Perjalanan',
                    'keterangan'        => "Pengiriman ke {$pengajuan->posko->nama_posko} menggunakan Armada {$armada->nama_armada} ({$armada->plat_nomor})",
                ]
            );

            return redirect()->back()->with('success', "Armada {$armada->nama_armada} berhasil ditugaskan untuk pengiriman ke Sub-Posko {$pengajuan->posko->nama_posko}!");
        });
    }

    /**
     * Pelaporan Kendala Jalan Baru
     */
    public function storeKendala(Request $request)
    {
        $request->validate([
            'nama_lokasi'   => 'required|string|max:255',
            'jenis_kendala' => 'required|string',
            'latitude'      => 'required|numeric',
            'longitude'     => 'required|numeric',
        ]);

        KendalaJalan::create([
            'nama_lokasi'   => $request->nama_lokasi,
            'jenis_kendala' => $request->jenis_kendala,
            'latitude'      => $request->latitude,
            'longitude'     => $request->longitude,
            'deskripsi'     => $request->deskripsi,
            'is_active'     => true,
        ]);

        return redirect()->back()->with('success', 'Laporan kendala jalan berhasil ditambahkan ke sistem GIS.');
    }

    /**
     * Pendaftaran Armada / Kendaraan Operasional Baru
     */
    public function storeArmada(Request $request)
    {
        $request->validate([
            'nama_armada' => 'required|string|max:255',
            'plat_nomor'  => 'required|string|max:50|unique:armadas,plat_nomor',
            'nama_driver' => 'required|string|max:255',
            'no_hp'       => 'nullable|string|max:20',
        ], [
            'nama_armada.required' => 'Nama armada/kendaraan wajib diisi.',
            'plat_nomor.required'  => 'Plat nomor kendaraan wajib diisi.',
            'plat_nomor.unique'    => 'Plat nomor ini sudah terdaftar di sistem.',
            'nama_driver.required' => 'Nama pengemudi wajib diisi.',
        ]);

        Armada::create([
            'nama_armada' => $request->nama_armada,
            'plat_nomor'  => strtoupper($request->plat_nomor),
            'nama_driver' => $request->nama_driver,
            'no_hp'       => $request->no_hp,
            'status'      => 'tersedia',
        ]);

        return redirect()->back()->with('success', "Armada '{$request->nama_armada}' ({$request->plat_nomor}) berhasil didaftarkan dan siap beroperasi.");
    }
}
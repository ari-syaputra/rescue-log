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

class KomandoDistribusiController extends Controller
{
    /**
     * Menampilkan Dashboard Distribusi & Fleet Tracking Khusus Pengiriman Posko Komando -> Sub-Posko
     */
    public function index()
    {
        $komandoPoskoId = Auth::user()->posko_id;

        // 1. Ambil Pengajuan Logistik HANYA dari Sub-Posko Lapangan yang SUDAH DISETUJUI
        $pengajuanSiapKirim = PengajuanKebutuhan::with(['posko.bencana', 'user', 'bencana'])
            ->where('status', 'disetujui')
            ->whereHas('posko', function ($q) use ($komandoPoskoId) {
                // Menyaring agar hanya Sub-Posko (bukan Posko Komando sendiri)
                $q->where('tipe_posko', '!=', 'komando')
                  ->orWhere('parent_id', $komandoPoskoId);
            })
            ->latest()
            ->get();

        // 2. Data Pengiriman Aktif / Dalam Perjalanan khusus yang ditangani Posko Komando
        $pengirimans = PengirimanInventaris::with(['pengajuan.posko', 'pengajuan.user', 'user', 'armada', 'posko'])
            ->whereHas('pengajuan.posko', function ($q) use ($komandoPoskoId) {
                $q->where('tipe_posko', '!=', 'komando')
                  ->orWhere('parent_id', $komandoPoskoId);
            })
            ->latest()
            ->get();

        // 3. Data Armada Siaga yang Tersedia
        $armadas = Armada::where('status', 'tersedia')->get();

        // 4. Data Kendala Jalan Real-Time GIS
        $kendalaJalans = KendalaJalan::latest()->get();

        return view('dashboard.komando.distribusi.index', compact(
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
}
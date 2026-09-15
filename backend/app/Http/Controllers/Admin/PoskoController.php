<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Posko;
use App\Models\Bencana;
use App\Models\User;
use App\Models\StokInventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // <-- TAMBAHKAN IMPORT INI

class PoskoController extends Controller
{
    /**
     * Tampilkan Halaman Management & Aktivasi Posko Komando
     */
    public function create(Request $request)
    {
        $bpbd = Auth::user()->bpbd;

        // Pastikan BPBD memiliki koordinat default jika belum di-set di database
        if ($bpbd) {
            $bpbd->latitude = $bpbd->latitude ?? -7.8893;
            $bpbd->longitude = $bpbd->longitude ?? 110.3288;
            $bpbd->nama_kabupaten_kota = $bpbd->nama_kabupaten_kota ?? 'BPBD Kabupaten Bantul';
            $bpbd->alamat_kantor = $bpbd->alamat_kantor ?? 'Jl. Jend. A. Yani No. 1, Badegan, Bantul';
        }

        $bencanaId = $request->query('bencana_id');
        $bencana = null;
        
        if ($bencanaId) {
            // Ambil spesifik bencana yang dikirim via URL parameter
            $bencana = Bencana::where('id', $bencanaId)->first();
        }
        
        if (!$bencana) {
            // Prioritaskan mencari yang 'sedang_berjalan' terlebih dahulu, baru 'menunggu_posko'
            $bencana = Bencana::where('status', 'sedang_berjalan')->latest()->first()
                    ?? Bencana::where('status', 'menunggu_posko')->latest()->first();
        }

        $availablePosko = Posko::where('tipe_posko', 'komando')
            ->with('user')
            ->where('bpbd_id', $bpbd?->id)
            ->latest()
            ->get();

        $subPoskoList = Posko::where('tipe_posko', '!=', 'komando')
            ->where('bpbd_id', $bpbd?->id)
            ->get();

        return view('dashboard.admin.posko.create', compact('bpbd', 'bencana', 'availablePosko', 'subPoskoList'));
    }

    /**
     * TAHAP 1: Registrasi Master Posko Komando Baru + Akun User Komandan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_posko'       => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'kontak_hp'        => 'required|string|max:20',
            'lokasi'           => 'required|string',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|string|min:6',
            'latitude'         => 'nullable|numeric',
            'longitude'        => 'nullable|numeric',
        ]);

        $bpbd = Auth::user()->bpbd;

        return DB::transaction(function () use ($validated, $bpbd) {
            // 1. Buat Akun User Komandan
            $user = User::create([
                'name'     => $validated['penanggung_jawab'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => 'komando',
                'bpbd_id'  => $bpbd?->id,
            ]);

            $posko = Posko::create([
                'nama_posko'       => $validated['nama_posko'],
                'tipe_posko'       => 'komando',
                'user_id'          => $user->id,
                'bpbd_id'          => $bpbd?->id,
                'bencana_id'       => null,
                'penanggung_jawab' => $validated['penanggung_jawab'],
                'kontak_hp'        => $validated['kontak_hp'],
                'lokasi'           => $validated['lokasi'],
                'latitude'         => $validated['latitude'] ?? null,
                'longitude'        => $validated['longitude'] ?? null,
                'status'           => 'nonaktif', // <-- Ganti 'siaga' menjadi 'nonaktif'
                'kode_undangan'    => 'KOMANDO-' . strtoupper(Str::random(6)),
            ]);

            // Update posko_id pada User
            $user->update(['posko_id' => $posko->id]);

            return redirect()->route('admin.posko.create')
                ->with('success', "Posko Komando '{$posko->nama_posko}' dan Akun Komandan ({$user->email}) berhasil didaftarkan! Status saat ini: Standby.");
        });
    }

    /**
     * TAHAP 2: Aktifkan & Plotting Penempatan GIS Posko ke Bencana + Transfer Buffer Stock Awal
     */
    public function activateExisting(Request $request)
    {
        $validated = $request->validate([
            'bencana_id' => 'required|exists:bencana,id',
            'posko_id'   => 'required|exists:poskos,id',
            'lokasi'     => 'required|string',
            'latitude'   => 'required|numeric',
            'longitude'  => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $posko = Posko::findOrFail($validated['posko_id']);
            $bencana = Bencana::findOrFail($validated['bencana_id']);
            
            // 1. Update Posko dengan Bencana & Titik Koordinat GIS Baru
            $posko->update([
                'bencana_id' => $validated['bencana_id'],
                'lokasi'     => $validated['lokasi'],
                'latitude'   => $validated['latitude'],
                'longitude'  => $validated['longitude'],
                'status'     => 'aktif',
            ]);

            // 2. Ubah Status Bencana menjadi Sedang Berjalan
            $bencana->update([
                'status' => 'sedang_berjalan'
            ]);

            // 3. ALOKASIKAN BUFFER STOCK LOGISTIK AWAL
            $this->alokasikanBufferStockAwal($posko, $bencana);

            DB::commit();

            // KUNCI PERUBAHAN: Redirect kembali ke halaman posko create dengan membawa ID bencana yang sama
            return redirect()->route('admin.posko.create', ['bencana_id' => $bencana->id])
                ->with('success', "Posko Komando '{$posko->nama_posko}' berhasil DIAKTIFKAN! Buffer Stock Logistik Awal telah dialokasikan ke Posko.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menempatkan posko: ' . $e->getMessage());
        }
    }

    /**
     * LOGIKA REVOLUSIONER: Otomatisasi Alokasi Buffer Stock Awal Logistik
     * Menghitung & Memindahkan Stok dari Gudang Utama BPBD (posko_id = null) ke Stok Posko Komando
     */
    private function alokasikanBufferStockAwal(Posko $posko, Bencana $bencana)
    {
        $pengungsi = $bencana->estimasi_pengungsi_awal ?? 100; // Fallback 100 jiwa jika tidak diisi

        // Formula Kebutuhan Dasar Darurat 3 Hari Pertama (Golden Hours)
        $rasioKebutuhan = [
            'Beras'           => $pengungsi * 0.4 * 3,   // 0.4 kg/jiwa/hari x 3 hari
            'Air Minum'       => ceil($pengungsi * 0.1 * 3), // 0.1 dus/jiwa/hari x 3 hari
            'Makanan Kaleng'  => $pengungsi * 1 * 3,     // 1 pack/jiwa/hari x 3 hari
            'Selimut'         => ceil($pengungsi * 0.3),  // 0.3 pcs/jiwa
            'Matras / Terpal' => ceil($pengungsi * 0.1),  // 0.1 pcs/jiwa
            'Hygiene Kit'     => ceil($pengungsi * 0.05), // 0.05 paket/jiwa
        ];

        foreach ($rasioKebutuhan as $namaBarang => $jumlahDibutuhkan) {
            // 1. Cari Stok di Gudang Utama BPBD (posko_id = null)
            $stokGudang = StokInventaris::whereNull('posko_id')
                ->where('nama_barang', 'LIKE', "%{$namaBarang}%")
                ->first();

            if ($stokGudang && $stokGudang->jumlah > 0) {
                // Tentukan berapa jumlah yang bisa ditransfer (maksimal sebesar stok yang ada)
                $jumlahTransfer = min($stokGudang->jumlah, $jumlahDibutuhkan);

                // Potong Stok Gudang Utama BPBD
                $stokGudang->decrement('jumlah', $jumlahTransfer);

                // 2. Tambahkan atau Buat Stok Logistik Baru di Posko Komando (Compatible dengan PostgreSQL)
                $stokPosko = StokInventaris::where('posko_id', $posko->id)
                    ->where('nama_barang', $stokGudang->nama_barang)
                    ->first();

                if ($stokPosko) {
                    // Jika barang sudah ada di posko, tambahkan jumlahnya
                    $stokPosko->increment('jumlah', $jumlahTransfer);
                } else {
                    // Jika belum ada, buat baris stok baru
                    StokInventaris::create([
                        'posko_id'    => $posko->id,
                        'nama_barang' => $stokGudang->nama_barang,
                        'kategori'    => $stokGudang->kategori,
                        'satuan'      => $stokGudang->satuan,
                        'jumlah'      => $jumlahTransfer,
                        'keterangan'  => "Buffer stock awal alokasi otomatis untuk bencana {$bencana->jenis_bencana}",
                    ]);
                }
            }
        }
    }

    /**
     * Tampilkan Halaman Detail Posko Komando & Stok Logistik Real-Time
     */
    public function show($id)
    {
        // Load Posko komando beserta relasi User, Bencana, dan Stok Inventaris miliknya
        $posko = Posko::with(['user', 'bencana', 'stokInventaris'])
            ->where('tipe_posko', 'komando')
            ->findOrFail($id);

        // Ambil daftar Sub-Posko (Posko Lapangan) yang berada di bawah komando ini
        $subPoskos = Posko::where('tipe_posko', '!=', 'komando')
            ->where('bencana_id', $posko->bencana_id)
            ->get();

        return view('dashboard.admin.posko.show', compact('posko', 'subPoskos'));
    }
}
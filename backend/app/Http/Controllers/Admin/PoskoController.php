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

class PoskoController extends Controller
{
    /**
     * Tampilkan Halaman Management & Aktivasi Posko Komando
     */
    public function create(Request $request)
    {
        $bpbd = Auth::user()->bpbd;
        $bencanaId = $request->query('bencana_id');

        // 1. Cari Bencana yang sedang 'menunggu_posko' (jika ada)
        $bencana = null;
        if ($bencanaId) {
            $bencana = Bencana::where('id', $bencanaId)->first();
        }
        
        if (!$bencana) {
            $bencana = Bencana::where('status', 'menunggu_posko')->latest()->first();
        }

        // 2. Ambil seluruh Posko Komando milik BPBD ini (beserta relasi akun User-nya)
        $availablePosko = Posko::where('tipe_posko', 'komando')
            ->with('user')
            ->where('bpbd_id', $bpbd?->id)
            ->latest()
            ->get();

        return view('dashboard.admin.posko.create', compact('bpbd', 'bencana', 'availablePosko'));
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
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|string|min:6',
            'lokasi'           => 'required|string',
            'bencana_id'       => 'nullable|exists:bencana,id',
        ]);

        $bpbd = Auth::user()->bpbd;

        DB::beginTransaction();
        try {
            // 1. Buat Akun User Komandan Posko
            $userKomandan = User::create([
                'name'     => $validated['penanggung_jawab'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => 'posko_komando',
                'bpbd_id'  => $bpbd?->id,
            ]);

            $isDirectActivate = !empty($validated['bencana_id']);

            // 2. Buat Posko Komando & Hubungkan ke user_id
            $posko = Posko::create([
                'nama_posko'       => $validated['nama_posko'],
                'tipe_posko'       => 'komando',
                'user_id'          => $userKomandan->id,
                'bpbd_id'          => $bpbd?->id,
                'bencana_id'       => $validated['bencana_id'] ?? null,
                'penanggung_jawab' => $validated['penanggung_jawab'],
                'kontak_hp'        => $validated['kontak_hp'],
                'lokasi'           => $validated['lokasi'],
                'status'           => $isDirectActivate ? 'aktif' : 'terdaftar_nonaktif',
            ]);

            // Update posko_id pada user
            $userKomandan->update(['posko_id' => $posko->id]);

            if ($isDirectActivate) {
                $bencana = Bencana::find($validated['bencana_id']);
                $bencana->update(['status' => 'sedang_berjalan']);

                // ALOKASIKAN BUFFER STOCK LOGISTIK AWAL KETIKA POSKO LANGSUNG AKTIF
                $this->alokasikanBufferStockAwal($posko, $bencana);
            }

            DB::commit();

            return redirect()->route('admin.posko.create')
                ->with('success', "Posko Komando '{$posko->nama_posko}' dan Akun Komandan ({$userKomandan->email}) berhasil didaftarkan & diaktifkan!");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal mendaftarkan Posko Komando: ' . $e->getMessage());
        }
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

            // 3. ALOKASIKAN BUFFER STOCK LOGISTIK AWAL DARI GUDANG UTAMA KE POSKO KOMANDO
            $this->alokasikanBufferStockAwal($posko, $bencana);

            DB::commit();

            return redirect()->route('admin.bencana')
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
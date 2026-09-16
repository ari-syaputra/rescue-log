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
use Illuminate\Support\Str;

class PoskoController extends Controller
{
    public function create(Request $request)
    {
        $bpbd = Auth::user()->bpbd;

        if ($bpbd) {
            $bpbd->latitude = $bpbd->latitude ?? -7.8893;
            $bpbd->longitude = $bpbd->longitude ?? 110.3288;
            $bpbd->nama_kabupaten_kota = $bpbd->nama_kabupaten_kota ?? 'BPBD Kabupaten Bantul';
            $bpbd->alamat_kantor = $bpbd->alamat_kantor ?? 'Jl. Jend. A. Yani No. 1, Badegan, Bantul';
        }

        $bencanaId = $request->query('bencana_id');
        $bencana = null;

        if ($bencanaId) {
            $bencana = Bencana::where('id', $bencanaId)->first();
        }
        
        if (!$bencana) {
            $bencana = Bencana::where('status', 'menunggu_posko')->latest()->first()
                    ?? Bencana::where('status', 'sedang_berjalan')->latest()->first();
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
                'status'           => 'nonaktif',
                'kode_undangan'    => 'KOMANDO-' . strtoupper(Str::random(6)),
            ]);

            $user->update(['posko_id' => $posko->id]);

            return redirect()->route('admin.posko.create')
                ->with('success', "Posko Komando '{$posko->nama_posko}' dan Akun Komandan ({$user->email}) berhasil didaftarkan! Status saat ini: Standby.");
        });
    }

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
            
            $posko->update([
                'bencana_id' => $validated['bencana_id'],
                'lokasi'     => $validated['lokasi'],
                'latitude'   => $validated['latitude'],
                'longitude'  => $validated['longitude'],
                'status'     => 'aktif',
            ]);

            $bencana->update([
                'status' => 'sedang_berjalan'
            ]);

            // Alokasikan Buffer Stock Awal (12 Logistik Utama Baku)
            $this->alokasikanBufferStockAwal($posko, $bencana);

            DB::commit();

            return redirect()->route('admin.posko.create', ['bencana_id' => $bencana->id])
                ->with('success', "Posko Komando '{$posko->nama_posko}' berhasil DIAKTIFKAN! Buffer Stock Logistik Awal (12 Item Utama) telah dialokasikan ke Posko.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menempatkan posko: ' . $e->getMessage());
        }
    }

    /**
     * 📦 LOGIKA ALOKASI BUFFER STOCK AWAL POSKO KOMANDO
     * Otomatisasi Alokasi 12 Logistik Utama Baku dari Gudang Utama BPBD
     */
    private function alokasikanBufferStockAwal(Posko $posko, Bencana $bencana)
    {
        $pengungsi = $bencana->estimasi_pengungsi_awal ?? 100;

        // Formula Rasio Kebutuhan 12 Logistik Utama Baku (Golden Hours 3 Hari)
        $rasioKebutuhan = [
            'Beras'               => $pengungsi * 0.4 * 3,        // 0.4 kg x 3 hari
            'Air Minum'           => ceil($pengungsi * 0.1 * 3),  // 0.1 dus x 3 hari
            'Makanan Kaleng'     => $pengungsi * 1 * 3,          // 1 pack x 3 hari
            'Makanan Bayi'        => ceil($pengungsi * 0.05 * 3), // Nutrisi Bayi
            'Minyak Goreng'       => ceil($pengungsi * 0.05 * 3), // Bahan Pokok
            'Popok Bayi'          => ceil($pengungsi * 0.1 * 3),  // Kebutuhan Bayi
            'Popok Dewasa'        => ceil($pengungsi * 0.05 * 3), // Sanitasi Lansia
            'Pembalut Wanita'     => ceil($pengungsi * 0.1 * 3),  // Sanitasi Wanita
            'Hygiene Kit'         => ceil($pengungsi * 0.05),     // Kebersihan
            'Obat-obatan / P3K'   => ceil($pengungsi * 0.02),     // Kesehatan
            'Selimut'             => ceil($pengungsi * 0.3),      // Perlengkapan
            'Matras / Terpal'     => ceil($pengungsi * 0.1),      // Tenda/Perlengkapan
        ];

        foreach ($rasioKebutuhan as $namaBarangBaku => $jumlahDibutuhkan) {
            // 1. Cari Stok Baku di Gudang Utama BPBD (posko_id IS NULL)
            $stokGudang = StokInventaris::whereNull('posko_id')
                ->where('nama_barang', $namaBarangBaku)
                ->first();

            // Fallback pencarian fleksibel jika nama persis tidak ditemukan
            if (!$stokGudang) {
                $stokGudang = StokInventaris::whereNull('posko_id')
                    ->where('nama_barang', 'LIKE', "%{$namaBarangBaku}%")
                    ->first();
            }

            if ($stokGudang && $stokGudang->jumlah > 0) {
                $jumlahTransfer = min($stokGudang->jumlah, $jumlahDibutuhkan);

                // Potong Stok Gudang Utama BPBD
                $stokGudang->decrement('jumlah', $jumlahTransfer);

                // 2. Tambahkan/Kreditkan ke Posko Komando
                $stokPosko = StokInventaris::where('posko_id', $posko->id)
                    ->where('nama_barang', $stokGudang->nama_barang)
                    ->first();

                if ($stokPosko) {
                    $stokPosko->increment('jumlah', $jumlahTransfer);
                } else {
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

    public function show($id)
    {
        $posko = Posko::with(['user', 'bencana', 'stokInventaris'])
            ->where('tipe_posko', 'komando')
            ->findOrFail($id);

        $subPoskos = Posko::where('tipe_posko', '!=', 'komando')
            ->where('bencana_id', $posko->bencana_id)
            ->get();

        return view('dashboard.admin.posko.show', compact('posko', 'subPoskos'));
    }
}
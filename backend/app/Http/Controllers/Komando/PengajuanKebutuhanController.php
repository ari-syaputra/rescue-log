<?php

namespace App\Http\Controllers\Komando;

use App\Http\Controllers\Controller;
use App\Models\Bencana;
use App\Models\PengajuanKebutuhan;
use App\Models\StokInventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanKebutuhanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | BAGIAN 1: PENGAJUAN LOGISTIK KELUAR (Komando -> BPBD)
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan daftar semua pengajuan logistik Posko Komando ke BPBD.
     */
    public function index(Request $request)
    {
        $poskoId = Auth::user()->posko_id;

        $query = PengajuanKebutuhan::with(['bencana', 'posko', 'user'])
            ->where('posko_id', $poskoId);

        // Filter berdasarkan pencarian (kode pengajuan atau bencana)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_pengajuan', 'ILIKE', "%{$search}%")
                  ->orWhereHas('bencana', function ($b) use ($search) {
                      $b->where('jenis_bencana', 'ILIKE', "%{$search}%");
                  });
            });
        }

        // Filter berdasarkan status pengajuan
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Data pengajuan dengan pagination
        $pengajuans = $query->latest()->paginate(10)->withQueryString();

        // Statistik Pengajuan Kebutuhan untuk Cards/Widgets Header
        $totalPengajuan = PengajuanKebutuhan::where('posko_id', $poskoId)->count();
        $pendingCount   = PengajuanKebutuhan::where('posko_id', $poskoId)->where('status', 'pending')->count();
        $disetujuiCount = PengajuanKebutuhan::where('posko_id', $poskoId)->whereIn('status', ['disetujui', 'disetujui_sebagian'])->count();
        $ditolakCount   = PengajuanKebutuhan::where('posko_id', $poskoId)->where('status', 'ditolak')->count();

        // Data Pendukung untuk Form Modal / Select Pengajuan Baru
        $bencanaAktif   = Bencana::orderBy('jenis_bencana', 'asc')->get();
        $barangs        = StokInventaris::orderBy('nama_barang', 'asc')->get();

        return view('dashboard.komando.pengajuan.index', compact(
            'pengajuans',
            'totalPengajuan',
            'pendingCount',
            'disetujuiCount',
            'ditolakCount',
            'bencanaAktif',
            'barangs'
        ));
    }

    /**
     * Menyimpan data pengajuan kebutuhan logistik ke BPBD.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validasi Request
        $validated = $request->validate([
            'bencana_id'      => 'required|exists:bencana,id',
            'catatan_komando' => 'nullable|string|max:1000',
            'items'           => 'nullable|array',
            'items.*.barang_id' => 'nullable|exists:stok_inventaris,id',
            'items.*.jumlah_diminta' => 'nullable|numeric|min:0',
        ]);

        $kodePengajuan = PengajuanKebutuhan::generateKode();

        // Transaction DB
        $pengajuan = DB::transaction(function () use ($request, $validated, $user, $kodePengajuan) {
            
            // Default 12 kolom barang ke 0
            $dataBarang = [
                'beras_kg'             => 0,
                'air_minum_dus'        => 0,
                'makanan_kaleng_pack'  => 0,
                'makanan_bayi_pack'    => 0,
                'minyak_goreng_liter'  => 0,
                'popok_bayi_pcs'       => 0,
                'popok_dewasa_pcs'     => 0,
                'pembalut_wanita_pack' => 0,
                'hygiene_kit_paket'    => 0,
                'selimut_pcs'          => 0,
                'matras_terpal_pcs'    => 0,
                'obat_p3k_paket'       => 0,
            ];

            // Map array item dari modal jika dikirim via dynamic form
            if ($request->has('items') && is_array($request->items)) {
                foreach ($request->items as $item) {
                    $jumlah = $item['jumlah_diminta'] ?? $item['jumlah'] ?? 0;
                    $barangId = $item['barang_id'] ?? $item['stok_inventaris_id'] ?? null;

                    if ($barangId && $jumlah > 0) {
                        $stok = StokInventaris::find($barangId);
                        if ($stok) {
                            $nama = strtolower($stok->nama_barang);

                            if (str_contains($nama, 'beras')) $dataBarang['beras_kg'] += $jumlah;
                            elseif (str_contains($nama, 'air')) $dataBarang['air_minum_dus'] += $jumlah;
                            elseif (str_contains($nama, 'kaleng')) $dataBarang['makanan_kaleng_pack'] += $jumlah;
                            elseif (str_contains($nama, 'bayi') && str_contains($nama, 'makanan')) $dataBarang['makanan_bayi_pack'] += $jumlah;
                            elseif (str_contains($nama, 'minyak')) $dataBarang['minyak_goreng_liter'] += $jumlah;
                            elseif (str_contains($nama, 'popok') && str_contains($nama, 'bayi')) $dataBarang['popok_bayi_pcs'] += $jumlah;
                            elseif (str_contains($nama, 'popok') && str_contains($nama, 'dewasa')) $dataBarang['popok_dewasa_pcs'] += $jumlah;
                            elseif (str_contains($nama, 'pembalut')) $dataBarang['pembalut_wanita_pack'] += $jumlah;
                            elseif (str_contains($nama, 'hygiene')) $dataBarang['hygiene_kit_paket'] += $jumlah;
                            elseif (str_contains($nama, 'selimut')) $dataBarang['selimut_pcs'] += $jumlah;
                            elseif (str_contains($nama, 'matras') || str_contains($nama, 'terpal')) $dataBarang['matras_terpal_pcs'] += $jumlah;
                            elseif (str_contains($nama, 'obat') || str_contains($nama, 'p3k')) $dataBarang['obat_p3k_paket'] += $jumlah;
                        }
                    }
                }
            }

            // Map jika form menginputkan langsung nama kolom (misal: request->beras_kg)
            foreach (array_keys($dataBarang) as $col) {
                if ($request->has($col) && $request->input($col) > 0) {
                    $dataBarang[$col] = $request->input($col);
                }
            }

            // Simpan data pengajuan langsung di header
            return PengajuanKebutuhan::create(array_merge([
                'kode_pengajuan'    => $kodePengajuan,
                'posko_id'          => $user->posko_id,
                'bencana_id'        => $validated['bencana_id'],
                'user_id'           => $user->id,
                'tanggal_pengajuan' => now(),
                'status'            => 'pending',
                'catatan_komando'   => $validated['catatan_komando'] ?? null,
            ], $dataBarang));
        });

        return redirect()->route('komando.pengajuan.index')
            ->with('success', "Pengajuan kebutuhan (#{$pengajuan->kode_pengajuan}) berhasil dikirim ke BPBD.");
    }

    /**
     * Membatalkan / Menghapus pengajuan ke BPBD (Hanya jika status 'pending').
     */
    public function destroy($id)
    {
        $poskoId = Auth::user()->posko_id;

        $pengajuan = PengajuanKebutuhan::where('posko_id', $poskoId)->findOrFail($id);

        if ($pengajuan->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini tidak dapat dibatalkan karena sudah diproses atau disetujui oleh BPBD.');
        }

        $pengajuan->delete();

        return redirect()->route('komando.pengajuan.index')
            ->with('success', 'Pengajuan kebutuhan berhasil dibatalkan.');
    }

    /*
    |--------------------------------------------------------------------------
    | BAGIAN 2: VERIFIKASI PENGAJUAN LOGISTIK MASUK (Lapangan -> Komando)
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan daftar pengajuan logistik yang MASUK dari Posko Lapangan.
     */
    public function logistikMasuk(Request $request)
    {
        $query = PengajuanKebutuhan::with(['posko', 'bencana', 'user'])
            ->whereHas('posko', function ($q) {
                $q->where('tipe_posko', 'lapangan_kecil');
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_pengajuan', 'ILIKE', "%{$search}%")
                  ->orWhereHas('posko', function ($p) use ($search) {
                      $p->where('nama_posko', 'ILIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->paginate(10)->withQueryString();

        $pendingCount   = PengajuanKebutuhan::whereHas('posko', fn($q) => $q->where('tipe_posko', 'lapangan_kecil'))->where('status', 'pending')->count();
        $disetujuiCount = PengajuanKebutuhan::whereHas('posko', fn($q) => $q->where('tipe_posko', 'lapangan_kecil'))->whereIn('status', ['disetujui', 'disetujui_sebagian'])->count();
        $ditolakCount   = PengajuanKebutuhan::whereHas('posko', fn($q) => $q->where('tipe_posko', 'lapangan_kecil'))->where('status', 'ditolak')->count();

        return view('dashboard.komando.logistik.index', compact(
            'pengajuans',
            'pendingCount',
            'disetujuiCount',
            'ditolakCount'
        ));
    }

    /**
     * Memperbarui status pengajuan logistik dari Posko Lapangan (Persetujuan/Penolakan).
     */
    public function updateStatusLogistik(Request $request, $id)
    {
        $request->validate([
            'status'          => 'required|in:disetujui,disetujui_sebagian,ditolak',
            'catatan_komando' => 'nullable|string|max:1000',
        ]);

        $pengajuan = PengajuanKebutuhan::findOrFail($id);

        $pengajuan->update([
            'status'          => $request->status,
            'catatan_komando' => $request->catatan_komando,
        ]);

        return back()->with('success', 'Status pengajuan logistik dari Posko Lapangan berhasil diperbarui.');
    }
}
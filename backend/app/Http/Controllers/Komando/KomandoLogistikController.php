<?php

namespace App\Http\Controllers\Komando;

use App\Http\Controllers\Controller;
use App\Models\Armada;
use App\Models\PengajuanKebutuhan;
use App\Models\PengajuanKebutuhanDetail;
use App\Models\PengirimanInventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KomandoLogistikController extends Controller
{
    /**
     * Menampilkan pengajuan logistik masuk dari Sub-Posko
     */
    public function index(Request $request)
    {
        $komandoPoskoId = Auth::user()->posko_id;

        $query = PengajuanKebutuhan::with(['user', 'posko', 'details.barang'])
            ->whereHas('posko', function ($q) use ($komandoPoskoId) {
                $q->where('parent_id', $komandoPoskoId)
                  ->where('tipe_posko', 'lapangan_kecil');
            })
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('kode_pengajuan', 'ILIKE', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->paginate(10)->withQueryString();
        $armadas = Armada::where('status', 'tersedia')->get();

        return view('dashboard.komando.logistik.index', compact('pengajuans', 'armadas'));
    }

    /**
     * ACC Full / Partial Pengajuan dari Sub-Posko
     */
    public function approve(Request $request, $id)
    {
        $validated = $request->validate([
            'status'            => 'required|in:disetujui,disetujui_sebagian,ditolak',
            'catatan_komando'   => 'nullable|string',
            'items'             => 'nullable|array',
            'items.*.id'        => 'required_with:items|exists:pengajuan_kebutuhan_detail,id',
            'items.*.disetujui' => 'required_with:items|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $pengajuan = PengajuanKebutuhan::findOrFail($id);

            // Update Status Header
            $pengajuan->update([
                'status'            => $validated['status'],
                'catatan_komando'   => $validated['catatan_komando'] ?? null,
                'user_id_responder' => Auth::id(),
            ]);

            $totalJumlahAcc = 0;

            // Update Jumlah Disetujui per Barang (Jika Partial)
            if (!empty($validated['items'])) {
                foreach ($validated['items'] as $item) {
                    PengajuanKebutuhanDetail::where('id', $item['id'])->update([
                        'jumlah_disetujui' => $item['disetujui'],
                    ]);
                    $totalJumlahAcc += $item['disetujui'];
                }
            } else {
                // Jika ACC Full, set jumlah_disetujui = jumlah_diminta
                foreach ($pengajuan->details as $detail) {
                    $detail->update(['jumlah_disetujui' => $detail->jumlah_diminta]);
                    $totalJumlahAcc += $detail->jumlah_diminta;
                }
            }

            // Catat ke PengirimanInventaris untuk Menunggu Penjadwalan Armada
            if ($validated['status'] !== 'ditolak') {
                PengirimanInventaris::updateOrCreate(
                    ['pengajuan_id' => $pengajuan->id],
                    [
                        'posko_id'          => $pengajuan->posko_id,
                        'jumlah_dikirim'    => $totalJumlahAcc,
                        'status_distribusi' => 'Menunggu Dijadwalkan',
                        'keterangan'        => 'ACC Logistik - ' . $pengajuan->kode_pengajuan,
                    ]
                );
            }

            DB::commit();
            return redirect()->back()->with('success', "Pengajuan ({$pengajuan->kode_pengajuan}) berhasil diproses.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses persetujuan: ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers\Komando;

use App\Http\Controllers\Controller;
use App\Models\Armada;
use App\Models\PermintaanAmbulans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomandoAmbulansController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $poskoKomando = $user->posko;

        // Ambil semua permintaan ambulans
        $requests = PermintaanAmbulans::with(['posko', 'armada', 'bencana'])
            ->where('bencana_id', $poskoKomando->bencana_id ?? null)
            ->orderBy('created_at', 'desc')
            ->get();

        // PERBAIKAN: Ambil armada yang tidak sedang dalam tugas (tersedia/standby)
        $armadaStandby = Armada::where('status', '!=', 'dalam_tugas')
            ->orWhereNull('status')
            ->get();

        // Jika data masih kosong (karena query kaku), ambil seluruh armada yang ada
        if ($armadaStandby->isEmpty()) {
            $armadaStandby = Armada::all();
        }

        $stats = [
            'total'     => $requests->count(),
            'pending'   => $requests->where('status', 'menunggu_penanganan')->count(),
            'meluncur'  => $requests->whereIn('status', ['ambulans_meluncur', 'proses_evakuasi'])->count(),
            'selesai'   => $requests->where('status', 'selesai')->count(),
        ];

        return view('dashboard.komando.ambulans.index', compact('requests', 'armadaStandby', 'stats'));
    }

    public function assignArmada(Request $request, $id)
    {
        $request->validate([
            'armada_id'  => 'required|exists:armadas,id',
            'rs_rujukan' => 'required|string|max:255',
        ], [
            'armada_id.required'  => 'Pilih unit armada ambulans yang akan ditugaskan.',
            'rs_rujukan.required' => 'Tentukan nama Rumah Sakit rujukan.',
        ]);

        $permintaan = PermintaanAmbulans::findOrFail($id);
        $armada = Armada::findOrFail($request->armada_id);

        // Update Permintaan Ambulans
        $permintaan->update([
            'armada_id'  => $armada->id,
            'rs_rujukan' => $request->rs_rujukan,
            'status'     => 'ambulans_meluncur',
        ]);

        // Ubah status armada menjadi sedang bertugas
        $armada->update(['status' => 'dalam_tugas']);

        return redirect()->back()->with('success', "Armada '{$armada->nama_armada}' berhasil ditugaskan ke lokasi pasien ({$permintaan->kode_sos}).");
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:ambulans_meluncur,proses_evakuasi,selesai,dibatalkan',
        ]);

        $permintaan = PermintaanAmbulans::findOrFail($id);
        $permintaan->update(['status' => $request->status]);

        if (in_array($request->status, ['selesai', 'dibatalkan']) && $permintaan->armada) {
            $permintaan->armada->update(['status' => 'tersedia']);
            if ($request->status == 'selesai') {
                $permintaan->update(['waktu_selesai' => now()]);
            }
        }

        return redirect()->back()->with('success', 'Status evakuasi medis berhasil diperbarui.');
    }
}
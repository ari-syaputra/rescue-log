<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Posko;
use App\Models\Bencana;
use App\Models\User;
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
        $availablePosko = Posko::komando()
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
                Bencana::where('id', $validated['bencana_id'])->update([
                    'status' => 'sedang_berjalan'
                ]);
            }

            DB::commit();

            return redirect()->route('admin.posko.create')
                ->with('success', "Posko Komando '{$posko->nama_posko}' dan Akun Komandan ({$userKomandan->email}) berhasil didaftarkan!");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal mendaftarkan Posko Komando: ' . $e->getMessage());
        }
    }

    /**
     * TAHAP 2: Aktifkan & Plotting Penempatan GIS Posko ke Bencana
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
            
            // Update Posko dengan Bencana & Titik Koordinat GIS Baru
            $posko->update([
                'bencana_id' => $validated['bencana_id'],
                'lokasi'     => $validated['lokasi'],
                'latitude'   => $validated['latitude'],
                'longitude'  => $validated['longitude'],
                'status'     => 'aktif',
            ]);

            // Ubah Status Bencana menjadi Sedang Berjalan
            Bencana::where('id', $validated['bencana_id'])->update([
                'status' => 'sedang_berjalan'
            ]);

            DB::commit();

            return redirect()->route('admin.bencana')
                ->with('success', "Posko Komando '{$posko->nama_posko}' berhasil ditempatkan dan DIAKTIFKAN!");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menempatkan posko: ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers\Komando;

use App\Http\Controllers\Controller;
use App\Models\Bencana;
use App\Models\Posko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SubPoskoController extends Controller
{
    public function index(Request $request)
    {
        $komandoPoskoId = Auth::user()->posko_id;

        if (!$komandoPoskoId) {
            return back()->with('error', 'Akun Anda belum terhubung dengan Posko Komando Utama.');
        }

        $baseQuery = Posko::where('parent_id', $komandoPoskoId)
            ->where('tipe_posko', 'lapangan_kecil');

        $query = (clone $baseQuery)->with('bencana');

        if ($request->filled('search')) {
            $query->where('nama_posko', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subPoskos = $query->latest()->paginate(10)->withQueryString();

        $totalPosko    = (clone $baseQuery)->count();
        $poskoAktif    = (clone $baseQuery)->where('status', 'aktif')->count();
        $totalPetugas  = (clone $baseQuery)->sum('jumlah_petugas') ?? 0;

        return view('dashboard.komando.posko-kecil.index', compact(
            'subPoskos', 
            'totalPosko', 
            'poskoAktif', 
            'totalPetugas'
        ));
    }

    public function create()
    {
        $komandoPosko = Posko::find(Auth::user()->posko_id);
        $bencanaAktif = Bencana::where('status', 'sedang_berjalan')->get();
        return view('dashboard.komando.posko-kecil.create', compact('bencanaAktif', 'komandoPosko'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->posko_id) {
            return back()->with('error', 'Gagal membuat Sub-Posko. Anda tidak memiliki Posko Induk.');
        }

        $validated = $request->validate([
            'nama_posko'       => 'required|string|max:255',
            'bencana_id'       => 'required|exists:bencana,id',
            'penanggung_jawab' => 'required|string|max:255',
            'kontak_hp'        => 'nullable|string|max:20',
            'jumlah_petugas'   => 'nullable|integer|min:0',
            'lokasi'           => 'nullable|string',
            'latitude'         => 'nullable|numeric',
            'longitude'        => 'nullable|numeric',
            'foto'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ], [
            'nama_posko.required'       => 'Nama Posko wajib diisi.',
            'bencana_id.exists'         => 'Bencana aktif tidak valid atau tidak ditemukan.',
            'penanggung_jawab.required' => 'Penanggung jawab posko wajib diisi.',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('posko-images', 'public');
        }

        // Generate Access Key Unik Sub-Posko
        $kodeUndangan = Posko::generateKodeUndangan();

        $subPosko = Posko::create([
            'nama_posko'       => $validated['nama_posko'],
            'tipe_posko'       => 'lapangan_kecil',
            'parent_id'        => $user->posko_id, 
            'bpbd_id'          => $user->bpbd_id,
            'bencana_id'       => $validated['bencana_id'],
            'kode_undangan'    => $kodeUndangan,
            'penanggung_jawab' => $validated['penanggung_jawab'],
            'kontak_hp'        => $validated['kontak_hp'] ?? null,
            'jumlah_petugas'   => $validated['jumlah_petugas'] ?? 0,
            'lokasi'           => $validated['lokasi'] ?? null,
            'latitude'         => $validated['latitude'] ?? null,
            'longitude'        => $validated['longitude'] ?? null,
            'foto'             => $fotoPath,
            'status'           => 'aktif',
        ]);

        return redirect()->route('komando.posko-kecil.index')
            ->with('success', "Sub-Posko '{$subPosko->nama_posko}' berhasil didaftarkan. Access Key: {$kodeUndangan}");
    }

    public function show($id)
    {
        $komandoPoskoId = Auth::user()->posko_id;

        $subPosko = Posko::where('parent_id', $komandoPoskoId)
            ->where('tipe_posko', 'lapangan_kecil')
            ->with([
                'bencana',
                'users',
                'fotos' => fn($q) => $q->latest(),
                'stokInventaris.inventarisGudang' // Menyesuaikan relasi stok jika ada
            ])
            ->findOrFail($id);

        return view('dashboard.komando.posko-kecil.show', compact('subPosko'));
    }
}
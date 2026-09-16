<?php

namespace App\Http\Controllers\Lapangan;

use App\Http\Controllers\Controller;
use App\Models\Bencana;
use App\Models\Pendataan;
use App\Models\PermintaanAmbulans;
use App\Models\Posko;
use App\Models\PoskoFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardLapanganController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Ambil data Posko beserta relasi Bencana secara Eager Loading
        $subPosko = null;
        if ($user->posko_id) {
            $subPosko = Posko::with('bencana')->find($user->posko_id);
        }

        if (!$subPosko) {
            $subPosko = (object) [
                'id' => null,
                'nama_posko' => 'Posko Lapangan (Belum Terdaftar)',
                'status' => 'Standby',
                'latitude' => -7.7956,
                'longitude' => 110.3695,
                'foto' => null,
                'bencana' => null,
            ];
        }

        // 2. Ambil data pendataan pengungsi terbaru dari posko ini
        $pendataanTerakhir = Pendataan::where('posko_id', $user->posko_id)
            ->latest()
            ->first();

        $totalPengungsiReal = $pendataanTerakhir ? $pendataanTerakhir->total_pengungsi : 0;

        // 3. AMBIL DATA BENCANA AKTIF (DENGAN MENJAMIN GEOJSON_POLYGON TERAMBIL)
        $bencanaAktif = null;
        
        // Opsi A: Cek relasi langsung dari posko jika ada
        if ($subPosko instanceof Posko && $subPosko->bencana) {
            $bencanaAktif = $subPosko->bencana;
        } 
        
        // Opsi B (Fallback 1): Cari bencana terbaru yang memiliki data poligon di database
        if (!$bencanaAktif) {
            $bencanaAktif = Bencana::whereNotNull('geojson_polygon')
                ->latest('tanggal_aktivasi')
                ->first();
        }

        // Opsi C (Fallback 2): Ambil bencana paling terbaru apapun statusnya jika tetap kosong
        if (!$bencanaAktif) {
            $bencanaAktif = Bencana::latest()->first();
        }

        // 4. Ambil Panggilan Ambulans Aktif
        $permintaanAmbulansAktif = null;
        if ($user->posko_id) {
            $permintaanAmbulansAktif = PermintaanAmbulans::with('armada')
                ->where('posko_id', $user->posko_id)
                ->whereIn('status', ['menunggu_penanganan', 'ambulans_meluncur', 'proses_evakuasi'])
                ->latest()
                ->first();
        }

        return view('dashboard.lapangan.index', compact(
            'subPosko',
            'totalPengungsiReal',
            'bencanaAktif',
            'permintaanAmbulansAktif'
        ));
    }
    
    public function uploadFoto(Request $request)
    {
        $request->validate([
            'fotos' => 'required',
            'fotos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $subPosko = Auth::user()->posko;

        if (!$subPosko) {
            return back()->with('error', 'Akun Anda belum terdaftar dalam posko manapun.');
        }

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                $path = $file->store('dokumentasi', 'public');

                $subPosko->fotos()->create([
                    'path_file' => $path
                ]);
            }
        }

        return back()->with('success', 'Dokumentasi foto berhasil ditambahkan!');
    }

    public function hapusFoto($id)
    {
        $foto = PoskoFoto::findOrFail($id);

        if ($foto->posko && $foto->posko->id === Auth::user()->posko_id) {
            if (Storage::disk('public')->exists($foto->path_file)) {
                Storage::disk('public')->delete($foto->path_file);
            }
            $foto->delete();
        }

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}
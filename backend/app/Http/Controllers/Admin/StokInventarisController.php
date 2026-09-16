<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StokInventaris;
use App\Models\Posko;
use Illuminate\Http\Request;

class StokInventarisController extends Controller
{
    /**
     * Menampilkan halaman utama stok inventaris gudang utama BPBD
     */
    public function index()
    {
        // Hanya ambil stok milik Gudang Induk BPBD (posko_id IS NULL)
        $stokInventaris = StokInventaris::whereNull('posko_id')->latest()->get();
        
        // Ambil daftar posko tipe komando untuk modal pengiriman
        $poskoKomando = Posko::where('tipe_posko', 'komando')->get();

        return view('dashboard.admin.inventaris.index', compact('stokInventaris', 'poskoKomando'));
    }

    /**
     * Menyimpan data stok inventaris baru di Gudang BPBD
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori'    => 'required|string|max:255',
            'jumlah'      => 'required|numeric|min:0',
            'satuan'      => 'required|string|max:50',
            'keterangan'  => 'nullable|string',
        ]);

        // Pastikan posko_id NULL untuk menandakan milik Gudang Utama BPBD
        $validated['posko_id'] = null;

        StokInventaris::create($validated);

        return redirect()->route('admin.inventaris')->with('success', 'Data stok inventaris berhasil ditambahkan.');
    }

    /**
     * Mengupdate data stok inventaris
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori'    => 'required|string|max:255',
            'jumlah'      => 'required|numeric|min:0',
            'satuan'      => 'required|string|max:50',
            'keterangan'  => 'nullable|string',
        ]);

        $item = StokInventaris::findOrFail($id);
        $item->update($validated);

        return redirect()->route('admin.inventaris')->with('success', 'Data stok inventaris berhasil diperbarui.');
    }

    /**
     * Menghapus data stok inventaris
     */
    public function destroy($id)
    {
        $item = StokInventaris::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.inventaris')->with('success', 'Data stok inventaris berhasil dihapus.');
    }
}
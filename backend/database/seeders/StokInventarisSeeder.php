<?php

namespace Database\Seeders;

use App\Models\StokInventaris;
use Illuminate\Database\Seeder;

class StokInventarisSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel sebelum di-seed (Opsional)
        StokInventaris::truncate();

        $stokAwal = [
            // 1. Makanan & Sembako
            [
                'posko_id'    => null, // Stok Gudang Utama BPBD
                'nama_barang' => 'Beras',
                'kategori'    => 'Makanan & Sembako',
                'jumlah'      => 5000.00,
                'satuan'      => 'KG',
                'keterangan'  => 'Stok beras putih medium gudang utama BPBD',
            ],
            [
                'posko_id'    => null,
                'nama_barang' => 'Minyak Goreng',
                'kategori'    => 'Makanan & Sembako',
                'jumlah'      => 1200.00,
                'satuan'      => 'LITER',
                'keterangan'  => 'Kemasan bantal 1 Liter',
            ],
            [
                'posko_id'    => null,
                'nama_barang' => 'Makanan Kaleng',
                'kategori'    => 'Makanan & Sembako',
                'jumlah'      => 3500.00,
                'satuan'      => 'PACK',
                'keterangan'  => 'Sarden dan cornet siap saji',
            ],

            // 2. Air & Minuman
            [
                'posko_id'    => null,
                'nama_barang' => 'Air Minum',
                'kategori'    => 'Air & Minuman',
                'jumlah'      => 800.00,
                'satuan'      => 'DUS',
                'keterangan'  => 'Air mineral botol 600ml (24 botol/dus)',
            ],

            // 3. Kelompok Rentan & Bayi
            [
                'posko_id'    => null,
                'nama_barang' => 'Makanan Bayi',
                'kategori'    => 'Kelompok Rentan & Bayi',
                'jumlah'      => 600.00,
                'satuan'      => 'PACK',
                'keterangan'  => 'Bubur bayi instan usia 6-24 bulan',
            ],
            [
                'posko_id'    => null,
                'nama_barang' => 'Popok Bayi',
                'kategori'    => 'Kelompok Rentan & Bayi',
                'jumlah'      => 1500.00,
                'satuan'      => 'PCS',
                'keterangan'  => 'Ukuran M & L campur',
            ],
            [
                'posko_id'    => null,
                'nama_barang' => 'Popok Dewasa',
                'kategori'    => 'Kelompok Rentan & Bayi',
                'jumlah'      => 400.00,
                'satuan'      => 'PCS',
                'keterangan'  => 'Kebutuhan pengungsi lansia',
            ],
            [
                'posko_id'    => null,
                'nama_barang' => 'Pembalut Wanita',
                'kategori'    => 'Kelompok Rentan & Bayi',
                'jumlah'      => 800.00,
                'satuan'      => 'PACK',
                'keterangan'  => 'Kemasan 10 pcs',
            ],

            // 4. Kesehatan & Sanitasi
            [
                'posko_id'    => null,
                'nama_barang' => 'Hygiene Kit',
                'kategori'    => 'Kesehatan & Sanitasi',
                'jumlah'      => 350.00,
                'satuan'      => 'PAKET',
                'keterangan'  => 'Paket mandiri (Sabun, Sampo, Sikat, Handuk)',
            ],
            [
                'posko_id'    => null,
                'nama_barang' => 'Obat P3K',
                'kategori'    => 'Kesehatan & Sanitasi',
                'jumlah'      => 150.00,
                'satuan'      => 'PAKET',
                'keterangan'  => 'Kit pertolongan pertama & obat-obatan darurat',
            ],

            // 5. Peralatan & Perlengkapan
            [
                'posko_id'    => null,
                'nama_barang' => 'Selimut',
                'kategori'    => 'Peralatan & Perlengkapan',
                'jumlah'      => 1000.00,
                'satuan'      => 'PCS',
                'keterangan'  => 'Selimut wol hangat pengungsian',
            ],
            [
                'posko_id'    => null,
                'nama_barang' => 'Matras / Terpal',
                'kategori'    => 'Peralatan & Perlengkapan',
                'jumlah'      => 500.00,
                'satuan'      => 'PCS',
                'keterangan'  => 'Terpal plastik tebal 4x6m & matras gulung',
            ],
        ];

        foreach ($stokAwal as $stok) {
            StokInventaris::create($stok);
        }
    }
}
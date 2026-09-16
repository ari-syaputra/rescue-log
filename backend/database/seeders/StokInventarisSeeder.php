<?php

namespace Database\Seeders;

use App\Models\StokInventaris;
use Illuminate\Database\Seeder;

class StokInventarisSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel sebelum di-seed untuk menghindari duplikasi data lama
        StokInventaris::truncate();

        $stokAwal = [
            // 1. Makanan Pokok & Bahan Pokok
            [
                'posko_id'    => null, // Stok Gudang Utama BPBD
                'nama_barang' => 'Beras',
                'kategori'    => 'Makanan Pokok',
                'jumlah'      => 5000.00,
                'satuan'      => 'Kg',
                'keterangan'  => 'Stok beras putih medium gudang utama BPBD',
            ],
            [
                'posko_id'    => null,
                'nama_barang' => 'Minyak Goreng',
                'kategori'    => 'Bahan Pokok',
                'jumlah'      => 1200.00,
                'satuan'      => 'Liter',
                'keterangan'  => 'Kemasan bantal 1 Liter',
            ],
            [
                'posko_id'    => null,
                'nama_barang' => 'Makanan Kaleng',
                'kategori'    => 'Makanan Cepat Saji',
                'jumlah'      => 3500.00,
                'satuan'      => 'Pack',
                'keterangan'  => 'Sarden dan kornet siap saji',
            ],

            // 2. Konsumsi
            [
                'posko_id'    => null,
                'nama_barang' => 'Air Minum',
                'kategori'    => 'Konsumsi',
                'jumlah'      => 800.00,
                'satuan'      => 'Dus',
                'keterangan'  => 'Air mineral botol 600ml (24 botol/dus)',
            ],

            // 3. Nutrisi Bayi & Kebutuhan Rentan
            [
                'posko_id'    => null,
                'nama_barang' => 'Makanan Bayi',
                'kategori'    => 'Nutrisi Bayi',
                'jumlah'      => 600.00,
                'satuan'      => 'Pack',
                'keterangan'  => 'Bubur bayi instan usia 6-24 bulan',
            ],
            [
                'posko_id'    => null,
                'nama_barang' => 'Popok Bayi',
                'kategori'    => 'Kebutuhan Bayi',
                'jumlah'      => 1500.00,
                'satuan'      => 'Pcs',
                'keterangan'  => 'Ukuran M & L campur',
            ],
            [
                'posko_id'    => null,
                'nama_barang' => 'Popok Dewasa',
                'kategori'    => 'Sanitasi',
                'jumlah'      => 400.00,
                'satuan'      => 'Pcs',
                'keterangan'  => 'Kebutuhan pengungsi lansia',
            ],
            [
                'posko_id'    => null,
                'nama_barang' => 'Pembalut Wanita',
                'kategori'    => 'Sanitasi',
                'jumlah'      => 800.00,
                'satuan'      => 'Pack',
                'keterangan'  => 'Kemasan 10 pcs',
            ],

            // 4. Kebersihan & Kesehatan
            [
                'posko_id'    => null,
                'nama_barang' => 'Hygiene Kit',
                'kategori'    => 'Kebersihan',
                'jumlah'      => 350.00,
                'satuan'      => 'Paket',
                'keterangan'  => 'Paket mandiri (Sabun, Sampo, Sikat, Handuk)',
            ],
            [
                'posko_id'    => null,
                'nama_barang' => 'Obat-obatan / P3K',
                'kategori'    => 'Kesehatan',
                'jumlah'      => 150.00,
                'satuan'      => 'Paket',
                'keterangan'  => 'Kit pertolongan pertama & obat-obatan darurat',
            ],

            // 5. Perlengkapan & Tenda
            [
                'posko_id'    => null,
                'nama_barang' => 'Selimut',
                'kategori'    => 'Perlengkapan',
                'jumlah'      => 1000.00,
                'satuan'      => 'Pcs',
                'keterangan'  => 'Selimut wol hangat pengungsian',
            ],
            [
                'posko_id'    => null,
                'nama_barang' => 'Matras / Terpal',
                'kategori'    => 'Tenda/Perlengkapan',
                'jumlah'      => 500.00,
                'satuan'      => 'Pcs',
                'keterangan'  => 'Terpal plastik tebal 4x6m & matras gulung',
            ],
        ];

        foreach ($stokAwal as $stok) {
            StokInventaris::create($stok);
        }
    }
}
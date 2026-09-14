<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\BencanaPending;
use App\Models\Bpbd;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BencanaSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // 1. Buat Data Master BPBD Sleman
            $bpbd = Bpbd::firstOrCreate(
                ['nama_kabupaten_kota' => 'Kabupaten Sleman'],
                ['alamat_kantor' => 'Jl. Merdeka No. 1, Sleman']
            );

            // 2. Buat Data Master Barang Logistik
            $items = [
                'Beras', 'Air Minum', 'Makanan Kaleng', 'Makanan Bayi',
                'Minyak Goreng', 'Popok Bayi', 'Popok Dewasa', 'Pembalut Wanita',
                'Hygiene Kit', 'Selimut', 'Matras / Terpal', 'Obat P3K'
            ];

            foreach ($items as $namaBarang) {
                Barang::firstOrCreate(['nama_barang' => $namaBarang]);
            }

            // 3. Buat Laporan Bencana Pending dari BMKG (Siap Divalidasi Admin)
            BencanaPending::create([
                'external_id'    => 'BMKG-TEST-001',
                'jenis_bencana'  => 'Gempabumi M 5.2',
                'wilayah'        => 'Kabupaten Sleman',
                'latitude'       => -7.7622,
                'longitude'      => 110.4025,
                'waktu_kejadian' => now(),
                'status'         => 'pending',
            ]);
        });
    }
}
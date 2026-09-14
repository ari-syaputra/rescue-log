<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Jalankan PoskoSeeder terlebih dahulu baru UserSeeder
        $this->call([
            BencanaSeeder::class,
            PoskoSeeder::class,
            UserSeeder::class,
            ArmadaSeeder::class,
            StokInventarisSeeder::class,
        ]);
    }
}
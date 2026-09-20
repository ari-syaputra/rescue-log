<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // PENTING: UserSeeder HARUS dijalankan sebelum PoskoSeeder!
        $this->call([
            BencanaSeeder::class,
            UserSeeder::class,
            PoskoSeeder::class,
            ArmadaSeeder::class,
            StokInventarisSeeder::class,
        ]);
    }
}
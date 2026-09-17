<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Posko;
use App\Models\Bpbd;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $bpbd = Bpbd::first();
        $poskoKomando = Posko::where('tipe_posko', 'komando')->first();
        
        // Ambil posko lapangan yang baru dibuat di PoskoSeeder
        $poskoLapangan = Posko::where('tipe_posko', 'lapangan_kecil')->first();

        // Admin Utama BPBD
        User::create([
            'name'     => 'Admin BPBD Utama',
            'email'    => 'admin@bpbd.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
            'posko_id' => null,
            'bpbd_id'  => $bpbd?->id,
        ]);

        // Koordinator Komando Utama
        User::create([
            'name'     => 'Koordinator Komando',
            'email'    => 'komando@rescuelog.com',
            'password' => Hash::make('password123'),
            'role'     => 'komando',
            'posko_id' => $poskoKomando?->id,
            'bpbd_id'  => $bpbd?->id,
        ]);

        // Petugas Lapangan A (Dihubungkan ke posko_id lapangan)
        User::create([
            'name'     => 'Petugas Lapangan A',
            'email'    => 'petugas@rescuelog.com',
            'password' => Hash::make('password123'),
            'role'     => 'lapangan',
            'posko_id' => $poskoLapangan?->id,
            'bpbd_id'  => $bpbd?->id,
        ]);
    }
}
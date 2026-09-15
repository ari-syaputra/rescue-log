<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Bpbd;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $bpbd = Bpbd::first();

        // Admin Utama BPBD (Super Admin Dashboard Management)
        User::firstOrCreate(
            ['email' => 'admin@bpbd.com'],
            [
                'name'     => 'Admin BPBD Utama',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
                'posko_id' => null,
                'bpbd_id'  => $bpbd?->id,
            ]
        );
    }
}
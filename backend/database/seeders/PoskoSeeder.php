<?php

namespace Database\Seeders;

use App\Models\Bpbd;
use App\Models\Posko;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PoskoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // 1. Ambil/Buat Data BPBD Sleman
            $bpbd = Bpbd::firstOrCreate(
                ['nama_kabupaten_kota' => 'Kabupaten Sleman'],
                ['alamat_kantor' => 'Jl. Merdeka No. 1, Sleman']
            );

            // 2. Buat Akun User Komandan Posko (Standby)
            $userKomandan = User::firstOrCreate(
                ['email' => 'komando.sleman@rescuelog.id'],
                [
                    'name'     => 'Komandan Budi Santoso',
                    'password' => Hash::make('password'),
                    'role'     => 'posko_komando',
                    'bpbd_id'  => $bpbd->id,
                ]
            );

            // 3. Buat Master Posko Komando (Status: Terdaftar Nonaktif & Belum ada Bencana)
            $poskoKomando = Posko::create([
                'nama_posko'       => 'Posko Komando Sleman Utama',
                'tipe_posko'       => 'komando',
                'user_id'          => $userKomandan->id,
                'bpbd_id'          => $bpbd->id,
                'bencana_id'       => null, // Belum di-plot ke bencana
                'penanggung_jawab' => 'Budi Santoso',
                'kontak_hp'        => '081234567890',
                'lokasi'           => $bpbd->alamat_kantor,
                'latitude'         => -7.7622,
                'longitude'        => 110.4025,
                'status'           => 'terdaftar_nonaktif', // BELUM AKTIF
            ]);

            // Hubungkan relasi user ke posko
            $userKomandan->update(['posko_id' => $poskoKomando->id]);
        });
    }
}
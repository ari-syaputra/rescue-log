<?php

namespace Database\Seeders;

use App\Models\Bpbd;
use App\Models\Bencana;
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
            // 1. Ambil Master BPBD Bantul
            $bpbd = Bpbd::where('nama_kabupaten_kota', 'Kabupaten Bantul')->first();
            $bencanaAktif = Bencana::where('status', 'sedang_berjalan')->first();

            // 2. Akun User Komandan Posko Komando Bantul
            $userKomandan = User::firstOrCreate(
                ['email' => 'komando.bantul@rescuelog.id'],
                [
                    'name'     => 'Komandan Budi Santoso',
                    'password' => Hash::make('password123'),
                    'role'     => 'komando',
                    'bpbd_id'  => $bpbd?->id,
                ]
            );

            // 3. Posko Komando Utama (Di Kantor Kapanewon Kretek - Titik Tengah Logistik)
            $poskoKomando = Posko::firstOrCreate(
                ['nama_posko' => 'Posko Komando Taktis Kretek'],
                [
                    'tipe_posko'       => 'komando',
                    'user_id'          => $userKomandan->id,
                    'bpbd_id'          => $bpbd?->id,
                    'bencana_id'       => $bencanaAktif?->id,
                    'penanggung_jawab' => 'Budi Santoso',
                    'kontak_hp'        => '081234567890',
                    'lokasi'           => 'Kantor Kapanewon Kretek, Bantul (Titik Tengah Logistik)',
                    'latitude'         => -7.9620,
                    'longitude'        => 110.3280,
                    'status'           => 'aktif',
                    'kode_undangan'    => 'KOMANDO-BANTUL-01',
                ]
            );
            $userKomandan->update(['posko_id' => $poskoKomando->id]);

            // 4. Akun User Petugas Lapangan Sub-Posko 1
            $userLapangan1 = User::firstOrCreate(
                ['email' => 'petugas.lapangan@rescuelog.id'],
                [
                    'name'     => 'Petugas Lapangan A',
                    'password' => Hash::make('password123'),
                    'role'     => 'lapangan',
                    'bpbd_id'  => $bpbd?->id,
                ]
            );

            // 5. Sub-Posko 1 (Balai Desa Parangtritis - Daratan Aman)
            $poskoLapangan1 = Posko::firstOrCreate(
                ['nama_posko' => 'Sub-Posko Pengungsian Parangtritis'],
                [
                    'tipe_posko'       => 'lapangan_kecil',
                    'parent_id'        => $poskoKomando->id,
                    'user_id'          => $userLapangan1->id,
                    'bpbd_id'          => $bpbd?->id,
                    'bencana_id'       => $bencanaAktif?->id,
                    'penanggung_jawab' => 'Petugas Lapangan A',
                    'kontak_hp'        => '089876543210',
                    'jumlah_petugas'   => 8,
                    'lokasi'           => 'Balai Desa Parangtritis (Zona Terdampak Utama)',
                    'latitude'         => -8.0120, // Daratan Parangtritis
                    'longitude'        => 110.3340,
                    'status'           => 'aktif',
                    'kode_undangan'    => 'PARANGTRITIS-2026',
                ]
            );
            $userLapangan1->update(['posko_id' => $poskoLapangan1->id]);

            // 6. Akun User Petugas Lapangan Sub-Posko 2
            $userLapangan2 = User::firstOrCreate(
                ['email' => 'petugas.depok@rescuelog.id'],
                [
                    'name'     => 'Petugas Lapangan B',
                    'password' => Hash::make('password123'),
                    'role'     => 'lapangan',
                    'bpbd_id'  => $bpbd?->id,
                ]
            );

            // 7. Sub-Posko 2 (Area Lapangan/TPI Depok - Daratan Aman)
            $poskoLapangan2 = Posko::firstOrCreate(
                ['nama_posko' => 'Sub-Posko Darurat Pantai Depok'],
                [
                    'tipe_posko'       => 'lapangan_kecil',
                    'parent_id'        => $poskoKomando->id,
                    'user_id'          => $userLapangan2->id,
                    'bpbd_id'          => $bpbd?->id,
                    'bencana_id'       => $bencanaAktif?->id,
                    'penanggung_jawab' => 'Petugas Lapangan B',
                    'kontak_hp'        => '089876543211',
                    'jumlah_petugas'   => 5,
                    'lokasi'           => 'TPI Pantai Depok, Parangtritis',
                    'latitude'         => -8.0100, // Daratan Depok
                    'longitude'        => 110.2920,
                    'status'           => 'aktif',
                    'kode_undangan'    => 'DEPOK-2026',
                ]
            );
            $userLapangan2->update(['posko_id' => $poskoLapangan2->id]);
        });
    }
}
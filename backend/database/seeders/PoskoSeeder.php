<?php

namespace Database\Seeders;

use App\Models\Bpbd;
use App\Models\Bencana;
use App\Models\Posko;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoskoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $bpbd = Bpbd::where('nama_kabupaten_kota', 'Kabupaten Bantul')->first() ?? Bpbd::first();
            $bencanaAktif = Bencana::where('status', 'sedang_berjalan')->first();

            $userKomandan = User::where('email', 'komando.bantul@rescuelog.id')->first();
            $userLapangan1 = User::where('email', 'petugas.lapangan@rescuelog.id')->first();
            $userLapangan2 = User::where('email', 'petugas.depok@rescuelog.id')->first();

            // 1. Buat / Ambil Posko Komando
            $poskoKomando = Posko::updateOrCreate(
                ['tipe_posko' => 'komando'],
                [
                    'nama_posko'       => 'Posko Komando Taktis Kretek',
                    'user_id'          => $userKomandan?->id,
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

            // Tautkan posko_id ke user Komando
            if ($userKomandan) {
                $userKomandan->update(['posko_id' => $poskoKomando->id]);
            }

            // 2. Sub-Posko Lapangan 1
            $poskoLapangan1 = Posko::updateOrCreate(
                ['nama_posko' => 'Sub-Posko Pengungsian Parangtritis'],
                [
                    'tipe_posko'       => 'lapangan_kecil',
                    'parent_id'        => $poskoKomando->id,
                    'user_id'          => $userLapangan1?->id,
                    'bpbd_id'          => $bpbd?->id,
                    'bencana_id'       => $bencanaAktif?->id,
                    'penanggung_jawab' => 'Petugas Lapangan A',
                    'kontak_hp'        => '089876543210',
                    'jumlah_petugas'   => 8,
                    'lokasi'           => 'Balai Desa Parangtritis (Zona Terdampak Utama)',
                    'latitude'         => -8.0120,
                    'longitude'        => 110.3340,
                    'status'           => 'aktif',
                    'kode_undangan'    => 'PARANGTRITIS-2026',
                ]
            );

            if ($userLapangan1) {
                $userLapangan1->update(['posko_id' => $poskoLapangan1->id]);
            }

            // 3. Sub-Posko Lapangan 2
            $poskoLapangan2 = Posko::updateOrCreate(
                ['nama_posko' => 'Sub-Posko Darurat Pantai Depok'],
                [
                    'tipe_posko'       => 'lapangan_kecil',
                    'parent_id'        => $poskoKomando->id,
                    'user_id'          => $userLapangan2?->id,
                    'bpbd_id'          => $bpbd?->id,
                    'bencana_id'       => $bencanaAktif?->id,
                    'penanggung_jawab' => 'Petugas Lapangan B',
                    'kontak_hp'        => '089876543211',
                    'jumlah_petugas'   => 5,
                    'lokasi'           => 'TPI Pantai Depok, Parangtritis',
                    'latitude'         => -8.0100,
                    'longitude'        => 110.2920,
                    'status'           => 'aktif',
                    'kode_undangan'    => 'DEPOK-2026',
                ]
            );

            if ($userLapangan2) {
                $userLapangan2->update(['posko_id' => $poskoLapangan2->id]);
            }
        });
    }
}
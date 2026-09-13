<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_kebutuhan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengajuan')->unique(); // Contoh: REQ-20260813-ABCD
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('posko_id')->nullable()->constrained('poskos')->onDelete('set null');
            $table->foreignId('bencana_id')->nullable()->constrained('bencana')->onDelete('set null');

            // -------------------------------------------------------------
            // 12 KOLOM NAMA BARANG EKSPLISIT (Sesuai Output Model AI)
            // -------------------------------------------------------------
            $table->double('beras_kg', 15, 2)->default(0);
            $table->double('air_minum_dus', 15, 2)->default(0);
            $table->double('makanan_kaleng_pack', 15, 2)->default(0);
            $table->double('makanan_bayi_pack', 15, 2)->default(0);
            $table->double('minyak_goreng_liter', 15, 2)->default(0);
            $table->double('popok_bayi_pcs', 15, 2)->default(0);
            $table->double('popok_dewasa_pcs', 15, 2)->default(0);
            $table->double('pembalut_wanita_pack', 15, 2)->default(0);
            $table->double('hygiene_kit_paket', 15, 2)->default(0);
            $table->double('selimut_pcs', 15, 2)->default(0);
            $table->double('matras_terpal_pcs', 15, 2)->default(0);
            $table->double('obat_p3k_paket', 15, 2)->default(0);
            // -------------------------------------------------------------

            $table->timestamp('tanggal_pengajuan')->useCurrent();
            $table->enum('status', [
                'pending', 
                'disetujui', 
                'disetujui_sebagian', 
                'ditolak', 
                'dieskalasi_provinsi', // Status baru untuk eskalasi ke Provinsi
                'dalam_pengiriman', 
                'selesai'
            ])->default('pending');

            $table->text('catatan_posko')->nullable();    // Catatan dari petugas lapangan
            $table->text('catatan_komando')->nullable();  // Catatan dari komando/BPBD
            $table->text('catatan_eskalasi')->nullable(); // Catatan alasan eskalasi ke provinsi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_kebutuhan');
    }
};
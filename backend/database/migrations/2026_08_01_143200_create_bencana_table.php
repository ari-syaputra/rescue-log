<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bencana', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_bencana');
            $table->string('lokasi_bencana');
            $table->decimal('koordinat_operasional_lat', 10, 7)->nullable();
            $table->decimal('koordinat_operasional_lng', 10, 7)->nullable();
            
            // TAMBAHAN BARU UNTUK KAJI TRC & SK
            $table->integer('estimasi_pengungsi_awal')->default(0);
            $table->string('sk_status_darurat_path')->nullable();
            
            $table->timestamp('tanggal_aktivasi');
            $table->timestamp('tanggal_selesai')->nullable();
            
            // UPDATE ENUM STATUS (Ditambah 'menunggu_posko')
            $table->enum('status', ['menunggu_posko', 'sedang_berjalan', 'selesai'])->default('menunggu_posko');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bencana');
    }
};
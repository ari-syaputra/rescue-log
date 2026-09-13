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
        Schema::create('poskos', function (Blueprint $table) {
            $table->id();
            $table->string('nama_posko');

            // Hierarki: Komando (Utama) vs Lapangan Kecil
            $table->enum('tipe_posko', ['komando', 'lapangan_kecil'])->default('lapangan_kecil');

            // Foreign Keys
            $table->foreignId('parent_id')->nullable()->constrained('poskos')->onDelete('cascade');
            $table->foreignId('bpbd_id')->nullable()->constrained('bpbd')->onDelete('cascade');
            $table->foreignId('bencana_id')->nullable()->constrained('bencana')->onDelete('cascade');
            
            // TAMBAHKAN KOLOM USER_ID DI SINI (Relasi ke Akun Komandan/Petugas)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');

            $table->string('kode_undangan')->nullable()->unique();
            $table->text('lokasi')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('kapasitas_maksimal')->nullable();
            $table->string('penanggung_jawab');
            $table->string('kontak_hp')->nullable();
            $table->integer('jumlah_petugas')->default(0);
            $table->string('foto')->nullable();

            $table->enum('status', [
                'terdaftar_nonaktif', 
                'aktif',  
                'penuh',
                'nonaktif',
                'ditutup',  
            ])->default('terdaftar_nonaktif');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poskos');
    }
};
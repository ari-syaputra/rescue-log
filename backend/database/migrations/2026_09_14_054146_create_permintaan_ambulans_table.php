<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan_ambulans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_sos')->unique();
            $table->foreignId('posko_id')->constrained('poskos')->onDelete('cascade');
            $table->foreignId('bencana_id')->constrained('bencana')->onDelete('cascade');
            $table->foreignId('armada_id')->nullable()->constrained('armadas')->onDelete('set null');
            
            $table->string('nama_pasien')->default('Anonim');
            $table->enum('kategori_darurat', ['kritis_nyawa', 'berat', 'sedang'])->default('berat');
            $table->text('kondisi_medis');
            $table->string('rs_rujukan')->nullable();
            
            $table->enum('status', [
                'menunggu_penanganan', 
                'ambulans_meluncur', 
                'proses_evakuasi', 
                'selesai', 
                'dibatalkan'
            ])->default('menunggu_penanganan');
            
            $table->timestamp('waktu_request')->useCurrent();
            $table->timestamp('waktu_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_ambulans');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_inventaris', function (Blueprint $table) {
            $table->id();
            
            // Nullable: Jika NULL berarti stok milik Gudang Utama BPBD
            // Jika terisi posko_id berarti stok berada di Posko Komando / Sub-Posko tertentu
            $table->foreignId('posko_id')->nullable()->constrained('poskos')->onDelete('cascade');
            
            $table->string('nama_barang');
            $table->string('kategori');
            $table->float('jumlah', 10, 2)->default(0);
            $table->string('satuan'); 
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_inventaris');
    }
};
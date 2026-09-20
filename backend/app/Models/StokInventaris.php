<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokInventaris extends Model
{
    use HasFactory;

    protected $table = 'stok_inventaris';

    protected $fillable = [
        'posko_id',
        'nama_barang',
        'kategori',
        'jumlah',
        'satuan',
        'keterangan',
    ];

    public function pengiriman()
    {
        return $this->hasMany(PengirimanInventaris::class, 'stok_inventaris_id');
    }
}
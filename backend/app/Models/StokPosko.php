<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokPosko extends Model
{
    use HasFactory;

    protected $table = 'stok_posko';

    protected $fillable = [
        'posko_id',
        'barang_id',
        'jumlah_stok',
    ];

    public function posko()
    {
        return $this->belongsTo(Posko::class, 'posko_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bpbd extends Model
{
    use HasFactory;

    protected $table = 'bpbd';

    protected $fillable = [
        'nama_kabupaten_kota',
        'alamat_kantor',
    ];

    /**
     * Relasi ke Posko
     */
    public function poskos()
    {
        return $this->hasMany(Posko::class, 'bpbd_id');
    }

    /**
     * Relasi ke Bencana
     */
    public function bencanas()
    {
        return $this->hasMany(Bencana::class, 'bpbd_id');
    }
}
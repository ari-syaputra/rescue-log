<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bencana extends Model
{
    protected $table = 'bencana';

    protected $fillable = [
        'jenis_bencana',
        'lokasi_bencana',
        'koordinat_operasional_lat',
        'koordinat_operasional_lng',
        'geojson_polygon',
        'luas_area_km2',
        'total_jiwa_terdampak',
        'total_kk_terdampak',
        'total_bangunan_terdampak',
        'tanggal_aktivasi',
        'tanggal_selesai',
        'status',
        'estimasi_pengungsi_awal',
        'sk_status_darurat_path',
    ];

    protected $casts = [
        'tanggal_aktivasi' => 'datetime',
        'tanggal_selesai'  => 'datetime',
        'geojson_polygon'  => 'array', // Otomatis meng-cast JSON menjadi Array PHP
        'luas_area_km2'    => 'float',
    ];

    public function poskos()
    {
        return $this->hasMany(Posko::class, 'bencana_id');
    }
}
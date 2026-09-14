<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanAmbulans extends Model
{
    use HasFactory;

    protected $table = 'permintaan_ambulans';

    protected $fillable = [
        'kode_sos',
        'posko_id',
        'bencana_id',
        'armada_id',
        'nama_pasien',
        'kategori_darurat',
        'kondisi_medis',
        'rs_rujukan',
        'status',
        'waktu_request',
        'waktu_selesai',
    ];

    protected $casts = [
        'waktu_request' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function posko()
    {
        return $this->belongsTo(Posko::class, 'posko_id');
    }

    public function bencana()
    {
        return $this->belongsTo(Bencana::class, 'bencana_id');
    }

    public function armada()
    {
        return $this->belongsTo(Armada::class, 'armada_id');
    }
}
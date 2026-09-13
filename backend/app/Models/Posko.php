<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Posko extends Model
{
    use HasFactory;

    protected $table = 'poskos';

    protected $fillable = [
        'nama_posko',
        'tipe_posko',
        'parent_id',
        'bpbd_id',
        'bencana_id',
        'user_id',
        'kode_undangan',
        'lokasi',
        'latitude',
        'longitude',
        'kapasitas_maksimal',
        'penanggung_jawab',
        'kontak_hp',
        'foto',
        'jumlah_petugas',
        'status',
    ];

    public static function generateKodeUndangan(): string
    {
        do {
            $kode = 'PSK-' . strtoupper(Str::random(6));
        } while (self::where('kode_undangan', $kode)->exists()); 

        return $kode;
    }

    // --- RELASI ---

    public function bpbd()
    {
        return $this->belongsTo(Bpbd::class, 'bpbd_id');
    }

    public function bencana()
    {
        return $this->belongsTo(Bencana::class, 'bencana_id');
    }

    public function parent()
    {
        return $this->belongsTo(Posko::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Posko::class, 'parent_id');
    }

    // TAMBAHKAN INI (Relasi ke User / Akun Komandan Posko)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'posko_id');
    }

    public function fotos()
    {
        return $this->hasMany(PoskoFoto::class, 'posko_id');
    }

    public function scopeKomando($query)
    {
        return $query->where('tipe_posko', 'komando');
    }
}
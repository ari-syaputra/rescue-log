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

    // --- HELPER METODE UNTUK GENERATE KODE ---

    /**
     * Generate kode undangan unik (contoh: PSK-A8K29X)
     */
    public static function generateKodeUndangan(): string
    {
        do {
            // Menghasilkan kombinasi 6 karakter acak kapital (huruf & angka)
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

    public function users()
    {
        return $this->hasMany(User::class, 'posko_id');
    }

    public function fotos()
    {
        return $this->hasMany(PoskoFoto::class, 'posko_id');
    }
}
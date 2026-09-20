<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // bnpb, bpbd_provinsi, admin (bpbd_kabkota), komando, lapangan
        'posko_id',
        'bpbd_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Helper Roles Checking
    public function isBnpb(): bool
    {
        return in_array($this->role, ['bnpb', 'pusat']);
    }

    public function isProvinsi(): bool
    {
        return in_array($this->role, ['bpbd_provinsi', 'provinsi']);
    }

    public function isAdminKabkota(): bool
    {
        return in_array($this->role, ['admin', 'bpbd', 'bpbd_kabkota']);
    }

    public function isKomando(): bool
    {
        return in_array($this->role, ['komando', 'koordinator_komando', 'posko_komando']);
    }

    public function isLapangan(): bool
    {
        return in_array($this->role, ['lapangan', 'sub_posko', 'petugas_lapangan']);
    }

    // Relasi
    public function posko()
    {
        return $this->belongsTo(Posko::class, 'posko_id');
    }

    public function bpbd()
    {
        return $this->belongsTo(Bpbd::class, 'bpbd_id');
    }
}
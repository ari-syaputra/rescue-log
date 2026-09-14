<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Pengiriman;

class Armada extends Model
{
    protected $table = 'armadas';
    protected $guarded = ['id'];

    public function pengirimans(): HasMany
    {
        return $this->hasMany(Pengiriman::class, 'armada_id');
    }

    public function permintaanAmbulans()
    {
        return $this->hasMany(PermintaanAmbulans::class, 'armada_id');
    }
}

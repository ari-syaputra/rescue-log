<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanInventaris extends Model
{
    use HasFactory;

    protected $table = 'pengiriman_inventaris';

    protected $fillable = [
        'pengajuan_id',
        'stok_inventaris_id',
        'posko_id',
        'armada_id',           // Relasi ke tabel armadas
        'user_id',
        'jumlah_dikirim',
        'status_distribusi',
        'estimasi_waktu',
        'waktu_diterima',
        'keterangan',
    ];

    protected $casts = [
        'jumlah_dikirim' => 'double',
        'waktu_diterima' => 'datetime',
    ];

    /**
     * Relasi ke Armada Pengiriman
     */
    public function armada()
    {
        return $this->belongsTo(Armada::class, 'armada_id');
    }

    /**
     * Relasi ke Pengajuan Logistik
     */
    public function pengajuan()
    {
        return $this->belongsTo(PengajuanKebutuhan::class, 'pengajuan_id');
    }

    /**
     * Relasi ke Stok Inventaris (Barang Gudang)
     */
    public function stokInventaris()
    {
        return $this->belongsTo(StokInventaris::class, 'stok_inventaris_id');
    }

    /**
     * Relasi ke Posko
     */
    public function posko()
    {
        return $this->belongsTo(Posko::class, 'posko_id');
    }

    /**
     * Relasi ke User (Petugas Lapangan / Pengirim)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Helper Method: Cek apakah transaksi ini masih boleh di-edit atau di-hapus (Maksimal 20 Menit)
     */
    public function canBeEditedOrDeleted(): bool
    {
        return $this->created_at->addMinutes(20)->isFuture();
    }

    /**
     * Helper Method: Mendapatkan sisa waktu sebelum opsi edit/delete dikunci
     */
    public function sisaWaktuMenit(): int
    {
        if (!$this->canBeEditedOrDeleted()) {
            return 0;
        }

        return max(0, (int) now()->diffInMinutes($this->created_at->addMinutes(20), false));
    }
}
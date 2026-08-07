<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Informasi extends Model
{
    protected $fillable = [
        'judul', 'isi', 'kategori', 'gambar',
        'published', 'tanggal_kegiatan', 'lokasi',
        'rt', 'rw',
        'user_id', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kegiatan' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Filter informasi yang berlaku untuk wilayah RT/RW tertentu.
     * Berita tanpa RT/RW (NULL) dianggap berlaku untuk seluruh desa.
     */
    public function scopeUntukWilayah($query, $rt, $rw)
    {
        return $query->where(function ($q) use ($rt, $rw) {
            $q->whereNull('rt')
                ->whereNull('rw')
                ->orWhere(function ($q2) use ($rt, $rw) {
                    $q2->whereRaw('rt + 0 = ?', [(int) $rt])
                        ->whereRaw('rw + 0 = ?', [(int) $rw]);
                });
        });
    }
}

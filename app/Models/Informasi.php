<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Informasi extends Model
{
    protected $fillable = [
        'judul', 'isi', 'kategori', 'gambar',
        'published', 'tanggal_kegiatan', 'lokasi',
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
}

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
    public function scopeUntukWilayah($query, $rt, $rw = '01')
    {
        return $query->where(function ($q) use ($rt, $rw) {
            $q->where(function ($all) {
                $all->whereNull('rt')->whereNull('rw');
            })
            ->orWhere(function ($specific) use ($rt, $rw) {
                $specific->whereNotNull('rt')
                    ->whereRaw('CAST(rt AS INTEGER) = ?', [(int) $rt])
                    ->where(function ($rwQuery) use ($rw) {
                        $rwQuery->whereNull('rw')
                            ->orWhereRaw('CAST(rw AS INTEGER) = ?', [(int) $rw]);
                    });
            });
        });
    }
}

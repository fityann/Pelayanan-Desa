<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Keluarga extends Model
{
    protected $table = 'keluarga';

    protected $fillable = [
        'no_kk',
        'kepala_keluarga',
        'kepala_keluarga_id',
        'alamat',
        'rt',
        'rw',
        'desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
    ];

    public function penduduk(): HasMany
    {
        return $this->hasMany(Penduduk::class, 'keluarga_id');
    }

    public function kepalaKeluarga(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'kepala_keluarga_id');
    }

    public function getAlamatLengkapAttribute(): string
    {
        return implode(', ', array_filter([
            $this->alamat,
            $this->desa,
            $this->kecamatan,
            $this->kabupaten,
            $this->provinsi,
        ]));
    }

    public function getLokasiAttribute(): string
    {
        return $this->rt && $this->rw ? "RT {$this->rt} / RW {$this->rw}" : '-';
    }
}
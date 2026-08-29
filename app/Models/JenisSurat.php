<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSurat extends Model
{
    protected $fillable = ['kode', 'nama', 'deskripsi', 'syarat', 'form_fields', 'masa_berlaku', 'butuh_ttd_fisik', 'aktif'];

    protected function casts(): array
    {
        return [
            'form_fields' => 'array',
            'butuh_ttd_fisik' => 'boolean',
            'aktif' => 'boolean',
        ];
    }

    public function pengajuanSurat(): HasMany
    {
        return $this->hasMany(PengajuanSurat::class);
    }
}

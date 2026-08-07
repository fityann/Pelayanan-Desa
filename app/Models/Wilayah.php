<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wilayah extends Model
{
    protected $table = 'wilayah';

    protected $fillable = [
        'nama',
        'rt',
        'rw',
        'kategori',
        'deskripsi',
    ];

    public function musrenbang(): HasMany
    {
        return $this->hasMany(Musrenbang::class, 'wilayah_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Apbde extends Model
{
    protected $table = 'apbdes';

    protected $fillable = [
        'tahun', 'kategori', 'bidang', 'sub_bidang', 'uraian',
        'anggaran', 'realisasi', 'status',
        'created_by', 'reviewed_by', 'published_by', 'tanggal_publikasi',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}

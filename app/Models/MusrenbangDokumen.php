<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MusrenbangDokumen extends Model
{
    protected $table = 'musrenbang_dokumen';
    
    protected $fillable = [
        'musrenbang_id',
        'nama_dokumen',
        'tipe_dokumen',
        'path_dokumen'
    ];

    public function musrenbang(): BelongsTo
    {
        return $this->belongsTo(Musrenbang::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PencairanDanaDokumen extends Model
{
    protected $table = 'pencairan_dana_dokumen';
    
    protected $fillable = [
        'pencairan_dana_id',
        'nama_dokumen',
        'tipe_dokumen',
        'path_dokumen'
    ];

    public function pencairanDana(): BelongsTo
    {
        return $this->belongsTo(PencairanDana::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BelanjaDokumen extends Model
{
    protected $table = 'belanja_dokumen';
    
    protected $fillable = [
        'belanja_id',
        'nama_dokumen',
        'tipe_dokumen',
        'path_dokumen'
    ];

    public function belanja(): BelongsTo
    {
        return $this->belongsTo(Belanja::class);
    }
}
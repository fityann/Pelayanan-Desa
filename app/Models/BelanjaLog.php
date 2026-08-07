<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BelanjaLog extends Model
{
    protected $table = 'belanja_log';
    
    protected $fillable = [
        'belanja_id',
        'user_id',
        'aksi',
        'deskripsi',
        'data_sebelum',
        'data_sesudah'
    ];

    protected $casts = [
        'data_sebelum' => 'array',
        'data_sesudah' => 'array',
        'created_at' => 'datetime'
    ];

    public function belanja(): BelongsTo
    {
        return $this->belongsTo(Belanja::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
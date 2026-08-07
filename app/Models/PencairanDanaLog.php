<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PencairanDanaLog extends Model
{
    protected $table = 'pencairan_dana_log';
    
    protected $fillable = [
        'pencairan_dana_id',
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

    public function pencairanDana(): BelongsTo
    {
        return $this->belongsTo(PencairanDana::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MusrenbangSuara extends Model
{
    protected $table = 'musrenbang_suara';
    
    protected $fillable = [
        'musrenbang_id',
        'user_id',
        'tipe_suara',
        'alasan'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function musrenbang(): BelongsTo
    {
        return $this->belongsTo(Musrenbang::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
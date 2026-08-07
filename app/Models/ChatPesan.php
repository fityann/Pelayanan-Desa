<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatPesan extends Model
{
    protected $table = 'chat_pesans';

    protected $fillable = [
        'chat_id',
        'sender_id',
        'sender_role',
        'isi',
        'dibaca_admin',
        'dibaca_warga',
    ];

    protected function casts(): array
    {
        return [
            'dibaca_admin' => 'boolean',
            'dibaca_warga' => 'boolean',
        ];
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}

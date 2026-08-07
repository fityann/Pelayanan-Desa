<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    protected $fillable = [
        'user_id',
        'rt',
        'rw',
        'last_message_at',
    ];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pesans(): HasMany
    {
        return $this->hasMany(ChatPesan::class)->latest();
    }

    /**
     * Ambil (atau buat) satu percakapan milik seorang warga.
     * Setiap warga hanya punya satu percakapan dengan admin.
     */
    public static function untukWarga(int $userId, ?string $rt = null, ?string $rw = null): self
    {
        return static::firstOrCreate(
            ['user_id' => $userId],
            ['rt' => $rt, 'rw' => $rw]
        );
    }

    public function tandaiDibacaWarga(): void
    {
        $this->pesans()
            ->where('sender_role', 'admin')
            ->where('dibaca_warga', false)
            ->update(['dibaca_warga' => true]);
    }

    public function tandaiDibacaAdmin(): void
    {
        $this->pesans()
            ->where('sender_role', 'warga')
            ->where('dibaca_admin', false)
            ->update(['dibaca_admin' => true]);
    }

    public function getBelumDibacaWargaAttribute(): int
    {
        return $this->pesans()
            ->where('sender_role', 'admin')
            ->where('dibaca_warga', false)
            ->count();
    }

    public function getBelumDibacaAdminAttribute(): int
    {
        return $this->pesans()
            ->where('sender_role', 'warga')
            ->where('dibaca_admin', false)
            ->count();
    }

    public function scopeYangBelumDibacaAdmin(Builder $query): Builder
    {
        return $query->whereHas('pesans', fn (Builder $q) => $q
            ->where('sender_role', 'warga')
            ->where('dibaca_admin', false));
    }

    /**
     * Jumlah percakapan dengan pesan warga yang belum dibaca admin.
     */
    public static function unreadAdminCount(): int
    {
        return static::yangBelumDibacaAdmin()->count();
    }
}

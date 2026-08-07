<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'icon',
        'warna',
        'link',
        'is_read',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeBelumDibaca(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    public function scopeUntuk(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function tandaiDibaca(): void
    {
        $this->update(['is_read' => true, 'read_at' => now()]);
    }

    /**
     * Buat notifikasi untuk satu pengguna.
     */
    public static function buat(int $userId, array $data): self
    {
        return static::create(array_merge([
            'is_read' => false,
            'icon' => 'notifications',
            'warna' => 'bg-primary/10 text-primary',
        ], $data, ['user_id' => $userId]));
    }

    /**
     * Kirim notifikasi ke semua user dengan role tertentu (mis. Admin Desa).
     */
    public static function kirimKeRole(string $roleName, array $data): void
    {
        $users = User::whereHas('roles', fn ($q) => $q->where('name', $roleName))->get();

        foreach ($users as $user) {
            static::buat($user->id, $data);
        }
    }

    /**
     * Kirim notifikasi ke semua user dengan salah satu role (mis. semua staff).
     */
    public static function kirimKeStaff(array $data): void
    {
        $roles = ['Super Admin', 'Admin Desa', 'Kepala Desa', 'Sekretaris Desa', 'Bendahara'];
        $users = User::whereHas('roles', fn ($q) => $q->whereIn('name', $roles))->get();

        foreach ($users as $user) {
            static::buat($user->id, $data);
        }
    }
}

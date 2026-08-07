<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'nik', 'phone', 'address', 'rt', 'rw'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    public function penduduk(): HasOne
    {
        return $this->hasOne(Penduduk::class);
    }

    public function keluarga(): BelongsTo
    {
        return $this->belongsTo(Keluarga::class);
    }

    public function pengaduans(): HasMany
    {
        return $this->hasMany(Pengaduan::class);
    }

    public function pengaduanDiproses(): HasMany
    {
        return $this->hasMany(Pengaduan::class, 'processed_by');
    }

    public function pengajuanSurats(): HasMany
    {
        return $this->hasMany(PengajuanSurat::class);
    }

    public function pengajuanSuratDiverifikasi(): HasMany
    {
        return $this->hasMany(PengajuanSurat::class, 'verified_by');
    }

    public function pengajuanSuratDisetujui(): HasMany
    {
        return $this->hasMany(PengajuanSurat::class, 'approved_by');
    }

    public function informasis(): HasMany
    {
        return $this->hasMany(Informasi::class);
    }

    public function riwayatStatusSurat(): HasMany
    {
        return $this->hasMany(RiwayatStatusSurat::class, 'oleh_user_id');
    }

    public function apbdesDibuat(): HasMany
    {
        return $this->hasMany(Apbde::class, 'created_by');
    }

    public function apbdesDireview(): HasMany
    {
        return $this->hasMany(Apbde::class, 'reviewed_by');
    }

    public function apbdesDipublikasi(): HasMany
    {
        return $this->hasMany(Apbde::class, 'published_by');
    }

    public function pencairanDana(): HasMany
    {
        return $this->hasMany(PencairanDana::class, 'pemohon_id');
    }

    public function belanjas(): HasMany
    {
        return $this->hasMany(Belanja::class, 'pemohon_id');
    }

    public function musrenbangs(): HasMany
    {
        return $this->hasMany(Musrenbang::class, 'pengusul_id');
    }

    public function rtQrCodes(): HasMany
    {
        return $this->hasMany(RtQrCode::class, 'created_by');
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

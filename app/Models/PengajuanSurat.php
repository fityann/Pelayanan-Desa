<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengajuanSurat extends Model
{
    protected $fillable = [
        'user_id', 'jenis_surat_id', 'nomor_surat', 'status',
        'nama_pemohon', 'nik_pemohon', 'alamat_pemohon', 'no_whatsapp', 'kode_tracking',
        'butuh_ttd_fisik', 'keterangan', 'data_isian', 'alasan_ditolak', 'file_pendukung',
        'verified_by', 'approved_by',
        'tanggal_diajukan', 'tanggal_disetujui', 'tanggal_ttd_fisik', 'tanggal_diambil',
    ];

    protected function casts(): array
    {
        return [
            'butuh_ttd_fisik' => 'boolean',
            'data_isian' => 'array',
            'tanggal_diajukan' => 'datetime',
            'tanggal_disetujui' => 'datetime',
            'tanggal_ttd_fisik' => 'datetime',
            'tanggal_diambil' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function riwayatStatus(): HasMany
    {
        return $this->hasMany(RiwayatStatusSurat::class);
    }

    public function getKodeTrackingValAttribute(): string
    {
        if (!empty($this->attributes['kode_tracking'])) {
            return $this->attributes['kode_tracking'];
        }
        return 'SRT-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Accessor Eloquent: $pengajuan->pemohon_name
     */
    public function getPemohonNameAttribute(): ?string
    {
        return $this->pemohonName();
    }

    /**
     * Accessor Eloquent: $pengajuan->pemohon_nik
     */
    public function getPemohonNikAttribute(): ?string
    {
        return $this->pemohonNik();
    }

    /**
     * Accessor Eloquent: $pengajuan->pemohon_alamat
     */
    public function getPemohonAlamatAttribute(): ?string
    {
        return $this->pemohonAlamat();
    }

    public function pemohonName(): ?string
    {
        return $this->nama_pemohon ?? $this->user?->name;
    }

    public function pemohonNik(): ?string
    {
        return $this->nik_pemohon ?? $this->user?->nik;
    }

    public function pemohonAlamat(): ?string
    {
        if ($this->alamat_pemohon) {
            return $this->alamat_pemohon;
        }

        $pd = $this->user?->penduduk;

        if ($pd) {
            return trim("{$pd->alamat} RT {$pd->rt}/RW {$pd->rw}");
        }

        return $this->user?->address;
    }

    public function catatStatus(string $status, ?string $catatan = null, ?int $olehUserId = null): void
    {
        $this->riwayatStatus()->create([
            'status' => $status,
            'catatan' => $catatan,
            'oleh_user_id' => $olehUserId ?? auth()->id(),
        ]);
    }
}

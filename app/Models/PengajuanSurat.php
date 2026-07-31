<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanSurat extends Model
{
    protected $fillable = [
        'user_id', 'jenis_surat_id', 'nomor_surat', 'status',
        'butuh_ttd_fisik', 'keterangan', 'alasan_ditolak', 'file_pendukung',
        'verified_by', 'approved_by',
        'tanggal_diajukan', 'tanggal_disetujui', 'tanggal_ttd_fisik', 'tanggal_diambil',
    ];

    protected function casts(): array
    {
        return [
            'butuh_ttd_fisik' => 'boolean',
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
}

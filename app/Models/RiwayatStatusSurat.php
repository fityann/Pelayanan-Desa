<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatStatusSurat extends Model
{
    protected $table = 'riwayat_status_surats';

    protected $fillable = [
        'pengajuan_surat_id',
        'status',
        'catatan',
        'oleh_user_id',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function pengajuanSurat(): BelongsTo
    {
        return $this->belongsTo(PengajuanSurat::class);
    }

    public function olehUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'oleh_user_id');
    }
}

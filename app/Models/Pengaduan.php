<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengaduan extends Model
{
    protected $fillable = [
        'user_id', 'kategori', 'judul', 'deskripsi', 'foto',
        'status', 'sumber_akses', 'tanggapan', 'processed_by',
        'tanggal_diterima', 'tanggal_diproses', 'tanggal_selesai',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_diterima' => 'datetime',
            'tanggal_diproses' => 'datetime',
            'tanggal_selesai' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Apbde extends Model
{
    protected $table = 'apbdes';

    protected $fillable = [
        'tahun', 'kategori', 'bidang', 'sub_bidang', 'uraian',
        'anggaran', 'realisasi', 'status',
        'created_by', 'reviewed_by', 'published_by', 'tanggal_publikasi',
    ];

    protected $casts = [
        'anggaran' => 'decimal:2',
        'realisasi' => 'decimal:2',
        'tanggal_publikasi' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function publishedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function pencairanDana(): HasMany
    {
        return $this->hasMany(PencairanDana::class, 'apbdes_id');
    }

    public function getPersentaseRealisasiAttribute()
    {
        return $this->anggaran > 0 ? round(($this->realisasi / $this->anggaran) * 100, 2) : 0;
    }

    public function getStatusKegiatanAttribute()
    {
        $persentase = $this->persentase_realisasi;
        if ($persentase >= 90) {
            return 'selesai';
        } elseif ($persentase >= 50) {
            return 'proses';
        }
        return 'belum';
    }

    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
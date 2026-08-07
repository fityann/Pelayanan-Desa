<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Musrenbang extends Model
{
    use SoftDeletes;

    protected $table = 'musrenbang';
    
    protected $fillable = [
        'tahun',
        'judul_kegiatan',
        'deskripsi_kegiatan',
        'wilayah_id',
        'jenis_kegiatan',
        'estimasi_biaya',
        'sumber_dana',
        'prioritas',
        'jumlah_pengusul',
        'jumlah_pendukung',
        'status_usulan',
        'pengusul_id',
        'verifikator_id',
        'reviewer_id',
        'catatan_review',
        'tanggal_musrenbang',
        'hasil_musrenbang',
        'alokasi_anggaran',
        'tanggal_realisasi'
    ];

    protected $casts = [
        'estimasi_biaya' => 'decimal:2',
        'alokasi_anggaran' => 'decimal:2',
        'tanggal_musrenbang' => 'datetime',
        'tanggal_realisasi' => 'datetime'
    ];

    public function pengusul(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengusul_id');
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class);
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(MusrenbangDokumen::class);
    }

    public function suara(): HasMany
    {
        return $this->hasMany(MusrenbangSuara::class);
    }

    public function dukungan(): HasMany
    {
        return $this->hasMany(MusrenbangSuara::class)->where('tipe_suara', 'dukung');
    }

    public function penolakan(): HasMany
    {
        return $this->hasMany(MusrenbangSuara::class)->where('tipe_suara', 'tolak');
    }

    public function pencairanDana(): HasMany
    {
        return $this->hasMany(PencairanDana::class, 'musrenbang_id');
    }

    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status_usulan', $status);
    }

    public function scopePrioritas($query, $prioritas)
    {
        return $query->where('prioritas', $prioritas);
    }

    public function getTotalDukunganAttribute()
    {
        return $this->dukungan()->count();
    }

    public function getTotalPenolakanAttribute()
    {
        return $this->penolakan()->count();
    }

    public function getPersentaseDukunganAttribute()
    {
        $total = $this->dukungan()->count() + $this->penolakan()->count();
        return $total > 0 ? ($this->dukungan()->count() / $total) * 100 : 0;
    }
}
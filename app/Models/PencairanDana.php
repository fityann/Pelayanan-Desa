<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PencairanDana extends Model
{
    use SoftDeletes;

    protected $table = 'pencairan_dana';
    
    protected $fillable = [
        'nomor_permohonan',
        'apbdes_id',
        'musrenbang_id',
        'nama_kegiatan',
        'jumlah_pencairan',
        'sumber_dana',
        'jenis_pencairan',
        'status_pencairan',
        'pemohon_id',
        'verifikator_keuangan_id',
        'penandatangan_id',
        'bendahara_id',
        'tanggal_pengajuan',
        'tanggal_verifikasi',
        'tanggal_persetujuan',
        'tanggal_pencairan',
        'catatan_pencairan',
        'metode_pembayaran',
        'nama_bank',
        'nomor_rekening',
        'atas_nama',
        'bukti_pembayaran'
    ];

    protected $casts = [
        'jumlah_pencairan' => 'decimal:2',
        'tanggal_pengajuan' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
        'tanggal_persetujuan' => 'datetime',
        'tanggal_pencairan' => 'datetime'
    ];

    public function apbdes(): BelongsTo
    {
        return $this->belongsTo(Apbde::class, 'apbdes_id');
    }

    public function musrenbang(): BelongsTo
    {
        return $this->belongsTo(Musrenbang::class);
    }

    public function pemohon(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pemohon_id');
    }

    public function verifikatorKeuangan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifikator_keuangan_id');
    }

    public function penandatangan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penandatangan_id');
    }

    public function bendahara(): BelongsTo
    {
        return $this->belongsTo(User::class, 'bendahara_id');
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(PencairanDanaDokumen::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PencairanDanaLog::class);
    }

    public function belanja(): HasMany
    {
        return $this->hasMany(Belanja::class, 'pencairan_dana_id');
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status_pencairan', $status);
    }

    public function scopeTahun($query, $tahun)
    {
        return $query->whereYear('tanggal_pengajuan', $tahun);
    }

    public function generateNomorPermohonan()
    {
        $tahun = date('Y');
        $count = self::whereYear('created_at', $tahun)->count() + 1;
        return "SPM/{$tahun}/" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Belanja extends Model
{
    use SoftDeletes;

    protected $table = 'belanja';
    
    protected $fillable = [
        'nomor_belanja',
        'pencairan_dana_id',
        'jenis_belanja',
        'nama_barang_jasa',
        'kuantitas',
        'satuan',
        'harga_satuan',
        'total_harga',
        'spesifikasi',
        'metode_pengadaan',
        'penyedia',
        'status_belanja',
        'pemohon_id',
        'penyedia_id',
        'penerima_id',
        'tanggal_pengajuan',
        'tanggal_persetujuan',
        'tanggal_pembelian',
        'tanggal_pengiriman',
        'tanggal_penerimaan',
        'catatan_penerimaan'
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'kuantitas' => 'integer',
        'tanggal_pengajuan' => 'datetime',
        'tanggal_persetujuan' => 'datetime',
        'tanggal_pembelian' => 'datetime',
        'tanggal_pengiriman' => 'datetime',
        'tanggal_penerimaan' => 'datetime'
    ];

    public function pencairanDana(): BelongsTo
    {
        return $this->belongsTo(PencairanDana::class);
    }

    public function pemohon(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pemohon_id');
    }

    public function penyedia(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penyedia_id');
    }

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penerima_id');
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(BelanjaDokumen::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(BelanjaLog::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->harga_satuan * $this->kuantitas;
    }

    public function generateNomorBelanja()
    {
        $tahun = date('Y');
        $count = self::whereYear('created_at', $tahun)->count() + 1;
        return "BL/{$tahun}/" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status_belanja', $status);
    }

    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis_belanja', $jenis);
    }

    public function scopeTahun($query, $tahun)
    {
        return $query->whereYear('tanggal_pengajuan', $tahun);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengaduan extends Model
{
    protected $table = 'pengaduans';
    
    protected $fillable = [
        'user_id',
        'kategori',
        'judul',
        'deskripsi',
        'foto',
        'status',
        'sumber_akses',
        'tanggapan',
        'processed_by',
        'tanggal_diterima',
        'tanggal_diproses',
        'tanggal_selesai',
        'nama_pelapor',
        'whatsapp',
        'tiket_id',
        'lokasi_qr',
        'latitude',
        'longitude',
        'rt',
        'rw'
    ];

    protected $casts = [
        'tanggal_diterima' => 'datetime',
        'tanggal_diproses' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Daftar foto bukti. Mendukung beberapa file (disimpan sebagai JSON)
     * sekaligus kompatibel dengan data lama yang hanya berisi satu path.
     */
    public function getFotoListAttribute(): array
    {
        if (empty($this->foto)) {
            return [];
        }

        $decoded = json_decode($this->foto, true);

        return is_array($decoded) ? $decoded : [$this->foto];
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function getKategoriDisplayAttribute()
    {
        $kategoriMap = [
            'sampah' => 'Sampah Menumpuk',
            'jalan' => 'Kerusakan Jalan',
            'drainase' => 'Drainase Tersumbat',
            'penerangan' => 'Lampu Jalan Rusak',
            'air' => 'Masalah Air Bersih',
            'lainnya' => 'Lainnya'
        ];

        return $kategoriMap[$this->kategori] ?? $this->kategori;
    }

    public function getStatusDisplayAttribute()
    {
        $statusMap = [
            'diterima' => ['label' => 'Diterima', 'color' => 'bg-blue-100 text-blue-800'],
            'diproses' => ['label' => 'Diproses', 'color' => 'bg-yellow-100 text-yellow-800'],
            'selesai' => ['label' => 'Selesai', 'color' => 'bg-green-100 text-green-800']
        ];

        return $statusMap[$this->status] ?? ['label' => $this->status, 'color' => 'bg-gray-100 text-gray-800'];
    }

    public function getLokasiDisplayAttribute()
    {
        if ($this->rt && $this->rw) {
            return "RT {$this->rt} / RW {$this->rw}";
        }
        return 'Lokasi tidak tersedia';
    }

    public function getWaktuLaluAttribute()
    {
        return $this->tanggal_diterima->diffForHumans();
    }

    public function scopeFromQR($query)
    {
        return $query->whereIn('sumber_akses', ['qr_code', 'qr_rt']);
    }

    public function scopeFromWeb($query)
    {
        return $query->where('sumber_akses', 'web');
    }

    public function getSourceLabelAttribute()
    {
        return match ($this->sumber_akses) {
            'qr_rt', 'qr_code' => 'QR Code',
            'web' => 'Web',
            default => ucfirst($this->sumber_akses ?? 'Web'),
        };
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeByLocation($query, $rt = null, $rw = null)
    {
        if ($rt) {
            $query->where('rt', $rt);
        }
        if ($rw) {
            $query->where('rw', $rw);
        }
        return $query;
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('tanggal_diterima', '>=', now()->subDays($days));
    }
}
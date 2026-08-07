<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RtQrCode extends Model
{
    protected $table = 'rt_qr_codes';
    
    protected $fillable = [
        'rt',
        'rw',
        'nama_rt',
        'deskripsi',
        'qr_code_path',
        'qr_code_url',
        'status',
        'tanggal_generate',
        'scan_count',
        'created_by'
    ];

    protected $casts = [
        'tanggal_generate' => 'datetime',
        'scan_count' => 'integer'
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(QrCodeLog::class, 'rt_qr_code_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function incrementScanCount()
    {
        $this->increment('scan_count');
        $this->save();
    }

    public function getFullLocationAttribute()
    {
        return "RT {$this->rt} / RW {$this->rw}";
    }

    public function getQrCodeUrlAttribute()
    {
        if ($this->attributes['qr_code_url']) {
            return $this->attributes['qr_code_url'];
        }
        return route('warga.rt.landing', ['rt' => $this->rt, 'rw' => $this->rw]);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByRtRw($query, $rt, $rw)
    {
        return $query->where('rt', $rt)->where('rw', $rw);
    }

    public function getStatsAttribute()
    {
        return [
            'total_scans' => $this->scan_count,
            'today_scans' => $this->logs()->whereDate('created_at', today())->count(),
            'last_scan' => $this->logs()->latest()->first()?->created_at,
        ];
    }
}
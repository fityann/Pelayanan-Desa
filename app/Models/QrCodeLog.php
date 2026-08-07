<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrCodeLog extends Model
{
    protected $table = 'qr_code_logs';
    
    protected $fillable = [
        'rt_qr_code_id',
        'ip_address',
        'user_agent',
        'device_type',
        'latitude',
        'longitude',
        'keterangan'
    ];

    protected $casts = [
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6'
    ];

    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(RtQrCode::class, 'rt_qr_code_id');
    }

    public function getDeviceInfoAttribute()
    {
        $agent = new \Jenssegers\Agent\Agent();
        $agent->setUserAgent($this->user_agent);
        
        return [
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
            'device' => $agent->device(),
            'is_mobile' => $agent->isMobile(),
            'is_tablet' => $agent->isTablet(),
            'is_desktop' => $agent->isDesktop(),
        ];
    }

    public function getLocationDisplayAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return "{$this->latitude}, {$this->longitude}";
        }
        return 'Lokasi tidak tersedia';
    }
}
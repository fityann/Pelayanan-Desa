@php
    $badgeConfig = match($status) {
        'diajukan' => [
            'label' => 'Diajukan Warga',
            'class' => 'bg-blue-50 text-blue-700 border border-blue-200/80',
            'icon' => 'schedule'
        ],
        'diverifikasi_admin' => [
            'label' => 'Diverifikasi Admin',
            'class' => 'bg-amber-50 text-amber-800 border border-amber-300/80',
            'icon' => 'verified'
        ],
        'disetujui_kades' => [
            'label' => 'Disetujui Kades',
            'class' => 'bg-emerald-50 text-emerald-800 border border-emerald-300/80',
            'icon' => 'check_circle'
        ],
        'menunggu_ttd_fisik' => [
            'label' => 'Menunggu TTD Fisik',
            'class' => 'bg-purple-50 text-purple-800 border border-purple-300/80',
            'icon' => 'draw'
        ],
        'selesai' => [
            'label' => 'Selesai',
            'class' => 'bg-[#4B5D3A]/10 text-[#364329] border border-[#4B5D3A]/30',
            'icon' => 'task_alt'
        ],
        'ditolak' => [
            'label' => 'Ditolak',
            'class' => 'bg-rose-50 text-rose-700 border border-rose-200/80',
            'icon' => 'cancel'
        ],
        default => [
            'label' => strtoupper($status),
            'class' => 'bg-slate-100 text-slate-700 border border-slate-200',
            'icon' => 'info'
        ],
    };
@endphp
<span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap shadow-xs {{ $badgeConfig['class'] }}">
    <span class="material-symbols-outlined text-sm font-bold">{{ $badgeConfig['icon'] }}</span>
    <span>{{ $badgeConfig['label'] }}</span>
</span>

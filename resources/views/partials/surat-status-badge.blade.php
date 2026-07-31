@php
    $statusClass = match($status) {
        'diajukan' => 'bg-on-tertiary-container/10 text-on-tertiary-container',
        'diverifikasi_admin' => 'bg-primary/10 text-primary',
        'ditolak' => 'bg-error/10 text-error',
        'disetujui_kades' => 'bg-success/10 text-success',
        'menunggu_ttd_fisik' => 'bg-secondary/10 text-secondary',
        'selesai' => 'bg-surface-variant/30 text-on-surface-variant',
        default => 'bg-surface-variant/30 text-on-surface-variant',
    };
@endphp
<span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider whitespace-nowrap {{ $statusClass }}">{{ $status }}</span>

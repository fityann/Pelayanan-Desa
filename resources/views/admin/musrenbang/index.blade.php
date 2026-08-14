@extends('layouts.admin')

@section('title', 'Musrenbang - SILAPU')

@section('content')
<div class="flex flex-col gap-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Musrenbang</h1>
            <p class="text-body-sm text-on-surface-variant">Musyawarah Perencanaan Pembangunan Desa</p>
        </div>
        <a href="{{ route('admin.musrenbang.create') }}" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Buat Usulan
        </a>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-lg">
        <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest">Total Usulan</p>
            <p class="font-headline-lg text-on-surface">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest">Diusulkan</p>
            <p class="font-headline-lg text-on-surface">{{ $stats['diusulkan'] ?? 0 }}</p>
        </div>
        <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest">Disetujui</p>
            <p class="font-headline-lg text-on-surface">{{ $stats['disetujui'] ?? 0 }}</p>
        </div>
        <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest">Total Alokasi</p>
            <p class="font-headline-md text-on-surface">Rp {{ number_format($stats['total_alokasi'] ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Daftar Usulan -->
    <div class="bg-surface-container-lowest rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-outline-variant/20">
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Kegiatan</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Tahun</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Estimasi</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Prioritas</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Status</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Pengusul</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($musrenbang as $m)
                        @php
                            $prioClass = match($m->prioritas) {
                                'sangat_tinggi' => 'bg-error/10 text-error',
                                'tinggi' => 'bg-primary/10 text-primary',
                                'sedang' => 'bg-warning/10 text-on-tertiary-container',
                                default => 'bg-surface-variant/30 text-on-surface-variant',
                            };
                            $statusClass = match($m->status_usulan) {
                                'disetujui' => 'bg-success/10 text-success',
                                'ditolak' => 'bg-error/10 text-error',
                                'diverifikasi', 'direview' => 'bg-secondary/10 text-secondary',
                                default => 'bg-on-tertiary-container/10 text-on-tertiary-container',
                            };
                        @endphp
                        <tr class="border-b border-outline-variant/10 hover:bg-surface-container/40 transition-colors">
                            <td class="py-3 px-4">
                                <p class="text-label-md font-semibold text-on-surface">{{ $m->judul_kegiatan }}</p>
                                <p class="text-[11px] text-on-surface-variant">{{ $m->sumber_dana }}</p>
                            </td>
                            <td class="py-3 px-4 text-label-sm text-on-surface">{{ $m->tahun }}</td>
                            <td class="py-3 px-4 text-label-sm text-on-surface">Rp {{ number_format($m->estimasi_biaya, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold capitalize {{ $prioClass }}">{{ str_replace('_', ' ', $m->prioritas) }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold capitalize {{ $statusClass }}">{{ str_replace('_', ' ', $m->status_usulan) }}</span>
                            </td>
                            <td class="py-3 px-4 text-label-sm text-on-surface">{{ $m->pengusul?->name ?? '-' }}</td>
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.musrenbang.show', $m) }}" class="text-primary text-label-sm font-semibold hover:underline flex items-center gap-xs">
                                    Detail <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($musrenbang->isEmpty())
            <div class="p-xl text-center text-on-surface-variant/60">
                <span class="material-symbols-outlined text-[40px] block mb-md">campaign</span>
                <p>Belum ada usulan musrenbang.</p>
            </div>
        @endif

        @if ($musrenbang->hasPages())
            <div class="p-lg">
                {{ $musrenbang->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
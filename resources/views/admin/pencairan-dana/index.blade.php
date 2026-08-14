@extends('layouts.admin')

@section('title', 'Pencairan Dana - SILAPU')

@section('content')
<div class="flex flex-col gap-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Pencairan Dana</h1>
            <p class="text-body-sm text-on-surface-variant">Permohonan pencairan dana desa</p>
        </div>
        <a href="{{ route('admin.pencairan-dana.create') }}" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Buat Permohonan
        </a>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
        <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest">Total Permohonan</p>
            <p class="font-headline-lg text-on-surface">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest">Menunggu Proses</p>
            <p class="font-headline-lg text-on-surface">{{ $stats['pending'] ?? 0 }}</p>
        </div>
        <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest">Total Dicairkan</p>
            <p class="font-headline-md text-on-surface">Rp {{ number_format($stats['total_dicairkan'] ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Daftar -->
    <div class="bg-surface-container-lowest rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-outline-variant/20">
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Nomor</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Kegiatan</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Jumlah</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Sumber Dana</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Status</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Pemohon</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pencairan as $p)
                        @php
                            $statusClass = match($p->status_pencairan) {
                                'dicairkan' => 'bg-success/10 text-success',
                                'ditolak' => 'bg-error/10 text-error',
                                'diverifikasi', 'disetujui' => 'bg-secondary/10 text-secondary',
                                'diproses' => 'bg-primary/10 text-primary',
                                default => 'bg-on-tertiary-container/10 text-on-tertiary-container',
                            };
                        @endphp
                        <tr class="border-b border-outline-variant/10 hover:bg-surface-container/40 transition-colors">
                            <td class="py-3 px-4 text-label-md font-mono font-semibold text-primary">{{ $p->nomor_permohonan }}</td>
                            <td class="py-3 px-4 text-label-sm font-semibold text-on-surface">{{ $p->nama_kegiatan }}</td>
                            <td class="py-3 px-4 text-label-sm text-on-surface">Rp {{ number_format($p->jumlah_pencairan, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-label-sm text-on-surface">{{ $p->sumber_dana }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold capitalize {{ $statusClass }}">{{ str_replace('_', ' ', $p->status_pencairan) }}</span>
                            </td>
                            <td class="py-3 px-4 text-label-sm text-on-surface">{{ $p->pemohon?->name ?? '-' }}</td>
                            <td class="py-3 px-4">
                                @if (auth()->user()->hasPermissionTo('U APBDes'))
                                    <div class="flex gap-xs">
                                        @if ($p->status_pencairan === 'draft')
                                            <form method="POST" action="{{ route('admin.pencairan-dana.verify', $p) }}">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 rounded-full text-[11px] font-bold bg-secondary text-on-secondary hover:bg-secondary/90 transition-colors">Verifikasi</button>
                                            </form>
                                        @endif
                                        @if ($p->status_pencairan === 'diverifikasi')
                                            <form method="POST" action="{{ route('admin.pencairan-dana.approve', $p) }}">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 rounded-full text-[11px] font-bold bg-success text-on-success hover:bg-success/90 transition-colors">Setujui</button>
                                            </form>
                                        @endif
                                        @if ($p->status_pencairan === 'disetujui')
                                            <form method="POST" action="{{ route('admin.pencairan-dana.process', $p) }}">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 rounded-full text-[11px] font-bold bg-primary text-on-primary hover:bg-primary/90 transition-colors">Proses</button>
                                            </form>
                                        @endif
                                        @if ($p->status_pencairan === 'diproses')
                                            <form method="POST" action="{{ route('admin.pencairan-dana.complete', $p) }}">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 rounded-full text-[11px] font-bold bg-success text-on-success hover:bg-success/90 transition-colors">Cairkan</button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($pencairan->isEmpty())
            <div class="p-xl text-center text-on-surface-variant/60">
                <span class="material-symbols-outlined text-[40px] block mb-md">account_balance_wallet</span>
                <p>Belum ada permohonan pencairan dana.</p>
            </div>
        @endif

        @if ($pencairan->hasPages())
            <div class="p-lg">
                {{ $pencairan->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('title', 'Status Pengajuan Surat - SIPANDA')

@section('content')
<div class="flex flex-col gap-lg max-w-3xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Status Pengajuan</h1>
            <p class="text-body-sm text-on-surface-variant">{{ $pengajuan->jenisSurat->nama }}</p>
        </div>
        <a href="{{ route('warga.surat.riwayat') }}" class="bg-surface-container-lowest px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all flex items-center gap-sm border border-outline-variant">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Kembali
        </a>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
        <div class="flex items-center justify-between mb-lg">
            <div>
                <p class="text-label-sm text-on-surface-variant">Nomor Surat</p>
                <p class="text-title-md font-mono font-bold text-on-surface">{{ $pengajuan->nomor_surat ?? '-' }}</p>
            </div>
            @php
                $statusClass = match($pengajuan->status) {
                    'diajukan' => 'bg-on-tertiary-container/10 text-on-tertiary-container',
                    'diproses' => 'bg-primary/10 text-primary',
                    'disetujui' => 'bg-success/10 text-success',
                    'ditolak' => 'bg-error/10 text-error',
                    'siap_diambil' => 'bg-secondary/10 text-secondary',
                    'selesai' => 'bg-surface-variant/30 text-on-surface-variant',
                    default => 'bg-surface-variant/30 text-on-surface-variant',
                };
            @endphp
            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusClass }}">{{ $pengajuan->status }}</span>
        </div>

        @if ($pengajuan->keterangan)
            <div class="bg-surface-container rounded-xl p-md mb-md">
                <p class="text-label-sm font-bold text-on-surface mb-xs">Keperluan</p>
                <p class="text-body-sm text-on-surface-variant">{{ $pengajuan->keterangan }}</p>
            </div>
        @endif

        @if ($pengajuan->alasan_ditolak)
            <div class="bg-error/5 border border-error/20 rounded-xl p-md flex gap-md">
                <span class="material-symbols-outlined text-error">cancel</span>
                <div>
                    <p class="text-label-sm font-bold text-error mb-xs">Pengajuan Ditolak</p>
                    <p class="text-body-sm text-on-surface-variant">{{ $pengajuan->alasan_ditolak }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Jenis Surat - SILAPU')

@section('content')
<div class="flex flex-col gap-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Jenis Surat</h1>
            <p class="text-body-sm text-on-surface-variant">Kelola jenis surat desa</p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg">
        @foreach ($jenisSurat as $jenis)
            <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg hover:shadow-md transition-all {{ $jenis->aktif ? '' : 'opacity-60' }}">
                <div class="flex items-start justify-between mb-md">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">description</span>
                    </div>
                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $jenis->aktif ? 'bg-success/10 text-success' : 'bg-surface-variant/30 text-on-surface-variant' }}">
                        {{ $jenis->aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                <h3 class="text-title-md font-bold text-on-surface mb-1">{{ $jenis->nama }}</h3>
                <p class="text-label-sm text-on-surface-variant mb-md">Kode: <span class="font-mono font-bold">{{ $jenis->kode }}</span></p>
                @if ($jenis->deskripsi)
                    <p class="text-body-sm text-on-surface-variant mb-md line-clamp-2">{{ $jenis->deskripsi }}</p>
                @endif
                @if ($jenis->syarat)
                    <div class="bg-surface-container rounded-lg p-md mb-md">
                        <p class="text-label-sm font-bold text-on-surface mb-xs">Syarat:</p>
                        <p class="text-body-sm text-on-surface-variant">{{ $jenis->syarat }}</p>
                    </div>
                @endif
                @if ($jenis->masa_berlaku)
                    <p class="text-label-sm text-on-surface-variant">Masa berlaku: {{ $jenis->masa_berlaku }} hari</p>
                @endif
                <p class="text-label-sm {{ $jenis->butuh_ttd_fisik ? 'text-on-surface-variant' : 'text-success' }} mt-xs">
                    {{ $jenis->butuh_ttd_fisik ? 'Butuh TTD fisik Kepala Desa' : 'Tanpa TTD fisik (langsung final)' }}
                </p>
            </div>
        @endforeach
    </div>
</div>
@endsection

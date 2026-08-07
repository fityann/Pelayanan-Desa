@extends('layouts.admin')

@section('title', 'Layanan Surat - SILAPU')

@section('content')
<div class="flex flex-col gap-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Layanan Surat</h1>
            <p class="text-body-sm text-on-surface-variant">Pilih jenis surat untuk diajukan</p>
        </div>
        <a href="{{ route('warga.surat.cek') }}" class="bg-surface-container-lowest px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all flex items-center gap-sm border border-outline-variant">
            <span class="material-symbols-outlined text-[18px]">manage_search</span>
            Cek Status
        </a>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    @if ($jenisSurat->isEmpty())
        <div class="bg-surface-container-lowest rounded-xl p-xl text-center text-on-surface-variant">
            <span class="material-symbols-outlined text-[40px] block mb-md">description</span>
            <p>Belum ada jenis surat yang tersedia. Silakan hubungi kantor desa.</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg">
        @foreach ($jenisSurat as $jenis)
            <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg hover:shadow-md hover:-translate-y-0.5 transition-all border border-outline-variant/10 flex flex-col">
                <div class="flex items-start justify-between mb-md">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">description</span>
                    </div>
                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider bg-primary-fixed/20 text-primary">Aktif</span>
                </div>
                <h3 class="text-title-md font-bold text-on-surface mb-1">{{ $jenis->nama }}</h3>
                @if ($jenis->deskripsi)
                    <p class="text-body-sm text-on-surface-variant mb-md">{{ $jenis->deskripsi }}</p>
                @endif
                @if ($jenis->syarat)
                    <div class="bg-surface-container rounded-lg p-md mb-md flex-1">
                        <p class="text-label-sm font-bold text-on-surface mb-xs">Syarat:</p>
                        <p class="text-body-sm text-on-surface-variant whitespace-pre-line">{{ $jenis->syarat }}</p>
                    </div>
                @endif
                @if ($jenis->masa_berlaku)
                    <span class="text-label-sm text-on-surface-variant">Berlaku {{ $jenis->masa_berlaku }} hari</span>
                @endif
                @if (!$jenis->butuh_ttd_fisik)
                    <span class="text-label-sm text-on-surface-variant">Tanpa TTD fisik</span>
                @endif
                <a href="{{ route('warga.surat.create', $jenis) }}" class="mt-lg bg-primary text-on-primary text-center px-lg py-2.5 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all flex items-center justify-center gap-sm">
                    <span class="material-symbols-outlined text-[18px]">edit_note</span>
                    Ajukan Surat
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection

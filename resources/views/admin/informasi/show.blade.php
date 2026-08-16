@extends('layouts.admin')

@section('title', 'Preview Informasi - SILAPU')

@section('content')
<div class="flex flex-col gap-lg max-w-4xl mx-auto">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-md">
            <a href="{{ route('admin.informasi.index') }}" class="w-10 h-10 rounded-full bg-surface-container hover:bg-surface-container-high flex items-center justify-center text-on-surface-variant transition-colors">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-headline-md font-bold text-on-surface">Preview Informasi</h1>
                <p class="text-body-sm text-on-surface-variant">Pratinjau tampilan artikel/berita</p>
            </div>
        </div>
        <div class="flex gap-sm">
            <a href="{{ route('admin.informasi.edit', $informasi) }}" class="bg-surface-container text-on-surface px-lg py-2 rounded-full text-label-md font-bold hover:bg-surface-container-high transition-all flex items-center gap-sm">
                <span class="material-symbols-outlined text-[18px]">edit</span>
                <span>Edit</span>
            </a>
            @if (!$informasi->published)
                <form method="POST" action="{{ route('admin.informasi.publish', $informasi) }}">
                    @csrf
                    <button type="submit" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all flex items-center gap-sm shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">publish</span>
                        <span>Publish Sekarang</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <article class="bg-surface-container-lowest rounded-3xl shadow-sm border border-outline-variant/20 overflow-hidden">
        <!-- Header Image -->
        @if ($informasi->gambar)
            <div class="w-full h-[300px] md:h-[400px] relative">
                <img src="{{ Storage::url($informasi->gambar) }}" alt="{{ $informasi->judul }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-xl">
                    <div class="flex items-center gap-sm mb-md">
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-primary text-on-primary">
                            {{ $informasi->kategori }}
                        </span>
                        @if ($informasi->published)
                            <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-success text-white">
                                Published
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-gray-500 text-white">
                                Draft
                            </span>
                        @endif
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-sm">{{ $informasi->judul }}</h1>
                </div>
            </div>
        @else
            <div class="p-xl bg-gradient-to-br from-primary/10 to-surface border-b border-outline-variant/20">
                <div class="flex items-center gap-sm mb-md">
                    <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-primary text-on-primary">
                        {{ $informasi->kategori }}
                    </span>
                    @if ($informasi->published)
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-success text-white">
                            Published
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-gray-500 text-white">
                            Draft
                        </span>
                    @endif
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-on-surface">{{ $informasi->judul }}</h1>
            </div>
        @endif

        <div class="p-xl">
            <!-- Meta Data -->
            <div class="flex flex-wrap items-center gap-md lg:gap-xl py-md border-b border-outline-variant/20 mb-lg">
                <div class="flex items-center gap-sm">
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Penulis</p>
                        <p class="text-label-md font-bold text-on-surface">{{ $informasi->user->name ?? 'Admin Desa' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-sm">
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">calendar_today</span>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Tanggal Dibuat</p>
                        <p class="text-label-md font-bold text-on-surface">{{ $informasi->created_at->format('d M Y') }}</p>
                    </div>
                </div>
                
                @if($informasi->kategori === 'agenda' && $informasi->tanggal_kegiatan)
                <div class="flex items-center gap-sm">
                    <div class="w-10 h-10 rounded-full bg-amber-500/10 flex items-center justify-center text-amber-600">
                        <span class="material-symbols-outlined">event</span>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Tanggal Kegiatan</p>
                        <p class="text-label-md font-bold text-on-surface">{{ \Carbon\Carbon::parse($informasi->tanggal_kegiatan)->format('d M Y') }}</p>
                    </div>
                </div>
                @endif

                @if($informasi->lokasi)
                <div class="flex items-center gap-sm">
                    <div class="w-10 h-10 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-600">
                        <span class="material-symbols-outlined">location_on</span>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Lokasi</p>
                        <p class="text-label-md font-bold text-on-surface">{{ $informasi->lokasi }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Konten Utama -->
            <div class="prose prose-slate max-w-none text-on-surface">
                {!! $informasi->isi !!}
            </div>
        </div>
    </article>
</div>
@endsection

@extends('layouts.warga')

@section('title', $informasi->judul . ' - Puspamukti')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Tombol Kembali -->
    <a href="{{ route('informasi.publik') }}" class="inline-flex items-center text-sm font-medium text-teal-600 hover:text-teal-700 hover:underline">
        <span class="material-symbols-outlined text-sm mr-1">arrow_back</span>
        Kembali ke Informasi Desa
    </a>

    <!-- Detail Card -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <!-- Header / Gambar -->
        @if ($informasi->gambar)
            <div class="w-full h-64 md:h-96 bg-cover bg-center" style="background-image: url('{{ Storage::url($informasi->gambar) }}')"></div>
        @else
            <div class="w-full h-48 bg-gradient-to-r from-emerald-100 to-teal-100 flex items-center justify-center">
                <span class="material-symbols-outlined text-6xl text-emerald-300">
                    {{ $informasi->kategori == 'pengumuman' ? 'campaign' : ($informasi->kategori == 'agenda' ? 'event' : 'newspaper') }}
                </span>
            </div>
        @endif

        <div class="p-6 md:p-10 space-y-6">
            <!-- Metadata -->
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 border-b border-gray-100 pb-4">
                <div class="flex items-center space-x-1">
                    <span class="material-symbols-outlined text-base">calendar_today</span>
                    <span>{{ $informasi->published_at?->translatedFormat('l, d F Y') }}</span>
                </div>
                
                @if($informasi->kategori)
                <div class="flex items-center space-x-1">
                    <span class="material-symbols-outlined text-base">category</span>
                    <span class="uppercase tracking-wider font-semibold text-xs text-teal-600 bg-teal-50 px-2 py-0.5 rounded-md">
                        {{ $informasi->kategori }}
                    </span>
                </div>
                @endif
            </div>

            <!-- Judul -->
            <h1 class="text-2xl md:text-4xl font-bold text-gray-900 leading-tight">
                {{ $informasi->judul }}
            </h1>

            <!-- Konten: strip_tags membatasi hanya tag HTML aman yang boleh ditampilkan -->
            <div class="prose prose-teal max-w-none text-gray-700">
                {!! strip_tags($informasi->isi, '<p><br><strong><b><em><i><u><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><a><img><table><thead><tbody><tr><th><td><span><div>') !!}
            </div>
        </div>
    </div>
</div>
@endsection

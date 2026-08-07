@extends('layouts.warga')

@section('title', 'Informasi Desa - Puspamukti')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl p-6 md:p-8">
        <div class="flex items-center space-x-3">
            <div class="bg-emerald-100 p-3 rounded-xl">
                <span class="material-symbols-outlined text-emerald-600 text-2xl">newspaper</span>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Informasi Desa</h1>
                <p class="text-gray-600 text-sm mt-1">Berita, pengumuman, dan agenda kegiatan Desa Puspamukti</p>
            </div>
        </div>
    </div>

    @if ($pengumuman->count() > 0)
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center space-x-2">
                <span class="material-symbols-outlined text-teal-600">campaign</span>
                <span>Pengumuman</span>
            </h2>
            <div class="space-y-4">
                @foreach ($pengumuman as $p)
                    <div class="bg-white rounded-xl shadow-sm p-5 flex items-start space-x-4">
                        <div class="w-12 h-12 rounded-xl bg-teal-100 flex items-center justify-center text-teal-600 shrink-0">
                            <span class="material-symbols-outlined">campaign</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900">{{ $p->judul }}</h3>
                            <p class="text-sm text-gray-600 mt-1 line-clamp-3">{{ strip_tags($p->isi) }}</p>
                            <span class="text-xs text-gray-500 mt-2 block">{{ $p->published_at?->format('d M Y') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($agenda->count() > 0)
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center space-x-2">
                <span class="material-symbols-outlined text-emerald-600">event</span>
                <span>Agenda Kegiatan</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($agenda as $a)
                    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-emerald-600">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="flex flex-col items-center bg-emerald-50 rounded-lg px-3 py-1">
                                <span class="font-bold text-emerald-700">{{ $a->tanggal_kegiatan?->format('d') }}</span>
                                <span class="text-xs text-gray-500 uppercase">{{ $a->tanggal_kegiatan?->format('M') }}</span>
                            </div>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1">{{ $a->judul }}</h3>
                        @if ($a->lokasi)
                            <p class="text-sm text-gray-500 flex items-center space-x-1">
                                <span class="material-symbols-outlined text-sm">location_on</span>
                                <span>{{ $a->lokasi }}</span>
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div>
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center space-x-2">
            <span class="material-symbols-outlined text-teal-600">article</span>
            <span>Berita Terkini</span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($berita as $b)
                <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-all">
                    @if ($b->gambar)
                        <div class="h-44 bg-cover bg-center" style="background-image: url('{{ Storage::url($b->gambar) }}')"></div>
                    @else
                        <div class="h-44 bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center">
                            <span class="material-symbols-outlined text-5xl text-emerald-300">newspaper</span>
                        </div>
                    @endif
                    <div class="p-5">
                        <h3 class="font-bold text-gray-900 mb-1 line-clamp-2">{{ $b->judul }}</h3>
                        <p class="text-sm text-gray-600 line-clamp-3 mb-3">{{ strip_tags($b->isi) }}</p>
                        <span class="text-xs text-gray-500">{{ $b->published_at?->format('d M Y') }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10 text-gray-500">Belum ada berita</div>
            @endforelse
        </div>
        @if ($berita->hasPages())
            <div class="mt-6">{{ $berita->appends(request()->only(['rt', 'rw']))->links() }}</div>
        @endif
    </div>
</div>
@endsection
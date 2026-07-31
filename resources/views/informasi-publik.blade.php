@extends('layouts.guest')

@section('title', 'Informasi Desa - Puspamukti')

@section('content')
<div class="min-h-screen bg-surface">
    <div class="bg-gradient-to-br from-primary to-secondary text-on-primary">
        <div class="max-w-6xl mx-auto px-lg py-xl">
            <a href="/" class="inline-flex items-center gap-sm text-on-primary/80 hover:text-on-primary mb-lg">
                <span class="material-symbols-outlined">arrow_back</span>
                Kembali ke Beranda
            </a>
            <h1 class="text-headline-lg font-bold mb-sm">Informasi Desa</h1>
            <p class="text-body-md text-on-primary/80">Berita, pengumuman, dan agenda kegiatan Desa Puspamukti</p>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-lg py-xl">
        @if ($pengumuman->count() > 0)
            <div class="mb-xl">
                <h2 class="text-headline-md font-bold text-on-surface mb-lg flex items-center gap-md">
                    <span class="material-symbols-outlined text-secondary">campaign</span>
                    Pengumuman
                </h2>
                <div class="space-y-md">
                    @foreach ($pengumuman as $p)
                        <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg flex items-start gap-lg">
                            <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary shrink-0">
                                <span class="material-symbols-outlined">campaign</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-title-md font-bold text-on-surface">{{ $p->judul }}</h3>
                                <p class="text-body-sm text-on-surface-variant mt-sm line-clamp-3">{{ strip_tags($p->isi) }}</p>
                                <span class="text-label-sm text-on-surface-variant mt-md block">{{ $p->published_at?->format('d M Y') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($agenda->count() > 0)
            <div class="mb-xl">
                <h2 class="text-headline-md font-bold text-on-surface mb-lg flex items-center gap-md">
                    <span class="material-symbols-outlined text-primary">event</span>
                    Agenda Kegiatan
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg">
                    @foreach ($agenda as $a)
                        <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg border-l-4 border-primary">
                            <div class="flex items-center gap-md mb-md">
                                <div class="flex flex-col items-center">
                                    <span class="font-headline-md text-primary">{{ $a->tanggal_kegiatan?->format('d') }}</span>
                                    <span class="text-label-sm text-on-surface-variant uppercase">{{ $a->tanggal_kegiatan?->format('M') }}</span>
                                </div>
                            </div>
                            <h3 class="text-title-md font-bold text-on-surface mb-xs">{{ $a->judul }}</h3>
                            @if ($a->lokasi)
                                <p class="text-body-sm text-on-surface-variant flex items-center gap-xs">
                                    <span class="material-symbols-outlined text-[14px]">location_on</span>
                                    {{ $a->lokasi }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div>
            <h2 class="text-headline-md font-bold text-on-surface mb-lg flex items-center gap-md">
                <span class="material-symbols-outlined text-on-tertiary-container">newspaper</span>
                Berita Terkini
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg">
                @forelse ($berita as $b)
                    <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-all">
                        @if ($b->gambar)
                            <div class="h-44 bg-cover bg-center" style="background-image: url('{{ Storage::url($b->gambar) }}')"></div>
                        @else
                            <div class="h-44 bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[48px] text-primary/30">newspaper</span>
                            </div>
                        @endif
                        <div class="p-lg">
                            <h3 class="text-title-md font-bold text-on-surface mb-sm">{{ $b->judul }}</h3>
                            <p class="text-body-sm text-on-surface-variant line-clamp-3 mb-md">{{ strip_tags($b->isi) }}</p>
                            <span class="text-label-sm text-on-surface-variant">{{ $b->published_at?->format('d M Y') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-xl text-on-surface-variant">Belum ada berita</div>
                @endforelse
            </div>
            @if ($berita->hasPages())
                <div class="mt-lg">{{ $berita->links() }}</div>
            @endif
        </div>
    </div>

    <footer class="bg-surface-container-high py-lg text-center text-label-sm text-on-surface-variant">
        <p>Puspamukti Smart Village — Informasi resmi Desa Puspamukti</p>
        <p class="mt-xs">© {{ date('Y') }} Pemerintah Desa Puspamukti</p>
    </footer>
</div>
@endsection

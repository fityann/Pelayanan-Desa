@extends('layouts.admin')

@section('title', 'Informasi & Pengumuman - SILAPU')

@section('content')
<div class="flex flex-col gap-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Informasi & Pengumuman</h1>
            <p class="text-body-sm text-on-surface-variant">Kelola berita, pengumuman, dan agenda desa</p>
        </div>
        <a href="{{ route('admin.informasi.create') }}" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Tambah
        </a>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg">
        @forelse ($informasi as $item)
            <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-all">
                @if ($item->gambar)
                    <div class="h-40 bg-cover bg-center" style="background-image: url('{{ Storage::url($item->gambar) }}')"></div>
                @else
                    <div class="h-40 bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[48px] text-primary/30">
                            {{ $item->kategori === 'agenda' ? 'event' : ($item->kategori === 'pengumuman' ? 'campaign' : 'newspaper') }}
                        </span>
                    </div>
                @endif
                <div class="p-lg">
                    <div class="flex items-center gap-sm mb-sm">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-primary/10 text-primary">{{ $item->kategori }}</span>
                        @if ($item->published)
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-success/10 text-success">Published</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-on-tertiary-container/10 text-on-tertiary-container">Draft</span>
                        @endif
                    </div>
                    <h3 class="text-title-md font-bold text-on-surface mb-sm line-clamp-2">{{ $item->judul }}</h3>
                    <p class="text-body-sm text-on-surface-variant line-clamp-2 mb-md">{{ strip_tags($item->isi) }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-label-sm text-on-surface-variant">{{ $item->created_at->format('d/m/Y') }}</span>
                        <div class="flex items-center gap-sm">
                            @if (!$item->published)
                                <form method="POST" action="{{ route('admin.informasi.publish', $item) }}" class="inline">
                                    @csrf
                                    <button class="text-success text-label-sm font-bold hover:underline">Publish</button>
                                </form>
                            @endif
                            <a href="{{ route('admin.informasi.edit', $item) }}" class="text-primary text-label-sm font-bold hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.informasi.destroy', $item) }}" class="inline" onsubmit="return confirm('Hapus informasi ini?')">
                                @csrf @method('DELETE')
                                <button class="text-error text-label-sm font-bold hover:underline">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-xl text-on-surface-variant">Belum ada informasi</div>
        @endforelse
    </div>

    @if ($informasi->hasPages())
        <div class="mt-lg">{{ $informasi->links() }}</div>
    @endif
</div>
@endsection

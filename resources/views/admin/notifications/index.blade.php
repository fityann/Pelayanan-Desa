@extends('layouts.admin')

@section('title', 'Notifikasi - SILAPU')

@section('content')
<div class="flex flex-col gap-lg max-w-4xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Notifikasi</h1>
            <p class="text-body-sm text-on-surface-variant">Pemberitahuan aktivitas warga dan sistem</p>
        </div>
        <form method="POST" action="{{ route('admin.notifications.read-all') }}">
            @csrf
            <button type="submit" class="bg-surface-container-lowest px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all border border-outline-variant">
                Tandai Semua Dibaca
            </button>
        </form>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-surface-container-lowest rounded-2xl shadow-sm overflow-hidden">
        @forelse ($notifications as $n)
            <div class="flex items-start gap-md p-lg border-b border-outline-variant/10 {{ !$n->is_read ? 'bg-primary-fixed/10' : '' }}" :id="'notif-' + {{ $n->id }}">
                <div class="w-10 h-10 rounded-xl {{ $n->warna ?? 'bg-primary/10 text-primary' }} flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined">{{ $n->icon }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-sm">
                        <h4 class="text-label-md font-bold text-on-surface">{{ $n->judul }}</h4>
                        <span class="text-[11px] text-on-surface-variant whitespace-nowrap">{{ $n->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-body-sm text-on-surface-variant mt-xs">{{ $n->pesan }}</p>
                    @if ($n->link)
                        <a href="{{ $n->link }}" class="inline-flex items-center gap-xs text-primary text-label-sm font-semibold mt-sm hover:underline">
                            Lihat <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                        </a>
                    @endif
                </div>
                <div class="flex flex-col items-end gap-sm flex-shrink-0">
                    @if (!$n->is_read)
                        <form method="POST" action="{{ route('admin.notifications.read', $n->id) }}">
                            @csrf
                            <button type="submit" class="text-xs text-primary font-semibold hover:underline">Tandai dibaca</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.notifications.destroy', $n) }}" onsubmit="return confirm('Hapus notifikasi ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-error hover:underline">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-xl text-center text-on-surface-variant/60">
                <span class="material-symbols-outlined text-[40px] block mb-md">notifications_none</span>
                <p>Belum ada notifikasi</p>
            </div>
        @endforelse

        @if ($notifications->hasPages())
            <div class="p-lg">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
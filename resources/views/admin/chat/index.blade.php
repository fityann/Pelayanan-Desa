@extends('layouts.admin')

@section('title', 'Chat Warga - SILAPU')

@section('content')
<div class="flex flex-col gap-lg">
    <div>
        <h1 class="text-headline-md font-bold text-on-surface">Chat Warga</h1>
        <p class="text-body-sm text-on-surface-variant">Percakapan warga dengan admin desa</p>
    </div>

    <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
        @forelse ($chats as $chat)
            @php
                $unread = $chat->pesans->where('sender_role', 'warga')->where('dibaca_admin', false)->count();
                $terakhir = $chat->pesans->first();
            @endphp
            <a href="{{ route('admin.chat.show', $chat) }}"
               class="flex items-center gap-md px-lg py-4 border-b border-surface-variant/20 hover:bg-surface-container/50 transition-colors {{ $unread > 0 ? 'bg-primary-fixed/10' : '' }}">
                <div class="w-11 h-11 rounded-full bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                    <span class="material-symbols-outlined">person</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-sm">
                        <span class="text-label-md font-bold text-on-surface truncate">{{ $chat->user->name }}</span>
                        <span class="text-[10px] bg-surface-container px-2 py-0.5 rounded-full text-on-surface-variant">RT {{ $chat->rt }} / RW {{ $chat->rw }}</span>
                    </div>
                    <p class="text-body-sm text-on-surface-variant truncate mt-0.5">
                        @if ($terakhir)
                            @if ($terakhir->sender_role === 'admin') <span class="text-primary">Anda:</span> @endif
                            {{ $terakhir->isi }}
                        @else
                            Belum ada pesan
                        @endif
                    </p>
                </div>
                <div class="flex flex-col items-end gap-1 flex-shrink-0">
                    @if ($terakhir)
                        <span class="text-[10px] text-on-surface-variant/70">{{ $terakhir->created_at->diffForHumans() }}</span>
                    @endif
                    @if ($unread > 0)
                        <span class="min-w-[20px] h-5 px-1.5 rounded-full bg-error text-on-error text-[11px] font-bold flex items-center justify-center">
                            {{ $unread }}
                        </span>
                    @endif
                </div>
            </a>
        @empty
            <div class="text-center py-xl text-on-surface-variant">
                <span class="material-symbols-outlined text-[40px] block mb-sm opacity-40">forum</span>
                <p>Belum ada percakapan dengan warga</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

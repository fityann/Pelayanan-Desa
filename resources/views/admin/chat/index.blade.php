@extends('layouts.admin')

@section('title', 'Chat Warga - SILAPU')

@section('content')
<div class="w-full flex flex-col gap-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span class="material-symbols-outlined text-2xl text-[#6A3297]">forum</span>
                <span>Chat & Konsultasi Warga</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">Daftar pesan dan konsultasi masuk dari warga Desa Puspamukti</p>
        </div>
        
        <div class="flex items-center gap-3">
            <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3.5 py-1.5 rounded-full border border-slate-200/80">
                Total Chat: {{ $chats->count() }}
            </span>
        </div>
    </div>

    <!-- Chat List -->
    <div class="w-full bg-white rounded-2xl border border-slate-200/90 shadow-md overflow-hidden divide-y divide-slate-100">
        @forelse ($chats as $chat)
            @php
                $unread = $chat->pesans->where('sender_role', 'warga')->where('dibaca_admin', false)->count();
                $terakhir = $chat->pesans->first();
            @endphp
            <a href="{{ route('admin.chat.show', $chat) }}"
               class="flex items-center gap-4 px-6 py-4.5 hover:bg-purple-50/50 transition-all group relative {{ $unread > 0 ? 'bg-purple-50/70' : '' }}">
                
                <!-- Unread Indicator Bar -->
                @if ($unread > 0)
                    <span class="absolute left-0 top-0 bottom-0 w-1 bg-[#6A3297]"></span>
                @endif

                <!-- User Avatar -->
                <div class="relative flex-shrink-0">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#6A3297] to-[#803CB5] p-0.5 shadow-sm group-hover:scale-105 transition-transform">
                        <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center text-[#6A3297] font-black text-base">
                            {{ strtoupper(substr($chat->user->name, 0, 1)) }}
                        </div>
                    </div>
                </div>

                <!-- Chat Info -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-[#6A3297] transition-colors truncate">
                            {{ $chat->user->name }}
                        </span>
                        <span class="text-[10px] font-black bg-slate-100 text-slate-600 px-2.5 py-0.5 rounded-full border border-slate-200/80">
                            RT {{ sprintf('%02d', $chat->rt) }}
                        </span>
                        @if($chat->user->nik)
                            <span class="text-[11px] font-mono text-slate-400 hidden md:inline">• NIK: {{ $chat->user->nik }}</span>
                        @endif
                    </div>
                    
                    <p class="text-xs sm:text-sm text-slate-500 truncate mt-1 font-medium">
                        @if ($terakhir)
                            @if ($terakhir->sender_role === 'admin') 
                                <span class="text-[#6A3297] font-bold">Anda: </span> 
                            @endif
                            {{ $terakhir->isi }}
                        @else
                            <span class="text-slate-400 italic">Belum ada pesan</span>
                        @endif
                    </p>
                </div>

                <!-- Timestamp & Unread Badge -->
                <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                    @if ($terakhir)
                        <span class="text-[11px] font-semibold text-slate-400 group-hover:text-[#6A3297] transition-colors">
                            {{ $terakhir->created_at->diffForHumans() }}
                        </span>
                    @endif

                    @if ($unread > 0)
                        <span class="min-w-[22px] h-5 px-2 rounded-full bg-[#6A3297] text-amber-300 text-[11px] font-black flex items-center justify-center shadow-xs">
                            {{ $unread }}
                        </span>
                    @endif
                </div>

                <span class="material-symbols-outlined text-slate-300 group-hover:text-[#6A3297] group-hover:translate-x-1 transition-all text-xl">
                    chevron_right
                </span>
            </a>
        @empty
            <div class="flex flex-col items-center justify-center py-16 text-slate-400">
                <div class="w-16 h-16 rounded-2xl bg-purple-50 text-[#6A3297] flex items-center justify-center mb-3">
                    <span class="material-symbols-outlined text-3xl">chat_bubble_outline</span>
                </div>
                <h3 class="text-sm font-bold text-slate-700">Belum Ada Percakapan</h3>
                <p class="text-xs text-slate-400 mt-1">Belum ada pesan dari warga desa yang masuk saat ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection


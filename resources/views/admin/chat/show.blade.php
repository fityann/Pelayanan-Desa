@extends('layouts.admin')

@section('title', 'Chat - ' . $chat->user->name . ' - SILAPU')

@section('full_width_content')
@php
    $dataRoute = route('admin.chat.data', $chat);
    $kirimRoute = route('admin.chat.store', $chat);
@endphp

<div x-data="adminChat()" x-init="init()" class="w-full h-[calc(100vh-4rem)] flex flex-col bg-white overflow-hidden m-0 p-0">
    <!-- Chat Card Container (100% Seamless Edge-to-Edge) -->
    <div class="w-full flex-1 flex flex-col overflow-hidden">
        
        <!-- Header Bar -->
        <div class="px-5 py-3.5 bg-gradient-to-r from-slate-950 via-slate-900 to-[#3b1959] text-white flex items-center justify-between border-b border-white/10 shadow-md">
            <div class="flex items-center gap-3.5 min-w-0">
                <a href="{{ route('admin.chat.index') }}" 
                   class="p-2 rounded-xl bg-white/10 hover:bg-white/20 transition-all text-white flex items-center justify-center group"
                   title="Kembali ke Daftar Chat">
                    <span class="material-symbols-outlined text-xl group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
                </a>

                <div class="relative flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-purple-600 to-amber-400 p-0.5 shadow-md">
                        <div class="w-full h-full bg-slate-900 rounded-full flex items-center justify-center text-amber-300 font-black text-sm">
                            {{ strtoupper(substr($chat->user->name, 0, 1)) }}
                        </div>
                    </div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-400 border-2 border-slate-900 rounded-full"></span>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <h1 class="text-base sm:text-lg font-black text-white truncate tracking-tight">{{ $chat->user->name }}</h1>
                        <span class="bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                            RT {{ sprintf('%02d', $chat->rt) }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-300 truncate font-medium flex items-center gap-1.5 mt-0.5">
                        <span class="material-symbols-outlined text-xs text-purple-300">mail</span>
                        <span>{{ $chat->user->email }}</span>
                        @if($chat->user->nik)
                            <span class="text-slate-500">•</span>
                            <span class="text-amber-200/90 font-mono text-[11px]">NIK: {{ $chat->user->nik }}</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Header Quick Actions -->
            <div class="hidden md:flex items-center gap-2">
                <a href="{{ route('admin.penduduk.index', ['search' => $chat->user->nik ?? $chat->user->name]) }}" 
                   target="_blank"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur-md transition-all border border-white/10">
                    <span class="material-symbols-outlined text-sm text-amber-300">person_search</span>
                    <span>Cek Data Penduduk</span>
                </a>
            </div>
        </div>

        <!-- Chat Messages Scrollable Area -->
        <div x-ref="pesanContainer" class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4 bg-slate-50/90">
            <!-- Loading State -->
            <template x-if="loading">
                <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                    <span class="material-symbols-outlined text-3xl animate-spin text-[#6A3297]">progress_activity</span>
                    <span class="text-xs font-semibold mt-2">Memuat percakapan warga...</span>
                </div>
            </template>

            <!-- Message Bubbles -->
            <template x-for="p in pesans" :key="p.id">
                <div class="flex items-end gap-2" :class="p.sender_role === 'admin' ? 'justify-end' : 'justify-start'">
                    
                    <!-- Avatar Warga (Left) -->
                    <template x-if="p.sender_role !== 'admin'">
                        <div class="w-7 h-7 rounded-full bg-purple-100 border border-purple-200 flex items-center justify-center text-purple-700 font-bold text-xs flex-shrink-0 shadow-xs">
                            <span class="material-symbols-outlined text-sm">person</span>
                        </div>
                    </template>

                    <!-- Bubble Content -->
                    <div class="max-w-[85%] sm:max-w-[70%] group">
                        <div class="px-4 py-3 rounded-2xl text-sm shadow-sm transition-all"
                             :class="p.sender_role === 'admin'
                                 ? 'bg-gradient-to-tr from-[#6A3297] to-[#803CB5] text-white rounded-br-xs shadow-purple-900/10 border border-purple-400/20'
                                 : 'bg-white text-slate-800 border border-slate-200/90 rounded-bl-xs shadow-slate-200/50'">
                            
                            <!-- Sender Name Header -->
                            <div class="text-[11px] font-bold mb-1 flex items-center justify-between gap-2"
                                 :class="p.sender_role === 'admin' ? 'text-purple-200' : 'text-slate-500'">
                                <span x-text="p.sender_role === 'admin' ? 'Perangkat Desa (Anda)' : '{{ $chat->user->name }}'"></span>
                            </div>

                            <!-- Message Text -->
                            <p class="whitespace-pre-wrap break-words leading-relaxed text-sm" x-text="p.isi"></p>
                            
                            <!-- Timestamp -->
                            <div class="flex items-center justify-end gap-1 text-[10px] mt-1.5 font-medium"
                                 :class="p.sender_role === 'admin' ? 'text-purple-200/80' : 'text-slate-400'">
                                <span x-text="p.waktu"></span>
                                <template x-if="p.sender_role === 'admin'">
                                    <span class="material-symbols-outlined text-[13px] text-amber-300">done_all</span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Avatar Admin (Right) -->
                    <template x-if="p.sender_role === 'admin'">
                        <div class="w-7 h-7 rounded-full bg-[#6A3297] text-amber-300 font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-xs border border-amber-300/40">
                            <span class="material-symbols-outlined text-sm">admin_panel_settings</span>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Empty State -->
            <template x-if="!loading && pesans.length === 0">
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-purple-50 text-[#6A3297] flex items-center justify-center mb-3 shadow-inner border border-purple-100">
                        <span class="material-symbols-outlined text-3xl">chat_bubble_outline</span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Belum Ada Pesan</h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm">Tulis balasan di bawah ini untuk memulai atau merespon pertanyaan warga.</p>
                </div>
            </template>
        </div>

        <!-- Input Form (Sticky Bottom) -->
        <form @submit.prevent="kirim()" class="p-3.5 sm:p-4 bg-white border-t border-slate-200/90 flex items-end gap-3 shadow-lg">
            <div class="flex-1 relative flex items-center">
                <textarea x-model="isi" 
                          rows="1" 
                          @keydown.enter.exact.prevent="kirim()"
                          placeholder="Ketik balasan Anda di sini... (Tekan Enter untuk mengirim)" 
                          required
                          class="w-full bg-slate-50 focus:bg-white border border-slate-200 rounded-2xl px-4 py-3 text-sm text-slate-900 outline-none focus:ring-2 focus:ring-[#6A3297]/20 focus:border-[#6A3297] transition-all resize-none max-h-36 placeholder-slate-400 font-medium"></textarea>
            </div>
            
            <button type="submit" 
                    :disabled="mengirim || !isi.trim()"
                    class="bg-gradient-to-r from-[#6A3297] to-[#803CB5] hover:from-[#582980] hover:to-[#6A3297] text-white px-5 sm:px-6 py-3 rounded-2xl font-black text-sm shadow-lg shadow-[#6A3297]/25 transition-all hover:scale-[1.02] active:scale-[0.98] disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-2 flex-shrink-0">
                <span class="material-symbols-outlined text-lg text-amber-300">send</span>
                <span class="hidden sm:inline">Kirim Balasan</span>
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function adminChat() {
        return {
            pesans: [],
            isi: '',
            loading: true,
            mengirim: false,
            timer: null,
            init() {
                this.muat();
                this.timer = setInterval(() => this.muat(), 4000);
            },
            muat() {
                fetch('{{ $dataRoute }}', { headers: { 'Accept': 'application/json' } })
                    .then(r => r.json())
                    .then(data => {
                        const adaPesanBaru = (data.pesans || []).length > this.pesans.length;
                        this.pesans = data.pesans || [];
                        this.loading = false;
                        if (adaPesanBaru) this.$nextTick(() => this.scrollBawah());
                    })
                    .catch(() => { this.loading = false; });
            },
            kirim() {
                const isi = this.isi.trim();
                if (!isi || this.mengirim) return;
                this.mengirim = true;
                fetch('{{ $kirimRoute }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ isi })
                })
                .then(r => r.json())
                .then(() => {
                    this.isi = '';
                    this.muat();
                    if (typeof showToast === 'function') showToast('Balasan terkirim ke warga', 'success');
                })
                .catch(() => {
                    if (typeof showToast === 'function') showToast('Gagal mengirim balasan, coba lagi', 'error');
                })
                .finally(() => { this.mengirim = false; });
            },
            scrollBawah() {
                if (this.$refs.pesanContainer) {
                    this.$refs.pesanContainer.scrollTop = this.$refs.pesanContainer.scrollHeight;
                }
            }
        };
    }
</script>
@endpush
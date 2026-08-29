@extends('layouts.warga')

@section('title', 'Chat dengan Admin - SILAPU')

@section('full_width_content')
@php
    $chatRoute = route('warga.rt.chat.data', ['rt' => $rt]);
    $kirimRoute = route('warga.rt.chat.store', ['rt' => $rt]);
@endphp

<div x-data="wargaChat()" x-init="init()" class="w-full h-[calc(100vh-130px)] md:h-[calc(100vh-65px)] flex flex-col bg-white overflow-hidden m-0 p-0 border-0 rounded-none">
    <!-- Chat Card Wrapper (100% Unified Zero-Margin) -->
    <div class="w-full flex-1 flex flex-col overflow-hidden">
        
        <!-- Header Bar (Unified with Top Header) -->
        <div class="px-4 sm:px-6 py-3 bg-gradient-to-r from-[#5B21B6] via-[#6A3297] to-[#4C1D95] text-white flex items-center justify-between shadow-xs flex-shrink-0">
            <div class="flex items-center gap-3 min-w-0">
                <div class="relative flex-shrink-0">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-amber-300 shadow-md">
                        <span class="material-symbols-outlined text-xl sm:text-2xl">forum</span>
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-400 border-2 border-[#6A3297] rounded-full"></span>
                </div>

                <div class="flex-1 min-w-0">
                    <h1 class="text-sm sm:text-base font-black text-white truncate tracking-tight">Chat Layanan Admin Desa</h1>
                    <p class="text-[11px] sm:text-xs text-purple-100/90 truncate font-medium flex items-center gap-1 mt-0.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Desa Puspamukti · Respon Cepat Jam Kerja</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <span class="bg-white/20 backdrop-blur-md border border-white/30 text-amber-300 text-[11px] sm:text-xs font-black px-2.5 sm:px-3.5 py-0.5 sm:py-1 rounded-full shadow-xs">
                    RT {{ sprintf('%02d', $rt) }}
                </span>
            </div>
        </div>

        <!-- Chat Messages Scrollable Box -->
        <div x-ref="pesanContainer" class="flex-1 overflow-y-auto p-3.5 sm:p-5 space-y-3.5 bg-slate-50/90">
            <!-- Loading State -->
            <template x-if="loading">
                <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                    <span class="material-symbols-outlined text-3xl animate-spin text-[#6A3297]">progress_activity</span>
                    <span class="text-xs font-semibold mt-2">Memuat percakapan...</span>
                </div>
            </template>

            <!-- Message List -->
            <template x-for="p in pesans" :key="p.id">
                <div class="flex items-end gap-2" :class="p.sender_role === 'warga' ? 'justify-end' : 'justify-start'">
                    
                    <!-- Avatar Admin (Left) -->
                    <template x-if="p.sender_role !== 'warga'">
                        <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#6A3297] text-amber-300 font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-xs border border-amber-300/40">
                            <span class="material-symbols-outlined text-xs sm:text-sm">admin_panel_settings</span>
                        </div>
                    </template>

                    <!-- Message Bubble -->
                    <div class="max-w-[88%] sm:max-w-[70%] group">
                        <div class="px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-2xl text-xs sm:text-sm shadow-sm transition-all"
                             :class="p.sender_role === 'warga'
                                 ? 'bg-gradient-to-tr from-[#6A3297] to-[#803CB5] text-white rounded-br-xs shadow-purple-900/10 border border-purple-400/20'
                                 : 'bg-white text-slate-800 border border-slate-200/90 rounded-bl-xs shadow-slate-200/50'">
                            
                            <!-- Sender Role Label -->
                            <div class="text-[10px] sm:text-[11px] font-bold mb-1 flex items-center justify-between gap-2"
                                 :class="p.sender_role === 'warga' ? 'text-purple-200' : 'text-[#6A3297]'">
                                <span x-text="p.sender_role === 'warga' ? 'Anda (Warga)' : 'Admin / Perangkat Desa'"></span>
                            </div>

                            <!-- Message Body -->
                            <p class="whitespace-pre-wrap break-words leading-relaxed text-xs sm:text-sm" x-text="p.isi"></p>
                            
                            <!-- Timestamp -->
                            <div class="flex items-center justify-end gap-1 text-[10px] mt-1 font-medium"
                                 :class="p.sender_role === 'warga' ? 'text-purple-200/80' : 'text-slate-400'">
                                <span x-text="p.waktu"></span>
                                <template x-if="p.sender_role === 'warga'">
                                    <span class="material-symbols-outlined text-[13px] text-amber-300">done_all</span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Avatar Warga (Right) -->
                    <template x-if="p.sender_role === 'warga'">
                        <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-purple-100 border border-purple-200 flex items-center justify-center text-purple-700 font-bold text-xs flex-shrink-0 shadow-xs">
                            <span class="material-symbols-outlined text-xs sm:text-sm">person</span>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Empty State -->
            <template x-if="!loading && pesans.length === 0">
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-purple-50 text-[#6A3297] flex items-center justify-center mb-2 shadow-inner border border-purple-100">
                        <span class="material-symbols-outlined text-2xl">chat_bubble_outline</span>
                    </div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-800">Belum Ada Percakapan</h3>
                    <p class="text-[11px] text-slate-400 mt-1 max-w-xs">Tulis pertanyaan atau konsultasi Anda di bawah ini untuk memulai percakapan dengan petugas desa.</p>
                </div>
            </template>
        </div>

        <!-- Input Form (Sticky Bottom Directly Above Bottom Nav) -->
        <form @submit.prevent="kirim()" class="p-2.5 sm:p-3 bg-white border-t border-slate-200/90 flex items-center gap-2 shadow-lg flex-shrink-0 z-10">
            <div class="flex-1 relative flex items-center">
                <textarea x-model="isi" 
                          rows="1" 
                          @keydown.enter.exact.prevent="kirim()"
                          placeholder="Tulis pesan..." 
                          required
                          class="w-full bg-slate-50 focus:bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 outline-none focus:ring-2 focus:ring-[#6A3297]/20 focus:border-[#6A3297] transition-all resize-none max-h-24 placeholder-slate-400 font-medium"></textarea>
            </div>
            
            <button type="submit" 
                    :disabled="mengirim || !isi.trim()"
                    class="bg-gradient-to-r from-[#6A3297] to-[#803CB5] hover:from-[#582980] hover:to-[#6A3297] text-white px-4 sm:px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm shadow-md shadow-[#6A3297]/25 transition-all hover:scale-[1.02] active:scale-[0.98] disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-1.5 flex-shrink-0">
                <span class="material-symbols-outlined text-base text-amber-300">send</span>
                <span class="hidden sm:inline">Kirim</span>
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function wargaChat() {
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
                fetch('{{ $chatRoute }}', { headers: { 'Accept': 'application/json' } })
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
                .then(async r => {
                    const data = await r.json().catch(() => ({}));
                    if (!r.ok) {
                        throw new Error(data.message || 'Gagal terkirim, coba lagi');
                    }
                    return data;
                })
                .then(() => {
                    this.isi = '';
                    this.muat();
                    if (typeof showToast === 'function') showToast('Pesan berhasil terkirim ke Admin', 'success');
                })
                .catch((error) => {
                    if (typeof showToast === 'function') showToast(error.message || 'Gagal terkirim, coba lagi', 'error');
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


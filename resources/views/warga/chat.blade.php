@extends('layouts.warga')

@section('title', 'Chat dengan Admin - SILAPU')

@section('content')
@php
    $chatRoute = route('warga.rt.chat.data', ['rt' => $rt]);
    $kirimRoute = route('warga.rt.chat.store', ['rt' => $rt]);
@endphp

<div x-data="wargaChat()" x-init="init()" class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 bg-[#6A3297] text-white flex items-center gap-3">
            <div class="w-11 h-11 rounded-full bg-white/20 flex items-center justify-center">
                <span class="material-symbols-outlined">forum</span>
            </div>
            <div class="flex-1">
                <h1 class="text-base font-bold">Chat dengan Admin Desa</h1>
                <p class="text-xs text-white/80">Puspamukti · Balasan biasanya dalam jam kerja</p>
            </div>
            <span class="text-xs bg-white/20 px-3 py-1 rounded-full">RT {{ $rt }}</span>
        </div>

        <div class="flex flex-col" style="height: 60vh;">
            <div x-ref="pesanContainer" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50">
                <template x-if="loading">
                    <div class="text-center text-sm text-gray-400 py-8">Memuat percakapan...</div>
                </template>
                <template x-for="p in pesans" :key="p.id">
                    <div class="flex" :class="p.sender_role === 'warga' ? 'justify-end' : 'justify-start'">
                        <div class="max-w-[80%] px-4 py-2.5 rounded-2xl text-sm shadow-sm"
                             :class="p.sender_role === 'warga'
                                 ? 'bg-[#6A3297] text-white rounded-br-md'
                                 : 'bg-white border border-gray-200 rounded-bl-md'">
                            <p class="whitespace-pre-wrap break-words" x-text="p.isi"></p>
                            <span class="block text-[10px] mt-1 opacity-70" x-text="p.waktu"></span>
                        </div>
                    </div>
                </template>
                <template x-if="!loading && pesans.length === 0">
                    <div class="text-center py-12">
                        <span class="material-symbols-outlined text-4xl text-gray-300">chat</span>
                        <p class="text-sm text-gray-400 mt-2">Belum ada percakapan. Tulis pertanyaanmu untuk admin desa.</p>
                    </div>
                </template>
            </div>

            <form @submit.prevent="kirim()" class="border-t border-gray-200 p-3 bg-white flex items-end gap-2">
                <textarea x-model="isi" rows="1" placeholder="Tulis pesan..." required
                          class="flex-1 resize-none px-4 py-3 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-[#6A3297] focus:border-transparent transition-all text-sm max-h-32"></textarea>
                <button type="submit" :disabled="mengirim || !isi.trim()"
                        class="bg-[#6A3297] text-white px-5 py-3 rounded-xl font-semibold shadow-lg shadow-[#6A3297]/20 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center gap-1">
                    <span class="material-symbols-outlined text-base">send</span>
                    <span class="hidden sm:inline text-sm">Kirim</span>
                </button>
            </form>
        </div>
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
                this.timer = setInterval(() => this.muat(), 5000);
            },
            muat() {
                fetch('{{ $chatRoute }}', { headers: { 'Accept': 'application/json' } })
                    .then(r => r.json())
                    .then(data => {
                        const adaPesanBaru = data.pesans.length > this.pesans.length;
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
                    if (typeof showToast === 'function') showToast('Pesan terkirim', 'success');
                })
                .catch(() => {
                    if (typeof showToast === 'function') showToast('Gagal terkirim, coba lagi', 'error');
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

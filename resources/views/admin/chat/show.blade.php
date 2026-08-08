@extends('layouts.admin')

@section('title', 'Chat - '.$chat->user->name.' - SILAPU')

@section('content')
@php
    $dataRoute = route('admin.chat.data', $chat);
    $kirimRoute = route('admin.chat.store', $chat);
@endphp

<div class="max-w-4xl mx-auto flex flex-col gap-md">
    <a href="{{ route('admin.chat.index') }}" class="inline-flex items-center gap-sm text-label-md font-semibold text-primary hover:underline w-fit">
        <span class="material-symbols-outlined text-base">arrow_back</span> Kembali
    </a>

    <div x-data="adminChat()" x-init="init()" class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
        <div class="px-lg py-4 border-b border-surface-variant/20 bg-primary-container flex items-center gap-md">
            <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-on-primary flex-shrink-0">
                <span class="material-symbols-outlined">person</span>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-title-md font-bold text-on-primary truncate">{{ $chat->user->name }}</h1>
                <p class="text-body-sm text-on-primary">Warga RT {{ $chat->rt }} · {{ $chat->user->email }}</p>
            </div>
        </div>

        <div class="flex flex-col" style="height: 60vh;">
            <div x-ref="pesanContainer" class="flex-1 overflow-y-auto p-lg space-y-3 bg-surface-container-low">
                <template x-if="loading">
                    <div class="text-center text-body-sm text-on-surface-variant py-8">Memuat percakapan...</div>
                </template>
                <template x-for="p in pesans" :key="p.id">
                    <div class="flex" :class="p.sender_role === 'admin' ? 'justify-end' : 'justify-start'">
                        <div class="max-w-[80%] px-4 py-2.5 rounded-xl text-body-sm shadow-sm"
                             :class="p.sender_role === 'admin'
                                 ? 'bg-primary text-on-primary rounded-br-sm'
                                 : 'bg-surface-container-high text-on-surface rounded-bl-sm border border-surface-variant/20'">
                            <p class="whitespace-pre-wrap break-words" x-text="p.isi"></p>
                            <span class="block text-[10px] mt-1 opacity-70" x-text="p.waktu"></span>
                        </div>
                    </div>
                </template>
                <template x-if="!loading && pesans.length === 0">
                    <div class="text-center py-xl">
                        <span class="material-symbols-outlined text-[40px] text-on-surface-variant/40">chat</span>
                        <p class="text-body-sm text-on-surface-variant mt-sm">Belum ada pesan. Balas untuk memulai percakapan.</p>
                    </div>
                </template>
            </div>

            <form @submit.prevent="kirim()" class="border-t border-surface-variant/20 p-md bg-surface-container-lowest flex items-end gap-sm">
                <div class="flex-1 flex items-end gap-sm">
                    <textarea x-model="isi" rows="1" placeholder="Tulis balasan..." required
                              class="flex-1 resize-none bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant transition-all max-h-32"></textarea>
                    <button type="submit" :disabled="mengirim || !isi.trim()"
                            class="bg-primary text-on-primary px-lg py-3 rounded-xl text-label-md font-bold hover:bg-primary/90 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-sm">
                        <span class="material-symbols-outlined text-base">send</span>
                        <span class="hidden sm:inline">Kirim</span>
                    </button>
                </div>
                <span x-show="status" x-cloak class="text-[11px] text-on-surface-variant whitespace-nowrap" x-text="status"></span>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function adminChat() {
        return {
            pesans: [],
            isi: '',
            status: '',
            loading: true,
            mengirim: false,
            timer: null,
            init() {
                this.muat();
                this.timer = setInterval(() => this.muat(), 5000);
            },
            muat() {
                fetch('{{ $dataRoute }}', { headers: { 'Accept': 'application/json' } })
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
                    this.status = 'Terkirim';
                    this.muat();
                    setTimeout(() => { this.status = ''; }, 2500);
                })
                .catch(() => {
                    this.status = 'Gagal terkirim, coba lagi';
                    setTimeout(() => { this.status = ''; }, 2500);
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
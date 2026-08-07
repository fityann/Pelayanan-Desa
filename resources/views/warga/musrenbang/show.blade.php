@extends('layouts.admin')

@section('title', 'Detail Usulan Kegiatan - Warga')

@section('content')
<div class="flex flex-col gap-lg">
    <!-- Header with Back Button -->
    <div class="flex items-center gap-md">
        <a href="{{ route('warga.musrenbang.index') }}" class="w-10 h-10 rounded-full bg-surface-container-lowest flex items-center justify-center text-on-surface-variant hover:bg-surface-container transition-all">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Detail Usulan</h1>
            <p class="text-body-sm text-on-surface-variant">Tinjau detail usulan kegiatan dan berikan suara Anda</p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
        <!-- Main Content -->
        <div class="lg:col-span-2 flex flex-col gap-lg">
            <div class="bg-surface-container-lowest rounded-xl p-xl shadow-sm border border-outline-variant/10">
                <div class="flex items-center gap-md mb-lg">
                    @php
                        $statusColors = [
                            'diusulkan' => 'bg-surface-variant text-on-surface-variant',
                            'diverifikasi' => 'bg-secondary-container text-on-secondary-container',
                            'direview' => 'bg-tertiary-container text-on-tertiary-container',
                            'disetujui' => 'bg-success/20 text-success',
                            'ditolak' => 'bg-error/20 text-error',
                        ];
                        $badgeClass = $statusColors[$musrenbang->status_usulan] ?? 'bg-surface-variant text-on-surface-variant';
                    @endphp
                    <span class="px-3 py-1 rounded-full text-label-md font-bold uppercase tracking-wider {{ $badgeClass }}">
                        {{ str_replace('_', ' ', $musrenbang->status_usulan) }}
                    </span>
                    <span class="text-body-sm text-on-surface-variant flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                        {{ \Carbon\Carbon::parse($musrenbang->created_at)->format('d M Y') }}
                    </span>
                </div>

                <h2 class="text-display-sm font-bold text-on-surface mb-md">{{ $musrenbang->judul_kegiatan }}</h2>
                
                <div class="flex items-center gap-md mb-xl text-body-sm text-on-surface-variant bg-surface-container/50 p-3 rounded-lg border border-outline-variant/50">
                    <div class="flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[18px]">person</span>
                        Oleh: <span class="font-bold text-on-surface">{{ $musrenbang->pengusul ? $musrenbang->pengusul->name : 'Sistem' }}</span>
                    </div>
                    <div class="w-1 h-1 rounded-full bg-outline-variant"></div>
                    <div class="flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[18px]">category</span>
                        {{ ucwords(str_replace('_', ' ', $musrenbang->jenis_kegiatan)) }}
                    </div>
                </div>

                <h3 class="text-title-lg font-bold text-on-surface mb-sm">Deskripsi Kegiatan</h3>
                <div class="prose max-w-none text-body-md text-on-surface-variant mb-xl whitespace-pre-line">
                    {{ $musrenbang->deskripsi_kegiatan }}
                </div>

                @if($musrenbang->dokumen && $musrenbang->dokumen->count() > 0)
                <h3 class="text-title-lg font-bold text-on-surface mb-sm">Dokumen Pendukung</h3>
                <div class="flex flex-col gap-sm">
                    @foreach($musrenbang->dokumen as $dok)
                        <a href="{{ Storage::url($dok->path_dokumen) }}" target="_blank" class="flex items-center justify-between p-md bg-surface-container-lowest border border-outline-variant rounded-lg hover:bg-surface-container transition-all">
                            <div class="flex items-center gap-md">
                                <span class="material-symbols-outlined text-primary">description</span>
                                <div>
                                    <p class="text-label-lg font-bold text-on-surface">{{ $dok->nama_dokumen }}</p>
                                    <p class="text-body-sm text-on-surface-variant uppercase">{{ $dok->tipe_dokumen }}</p>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-on-surface-variant">download</span>
                        </a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar / Voting Section -->
        <div class="flex flex-col gap-lg">
            <div class="bg-surface-container-lowest rounded-xl p-xl shadow-sm border border-outline-variant/10">
                <h3 class="text-title-lg font-bold text-on-surface mb-lg">Informasi Anggaran</h3>
                
                <div class="flex flex-col gap-md">
                    <div class="flex justify-between items-center pb-md border-b border-outline-variant/50">
                        <span class="text-body-md text-on-surface-variant">Tahun Pelaksanaan</span>
                        <span class="text-title-md font-bold text-on-surface">{{ $musrenbang->tahun }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-md border-b border-outline-variant/50">
                        <span class="text-body-md text-on-surface-variant">Estimasi Biaya</span>
                        <span class="text-title-md font-bold text-primary">Rp {{ number_format($musrenbang->estimasi_biaya, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-md border-b border-outline-variant/50">
                        <span class="text-body-md text-on-surface-variant">Sumber Dana</span>
                        <span class="text-label-md bg-surface-container px-2 py-1 rounded font-bold text-on-surface">{{ $musrenbang->sumber_dana }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-md border-b border-outline-variant/50">
                        <span class="text-body-md text-on-surface-variant">Prioritas</span>
                        <span class="text-label-md uppercase font-bold text-on-surface">{{ str_replace('_', ' ', $musrenbang->prioritas) }}</span>
                    </div>
                </div>
            </div>

            <!-- Voting Section -->
            <div class="bg-surface-container-lowest rounded-xl p-xl shadow-sm border border-outline-variant/10">
                <h3 class="text-title-lg font-bold text-on-surface mb-sm">Suara Warga</h3>
                <p class="text-body-sm text-on-surface-variant mb-lg">Total {{ $stats['dukung'] + $stats['tolak'] + $stats['abstain'] }} suara masuk</p>

                <!-- Stats Bar -->
                <div class="flex flex-col gap-sm mb-xl">
                    @php
                        $total = $stats['dukung'] + $stats['tolak'] + $stats['abstain'];
                        $pctDukung = $total > 0 ? round(($stats['dukung'] / $total) * 100) : 0;
                        $pctTolak = $total > 0 ? round(($stats['tolak'] / $total) * 100) : 0;
                        $pctAbstain = $total > 0 ? round(($stats['abstain'] / $total) * 100) : 0;
                    @endphp
                    
                    <div class="flex h-3 rounded-full overflow-hidden bg-surface-container w-full">
                        <div class="bg-success" style="width: {{ $pctDukung }}%"></div>
                        <div class="bg-error" style="width: {{ $pctTolak }}%"></div>
                        <div class="bg-outline-variant" style="width: {{ $pctAbstain }}%"></div>
                    </div>
                    
                    <div class="flex justify-between text-label-sm text-on-surface-variant mt-1">
                        <div class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-success"></div> Dukung ({{ $stats['dukung'] }})</div>
                        <div class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-error"></div> Tolak ({{ $stats['tolak'] }})</div>
                        <div class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-outline-variant"></div> Abstain ({{ $stats['abstain'] }})</div>
                    </div>
                </div>

                <hr class="border-outline-variant/30 mb-lg">

                @auth('warga')
                    @if ($userVote)
                        <div class="bg-primary/10 border border-primary/20 rounded-xl p-md mb-lg">
                            <p class="text-label-md font-bold text-primary mb-1">Anda sudah memberikan suara</p>
                            <p class="text-body-sm text-on-surface-variant">Pilihan Anda: <strong class="uppercase">{{ $userVote->tipe_suara }}</strong></p>
                            @if($userVote->alasan)
                                <p class="text-body-sm text-on-surface-variant italic mt-2">"{{ $userVote->alasan }}"</p>
                            @endif
                        </div>
                        <p class="text-label-sm text-center text-on-surface-variant mb-md">Ingin mengubah suara?</p>
                    @endif

                    <form action="{{ route('warga.musrenbang.support', $musrenbang) }}" method="POST" class="flex flex-col gap-md">
                        @csrf
                        <div>
                            <label class="block text-label-md font-bold text-on-surface mb-2">Berikan Suara Anda</label>
                            <div class="grid grid-cols-3 gap-sm">
                                <label class="cursor-pointer">
                                    <input type="radio" name="tipe_suara" value="dukung" class="peer sr-only" required {{ ($userVote && $userVote->tipe_suara == 'dukung') ? 'checked' : '' }}>
                                    <div class="text-center py-2 rounded-lg border border-outline-variant text-label-md font-bold text-on-surface-variant peer-checked:bg-success/20 peer-checked:text-success peer-checked:border-success/50 transition-all">
                                        Dukung
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="tipe_suara" value="tolak" class="peer sr-only" required {{ ($userVote && $userVote->tipe_suara == 'tolak') ? 'checked' : '' }}>
                                    <div class="text-center py-2 rounded-lg border border-outline-variant text-label-md font-bold text-on-surface-variant peer-checked:bg-error/20 peer-checked:text-error peer-checked:border-error/50 transition-all">
                                        Tolak
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="tipe_suara" value="abstain" class="peer sr-only" required {{ ($userVote && $userVote->tipe_suara == 'abstain') ? 'checked' : '' }}>
                                    <div class="text-center py-2 rounded-lg border border-outline-variant text-label-md font-bold text-on-surface-variant peer-checked:bg-surface-variant peer-checked:text-on-surface peer-checked:border-outline transition-all">
                                        Abstain
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-label-md font-bold text-on-surface mb-2">Alasan / Komentar (Opsional)</label>
                            <textarea name="alasan" rows="3" class="w-full bg-surface-container rounded-lg border-outline-variant px-md py-2 text-body-md focus:ring-primary focus:border-primary" placeholder="Tuliskan alasan Anda...">{{ $userVote ? $userVote->alasan : '' }}</textarea>
                        </div>

                        <button type="submit" class="w-full bg-primary text-on-primary font-bold text-label-lg py-3 rounded-full hover:bg-primary/90 transition-all flex items-center justify-center gap-sm">
                            <span class="material-symbols-outlined">how_to_vote</span>
                            Kirim Suara
                        </button>
                    </form>
                @else
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md text-center">
                        <span class="material-symbols-outlined text-[32px] text-on-surface-variant mb-sm">lock</span>
                        <p class="text-body-md text-on-surface mb-md">Silakan login untuk dapat memberikan suara pada usulan ini.</p>
                        <a href="{{ route('login') }}" class="inline-block w-full bg-primary text-on-primary font-bold text-label-md py-2.5 rounded-full hover:bg-primary/90 transition-all">
                            Login Warga
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection

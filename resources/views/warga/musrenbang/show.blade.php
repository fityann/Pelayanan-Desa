@extends('layouts.warga')

@section('title', 'Detail Usulan Kegiatan - Warga')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto py-8">
    <!-- Header with Back Button -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('warga.musrenbang.index') }}" class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-500 hover:bg-gray-50 border border-gray-200 shadow-sm transition-all">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Usulan</h1>
            <p class="text-sm text-gray-500 mt-1">Tinjau detail usulan kegiatan dan berikan suara Anda</p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg flex items-center space-x-3">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 flex flex-col gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex flex-wrap items-center gap-4 mb-6">
                    @php
                        $statusColors = [
                            'diusulkan' => 'bg-gray-100 text-gray-700',
                            'diverifikasi' => 'bg-blue-50 text-blue-700',
                            'direview' => 'bg-purple-50 text-purple-700',
                            'disetujui' => 'bg-emerald-50 text-emerald-700',
                            'ditolak' => 'bg-red-50 text-red-700',
                        ];
                        $badgeClass = $statusColors[$musrenbang->status_usulan] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $badgeClass }}">
                        {{ str_replace('_', ' ', $musrenbang->status_usulan) }}
                    </span>
                    <span class="text-sm text-gray-500 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                        {{ \Carbon\Carbon::parse($musrenbang->created_at)->format('d M Y') }}
                    </span>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $musrenbang->judul_kegiatan }}</h2>
                
                <div class="flex flex-wrap items-center gap-4 mb-8 text-sm text-gray-600 bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-gray-400">person</span>
                        Oleh: <span class="font-bold text-gray-900">{{ $musrenbang->pengusul ? $musrenbang->pengusul->name : 'Sistem' }}</span>
                    </div>
                    <div class="hidden sm:block w-1 h-1 rounded-full bg-gray-300"></div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-gray-400">category</span>
                        {{ ucwords(str_replace('_', ' ', $musrenbang->jenis_kegiatan)) }}
                    </div>
                </div>

                <h3 class="text-lg font-bold text-gray-900 mb-3">Deskripsi Kegiatan</h3>
                <div class="prose max-w-none text-base text-gray-700 mb-8 whitespace-pre-line leading-relaxed">
                    {{ $musrenbang->deskripsi_kegiatan }}
                </div>

                @if($musrenbang->dokumen && $musrenbang->dokumen->count() > 0)
                <h3 class="text-lg font-bold text-gray-900 mb-3">Dokumen Pendukung</h3>
                <div class="flex flex-col gap-3">
                    @foreach($musrenbang->dokumen as $dok)
                        <a href="{{ Storage::url($dok->path_dokumen) }}" target="_blank" class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 transition-all">
                            <div class="flex items-center gap-4">
                                <div class="bg-emerald-100 p-2 rounded-lg">
                                    <span class="material-symbols-outlined text-emerald-600">description</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $dok->nama_dokumen }}</p>
                                    <p class="text-xs text-gray-500 uppercase mt-1">{{ $dok->tipe_dokumen }}</p>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-gray-400">download</span>
                        </a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar / Voting Section -->
        <div class="flex flex-col gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-5">Informasi Anggaran</h3>
                
                <div class="flex flex-col gap-4">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Tahun Pelaksanaan</span>
                        <span class="text-base font-bold text-gray-900">{{ $musrenbang->tahun }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Estimasi Biaya</span>
                        <span class="text-base font-bold text-emerald-600">Rp {{ number_format($musrenbang->estimasi_biaya, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Sumber Dana</span>
                        <span class="text-xs bg-gray-100 px-3 py-1.5 rounded-full font-bold text-gray-700">{{ $musrenbang->sumber_dana }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-2">
                        <span class="text-sm text-gray-500">Prioritas</span>
                        <span class="text-xs uppercase font-bold text-gray-900">{{ str_replace('_', ' ', $musrenbang->prioritas) }}</span>
                    </div>
                </div>
            </div>

            <!-- Voting Section -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Suara Warga</h3>
                <p class="text-sm text-gray-500 mb-6">Total {{ $stats['dukung'] + $stats['tolak'] + $stats['abstain'] }} suara masuk</p>

                <!-- Stats Bar -->
                <div class="flex flex-col gap-3 mb-8">
                    @php
                        $total = $stats['dukung'] + $stats['tolak'] + $stats['abstain'];
                        $pctDukung = $total > 0 ? round(($stats['dukung'] / $total) * 100) : 0;
                        $pctTolak = $total > 0 ? round(($stats['tolak'] / $total) * 100) : 0;
                        $pctAbstain = $total > 0 ? round(($stats['abstain'] / $total) * 100) : 0;
                    @endphp
                    
                    <div class="flex h-3 rounded-full overflow-hidden bg-gray-100 w-full">
                        <div class="bg-emerald-500" style="width: {{ $pctDukung }}%"></div>
                        <div class="bg-red-500" style="width: {{ $pctTolak }}%"></div>
                        <div class="bg-gray-400" style="width: {{ $pctAbstain }}%"></div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:justify-between gap-2 text-xs text-gray-600 mt-2">
                        <div class="flex items-center gap-2"><div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div> Dukung ({{ $stats['dukung'] }})</div>
                        <div class="flex items-center gap-2"><div class="w-2.5 h-2.5 rounded-full bg-red-500"></div> Tolak ({{ $stats['tolak'] }})</div>
                        <div class="flex items-center gap-2"><div class="w-2.5 h-2.5 rounded-full bg-gray-400"></div> Abstain ({{ $stats['abstain'] }})</div>
                    </div>
                </div>

                <hr class="border-gray-100 mb-6">

                @auth('warga')
                    @if ($userVote)
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-6">
                            <p class="text-sm font-bold text-emerald-700 mb-1">Anda sudah memberikan suara</p>
                            <p class="text-sm text-gray-700">Pilihan Anda: <strong class="uppercase">{{ $userVote->tipe_suara }}</strong></p>
                            @if($userVote->alasan)
                                <p class="text-sm text-gray-600 italic mt-2">"{{ $userVote->alasan }}"</p>
                            @endif
                        </div>
                    @else
                        <form action="{{ route('warga.musrenbang.support', $musrenbang) }}" method="POST" class="flex flex-col gap-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-bold text-gray-900 mb-3">Berikan Suara Anda</label>
                                <div class="grid grid-cols-3 gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="tipe_suara" value="dukung" class="peer sr-only" required>
                                        <div class="text-center py-2.5 rounded-xl border border-gray-200 text-sm font-bold text-gray-600 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 peer-checked:border-emerald-500 transition-all hover:bg-gray-50">
                                            Dukung
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="tipe_suara" value="tolak" class="peer sr-only" required>
                                        <div class="text-center py-2.5 rounded-xl border border-gray-200 text-sm font-bold text-gray-600 peer-checked:bg-red-50 peer-checked:text-red-700 peer-checked:border-red-500 transition-all hover:bg-gray-50">
                                            Tolak
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="tipe_suara" value="abstain" class="peer sr-only" required>
                                        <div class="text-center py-2.5 rounded-xl border border-gray-200 text-sm font-bold text-gray-600 peer-checked:bg-gray-100 peer-checked:text-gray-900 peer-checked:border-gray-400 transition-all hover:bg-gray-50">
                                            Abstain
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-900 mb-2">Alasan / Komentar (Opsional)</label>
                                <textarea name="alasan" rows="3" class="w-full bg-gray-50 rounded-xl border-gray-200 px-4 py-3 text-sm text-gray-700 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Tuliskan alasan Anda..."></textarea>
                            </div>

                            <button type="submit" class="w-full bg-emerald-600 text-white font-bold text-base py-3.5 rounded-xl hover:bg-emerald-700 transition-all flex items-center justify-center gap-2 mt-2 shadow-sm shadow-emerald-200">
                                <span class="material-symbols-outlined">how_to_vote</span>
                                Kirim Suara
                            </button>
                        </form>
                    @endif
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 text-center">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                            <span class="material-symbols-outlined text-[32px] text-gray-400">lock</span>
                        </div>
                        <p class="text-sm font-medium text-gray-900 mb-2">Login Diperlukan</p>
                        <p class="text-sm text-gray-500 mb-0">Silakan login melalui Portal RT (Scan QR Code RT Anda) untuk dapat memberikan suara pada usulan ini.</p>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection

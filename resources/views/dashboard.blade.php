@extends('layouts.admin')

@section('title', 'Dashboard - Puspamukti Smart Village')

@section('content')
<div class="flex flex-col w-full gap-lg">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Dashboard</h1>
            <p class="text-body-sm text-on-surface-variant">Selamat datang, {{ auth()->user()->name }} — {{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
        <div class="flex items-center gap-sm bg-surface-container-lowest rounded-full px-lg py-2 shadow-sm">
            <span class="material-symbols-outlined text-[18px] text-on-surface-variant">calendar_today</span>
            <span class="text-label-sm font-bold text-on-surface">{{ now()->format('M Y') }}</span>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-lg">
        <div class="bg-surface-container-lowest p-lg rounded-2xl shadow-sm relative overflow-hidden group hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 border border-outline-variant/10">
            <div class="absolute top-0 right-0 p-4 opacity-[0.04] group-hover:opacity-[0.08] group-hover:scale-110 transition-all duration-500">
                <span class="material-symbols-outlined text-[80px] text-primary">description</span>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-sm mb-lg">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary/20 to-primary/5 flex items-center justify-center text-primary shadow-sm">
                        <span class="material-symbols-outlined">description</span>
                    </div>
                    <div>
                        <span class="text-label-sm text-on-surface-variant">Total Surat</span>
                        <div class="flex items-baseline gap-xs">
                            <span class="font-headline-lg text-on-surface tracking-tight">{{ $totalSurat }}</span>
                            <span class="text-label-sm text-success flex items-center font-semibold">
                                <span class="material-symbols-outlined text-[14px]">trending_up</span>
                                +{{ $suratBulanIni }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[10px] uppercase tracking-wider text-on-surface-variant/60">
                    <span>Sepanjang masa</span>
                    <span class="font-semibold text-success">Bulan ini</span>
                </div>
            </div>
        </div>

        <div class="bg-surface-container-lowest p-lg rounded-2xl shadow-sm relative overflow-hidden group hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 border border-outline-variant/10">
            <div class="absolute top-0 right-0 p-4 opacity-[0.04] group-hover:opacity-[0.08] group-hover:scale-110 transition-all duration-500">
                <span class="material-symbols-outlined text-[80px] text-error">campaign</span>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-sm mb-lg">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-error/20 to-error/5 flex items-center justify-center text-error shadow-sm">
                        <span class="material-symbols-outlined">campaign</span>
                    </div>
                    <div>
                        <span class="text-label-sm text-on-surface-variant">Pengaduan Aktif</span>
                        <div class="flex items-baseline gap-xs">
                            <span class="font-headline-lg text-on-surface tracking-tight">{{ $pengaduanAktif }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-sm text-[10px] uppercase tracking-wider text-on-surface-variant/60">
                    <span class="flex gap-1">Menunggu tindak lanjut</span>
                </div>
            </div>
        </div>

        <div class="bg-surface-container-lowest p-lg rounded-2xl shadow-sm relative overflow-hidden group hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 border border-outline-variant/10">
            <div class="absolute top-0 right-0 p-4 opacity-[0.04] group-hover:opacity-[0.08] group-hover:scale-110 transition-all duration-500">
                <span class="material-symbols-outlined text-[80px] text-on-tertiary-container">account_balance_wallet</span>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-sm mb-lg">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-on-tertiary-container/20 to-on-tertiary-container/5 flex items-center justify-center text-on-tertiary-container shadow-sm">
                        <span class="material-symbols-outlined">account_balance_wallet</span>
                    </div>
                    <div>
                        <span class="text-label-sm text-on-surface-variant">Realisasi APBDes</span>
                        <div class="flex items-baseline gap-xs">
                            <span class="font-headline-lg text-on-surface tracking-tight">{{ $realisasiPersen }}%</span>
                        </div>
                    </div>
                </div>
                <div class="w-full bg-surface-container rounded-full h-1.5 overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-on-tertiary-container to-secondary rounded-full transition-all duration-1000" style="width: {{ $realisasiPersen }}%"></div>
                </div>
            </div>
        </div>

        <div class="bg-surface-container-lowest p-lg rounded-2xl shadow-sm relative overflow-hidden group hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 border border-outline-variant/10">
            <div class="absolute top-0 right-0 p-4 opacity-[0.04] group-hover:opacity-[0.08] group-hover:scale-110 transition-all duration-500">
                <span class="material-symbols-outlined text-[80px] text-secondary">groups</span>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-sm mb-lg">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-secondary/20 to-secondary/5 flex items-center justify-center text-secondary shadow-sm">
                        <span class="material-symbols-outlined">groups</span>
                    </div>
                    <div>
                        <span class="text-label-sm text-on-surface-variant">Warga Terdaftar</span>
                        <div class="flex items-baseline gap-xs">
                            <span class="font-headline-lg text-on-surface tracking-tight">{{ $wargaTerdaftar }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-sm text-[10px] uppercase tracking-wider text-on-surface-variant/60">
                    <span class="material-symbols-outlined text-[14px]">person_add</span>
                    <span>Total akun sistem</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-12 gap-lg">
        {{-- Left Column --}}
        <div class="col-span-12 lg:col-span-7 flex flex-col gap-lg">

            {{-- Surat Status --}}
            <div class="bg-surface-container-lowest p-lg rounded-2xl shadow-sm border border-outline-variant/10">
                <div class="flex items-center justify-between mb-lg">
                    <h3 class="text-label-md text-on-surface uppercase tracking-widest font-semibold">Surat Per Status</h3>
                    <a href="{{ route('admin.surat.pengajuan') }}" class="text-label-sm text-primary font-semibold hover:underline">Lihat semua</a>
                </div>
                @php
                    $statusLabels = [
                        'diajukan' => 'Diajukan',
                        'diverifikasi_admin' => 'Verifikasi',
                        'ditolak' => 'Ditolak',
                        'disetujui_kades' => 'Disetujui',
                        'menunggu_ttd_fisik' => 'Menunggu TTD',
                        'selesai' => 'Selesai',
                    ];
                @endphp
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                    @foreach ([
                        'diajukan' => ['bg-amber-50 text-amber-700 border border-amber-200/50', 'pending'],
                        'diverifikasi_admin' => ['bg-blue-50 text-blue-700 border border-blue-200/50', 'fact_check'],
                        'ditolak' => ['bg-red-50 text-red-700 border border-red-200/50', 'cancel'],
                        'disetujui_kades' => ['bg-emerald-50 text-emerald-700 border border-emerald-200/50', 'check_circle'],
                        'menunggu_ttd_fisik' => ['bg-purple-50 text-purple-700 border border-purple-200/50', 'ink_pen'],
                        'selesai' => ['bg-gray-100 text-gray-700 border border-gray-200/50', 'task_alt'],
                    ] as $sts => $cfg)
                        <div class="flex flex-col items-center justify-center gap-1 p-2.5 rounded-xl min-w-0 text-center shadow-xs transition-all hover:scale-[1.02] {{ $cfg[0] }}">
                            <span class="material-symbols-outlined text-[18px]">{{ $cfg[1] }}</span>
                            <span class="text-lg font-black leading-none">{{ $suratPerStatus[$sts] ?? 0 }}</span>
                            <span class="text-[9px] font-bold uppercase tracking-tight truncate w-full text-center opacity-90" title="{{ $statusLabels[$sts] ?? $sts }}">
                                {{ $statusLabels[$sts] ?? $sts }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Pendapatan vs Belanja --}}
            <div class="bg-surface-container-lowest p-lg rounded-2xl shadow-sm border border-outline-variant/10">
                <div class="flex items-center justify-between mb-lg">
                    <h3 class="text-label-md text-on-surface uppercase tracking-widest font-semibold">Pendapatan vs Belanja</h3>
                    <span class="text-label-sm text-on-surface-variant font-bold bg-surface-container px-3 py-1 rounded-full">{{ $latestTahun ?? date('Y') }}</span>
                </div>
                @php
                    $maxVal = max($pendapatan, $belanja, 1);
                    $pHeight = $pendapatan > 0 ? max(round(($pendapatan / $maxVal) * 100), 12) : 10;
                    $bHeight = $belanja > 0 ? max(round(($belanja / $maxVal) * 100), 12) : 10;
                @endphp
                <div class="flex items-end justify-center gap-12 py-4 px-lg">
                    <!-- Bar Pendapatan -->
                    <div class="flex flex-col items-center gap-3 w-32">
                        <div class="h-40 w-full flex items-end justify-center bg-surface-container/40 rounded-xl p-1.5 relative border border-outline-variant/10">
                            <div class="w-full bg-gradient-to-t from-emerald-600 to-teal-400 rounded-lg relative group/bar transition-all duration-500 hover:brightness-110 shadow-sm flex items-start justify-center pt-1" style="height: {{ $pHeight }}%">
                                <div class="absolute -top-9 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[11px] font-extrabold px-2.5 py-1 rounded-lg shadow-lg opacity-0 group-hover/bar:opacity-100 transition-all pointer-events-none whitespace-nowrap z-30">
                                    Rp {{ number_format($pendapatan, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-center text-center">
                            <span class="text-xs font-black text-emerald-700">Rp {{ number_format($pendapatan / 1000000, 1) }} jt</span>
                            <span class="text-[10px] uppercase tracking-wider text-on-surface-variant font-bold">Pendapatan</span>
                        </div>
                    </div>
                    <!-- Bar Belanja -->
                    <div class="flex flex-col items-center gap-3 w-32">
                        <div class="h-40 w-full flex items-end justify-center bg-surface-container/40 rounded-xl p-1.5 relative border border-outline-variant/10">
                            <div class="w-full bg-gradient-to-t from-blue-600 to-indigo-400 rounded-lg relative group/bar transition-all duration-500 hover:brightness-110 shadow-sm flex items-start justify-center pt-1" style="height: {{ $bHeight }}%">
                                <div class="absolute -top-9 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[11px] font-extrabold px-2.5 py-1 rounded-lg shadow-lg opacity-0 group-hover/bar:opacity-100 transition-all pointer-events-none whitespace-nowrap z-30">
                                    Rp {{ number_format($belanja, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-center text-center">
                            <span class="text-xs font-black text-blue-700">Rp {{ number_format($belanja / 1000000, 1) }} jt</span>
                            <span class="text-[10px] uppercase tracking-wider text-on-surface-variant font-bold">Belanja</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-lg mt-md pt-md border-t border-surface-variant/20 text-[10px] uppercase tracking-wider">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-sm bg-gradient-to-br from-emerald-600 to-teal-400"></div>
                        <span class="font-bold text-on-surface-variant">Pendapatan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-sm bg-gradient-to-br from-blue-600 to-indigo-400"></div>
                        <span class="font-bold text-on-surface-variant">Belanja</span>
                    </div>
                </div>
            </div>

            {{-- Aktivitas --}}
            <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden">
                <div class="p-lg border-b border-surface-variant/20 flex items-center justify-between">
                    <h3 class="text-title-md font-bold text-on-surface">Aktivitas Terkini</h3>
                    <span class="flex gap-1">
                        <span class="w-2 h-2 rounded-full bg-success animate-pulse"></span>
                        <span class="text-[10px] text-on-surface-variant">Live</span>
                    </span>
                </div>
                <div class="divide-y divide-surface-variant/10">
                    @forelse ($aktivitasTerbaru as $item)
                        <div class="p-lg flex gap-md hover:bg-surface-container/40 transition-colors">
                            <div class="mt-0.5">
                                <div class="w-10 h-10 rounded-xl {{ $item['icon_bg'] }} flex items-center justify-center {{ $item['icon_color'] }} shadow-sm">
                                    <span class="material-symbols-outlined text-[20px]">{{ $item['icon'] }}</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-sm mb-0.5">
                                    <span class="text-body-md font-semibold text-on-surface truncate">{{ $item['title'] }}</span>
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-widest {{ $item['badge_class'] }} whitespace-nowrap shrink-0">{{ $item['badge'] }}</span>
                                </div>
                                <p class="text-body-sm text-on-surface-variant line-clamp-1">{{ $item['desc'] }}</p>
                                <span class="text-[11px] text-on-surface-variant/50 flex items-center gap-1 mt-1.5">
                                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                                    {{ $item['time'] }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-xl text-center text-on-surface-variant/60">
                            <span class="material-symbols-outlined text-[40px] block mb-md">inbox</span>
                            <p>Belum ada aktivitas terbaru</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-span-12 lg:col-span-5 flex flex-col gap-lg">

            {{-- Agenda --}}
            <div class="bg-surface-container-lowest p-lg rounded-2xl shadow-sm border border-outline-variant/10">
                <div class="flex items-center justify-between mb-lg">
                    <h3 class="text-label-md text-on-surface uppercase tracking-widest font-bold">
                        <span class="material-symbols-outlined text-[18px] align-middle mr-1 text-primary">event</span>
                        Agenda Desa
                    </h3>
                    <a href="{{ route('admin.informasi.index') }}" class="text-label-sm text-primary font-semibold hover:underline">Atur</a>
                </div>
                <div class="flex flex-col gap-2.5">
                    @forelse ($agendaTerdekat as $a)
                        <div class="flex items-center gap-md p-3.5 bg-primary-fixed/20 rounded-xl border-l-[5px] border-primary hover:bg-primary-fixed/30 transition-all">
                            <div class="flex flex-col items-center justify-center bg-surface-container-lowest rounded-lg px-3 py-1.5 min-w-[52px] shadow-sm">
                                <span class="text-title-md text-primary font-extrabold leading-none">{{ $a->tanggal_kegiatan?->format('d') }}</span>
                                <span class="text-[9px] text-primary uppercase font-bold tracking-wider">{{ $a->tanggal_kegiatan?->format('M') }}</span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-body-sm font-bold text-on-surface truncate">{{ $a->judul }}</p>
                                @if ($a->lokasi)
                                    <p class="text-[11px] text-on-surface-variant/70 flex items-center gap-0.5 mt-0.5">
                                        <span class="material-symbols-outlined text-[12px]">location_on</span>
                                        {{ $a->lokasi }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-lg text-on-surface-variant/60">
                            <span class="material-symbols-outlined text-[32px]">event_busy</span>
                            <p class="text-body-sm">Belum ada agenda</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Map Card --}}
            <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden group hover:shadow-md transition-all">
                <div class="relative h-44 bg-cover bg-center grayscale group-hover:grayscale-0 transition-all duration-700 cursor-pointer" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAy_ehTvAEuj4akK2GkCC1nDRIEXb6mawMXf2pjndDiVoqKqJDA_zxc5FZ3TZMupSK2BHlgXUynt1DhgTD7rlhssGcCllUM-AJmgP0M1-Si4SKMJjFL8-VisEDmySs4NB_usjl3Rye32iwB7Ecl9SdB2FMsJumXHKC9O7va3HIT6Eri7R8KxRz5ac2ROZfnNiGKQJaIUZHlR9F7UG9fZ1XvwUdGsWb0rdFb7aWNCghNo_A-r_9emWazbA')">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    <div class="absolute bottom-3 left-4 flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-white/90 backdrop-blur flex items-center justify-center shadow-lg">
                            <span class="material-symbols-outlined text-[18px] text-primary">map</span>
                        </div>
                        <div>
                            <p class="text-body-sm font-bold text-white drop-shadow-lg">Peta Digital Desa</p>
                            <p class="text-[10px] text-white/80">Puspamukti, Cigalontang</p>
                        </div>
                    </div>
                </div>
                <div class="p-lg flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px] text-on-surface-variant">update</span>
                        <span class="text-[11px] text-on-surface-variant">Foto satelit terkini</span>
                    </div>
                    <button class="text-label-sm font-bold text-primary hover:underline flex items-center gap-1">
                        Buka Peta
                        <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                    </button>
                </div>
            </div>

            {{-- Pengaduan Kategori --}}
            <div class="bg-surface-container-lowest p-lg rounded-2xl shadow-sm border border-outline-variant/10">
                <div class="flex items-center justify-between mb-lg">
                    <h3 class="text-label-md text-on-surface uppercase tracking-widest font-bold">Pengaduan per Kategori</h3>
                    <a href="{{ route('admin.pengaduan.index') }}" class="text-label-sm text-primary font-semibold hover:underline">Detail</a>
                </div>
                <div class="space-y-3">
                    @forelse ($pengaduanPerKategori as $pk)
                        <div class="flex items-center justify-between">
                            <span class="text-body-sm font-semibold text-on-surface">{{ $pk->kategori }}</span>
                            <div class="flex items-center gap-2">
                                <div class="w-24 bg-surface-container rounded-full h-1.5 overflow-hidden">
                                    @php $maxCat = $pengaduanPerKategori->max('total'); @endphp
                                    <div class="h-full bg-gradient-to-r from-primary to-secondary rounded-full" style="width: {{ $maxCat > 0 ? ($pk->total / $maxCat) * 100 : 0 }}%"></div>
                                </div>
                                <span class="text-label-sm font-bold text-on-surface min-w-[20px] text-right">{{ $pk->total }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-body-sm text-on-surface-variant/60 text-center py-md">Belum ada pengaduan</p>
                    @endforelse
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="bg-gradient-to-br from-emerald-950 via-teal-900 to-slate-900 p-xl rounded-2xl shadow-xl relative overflow-hidden text-white border border-emerald-500/20">
                <div class="absolute -right-6 -bottom-6 opacity-[0.06]">
                    <span class="material-symbols-outlined text-[160px]">rocket_launch</span>
                </div>
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-white/15 backdrop-blur-md rounded-xl flex items-center justify-center mb-lg shadow-lg">
                        <span class="material-symbols-outlined text-[24px]">flash_on</span>
                    </div>
                    <h3 class="text-headline-sm font-extrabold mb-1">Akses Cepat</h3>
                    <p class="text-body-sm text-on-primary/70 mb-xl leading-relaxed">Modul utama sistem SILAPU</p>
                    <div class="grid grid-cols-1 gap-2.5">
                        <a class="flex items-center gap-3 bg-white/10 hover:bg-white/20 backdrop-blur-sm px-lg py-3 rounded-xl text-label-sm font-bold transition-all group/btn" href="{{ route('admin.surat.pengajuan') }}">
                            <span class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center group-hover/btn:bg-white/20 transition-all">
                                <span class="material-symbols-outlined text-[18px]">forward_to_inbox</span>
                            </span>
                            Pengajuan Surat
                            <span class="material-symbols-outlined text-[16px] ml-auto opacity-50 group-hover/btn:opacity-100 transition-all">arrow_forward</span>
                        </a>
                        <a class="flex items-center gap-3 bg-white/10 hover:bg-white/20 backdrop-blur-sm px-lg py-3 rounded-xl text-label-sm font-bold transition-all group/btn" href="{{ route('admin.apbdes.index') }}">
                            <span class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center group-hover/btn:bg-white/20 transition-all">
                                <span class="material-symbols-outlined text-[18px]">account_balance</span>
                            </span>
                            APBDes
                            <span class="material-symbols-outlined text-[16px] ml-auto opacity-50 group-hover/btn:opacity-100 transition-all">arrow_forward</span>
                        </a>
                        <a class="flex items-center gap-3 bg-white/10 hover:bg-white/20 backdrop-blur-sm px-lg py-3 rounded-xl text-label-sm font-bold transition-all group/btn" href="{{ route('admin.pengaduan.index') }}">
                            <span class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center group-hover/btn:bg-white/20 transition-all">
                                <span class="material-symbols-outlined text-[18px]">campaign</span>
                            </span>
                            Pengaduan
                            <span class="material-symbols-outlined text-[16px] ml-auto opacity-50 group-hover/btn:opacity-100 transition-all">arrow_forward</span>
                        </a>
                        <a class="flex items-center gap-3 bg-white/10 hover:bg-white/20 backdrop-blur-sm px-lg py-3 rounded-xl text-label-sm font-bold transition-all group/btn" href="{{ route('admin.informasi.index') }}">
                            <span class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center group-hover/btn:bg-white/20 transition-all">
                                <span class="material-symbols-outlined text-[18px]">newspaper</span>
                            </span>
                            Informasi & Agenda
                            <span class="material-symbols-outlined text-[16px] ml-auto opacity-50 group-hover/btn:opacity-100 transition-all">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

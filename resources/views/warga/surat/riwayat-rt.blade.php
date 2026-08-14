@extends('layouts.warga')

@section('title', "Riwayat & Tracking Surat Saya - RT $rt")

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center space-x-3">
            <div class="bg-[#6A3297]/10 p-3 rounded-2xl text-[#6A3297]">
                <span class="material-symbols-outlined text-3xl">lan</span>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">Riwayat & Tracking Surat Saya</h1>
                <p class="text-xs text-gray-500 font-medium">Pantau status persetujuan, verifikasi admin, dan unduh PDF surat keterangan Anda.</p>
            </div>
        </div>
        <a href="{{ route('warga.rt.surat.index', ['rt' => $rt]) }}" 
           class="inline-flex items-center space-x-2 bg-[#6A3297] hover:bg-[#4E2472] text-white px-4 py-2.5 rounded-xl text-xs font-bold transition-all shadow-xs self-start sm:self-center">
            <span class="material-symbols-outlined text-base">add_circle</span>
            <span>Ajukan Surat Baru</span>
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 space-y-3">
        <form method="GET" action="{{ route('warga.rt.surat.riwayat', ['rt' => $rt]) }}" class="flex flex-col md:flex-row gap-3">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg">search</span>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari berdasarkan Kode Tracking (SRT-xxx) atau Jenis Surat..." 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 pl-9 pr-4 text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#6A3297] focus:ring-2 focus:ring-[#6A3297]/20 transition-all outline-none">
            </div>

            <!-- Filter Status -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0 scrollbar-none">
                @php
                    $currentStatus = request('status', 'semua');
                    $statuses = [
                        'semua' => 'Semua Status',
                        'diajukan' => 'Diajukan',
                        'diverifikasi_admin' => 'Diverifikasi',
                        'disetujui_kades' => 'Disetujui',
                        'menunggu_ttd_fisik' => 'Menunggu TTD',
                        'selesai' => 'Selesai',
                        'ditolak' => 'Ditolak',
                    ];
                @endphp
                @foreach ($statuses as $key => $label)
                    <a href="{{ route('warga.rt.surat.riwayat', array_merge(['rt' => $rt], request()->except('page', 'status'), ['status' => $key])) }}"
                       class="px-3 py-1.5 rounded-xl text-[11px] font-bold whitespace-nowrap transition-all border {{ $currentStatus === $key ? 'bg-[#6A3297] text-white border-[#6A3297] shadow-xs' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </form>
    </div>

    <!-- List Surat Cards -->
    <div class="space-y-4">
        @forelse ($pengajuanList as $item)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden hover:border-[#6A3297]/30 transition-all">
                <!-- Card Header -->
                <div class="p-5 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3 bg-slate-50/50">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-[#6A3297]/10 text-[#6A3297] flex items-center justify-center font-bold">
                            <span class="material-symbols-outlined text-xl">description</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">{{ $item->jenisSurat->nama }}</h3>
                            <p class="text-[11px] text-slate-500 font-medium mt-0.5">
                                Diajukan pada <span class="font-bold text-slate-700">{{ $item->created_at->format('d/m/Y H:i') }} WIB</span>
                            </p>
                        </div>
                    </div>
                    <div>
                        @include('partials.surat-status-badge', ['status' => $item->status])
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100 text-xs">
                        <div>
                            <span class="text-slate-400 font-medium block text-[10px] uppercase">Kode Tracking</span>
                            <span class="font-mono font-extrabold text-[#6A3297] text-sm">{{ $item->kode_tracking_val }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-medium block text-[10px] uppercase">Nomor Surat Resmi</span>
                            <span class="font-mono font-bold text-slate-800 text-xs">{{ $item->nomor_surat ?? 'Belum diterbitkan' }}</span>
                        </div>
                        @if ($item->keterangan || $item->keperluan)
                            <div class="sm:col-span-2 pt-2 border-t border-slate-200/60">
                                <span class="text-slate-400 font-medium block text-[10px] uppercase">Keperluan</span>
                                <p class="text-slate-700 font-medium leading-relaxed mt-0.5">{{ $item->keterangan ?? $item->keperluan }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Progress Step Timeline -->
                    @php
                        $step = match($item->status) {
                            'diajukan' => 1,
                            'diverifikasi_admin' => 2,
                            'disetujui_kades' => 3,
                            'menunggu_ttd_fisik' => 3,
                            'selesai' => 4,
                            'ditolak' => 0,
                            default => 1,
                        };
                    @endphp

                    @if ($item->status !== 'ditolak')
                        <div class="py-2">
                            <div class="flex items-center justify-between text-[10px] font-bold text-slate-500 mb-2">
                                <span class="{{ $step >= 1 ? 'text-[#6A3297]' : '' }}">1. Diajukan</span>
                                <span class="{{ $step >= 2 ? 'text-[#6A3297]' : '' }}">2. Diverifikasi Admin</span>
                                <span class="{{ $step >= 3 ? 'text-[#6A3297]' : '' }}">3. Approval Kades</span>
                                <span class="{{ $step >= 4 ? 'text-[#6A3297]' : '' }}">4. Selesai</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden flex">
                                <div class="bg-[#6A3297] h-2 transition-all duration-500 rounded-full" 
                                     style="width: {{ $step == 1 ? '25%' : ($step == 2 ? '50%' : ($step == 3 ? '75%' : '100%')) }}"></div>
                            </div>
                        </div>
                    @else
                        <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-xl text-xs flex items-center gap-2">
                            <span class="material-symbols-outlined text-red-500 text-base">cancel</span>
                            <div>
                                <span class="font-bold">Pengajuan Ditolak:</span> {{ $item->alasan_ditolak }}
                            </div>
                        </div>
                    @endif

                    <!-- Card Actions Footer -->
                    <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                        <a href="{{ route('warga.rt.surat.status', ['rt' => $rt, 'kode' => $item->kode_tracking_val]) }}" 
                           class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-xl bg-[#6A3297]/10 text-[#6A3297] text-xs font-extrabold hover:bg-[#6A3297] hover:text-white transition-all shadow-xs group">
                            <span>Lacak Progress Rincian</span>
                            <span class="material-symbols-outlined text-base group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </a>

                        @if (in_array($item->status, ['disetujui_kades', 'menunggu_ttd_fisik', 'selesai']))
                            <a href="{{ route('warga.rt.surat.pdf', ['rt' => $rt, 'kode' => $item->kode_tracking_val]) }}" target="_blank"
                               class="bg-[#D8B84C] hover:bg-[#c9a73b] text-[#2A3520] text-xs font-extrabold px-3.5 py-1.5 rounded-xl shadow-xs transition-all flex items-center space-x-1">
                                <span class="material-symbols-outlined text-base">picture_as_pdf</span>
                                <span>Unduh PDF</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-12 text-center border border-slate-200/80 shadow-xs">
                <span class="material-symbols-outlined text-5xl text-slate-300 mb-2">find_in_page</span>
                <h3 class="text-sm font-bold text-slate-700">Belum Ada Pengajuan Surat</h3>
                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                    Anda belum pernah mengajukan surat online atau pencarian tidak ditemukan. Klik tombol di bawah untuk membuat pengajuan baru.
                </p>
                <div class="mt-4">
                    <a href="{{ route('warga.rt.surat.index', ['rt' => $rt]) }}" 
                       class="inline-flex items-center space-x-1.5 bg-[#6A3297] text-white px-4 py-2 rounded-xl text-xs font-bold shadow-xs hover:bg-[#4E2472] transition-all">
                        <span class="material-symbols-outlined text-sm">add_circle</span>
                        <span>Buat Surat Pengajuan</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    @if ($pengajuanList->hasPages())
        <div class="pt-2">
            {{ $pengajuanList->links() }}
        </div>
    @endif
</div>
@endsection

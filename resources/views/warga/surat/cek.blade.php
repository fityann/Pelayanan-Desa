@extends('layouts.warga')

@section('title', 'Cek Status Surat - SILAPU')

@section('content')
<div class="space-y-6 max-w-3xl mx-auto py-6">
    <!-- Hero Header -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-900 via-teal-900 to-slate-900 text-white p-6 sm:p-8 shadow-xl border border-white/10">
        <div class="absolute -top-24 -right-24 w-72 h-72 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-gradient-to-tr from-emerald-500 to-teal-400 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30 flex-shrink-0">
                    <span class="material-symbols-outlined text-white text-3xl">search</span>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Cek Status Surat</h1>
                    <p class="text-sm text-emerald-200/80 mt-1">Masukkan kode tracking untuk memantau pengajuan surat Anda.</p>
                </div>
            </div>
            <a href="{{ route('warga.surat.index') }}" class="inline-flex items-center space-x-2 bg-white/10 hover:bg-white/20 backdrop-blur text-white px-5 py-3 rounded-xl font-semibold transition-all border border-white/20 shadow-md">
                <span class="material-symbols-outlined text-xl">add</span>
                <span>Ajukan Surat</span>
            </a>
        </div>
    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('warga.surat.cek') }}" class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-lg border border-gray-100 p-6 space-y-4">
        <div>
            <label class="text-sm font-extrabold text-gray-800 block mb-2">Kode Tracking Surat</label>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <span class="material-symbols-outlined text-xl">qr_code</span>
                    </div>
                    <input type="text" name="kode" value="{{ $kode }}" required
                           class="w-full pl-11 pr-4 py-3.5 bg-gray-50/80 border border-gray-200 rounded-xl text-gray-900 text-base font-mono uppercase font-bold placeholder-gray-400 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                           placeholder="Contoh: SRT-04082026-XXXX">
                </div>
                <button type="submit"
                        class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 hover:from-emerald-700 hover:to-cyan-700 text-white font-bold px-7 py-3.5 rounded-xl shadow-lg shadow-emerald-600/25 hover:shadow-emerald-600/40 transition-all flex items-center justify-center space-x-2 text-base">
                    <span class="material-symbols-outlined text-xl">search</span>
                    <span>Cari Status</span>
                </button>
            </div>
        </div>
    </form>

    @if ($kode)
        @if ($pengajuan)
            <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-xl border border-gray-100 p-6 md:p-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full bg-gradient-to-b from-emerald-500 to-teal-500"></div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-1">Jenis Surat</span>
                        <h2 class="text-xl font-black text-gray-900">{{ $pengajuan->jenisSurat->nama }}</h2>
                    </div>
                    @php
                        $statusClass = match($pengajuan->status) {
                            'diajukan' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'diverifikasi_admin' => 'bg-purple-50 text-purple-700 border-purple-200',
                            'ditolak' => 'bg-red-50 text-red-700 border-red-200',
                            'disetujui_kades' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'menunggu_ttd_fisik' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                            default => 'bg-gray-100 text-gray-700 border-gray-200',
                        };
                    @endphp
                    <span class="px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wider border whitespace-nowrap shadow-sm {{ $statusClass }}">
                        {{ str_replace('_', ' ', $pengajuan->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 bg-gray-50/80 rounded-2xl p-4 border border-gray-100">
                    <div>
                        <span class="text-xs text-gray-400 font-bold block mb-1">Nomor Surat</span>
                        <p class="text-base font-mono font-bold text-gray-900">{{ $pengajuan->nomor_surat ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 font-bold block mb-1">Nama Pemohon</span>
                        <p class="text-base font-semibold text-gray-900">{{ $pengajuan->pemohon_name }}</p>
                    </div>
                </div>

                <a href="{{ route('warga.surat.status', $pengajuan->kode_tracking) }}"
                   class="inline-flex items-center space-x-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold px-6 py-3 rounded-xl shadow-md shadow-emerald-600/20 hover:shadow-emerald-600/40 transition-all w-full sm:w-auto justify-center">
                    <span class="material-symbols-outlined text-lg">visibility</span>
                    <span>Lihat Rincian Status Lengkap</span>
                </a>
            </div>
        @else
            <div class="bg-red-50/90 border border-red-200 rounded-3xl p-6 flex gap-4 items-start shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center text-red-600 flex-shrink-0">
                    <span class="material-symbols-outlined text-2xl">search_off</span>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-red-800 mb-1">Kode Tracking Tidak Ditemukan</h3>
                    <p class="text-xs sm:text-sm text-red-700 leading-relaxed">Pastikan kode tracking yang Anda ketik sudah benar, atau hubungi kantor desa apabila memerlukan kendala teknis.</p>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
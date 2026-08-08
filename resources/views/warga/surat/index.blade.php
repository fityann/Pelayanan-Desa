@extends('layouts.warga')

@section('title', 'Layanan Surat - SILAPU')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto py-6">
    <!-- Hero Header -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-900 via-teal-900 to-slate-900 text-white p-6 sm:p-8 shadow-xl border border-white/10">
        <div class="absolute -top-24 -right-24 w-72 h-72 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-gradient-to-tr from-emerald-500 to-teal-400 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30 flex-shrink-0">
                    <span class="material-symbols-outlined text-white text-3xl">edit_note</span>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Layanan Surat Online</h1>
                    <p class="text-sm text-emerald-200/80 mt-1">Pilih jenis surat keterangan yang ingin Anda ajukan secara mandiri.</p>
                </div>
            </div>
            <a href="{{ route('warga.surat.cek') }}" class="inline-flex items-center space-x-2 bg-white/10 hover:bg-white/20 backdrop-blur text-white px-5 py-3 rounded-xl font-semibold transition-all border border-white/20 shadow-md">
                <span class="material-symbols-outlined text-xl">manage_search</span>
                <span>Cek Status Surat</span>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl flex items-center space-x-3 shadow-sm">
            <span class="material-symbols-outlined text-emerald-600">check_circle</span>
            <p class="text-sm font-semibold text-emerald-900">{{ session('success') }}</p>
        </div>
    @endif

    @if ($jenisSurat->isEmpty())
        <div class="bg-white rounded-3xl shadow-sm p-12 text-center border border-gray-100">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-gray-400">
                <span class="material-symbols-outlined text-4xl">description</span>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Jenis Surat</h3>
            <p class="text-sm text-gray-500">Belum ada jenis surat yang tersedia. Silakan hubungi kantor desa.</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($jenisSurat as $jenis)
            <div class="bg-white rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100 p-6 flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-500/10 to-teal-500/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-emerald-600 text-2xl">description</span>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200/50">Layanan Aktif</span>
                    </div>

                    <h3 class="text-lg font-extrabold text-gray-900 mb-2 group-hover:text-emerald-700 transition-colors">{{ $jenis->nama }}</h3>
                    
                    @if ($jenis->deskripsi)
                        <p class="text-xs sm:text-sm text-gray-600 mb-4 leading-relaxed line-clamp-3">{{ $jenis->deskripsi }}</p>
                    @endif

                    @if ($jenis->syarat)
                        <div class="bg-gray-50/80 rounded-2xl p-3.5 mb-4 border border-gray-100">
                            <p class="text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm text-emerald-600">assignment_turned_in</span>
                                <span>Persyaratan:</span>
                            </p>
                            <p class="text-xs text-gray-600 whitespace-pre-line leading-relaxed">{{ $jenis->syarat }}</p>
                        </div>
                    @endif
                </div>

                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-4 pt-2 border-t border-gray-100">
                        @if ($jenis->masa_berlaku)
                            <span class="inline-flex items-center text-[11px] font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-md">
                                <span class="material-symbols-outlined text-xs mr-1">schedule</span>
                                <span>Berlaku {{ $jenis->masa_berlaku }} hari</span>
                            </span>
                        @endif
                        @if (!$jenis->butuh_ttd_fisik)
                            <span class="inline-flex items-center text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md">
                                <span class="material-symbols-outlined text-xs mr-1">verified</span>
                                <span>Digital (Tanpa TTD fisik)</span>
                            </span>
                        @endif
                    </div>

                    <a href="{{ route('warga.surat.create', $jenis) }}"
                       class="w-full bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 hover:from-emerald-700 hover:via-teal-700 hover:to-cyan-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-emerald-600/20 hover:shadow-emerald-600/40 transition-all flex items-center justify-center space-x-2 text-sm">
                        <span class="material-symbols-outlined text-lg">edit_note</span>
                        <span>Ajukan Surat Sekarang</span>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

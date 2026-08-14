@extends('layouts.warga')

@section('title', "Surat Online - RT $rt")

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3 mb-3">
                <div class="bg-emerald-100 p-3 rounded-xl">
                    <span class="material-symbols-outlined text-emerald-600 text-2xl">edit_note</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Surat Online</h1>
                    <p class="text-sm text-gray-500">Layanan pengajuan surat untuk warga RT {{ $rt }}</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('warga.rt.surat.riwayat', ['rt' => $rt]) }}"
               class="inline-flex items-center space-x-2 bg-[#6A3297] text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-[#4E2472] transition-all shadow-xs">
                <span class="material-symbols-outlined text-sm">lan</span>
                <span>Riwayat & Tracking Surat Saya</span>
            </a>
            <a href="{{ route('warga.rt.landing', ['rt' => $rt]) }}"
               class="inline-flex items-center space-x-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-xl text-xs font-bold hover:bg-gray-200 transition-colors">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg flex items-center space-x-3">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    @if ($jenisSurat->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
            <span class="material-symbols-outlined text-5xl text-gray-300 block mb-3">description</span>
            <p class="text-gray-600">Belum ada jenis surat yang tersedia. Silakan hubungi kantor desa.</p>
        </div>
    @endif

    <!-- Daftar Jenis Surat -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($jenisSurat as $jenis)
        <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md hover:-translate-y-0.5 transition-all border border-gray-100 flex flex-col">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-emerald-100 w-12 h-12 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-emerald-600">description</span>
                </div>
                <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700">Aktif</span>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $jenis->nama }}</h3>
            @if ($jenis->deskripsi)
                <p class="text-sm text-gray-600 mb-4">{{ $jenis->deskripsi }}</p>
            @endif
            @if ($jenis->syarat)
                <div class="bg-gray-50 rounded-lg p-4 mb-4 flex-1">
                    <p class="text-xs font-bold text-gray-900 mb-1">Syarat:</p>
                    <p class="text-sm text-gray-600 whitespace-pre-line">{{ $jenis->syarat }}</p>
                </div>
            @endif
            <div class="flex flex-wrap gap-2 mb-4">
                @if ($jenis->masa_berlaku)
                    <span class="text-xs text-gray-500">Berlaku {{ $jenis->masa_berlaku }} hari</span>
                @endif
                @if (!$jenis->butuh_ttd_fisik)
                    <span class="text-xs text-gray-500">Tanpa TTD fisik</span>
                @endif
            </div>
            <a href="{{ route('warga.rt.surat.create', ['rt' => $rt, 'jenisSurat' => $jenis]) }}"
               class="mt-auto bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-center px-4 py-2.5 rounded-xl font-semibold hover:from-emerald-700 hover:to-teal-700 transition-all flex items-center justify-center space-x-2">
                <span class="material-symbols-outlined text-[18px]">edit_note</span>
                <span>Ajukan Surat</span>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection

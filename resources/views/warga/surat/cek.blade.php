@extends('layouts.warga')

@section('title', 'Cek Status Surat - SILAPU')

@section('content')
<div class="space-y-6 max-w-3xl mx-auto py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Cek Status Pengajuan Surat</h1>
            <p class="text-sm text-gray-500 mt-1">Masukkan kode tracking yang Anda terima saat mengajukan surat.</p>
        </div>
        <a href="{{ route('warga.surat.index') }}" class="inline-flex items-center space-x-2 bg-emerald-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-emerald-700 transition-colors">
            <span class="material-symbols-outlined text-[18px]">add</span>
            <span>Ajukan Surat</span>
        </a>
    </div>

    <form method="GET" action="{{ route('warga.surat.cek') }}" class="bg-white rounded-2xl shadow-sm p-6 space-y-4">
        <div>
            <label class="text-sm font-bold text-gray-900 block mb-2">Kode Tracking</label>
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" name="kode" value="{{ $kode }}" required class="flex-1 bg-gray-50 rounded-xl px-4 py-3 text-base font-mono uppercase outline-none focus:ring-2 focus:ring-emerald-500/20 border border-gray-200" placeholder="Contoh: SRT-04082026-XXXX">
                <button type="submit" class="bg-emerald-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-emerald-700 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                    Cari
                </button>
            </div>
        </div>
    </form>

    @if ($kode)
        @if ($pengajuan)
            <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-emerald-500">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Jenis Surat</p>
                        <p class="text-lg font-bold text-gray-900">{{ $pengajuan->jenisSurat->nama }}</p>
                    </div>
                    @php
                        $statusClass = match($pengajuan->status) {
                            'diajukan' => 'bg-blue-50 text-blue-700',
                            'diverifikasi_admin' => 'bg-purple-50 text-purple-700',
                            'ditolak' => 'bg-red-50 text-red-700',
                            'disetujui_kades' => 'bg-emerald-50 text-emerald-700',
                            'menunggu_ttd_fisik' => 'bg-amber-50 text-amber-700',
                            'selesai' => 'bg-gray-100 text-gray-700',
                            default => 'bg-gray-100 text-gray-700',
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider whitespace-nowrap {{ $statusClass }}">{{ str_replace('_', ' ', $pengajuan->status) }}</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Nomor Surat</p>
                        <p class="text-base font-mono text-gray-900">{{ $pengajuan->nomor_surat ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Nama Pemohon</p>
                        <p class="text-base text-gray-900">{{ $pengajuan->pemohon_name }}</p>
                    </div>
                </div>
                <a href="{{ route('warga.surat.status', $pengajuan->kode_tracking) }}" class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 px-4 py-2 rounded-xl text-sm font-bold hover:bg-emerald-100 transition-all w-full sm:w-auto justify-center">
                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                    Lihat Detail Status
                </a>
            </div>
        @else
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex gap-3 items-start">
                <span class="material-symbols-outlined text-red-500">search_off</span>
                <div>
                    <p class="text-sm font-bold text-red-700 mb-1">Kode Tidak Ditemukan</p>
                    <p class="text-sm text-red-600">Pastikan kode tracking benar, atau hubungi kantor desa jika mengalami kendala.</p>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
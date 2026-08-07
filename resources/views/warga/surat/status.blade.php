@extends('layouts.warga')

@section('title', 'Status Pengajuan Surat - SILAPU')

@section('content')
<div class="space-y-6 max-w-3xl mx-auto py-8">
    <!-- Header -->
    <div>
        <a href="{{ route('warga.surat.cek') }}" class="inline-flex items-center space-x-1 text-gray-500 hover:text-gray-700 text-sm font-medium mb-4">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            <span>Cek Surat Lain</span>
        </a>
        <div class="flex items-center space-x-3">
            <div class="bg-emerald-100 p-3 rounded-xl">
                <span class="material-symbols-outlined text-emerald-600 text-2xl">description</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Status Pengajuan</h1>
                <p class="text-sm text-gray-500">{{ $pengajuan->jenisSurat->nama }}</p>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg flex items-center space-x-3">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Kartu Status -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
            <div>
                <p class="text-xs text-gray-500">Kode Tracking</p>
                <p class="text-lg font-mono font-bold text-emerald-700">{{ $pengajuan->kode_tracking }}</p>
                <p class="text-[10px] text-gray-500 mt-1">Simpan kode ini untuk memantau status pengajuan Anda.</p>
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

        <div class="mb-5">
            <p class="text-xs text-gray-500">Nomor Surat</p>
            <p class="text-lg font-mono font-bold text-gray-900">{{ $pengajuan->nomor_surat ?? '-' }}</p>
        </div>

        @if ($pengajuan->keterangan)
            <div class="bg-gray-50 rounded-xl p-4 mb-4">
                <p class="text-xs font-bold text-gray-900 mb-1">Keperluan</p>
                <p class="text-sm text-gray-600">{{ $pengajuan->keterangan }}</p>
            </div>
        @endif

        @if ($pengajuan->alasan_ditolak)
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4 flex space-x-3">
                <span class="material-symbols-outlined text-red-500">cancel</span>
                <div>
                    <p class="text-xs font-bold text-red-700 mb-1">Pengajuan Ditolak</p>
                    <p class="text-sm text-gray-700">{{ $pengajuan->alasan_ditolak }}</p>
                </div>
            </div>
        @endif

        @if ($pengajuan->status === 'menunggu_ttd_fisik')
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex space-x-3">
                <span class="material-symbols-outlined text-amber-600">print</span>
                <div>
                    <p class="text-xs font-bold text-amber-800 mb-1">Menunggu Tanda Tangan Kepala Desa</p>
                    <p class="text-sm text-gray-700 mb-3">
                        Draft PDF sudah siap. Silakan unduh, cetak, lalu bawa ke kantor desa untuk ditandatangani Kepala Desa.
                    </p>
                    <a href="{{ route('warga.surat.pdf', $pengajuan->kode_tracking) }}" class="inline-flex items-center space-x-2 bg-amber-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-amber-700 transition-all">
                        <span class="material-symbols-outlined text-[18px]">download</span>
                        <span>Unduh Draft PDF</span>
                    </a>
                </div>
            </div>
        @endif

        @if (in_array($pengajuan->status, ['disetujui_kades', 'selesai']) && !$pengajuan->butuh_ttd_fisik)
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex space-x-3">
                <span class="material-symbols-outlined text-emerald-600">task_alt</span>
                <div>
                    <p class="text-xs font-bold text-emerald-800 mb-1">Surat Selesai</p>
                    <p class="text-sm text-gray-700 mb-3">Surat Anda sudah final dan dapat diunduh langsung.</p>
                    <a href="{{ route('warga.surat.pdf', $pengajuan->kode_tracking) }}" class="inline-flex items-center space-x-2 bg-emerald-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-emerald-700 transition-all">
                        <span class="material-symbols-outlined text-[18px]">download</span>
                        <span>Unduh PDF Final</span>
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Riwayat Status -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-xs uppercase tracking-widest font-bold text-gray-500 mb-5">Riwayat Proses</h3>
        <div class="relative pl-6 border-l-2 border-gray-200 space-y-6">
            @forelse ($pengajuan->riwayatStatus as $riwayat)
                <div class="relative">
                    <span class="absolute -left-[31px] top-1 w-4 h-4 rounded-full border-2 border-white bg-emerald-500 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[10px] text-white">check</span>
                    </span>
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm font-bold text-gray-900 capitalize">{{ str_replace('_', ' ', $riwayat->status) }}</p>
                        <span class="text-xs text-gray-500 whitespace-nowrap">{{ $riwayat->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if ($riwayat->catatan)
                        <p class="text-sm text-gray-600 mt-1">{{ $riwayat->catatan }}</p>
                    @endif
                    @if ($riwayat->olehUser)
                        <p class="text-xs text-gray-400 mt-1">oleh {{ $riwayat->olehUser->name }}</p>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-500">Belum ada riwayat.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

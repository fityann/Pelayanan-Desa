@extends('layouts.warga')

@section('title', 'Status Pengajuan Surat - SILAPU')

@section('content')
<div class="space-y-6 max-w-3xl mx-auto py-6">
    <!-- Header Back -->
    <div>
        <a href="{{ route('warga.surat.cek') }}" class="inline-flex items-center space-x-2 text-emerald-700 hover:text-emerald-900 text-sm font-semibold transition-colors mb-4">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            <span>Cek Surat Lain</span>
        </a>
    </div>

    <!-- Hero Header -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-900 via-teal-900 to-slate-900 text-white p-6 sm:p-8 shadow-xl border border-white/10">
        <div class="absolute -top-24 -right-24 w-72 h-72 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-gradient-to-tr from-emerald-500 to-teal-400 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30 flex-shrink-0">
                    <span class="material-symbols-outlined text-white text-3xl">description</span>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Rincian Pengajuan Surat</h1>
                    <p class="text-sm text-emerald-200/80 mt-1">{{ $pengajuan->jenisSurat->nama }}</p>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl flex items-center space-x-3 shadow-sm">
            <span class="material-symbols-outlined text-emerald-600">check_circle</span>
            <p class="text-sm font-semibold text-emerald-900">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Main Card Status -->
    <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-xl border border-gray-100 p-6 md:p-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-gray-100">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Kode Tracking</span>
                <p class="text-xl sm:text-2xl font-mono font-black text-emerald-700 tracking-wider">{{ $pengajuan->kode_tracking }}</p>
                <p class="text-xs text-gray-500 mt-1">Simpan kode ini untuk memantau status pengajuan Anda kapan saja.</p>
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
            <span class="px-4 py-2 rounded-full text-xs font-extrabold uppercase tracking-wider border whitespace-nowrap shadow-sm {{ $statusClass }}">
                {{ str_replace('_', ' ', $pengajuan->status) }}
            </span>
        </div>

        <!-- 4-Step Visual Progress Bar -->
        @php
            $currentStep = match($pengajuan->status) {
                'diajukan' => 1,
                'diverifikasi_admin' => 2,
                'disetujui_kades' => 3,
                'menunggu_ttd_fisik' => 3,
                'selesai' => 4,
                'ditolak' => 0,
                default => 1,
            };
        @endphp

        @if ($pengajuan->status !== 'ditolak')
            <div class="mb-6">
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[#4B5D3A] text-lg">conversion_path</span>
                    <span>Alur & Status Tahapan Surat</span>
                </h4>
                
                <div class="relative">
                    <div class="overflow-hidden h-2.5 mb-4 text-xs flex rounded-full bg-slate-100">
                        <div style="width: {{ $currentStep == 1 ? '25%' : ($currentStep == 2 ? '50%' : ($currentStep == 3 ? '75%' : '100%')) }}" 
                             class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-[#4B5D3A] transition-all duration-500 rounded-full"></div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-center">
                        <div class="p-3 rounded-xl border {{ $currentStep >= 1 ? 'bg-emerald-50/70 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                            <div class="w-8 h-8 rounded-full mx-auto mb-1.5 flex items-center justify-center font-bold text-xs {{ $currentStep >= 1 ? 'bg-[#4B5D3A] text-white' : 'bg-slate-200 text-slate-500' }}">1</div>
                            <p class="text-xs font-black">Diajukan</p>
                            <p class="text-[10px] opacity-80">Warga Mengirim</p>
                        </div>
                        <div class="p-3 rounded-xl border {{ $currentStep >= 2 ? 'bg-emerald-50/70 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                            <div class="w-8 h-8 rounded-full mx-auto mb-1.5 flex items-center justify-center font-bold text-xs {{ $currentStep >= 2 ? 'bg-[#4B5D3A] text-white' : 'bg-slate-200 text-slate-500' }}">2</div>
                            <p class="text-xs font-black">Verifikasi Admin</p>
                            <p class="text-[10px] opacity-80">Berkas Diperiksa</p>
                        </div>
                        <div class="p-3 rounded-xl border {{ $currentStep >= 3 ? 'bg-emerald-50/70 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                            <div class="w-8 h-8 rounded-full mx-auto mb-1.5 flex items-center justify-center font-bold text-xs {{ $currentStep >= 3 ? 'bg-[#4B5D3A] text-white' : 'bg-slate-200 text-slate-500' }}">3</div>
                            <p class="text-xs font-black">Approval Kades</p>
                            <p class="text-[10px] opacity-80">Persetujuan & No. Surat</p>
                        </div>
                        <div class="p-3 rounded-xl border {{ $currentStep >= 4 ? 'bg-emerald-50/70 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                            <div class="w-8 h-8 rounded-full mx-auto mb-1.5 flex items-center justify-center font-bold text-xs {{ $currentStep >= 4 ? 'bg-[#4B5D3A] text-white' : 'bg-slate-200 text-slate-500' }}">4</div>
                            <p class="text-xs font-black">Selesai / Cetak</p>
                            <p class="text-[10px] opacity-80">Dokumen Siap</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-50/80 rounded-2xl p-4 border border-gray-100">
            <div>
                <span class="text-xs text-gray-400 font-bold block mb-1">Nomor Surat Resmi</span>
                <p class="text-base font-mono font-bold text-gray-900">{{ $pengajuan->nomor_surat ?? 'Belum terbit' }}</p>
            </div>
            <div>
                <span class="text-xs text-gray-400 font-bold block mb-1">Tanggal Pengajuan</span>
                <p class="text-base font-semibold text-gray-900">{{ $pengajuan->created_at ? $pengajuan->created_at->format('d F Y, H:i') : '-' }}</p>
            </div>
        </div>

        @if ($pengajuan->keterangan)
            <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-1">Keperluan / Keterangan</span>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $pengajuan->keterangan }}</p>
            </div>
        @endif

        @if ($pengajuan->alasan_ditolak)
            <div class="bg-red-50 border border-red-200 rounded-2xl p-5 flex space-x-4 items-start shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center text-red-600 flex-shrink-0">
                    <span class="material-symbols-outlined text-2xl">cancel</span>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-red-800 mb-1">Pengajuan Ditolak</h3>
                    <p class="text-sm text-red-700 leading-relaxed">{{ $pengajuan->alasan_ditolak }}</p>
                </div>
            </div>
        @endif

        @if ($pengajuan->status === 'menunggu_ttd_fisik')
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 flex space-x-4 items-start shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-700 flex-shrink-0">
                    <span class="material-symbols-outlined text-2xl">print</span>
                </div>
                <div class="space-y-3">
                    <div>
                        <h3 class="text-sm font-extrabold text-amber-900 mb-1">Menunggu Tanda Tangan Fisik Kepala Desa</h3>
                        <p class="text-xs sm:text-sm text-amber-800 leading-relaxed">
                            Draft PDF surat telah siap. Silakan unduh, cetak, lalu bawa ke kantor desa untuk proses tanda tangan basah Kepala Desa.
                        </p>
                    </div>
                    <a href="{{ route('warga.surat.pdf', $pengajuan->kode_tracking) }}"
                       class="inline-flex items-center space-x-2 bg-amber-600 hover:bg-amber-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm shadow-md transition-all">
                        <span class="material-symbols-outlined text-lg">download</span>
                        <span>Unduh Draft PDF</span>
                    </a>
                </div>
            </div>
        @endif

        @if (in_array($pengajuan->status, ['disetujui_kades', 'selesai']) && !$pengajuan->butuh_ttd_fisik)
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 flex space-x-4 items-start shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-700 flex-shrink-0">
                    <span class="material-symbols-outlined text-2xl">task_alt</span>
                </div>
                <div class="space-y-3">
                    <div>
                        <h3 class="text-sm font-extrabold text-emerald-900 mb-1">Surat Keterangan Resmi Selesai</h3>
                        <p class="text-xs sm:text-sm text-emerald-800 leading-relaxed">Surat keterangan Anda telah resmi terverifikasi dan siap diunduh langsung dalam format PDF.</p>
                    </div>
                    <a href="{{ route('warga.surat.pdf', $pengajuan->kode_tracking) }}"
                       class="inline-flex items-center space-x-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-lg shadow-emerald-600/20 hover:shadow-emerald-600/40 transition-all">
                        <span class="material-symbols-outlined text-lg">download</span>
                        <span>Unduh PDF Surat Final</span>
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Riwayat Status Process -->
    <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-xl border border-gray-100 p-6 md:p-8">
        <h3 class="text-xs uppercase tracking-widest font-extrabold text-gray-400 mb-6 flex items-center space-x-2">
            <span class="material-symbols-outlined text-base text-emerald-600">history</span>
            <span>Riwayat Tahapan Proses</span>
        </h3>
        
        <div class="relative pl-6 border-l-2 border-emerald-200 space-y-6">
            @forelse ($pengajuan->riwayatStatus as $riwayat)
                <div class="relative">
                    <span class="absolute -left-[31px] top-0.5 w-5 h-5 rounded-full border-2 border-white bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center shadow-md">
                        <span class="material-symbols-outlined text-[10px] text-white font-bold">check</span>
                    </span>
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm font-extrabold text-gray-900 capitalize">{{ str_replace('_', ' ', $riwayat->status) }}</p>
                        <span class="text-xs text-gray-400 font-medium whitespace-nowrap">{{ $riwayat->created_at ? $riwayat->created_at->format('d/m/Y H:i') : '' }}</span>
                    </div>
                    @if ($riwayat->catatan)
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 leading-relaxed">{{ $riwayat->catatan }}</p>
                    @endif
                    @if ($riwayat->olehUser)
                        <p class="text-[11px] text-gray-400 mt-1">Petugas: <strong class="font-semibold text-gray-600">{{ $riwayat->olehUser->name }}</strong></p>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-500">Belum ada riwayat proses.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

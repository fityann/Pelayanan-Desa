@extends('layouts.warga')

@section('title', "Rincian Progress Surat - RT $rt")

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center space-x-3">
            <div class="bg-[#6A3297]/10 p-3 rounded-2xl text-[#6A3297]">
                <span class="material-symbols-outlined text-3xl">mark_email_read</span>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Rincian Progress Surat</h1>
                <p class="text-xs text-slate-500 font-medium">{{ $pengajuan->jenisSurat->nama }}</p>
            </div>
        </div>
        <a href="{{ route('warga.rt.surat.riwayat', ['rt' => $rt]) }}" 
           class="inline-flex items-center space-x-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shadow-xs self-start sm:self-center">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            <span>Kembali ke Riwayat Surat</span>
        </a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-300 text-emerald-900 px-5 py-3.5 rounded-2xl flex items-center gap-3 shadow-xs">
            <span class="material-symbols-outlined text-emerald-600 font-bold">check_circle</span>
            <p class="text-xs sm:text-sm font-bold">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Visual Progress Timeline Header Card -->
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

    <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200/80">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-5 border-b border-slate-100">
            <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Kode Tracking Pengajuan</span>
                <span class="text-lg font-mono font-extrabold text-[#6A3297]">{{ $pengajuan->kode_tracking_val }}</span>
            </div>
            <div>
                @include('partials.surat-status-badge', ['status' => $pengajuan->status])
            </div>
        </div>

        <!-- 4-Step Visual Progress Bar -->
        @if ($pengajuan->status !== 'ditolak')
            <div class="mb-6">
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[#6A3297] text-lg">conversion_path</span>
                    <span>Alur & Status Tahapan Surat</span>
                </h4>
                
                <div class="relative">
                    <div class="overflow-hidden h-2.5 mb-4 text-xs flex rounded-full bg-slate-100">
                        <div style="width: {{ $currentStep == 1 ? '25%' : ($currentStep == 2 ? '50%' : ($currentStep == 3 ? '75%' : '100%')) }}" 
                             class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-[#6A3297] transition-all duration-500 rounded-full"></div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-center">
                        <!-- Step 1 -->
                        <div class="p-3 rounded-xl border {{ $currentStep >= 1 ? 'bg-emerald-50/70 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                            <div class="w-8 h-8 rounded-full mx-auto mb-1.5 flex items-center justify-center font-bold text-xs {{ $currentStep >= 1 ? 'bg-[#6A3297] text-white' : 'bg-slate-200 text-slate-500' }}">
                                1
                            </div>
                            <p class="text-xs font-black">Diajukan</p>
                            <p class="text-[10px] opacity-80">Warga Mengirim</p>
                        </div>

                        <!-- Step 2 -->
                        <div class="p-3 rounded-xl border {{ $currentStep >= 2 ? 'bg-emerald-50/70 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                            <div class="w-8 h-8 rounded-full mx-auto mb-1.5 flex items-center justify-center font-bold text-xs {{ $currentStep >= 2 ? 'bg-[#6A3297] text-white' : 'bg-slate-200 text-slate-500' }}">
                                2
                            </div>
                            <p class="text-xs font-black">Verifikasi Admin</p>
                            <p class="text-[10px] opacity-80">Berkas Diperiksa</p>
                        </div>

                        <!-- Step 3 -->
                        <div class="p-3 rounded-xl border {{ $currentStep >= 3 ? 'bg-emerald-50/70 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                            <div class="w-8 h-8 rounded-full mx-auto mb-1.5 flex items-center justify-center font-bold text-xs {{ $currentStep >= 3 ? 'bg-[#6A3297] text-white' : 'bg-slate-200 text-slate-500' }}">
                                3
                            </div>
                            <p class="text-xs font-black">Approval Kades</p>
                            <p class="text-[10px] opacity-80">Persetujuan & No. Surat</p>
                        </div>

                        <!-- Step 4 -->
                        <div class="p-3 rounded-xl border {{ $currentStep >= 4 ? 'bg-emerald-50/70 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                            <div class="w-8 h-8 rounded-full mx-auto mb-1.5 flex items-center justify-center font-bold text-xs {{ $currentStep >= 4 ? 'bg-[#6A3297] text-white' : 'bg-slate-200 text-slate-500' }}">
                                4
                            </div>
                            <p class="text-xs font-black">Selesai / Cetak</p>
                            <p class="text-[10px] opacity-80">Dokumen Siap</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-4 flex items-start space-x-3 text-xs text-red-900">
                <span class="material-symbols-outlined text-red-600 text-xl mt-0.5">cancel</span>
                <div>
                    <p class="font-bold text-sm text-red-800">Pengajuan Surat Ditolak</p>
                    <p class="mt-0.5">Alasan: {{ $pengajuan->alasan_ditolak }}</p>
                </div>
            </div>
        @endif

        <!-- Letter Info Details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100 text-xs mt-4">
            <div>
                <span class="text-slate-400 font-medium block text-[10px] uppercase">Nomor Surat Resmi</span>
                <span class="font-mono font-bold text-slate-900 text-sm">{{ $pengajuan->nomor_surat ?? 'Menunggu Persetujuan' }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-medium block text-[10px] uppercase">Nama Pemohon</span>
                <span class="font-bold text-slate-900 text-sm">{{ $pengajuan->pemohon_name }} (NIK: {{ $pengajuan->pemohon_nik }})</span>
            </div>
            @if ($pengajuan->keterangan || $pengajuan->keperluan)
                <div class="sm:col-span-2 pt-2 border-t border-slate-200/60">
                    <span class="text-slate-400 font-medium block text-[10px] uppercase">Keperluan Surat</span>
                    <p class="text-slate-800 font-bold leading-relaxed mt-0.5 text-xs">{{ $pengajuan->keterangan ?? $pengajuan->keperluan }}</p>
                </div>
            @endif
        </div>

        <!-- Download Action if Available -->
        @if (in_array($pengajuan->status, ['disetujui_kades', 'menunggu_ttd_fisik', 'selesai']))
            <div class="mt-5 p-4 rounded-2xl bg-amber-50 border border-amber-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center space-x-3">
                    <span class="material-symbols-outlined text-amber-700 text-2xl">picture_as_pdf</span>
                    <div>
                        <p class="text-xs font-bold text-amber-900">Dokumen PDF Surat Keterangan Ready</p>
                        <p class="text-[11px] text-amber-800/90">Klik tombol di samping untuk mengunduh berkas PDF surat resmi.</p>
                    </div>
                </div>
                <a href="{{ route('warga.rt.surat.pdf', ['rt' => $rt, 'kode' => $pengajuan->kode_tracking_val]) }}" target="_blank"
                   class="bg-[#D8B84C] hover:bg-[#c9a73b] text-[#2A3520] font-extrabold px-5 py-2.5 rounded-xl text-xs shadow-sm transition-all flex items-center justify-center space-x-1.5 self-start sm:self-center">
                    <span class="material-symbols-outlined text-lg">download</span>
                    <span>Unduh PDF Surat</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Riwayat Catatan Log Proses -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200/80">
        <h3 class="text-xs uppercase tracking-widest font-black text-slate-800 mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-[#6A3297] text-lg">history</span>
            <span>Riwayat & Catatan Verifikasi Petugas</span>
        </h3>

        <div class="relative pl-6 border-l-2 border-[#6A3297]/30 space-y-6">
            @forelse ($pengajuan->riwayatStatus as $riwayat)
                <div class="relative">
                    <span class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full border-2 border-white bg-[#6A3297] flex items-center justify-center shadow-xs">
                        <span class="material-symbols-outlined text-[10px] text-white font-bold">check</span>
                    </span>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <span class="text-xs font-extrabold text-slate-900 uppercase tracking-wider bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200">
                            {{ str_replace('_', ' ', $riwayat->status) }}
                        </span>
                        <span class="text-[11px] font-mono text-slate-400 font-bold">{{ $riwayat->created_at->format('d/m/Y H:i') }} WIB</span>
                    </div>
                    @if ($riwayat->catatan)
                        <p class="text-xs text-slate-700 font-medium mt-1.5 bg-slate-50 p-2.5 rounded-xl border border-slate-100">{{ $riwayat->catatan }}</p>
                    @endif
                    @if ($riwayat->olehUser)
                        <p class="text-[10px] text-slate-400 font-semibold mt-1">Oleh: {{ $riwayat->olehUser->name }}</p>
                    @endif
                </div>
            @empty
                <div class="text-center py-6 text-slate-400 text-xs font-medium">
                    Belum ada riwayat catatan proses.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

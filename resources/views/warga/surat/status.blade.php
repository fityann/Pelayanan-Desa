@extends('layouts.admin')

@section('title', 'Status Pengajuan Surat - SIPANDA')

@section('content')
<div class="flex flex-col gap-lg max-w-3xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Status Pengajuan</h1>
            <p class="text-body-sm text-on-surface-variant">{{ $pengajuan->jenisSurat->nama }}</p>
        </div>
        <a href="{{ route('warga.surat.riwayat') }}" class="bg-surface-container-lowest px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all flex items-center gap-sm border border-outline-variant">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Kembali
        </a>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
        <div class="flex items-center justify-between mb-lg">
            <div>
                <p class="text-label-sm text-on-surface-variant">Nomor Surat</p>
                <p class="text-title-md font-mono font-bold text-on-surface">{{ $pengajuan->nomor_surat ?? '-' }}</p>
            </div>
            @include('partials.surat-status-badge', ['status' => $pengajuan->status])
        </div>

        @if ($pengajuan->keterangan)
            <div class="bg-surface-container rounded-xl p-md mb-md">
                <p class="text-label-sm font-bold text-on-surface mb-xs">Keperluan</p>
                <p class="text-body-sm text-on-surface-variant">{{ $pengajuan->keterangan }}</p>
            </div>
        @endif

        @if ($pengajuan->alasan_ditolak)
            <div class="bg-error/5 border border-error/20 rounded-xl p-md mb-md flex gap-md">
                <span class="material-symbols-outlined text-error">cancel</span>
                <div>
                    <p class="text-label-sm font-bold text-error mb-xs">Pengajuan Ditolak</p>
                    <p class="text-body-sm text-on-surface-variant">{{ $pengajuan->alasan_ditolak }}</p>
                </div>
            </div>
        @endif

        @if ($pengajuan->status === 'menunggu_ttd_fisik')
            <div class="bg-secondary/10 border border-secondary/20 rounded-xl p-md flex gap-md">
                <span class="material-symbols-outlined text-secondary">print</span>
                <div>
                    <p class="text-label-sm font-bold text-secondary mb-xs">Menunggu Tanda Tangan Kepala Desa</p>
                    <p class="text-body-sm text-on-surface-variant">
                        Draft PDF sudah siap. Silakan unduh, cetak, lalu bawa ke kantor desa untuk ditandatangani Kepala Desa.
                    </p>
                    <a href="{{ route('warga.surat.pdf', $pengajuan) }}" class="inline-flex items-center gap-sm mt-md bg-secondary text-on-secondary px-lg py-2 rounded-full text-label-md font-bold hover:bg-secondary/90 transition-all">
                        <span class="material-symbols-outlined text-[18px]">download</span>
                        Unduh Draft PDF
                    </a>
                </div>
            </div>
        @endif

        @if (in_array($pengajuan->status, ['disetujui_kades', 'selesai']) && !$pengajuan->butuh_ttd_fisik)
            <div class="bg-success/10 border border-success/20 rounded-xl p-md flex gap-md">
                <span class="material-symbols-outlined text-success">task_alt</span>
                <div>
                    <p class="text-label-sm font-bold text-success mb-xs">Surat Selesai</p>
                    <p class="text-body-sm text-on-surface-variant">Surat Anda sudah final dan dapat diunduh langsung.</p>
                    <a href="{{ route('warga.surat.pdf', $pengajuan) }}" class="inline-flex items-center gap-sm mt-md bg-success text-on-success px-lg py-2 rounded-full text-label-md font-bold hover:bg-success/90 transition-all">
                        <span class="material-symbols-outlined text-[18px]">download</span>
                        Unduh PDF Final
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

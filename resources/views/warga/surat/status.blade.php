@extends('layouts.admin')

@section('title', 'Status Pengajuan Surat - SILAPU')

@section('content')
<div class="flex flex-col gap-lg max-w-3xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Status Pengajuan</h1>
            <p class="text-body-sm text-on-surface-variant">{{ $pengajuan->jenisSurat->nama }}</p>
        </div>
        <a href="{{ route('warga.surat.cek') }}" class="bg-surface-container-lowest px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all flex items-center gap-sm border border-outline-variant">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Cek Surat Lain
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
                <p class="text-label-sm text-on-surface-variant">Kode Tracking</p>
                <p class="text-title-md font-mono font-bold text-primary">{{ $pengajuan->kode_tracking }}</p>
                <p class="text-[10px] text-on-surface-variant mt-xs">Simpan kode ini untuk memantau status pengajuan Anda.</p>
            </div>
            @include('partials.surat-status-badge', ['status' => $pengajuan->status])
        </div>

        <div class="mb-lg">
            <p class="text-label-sm text-on-surface-variant">Nomor Surat</p>
            <p class="text-title-md font-mono font-bold text-on-surface">{{ $pengajuan->nomor_surat ?? '-' }}</p>
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
                    <a href="{{ route('warga.surat.pdf', $pengajuan->kode_tracking) }}" class="inline-flex items-center gap-sm mt-md bg-secondary text-on-secondary px-lg py-2 rounded-full text-label-md font-bold hover:bg-secondary/90 transition-all">
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
                    <a href="{{ route('warga.surat.pdf', $pengajuan->kode_tracking) }}" class="inline-flex items-center gap-sm mt-md bg-success text-on-success px-lg py-2 rounded-full text-label-md font-bold hover:bg-success/90 transition-all">
                        <span class="material-symbols-outlined text-[18px]">download</span>
                        Unduh PDF Final
                    </a>
                </div>
            </div>
        @endif
    </div>

    {{-- Timeline riwayat status --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
        <h3 class="text-label-md text-on-surface uppercase tracking-widest font-bold mb-lg">Riwayat Proses</h3>
        <div class="relative pl-lg border-l-2 border-surface-variant/30 space-y-lg">
            @forelse ($pengajuan->riwayatStatus as $riwayat)
                <div class="relative">
                    <span class="absolute -left-[27px] top-1 w-4 h-4 rounded-full border-2 border-surface-container-lowest bg-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[10px] text-on-primary">check</span>
                    </span>
                    <div class="flex items-center justify-between gap-sm">
                        <p class="text-body-md font-bold text-on-surface capitalize">{{ str_replace('_', ' ', $riwayat->status) }}</p>
                        <span class="text-label-sm text-on-surface-variant">{{ $riwayat->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if ($riwayat->catatan)
                        <p class="text-body-sm text-on-surface-variant mt-xs">{{ $riwayat->catatan }}</p>
                    @endif
                    @if ($riwayat->olehUser)
                        <p class="text-[10px] text-on-surface-variant/60 mt-xs">oleh {{ $riwayat->olehUser->name }}</p>
                    @endif
                </div>
            @empty
                <p class="text-body-sm text-on-surface-variant">Belum ada riwayat.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Cek Status Surat - SILAPU')

@section('content')
<div class="flex flex-col gap-lg max-w-2xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Cek Status Pengajuan Surat</h1>
            <p class="text-body-sm text-on-surface-variant">Masukkan kode tracking yang Anda terima saat mengajukan surat.</p>
        </div>
        <a href="{{ route('warga.surat.index') }}" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Ajukan Surat
        </a>
    </div>

    <form method="GET" action="{{ route('warga.surat.cek') }}" class="bg-surface-container-lowest rounded-xl shadow-sm p-lg space-y-md">
        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Kode Tracking</label>
            <div class="flex gap-md">
                <input type="text" name="kode" value="{{ $kode }}" required class="flex-1 bg-surface-container rounded-xl px-lg py-3 text-body-md font-mono uppercase outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Contoh: SRT-04082026-XXXX">
                <button type="submit" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all flex items-center gap-sm">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                    Cari
                </button>
            </div>
        </div>
    </form>

    @if ($kode)
        @if ($pengajuan)
            <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg border-l-4 border-success">
                <div class="flex items-center justify-between mb-md">
                    <div>
                        <p class="text-label-sm text-on-surface-variant">Jenis Surat</p>
                        <p class="text-title-md font-bold text-on-surface">{{ $pengajuan->jenisSurat->nama }}</p>
                    </div>
                    @include('partials.surat-status-badge', ['status' => $pengajuan->status])
                </div>
                <div class="grid grid-cols-2 gap-md mb-md">
                    <div>
                        <p class="text-label-sm text-on-surface-variant">Nomor Surat</p>
                        <p class="text-body-md font-mono text-on-surface">{{ $pengajuan->nomor_surat ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant">Nama Pemohon</p>
                        <p class="text-body-md text-on-surface">{{ $pengajuan->pemohon_name }}</p>
                    </div>
                </div>
                <a href="{{ route('warga.surat.status', $pengajuan->kode_tracking) }}" class="inline-flex items-center gap-sm bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all">
                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                    Lihat Detail Status
                </a>
            </div>
        @else
            <div class="bg-error/5 border border-error/20 rounded-xl p-md flex gap-md items-start">
                <span class="material-symbols-outlined text-error">search_off</span>
                <div>
                    <p class="text-label-sm font-bold text-error mb-xs">Kode Tidak Ditemukan</p>
                    <p class="text-body-sm text-on-surface-variant">Pastikan kode tracking benar, atau hubungi kantor desa jika mengalami kendala.</p>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
@extends('layouts.admin')

@section('title', 'Detail Usulan Musrenbang - SILAPU')

@section('content')
<div class="flex flex-col gap-lg max-w-4xl">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-md">
            <a href="{{ route('admin.musrenbang.index') }}" class="bg-surface-container-lowest p-2 rounded-lg hover:bg-surface-container transition-all flex items-center justify-center">
                <span class="material-symbols-outlined text-on-surface-variant">arrow_back</span>
            </a>
            <div>
                <h1 class="text-headline-md font-bold text-on-surface">Detail Musrenbang</h1>
                <p class="text-body-sm text-on-surface-variant">{{ $musrenbang->judul_kegiatan }}</p>
            </div>
        </div>
        @php
            $statusClass = match($musrenbang->status_usulan) {
                'disetujui' => 'bg-success/10 text-success',
                'ditolak' => 'bg-error/10 text-error',
                'diverifikasi', 'direview' => 'bg-secondary/10 text-secondary',
                default => 'bg-on-tertiary-container/10 text-on-tertiary-container',
            };
        @endphp
        <span class="px-3 py-1 rounded-full text-label-sm font-bold capitalize {{ $statusClass }}">{{ str_replace('_', ' ', $musrenbang->status_usulan) }}</span>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <!-- Info Kegiatan -->
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg space-y-md">
        <h3 class="text-label-md text-on-surface uppercase tracking-widest font-bold">Informasi Kegiatan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
            <div>
                <p class="text-label-sm text-on-surface-variant">Tahun</p>
                <p class="text-body-md font-semibold text-on-surface">{{ $musrenbang->tahun }}</p>
            </div>
            <div>
                <p class="text-label-sm text-on-surface-variant">Jenis Kegiatan</p>
                <p class="text-body-md font-semibold text-on-surface capitalize">{{ $musrenbang->jenis_kegiatan }}</p>
            </div>
            <div>
                <p class="text-label-sm text-on-surface-variant">Sumber Dana</p>
                <p class="text-body-md font-semibold text-on-surface">{{ $musrenbang->sumber_dana }}</p>
            </div>
            <div>
                <p class="text-label-sm text-on-surface-variant">Prioritas</p>
                <p class="text-body-md font-semibold text-on-surface capitalize">{{ str_replace('_', ' ', $musrenbang->prioritas) }}</p>
            </div>
            <div>
                <p class="text-label-sm text-on-surface-variant">Pengusul</p>
                <p class="text-body-md font-semibold text-on-surface">{{ $musrenbang->pengusul?->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-label-sm text-on-surface-variant">Estimasi Biaya</p>
                <p class="text-body-md font-semibold text-on-surface">Rp {{ number_format($musrenbang->estimasi_biaya, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-label-sm text-on-surface-variant">Jadwal Musrenbang</p>
                <p class="text-body-md font-semibold text-on-surface">{{ $musrenbang->tanggal_musrenbang ? $musrenbang->tanggal_musrenbang->translatedFormat('d F Y') : '-' }}</p>
            </div>
        </div>

        <div>
            <p class="text-label-sm text-on-surface-variant">Deskripsi</p>
            <p class="text-body-md text-on-surface">{{ $musrenbang->deskripsi_kegiatan }}</p>
        </div>

        @if ($musrenbang->alokasi_anggaran)
            <div class="bg-success/5 border border-success/20 rounded-xl p-md">
                <p class="text-label-sm font-bold text-success mb-xs">Usulan Disetujui</p>
                <p class="text-body-md text-on-surface">Alokasi anggaran: <strong>Rp {{ number_format($musrenbang->alokasi_anggaran, 0, ',', '.') }}</strong></p>
            </div>
        @endif

        @if ($musrenbang->catatan_review)
            <div class="bg-surface-container rounded-xl p-md">
                <p class="text-label-sm font-bold text-on-surface mb-xs">Catatan Review</p>
                <p class="text-body-sm text-on-surface-variant">{{ $musrenbang->catatan_review }}</p>
            </div>
        @endif
    </div>

    <!-- Aksi Alur -->
    @if (auth()->user()->hasPermissionTo('U APBDes'))
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
        <h3 class="text-label-md text-on-surface uppercase tracking-widest font-bold mb-md">Proses Usulan</h3>
        <div class="flex flex-wrap gap-sm">
            @if ($musrenbang->status_usulan === 'diusulkan')
                <form method="POST" action="{{ route('admin.musrenbang.verify', $musrenbang) }}">
                    @csrf
                    <button type="submit" class="bg-secondary text-on-secondary px-lg py-2 rounded-full text-label-md font-bold hover:bg-secondary/90 transition-all">Verifikasi</button>
                </form>
            @endif

            @if ($musrenbang->status_usulan === 'diverifikasi')
                <form method="POST" action="{{ route('admin.musrenbang.review', $musrenbang) }}" class="flex flex-wrap gap-sm items-center">
                    @csrf
                    <select name="hasil_musrenbang" required class="bg-surface-container rounded-lg px-md py-2 text-body-sm border border-outline-variant">
                        <option value="layak">Layak</option>
                        <option value="revisi">Perlu Revisi</option>
                        <option value="ditunda">Ditunda</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                    <input type="text" name="catatan_review" placeholder="Catatan review" class="bg-surface-container rounded-lg px-md py-2 text-body-sm border border-outline-variant">
                    <button type="submit" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all">Simpan Review</button>
                </form>
            @endif

            @if ($musrenbang->hasil_musrenbang === 'layak' && $musrenbang->status_usulan === 'direview')
                <form method="POST" action="{{ route('admin.musrenbang.approve', $musrenbang) }}" class="flex flex-wrap gap-sm items-center">
                    @csrf
                    <input type="number" name="alokasi_anggaran" placeholder="Alokasi anggaran" min="0" step="0.01" required class="bg-surface-container rounded-lg px-md py-2 text-body-sm border border-outline-variant">
                    <button type="submit" class="bg-success text-on-success px-lg py-2 rounded-full text-label-md font-bold hover:bg-success/90 transition-all">Setujui</button>
                </form>
            @endif
        </div>
    </div>
    @endif

    <!-- Suara -->
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
        <h3 class="text-label-md text-on-surface uppercase tracking-widest font-bold mb-md">Dukungan Warga</h3>
        <div class="grid grid-cols-3 gap-md mb-md text-center">
            <div class="bg-success/5 rounded-xl p-md">
                <p class="font-headline-lg text-success">{{ $musrenbang->total_dukungan }}</p>
                <p class="text-label-sm text-on-surface-variant">Dukungan</p>
            </div>
            <div class="bg-error/5 rounded-xl p-md">
                <p class="font-headline-lg text-error">{{ $musrenbang->total_penolakan }}</p>
                <p class="text-label-sm text-on-surface-variant">Penolakan</p>
            </div>
            <div class="bg-surface-container rounded-xl p-md">
                <p class="font-headline-lg text-on-surface">{{ number_format($musrenbang->persentase_dukungan, 1) }}%</p>
                <p class="text-label-sm text-on-surface-variant">Persentase</p>
            </div>
        </div>

        @if ($musrenbang->suara->isNotEmpty())
            <div class="space-y-sm">
                @foreach ($musrenbang->suara as $vote)
                    <div class="flex items-center justify-between p-md bg-surface-container rounded-lg">
                        <div>
                            <p class="text-body-sm font-semibold text-on-surface">{{ $vote->user?->name ?? '-' }}</p>
                            <p class="text-[11px] text-on-surface-variant">{{ $vote->alasan }}</p>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold capitalize {{ $vote->tipe_suara === 'dukung' ? 'bg-success/10 text-success' : ($vote->tipe_suara === 'tolak' ? 'bg-error/10 text-error' : 'bg-surface-variant/30 text-on-surface-variant') }}">{{ $vote->tipe_suara }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-body-sm text-on-surface-variant">Belum ada suara warga.</p>
        @endif
    </div>

    <!-- Dokumen -->
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
        <h3 class="text-label-md text-on-surface uppercase tracking-widest font-bold mb-md">Dokumen</h3>
        @if ($musrenbang->dokumen->isNotEmpty())
            <div class="space-y-sm">
                @foreach ($musrenbang->dokumen as $doc)
                    <div class="flex items-center justify-between p-md bg-surface-container rounded-lg">
                        <p class="text-body-sm text-on-surface">{{ $doc->nama_dokumen }}</p>
                        <a href="{{ Storage::url($doc->path_dokumen) }}" target="_blank" class="text-primary text-label-sm font-semibold flex items-center gap-xs">
                            Unduh <span class="material-symbols-outlined text-[16px]">download</span>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-body-sm text-on-surface-variant">Tidak ada dokumen terlampir.</p>
        @endif
    </div>
</div>
@endsection
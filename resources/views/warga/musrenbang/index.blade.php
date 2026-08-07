@extends('layouts.admin')

@section('title', 'Usulan Kegiatan (Musrenbang) - Warga')

@section('content')
<div class="flex flex-col gap-lg">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-md">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Usulan Kegiatan Desa</h1>
            <p class="text-body-sm text-on-surface-variant">Daftar usulan kegiatan (Musrenbang) dan aspirasi warga</p>
        </div>
        
        <form action="{{ route('warga.musrenbang.index') }}" method="GET" class="flex items-center gap-sm">
            <select name="tahun" class="bg-surface-container rounded-lg border-outline-variant px-md py-2 text-label-md" onchange="this.form.submit()">
                <option value="">Semua Tahun</option>
                @for($i = date('Y') + 1; $i >= date('Y') - 2; $i--)
                    <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </form>
    </div>

    @if ($musrenbangs->isEmpty())
        <div class="bg-surface-container-lowest rounded-xl p-xl text-center text-on-surface-variant">
            <span class="material-symbols-outlined text-[40px] block mb-md">inbox</span>
            <p>Belum ada usulan kegiatan untuk periode ini.</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg">
        @foreach ($musrenbangs as $musrenbang)
            <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg hover:shadow-md hover:-translate-y-0.5 transition-all border border-outline-variant/10 flex flex-col">
                <div class="flex items-start justify-between mb-md">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">architecture</span>
                    </div>
                    @php
                        $statusColors = [
                            'diusulkan' => 'bg-surface-variant text-on-surface-variant',
                            'diverifikasi' => 'bg-secondary-container text-on-secondary-container',
                            'direview' => 'bg-tertiary-container text-on-tertiary-container',
                            'disetujui' => 'bg-success/20 text-success',
                            'ditolak' => 'bg-error/20 text-error',
                        ];
                        $badgeClass = $statusColors[$musrenbang->status_usulan] ?? 'bg-surface-variant text-on-surface-variant';
                    @endphp
                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                        {{ $musrenbang->status_usulan }}
                    </span>
                </div>
                
                <div class="mb-2">
                    <span class="text-label-sm text-primary font-bold">{{ $musrenbang->tahun }}</span>
                    <h3 class="text-title-md font-bold text-on-surface mt-1 line-clamp-2">{{ $musrenbang->judul_kegiatan }}</h3>
                </div>
                
                <p class="text-body-sm text-on-surface-variant mb-md line-clamp-3 flex-1">{{ $musrenbang->deskripsi_kegiatan }}</p>
                
                <div class="bg-surface-container rounded-lg p-md mb-md">
                    <div class="flex items-center gap-xs text-on-surface-variant text-body-sm mb-1">
                        <span class="material-symbols-outlined text-[16px]">location_on</span>
                        <span>{{ $musrenbang->wilayah ? $musrenbang->wilayah->nama : 'Umum (Tingkat Desa)' }}</span>
                    </div>
                    <div class="flex items-center gap-xs text-on-surface-variant text-body-sm mb-1">
                        <span class="material-symbols-outlined text-[16px]">payments</span>
                        <span>Rp {{ number_format($musrenbang->estimasi_biaya, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center gap-xs text-on-surface-variant text-body-sm">
                        <span class="material-symbols-outlined text-[16px]">group</span>
                        <span>{{ $musrenbang->jumlah_pendukung }} Dukungan</span>
                    </div>
                </div>
                
                <a href="{{ route('warga.musrenbang.show', $musrenbang) }}" class="mt-auto bg-primary/10 text-primary text-center px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/20 transition-all flex items-center justify-center gap-sm">
                    Lihat Detail & Voting
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
            </div>
        @endforeach
    </div>
    
    <div class="mt-lg">
        {{ $musrenbangs->links() }}
    </div>
</div>
@endsection

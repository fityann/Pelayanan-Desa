@extends('layouts.admin')

@section('title', 'Detail Penduduk - SILAPU')

@section('content')
<div class="flex flex-col gap-lg max-w-5xl mx-auto">
    <div class="flex items-center gap-md">
        <a href="{{ route('admin.penduduk.index') }}" class="w-10 h-10 rounded-full bg-surface-container hover:bg-surface-container-high flex items-center justify-center text-on-surface-variant transition-colors">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Detail Penduduk</h1>
            <p class="text-body-sm text-on-surface-variant">Profil dan informasi lengkap warga</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
        <!-- Kolom Kiri: Profil Singkat -->
        <div class="lg:col-span-1 space-y-lg">
            <div class="bg-surface-container-lowest rounded-3xl shadow-sm border border-outline-variant/20 p-xl flex flex-col items-center text-center">
                <div class="w-24 h-24 rounded-full bg-primary/10 text-primary flex items-center justify-center mb-md border-4 border-primary/20">
                    <span class="material-symbols-outlined text-[48px]">{{ $penduduk->jenis_kelamin === 'P' ? 'face_4' : 'face' }}</span>
                </div>
                <h2 class="text-title-lg font-bold text-on-surface mb-xs">{{ $penduduk->nama }}</h2>
                <p class="text-body-sm text-on-surface-variant mb-md">{{ $penduduk->pekerjaan ?? 'Belum ada pekerjaan' }}</p>
                <div class="font-mono bg-surface-container px-lg py-2 rounded-xl text-label-md font-bold text-primary tracking-widest w-full">
                    {{ $penduduk->nik }}
                </div>
                
                <div class="w-full mt-lg pt-lg border-t border-outline-variant/20 flex flex-col gap-sm text-left">
                    <div class="flex items-center gap-sm">
                        <span class="material-symbols-outlined text-on-surface-variant text-[18px]">wc</span>
                        <span class="text-label-sm font-medium text-on-surface">{{ $penduduk->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                    <div class="flex items-center gap-sm">
                        <span class="material-symbols-outlined text-on-surface-variant text-[18px]">cake</span>
                        <span class="text-label-sm font-medium text-on-surface">{{ $penduduk->tempat_lahir }}, {{ $penduduk->tanggal_lahir ? \Carbon\Carbon::parse($penduduk->tanggal_lahir)->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="flex items-center gap-sm">
                        <span class="material-symbols-outlined text-on-surface-variant text-[18px]">home_pin</span>
                        <span class="text-label-sm font-medium text-on-surface">RT {{ $penduduk->rt }} / RW {{ $penduduk->rw ?? '01' }}</span>
                    </div>
                </div>
            </div>
            
            @if($penduduk->user)
            <div class="bg-primary/5 rounded-3xl border border-primary/20 p-lg">
                <div class="flex items-center gap-sm mb-md">
                    <span class="material-symbols-outlined text-primary text-[24px]">verified_user</span>
                    <h3 class="text-title-sm font-bold text-primary">Akun Digital Warga</h3>
                </div>
                <div class="space-y-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-label-sm text-on-surface-variant">Email</span>
                        <span class="text-label-sm font-bold text-on-surface">{{ $penduduk->user->email }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-label-sm text-on-surface-variant">Terdaftar</span>
                        <span class="text-label-sm font-bold text-on-surface">{{ $penduduk->user->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Kolom Kanan: Detail Informasi Lengkap -->
        <div class="lg:col-span-2 space-y-lg">
            <!-- Informasi Personal -->
            <div class="bg-surface-container-lowest rounded-3xl shadow-sm border border-outline-variant/20 p-xl">
                <div class="flex items-center gap-sm mb-lg pb-sm border-b border-outline-variant/20">
                    <span class="material-symbols-outlined text-primary text-[24px]">person</span>
                    <h3 class="text-title-md font-bold text-on-surface">Informasi Personal</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-md gap-x-xl">
                    <div>
                        <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Agama</p>
                        <p class="text-body-md font-semibold text-on-surface">{{ $penduduk->agama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Status Perkawinan</p>
                        <p class="text-body-md font-semibold text-on-surface">{{ $penduduk->status_perkawinan ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Pendidikan Terakhir</p>
                        <p class="text-body-md font-semibold text-on-surface">{{ $penduduk->pendidikan_terakhir ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Kewarganegaraan</p>
                        <p class="text-body-md font-semibold text-on-surface">{{ $penduduk->kewarganegaraan ?? 'WNI' }}</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Kontak & Alamat -->
            <div class="bg-surface-container-lowest rounded-3xl shadow-sm border border-outline-variant/20 p-xl">
                <div class="flex items-center gap-sm mb-lg pb-sm border-b border-outline-variant/20">
                    <span class="material-symbols-outlined text-primary text-[24px]">location_on</span>
                    <h3 class="text-title-md font-bold text-on-surface">Informasi Alamat</h3>
                </div>
                
                <div class="space-y-md">
                    <div>
                        <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Alamat Lengkap</p>
                        <p class="text-body-md font-semibold text-on-surface">{{ $penduduk->alamat ?? '-' }}</p>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-md">
                        <div>
                            <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">RT</p>
                            <p class="text-body-md font-semibold text-on-surface">{{ $penduduk->rt }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">RW</p>
                            <p class="text-body-md font-semibold text-on-surface">{{ $penduduk->rw ?? '01' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Desa/Kelurahan</p>
                            <p class="text-body-md font-semibold text-on-surface">Puspamukti</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Kecamatan</p>
                            <p class="text-body-md font-semibold text-on-surface">Cigalontang</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Informasi Keluarga -->
            <div class="bg-surface-container-lowest rounded-3xl shadow-sm border border-outline-variant/20 p-xl">
                <div class="flex items-center justify-between mb-lg pb-sm border-b border-outline-variant/20">
                    <div class="flex items-center gap-sm">
                        <span class="material-symbols-outlined text-primary text-[24px]">family_restroom</span>
                        <h3 class="text-title-md font-bold text-on-surface">Informasi Keluarga</h3>
                    </div>
                    @if($penduduk->keluarga)
                        <a href="{{ route('admin.keluarga.index') }}?search={{ $penduduk->keluarga->no_kk }}" class="text-primary hover:text-primary-dark font-bold text-label-sm inline-flex items-center gap-1">
                            Lihat Kartu Keluarga <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                        </a>
                    @endif
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-md gap-x-xl">
                    <div>
                        <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Nomor KK</p>
                        <p class="font-mono font-bold text-on-surface bg-surface-container px-3 py-1 rounded inline-block">{{ $penduduk->no_kk ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Status Hubungan Dalam Keluarga</p>
                        <p class="text-body-md font-bold text-on-surface">{{ $penduduk->hubungan_keluarga ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

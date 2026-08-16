@extends('layouts.admin')

@section('title', 'Detail Keluarga - SILAPU')

@section('content')
<div class="flex flex-col gap-lg max-w-5xl mx-auto">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-md">
            <a href="{{ route('admin.keluarga.index') }}" class="w-10 h-10 rounded-full bg-surface-container hover:bg-surface-container-high flex items-center justify-center text-on-surface-variant transition-colors">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-headline-md font-bold text-on-surface">Detail Kartu Keluarga</h1>
                <p class="text-body-sm text-on-surface-variant">Informasi lengkap data keluarga dan anggotanya</p>
            </div>
        </div>
        <button class="bg-primary/10 text-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/20 transition-all flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">print</span>
            <span>Cetak Data</span>
        </button>
    </div>

    <!-- Info Utama KK -->
    <div class="bg-surface-container-lowest rounded-3xl shadow-sm border border-outline-variant/20 p-xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-lg mb-lg border-b border-outline-variant/20 pb-lg">
            <div class="flex items-center gap-md">
                <div class="w-16 h-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center border-2 border-primary/20">
                    <span class="material-symbols-outlined text-[32px]">family_restroom</span>
                </div>
                <div>
                    <h2 class="text-title-lg font-bold text-on-surface mb-1">{{ $keluarga->kepala_keluarga }}</h2>
                    <p class="text-body-sm text-on-surface-variant">Kepala Keluarga</p>
                </div>
            </div>
            <div class="bg-surface-container px-lg py-md rounded-2xl text-center md:text-right">
                <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Nomor Kartu Keluarga</p>
                <p class="font-mono text-title-md font-bold text-primary tracking-widest">{{ $keluarga->no_kk }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
            <div class="bg-surface-container/30 p-md rounded-xl">
                <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Alamat</p>
                <p class="text-body-sm font-semibold text-on-surface">{{ $keluarga->alamat ?? '-' }}</p>
            </div>
            <div class="bg-surface-container/30 p-md rounded-xl">
                <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">RT / RW</p>
                <p class="text-body-sm font-semibold text-on-surface">RT {{ $keluarga->rt }} / RW {{ $keluarga->rw ?? '01' }}</p>
            </div>
            <div class="bg-surface-container/30 p-md rounded-xl">
                <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Desa / Kelurahan</p>
                <p class="text-body-sm font-semibold text-on-surface">{{ $keluarga->desa ?? 'Puspamukti' }}</p>
            </div>
            <div class="bg-surface-container/30 p-md rounded-xl">
                <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Kecamatan</p>
                <p class="text-body-sm font-semibold text-on-surface">{{ $keluarga->kecamatan ?? 'Cigalontang' }}</p>
            </div>
        </div>
    </div>

    <!-- Daftar Anggota Keluarga -->
    <div class="bg-surface-container-lowest rounded-3xl shadow-sm border border-outline-variant/20 overflow-hidden">
        <div class="p-lg border-b border-outline-variant/20 flex items-center justify-between bg-surface-container/30">
            <div class="flex items-center gap-sm">
                <span class="material-symbols-outlined text-primary">groups</span>
                <h3 class="text-title-md font-bold text-on-surface">Daftar Anggota Keluarga</h3>
            </div>
            <div class="bg-primary text-on-primary px-3 py-1 rounded-full text-label-sm font-bold">
                {{ $keluarga->penduduk->count() }} Orang
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-container/50">
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest w-12">No</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">NIK</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Nama Lengkap</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Hubungan</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">L/P</th>
                        <th class="text-center px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant/20">
                    @forelse ($keluarga->penduduk as $index => $anggota)
                        <tr class="hover:bg-surface-container/30 transition-colors">
                            <td class="px-lg py-4 text-body-sm text-on-surface-variant font-medium">{{ $index + 1 }}</td>
                            <td class="px-lg py-4 text-body-sm font-mono text-on-surface">{{ $anggota->nik }}</td>
                            <td class="px-lg py-4 text-body-md font-semibold text-on-surface flex items-center gap-2">
                                {{ $anggota->nama }}
                                @if($anggota->nama == $keluarga->kepala_keluarga || $anggota->hubungan_keluarga == 'Kepala Keluarga')
                                    <span class="bg-amber-100 text-amber-700 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">Kepala</span>
                                @endif
                            </td>
                            <td class="px-lg py-4 text-body-sm text-on-surface-variant font-medium">{{ $anggota->hubungan_keluarga ?? '-' }}</td>
                            <td class="px-lg py-4 text-body-sm text-on-surface-variant">{{ $anggota->jenis_kelamin ?? '-' }}</td>
                            <td class="px-lg py-4 text-center">
                                <a href="{{ route('admin.penduduk.show', $anggota) }}" class="inline-flex items-center gap-1 bg-blue-500/10 text-blue-700 hover:bg-blue-500 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                                    <span class="material-symbols-outlined text-[15px]">person</span>
                                    <span>Profil</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-lg py-8 text-center text-on-surface-variant">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-4xl opacity-50">person_off</span>
                                    <p class="text-body-sm font-medium">Belum ada data anggota keluarga (Penduduk) yang dihubungkan dengan Nomor KK ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

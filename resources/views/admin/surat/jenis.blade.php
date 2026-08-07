@extends('layouts.admin')

@section('title', 'Jenis Surat - SILAPU')

@section('content')
<div class="flex flex-col gap-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Jenis Surat</h1>
            <p class="text-body-sm text-on-surface-variant">Kelola jenis surat desa</p>
        </div>
        <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Tambah Jenis Surat
        </button>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg">
        @foreach ($jenisSurat as $jenis)
            <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg hover:shadow-md transition-all {{ $jenis->aktif ? '' : 'opacity-60' }}">
                <div class="flex items-start justify-between mb-md">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">description</span>
                    </div>
                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $jenis->aktif ? 'bg-success/10 text-success' : 'bg-surface-variant/30 text-on-surface-variant' }}">
                        {{ $jenis->aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                <h3 class="text-title-md font-bold text-on-surface mb-1">{{ $jenis->nama }}</h3>
                <p class="text-label-sm text-on-surface-variant mb-md">Kode: <span class="font-mono font-bold">{{ $jenis->kode }}</span></p>
                @if ($jenis->deskripsi)
                    <p class="text-body-sm text-on-surface-variant mb-md line-clamp-2">{{ $jenis->deskripsi }}</p>
                @endif
                @if ($jenis->syarat)
                    <div class="bg-surface-container rounded-lg p-md mb-md">
                        <p class="text-label-sm font-bold text-on-surface mb-xs">Syarat:</p>
                        <p class="text-body-sm text-on-surface-variant">{{ $jenis->syarat }}</p>
                    </div>
                @endif
                @if ($jenis->masa_berlaku)
                    <p class="text-label-sm text-on-surface-variant">Masa berlaku: {{ $jenis->masa_berlaku }} hari</p>
                @endif
                <p class="text-label-sm {{ $jenis->butuh_ttd_fisik ? 'text-on-surface-variant' : 'text-success' }} mt-xs">
                    {{ $jenis->butuh_ttd_fisik ? 'Butuh TTD fisik Kepala Desa' : 'Tanpa TTD fisik (langsung final)' }}
                </p>
            </div>
        @endforeach
    </div>
</div>

<div id="modalTambah" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center hidden">
    <div class="bg-surface-container-lowest rounded-2xl shadow-2xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-lg border-b border-surface-variant/30 flex items-center justify-between">
            <h2 class="text-title-md font-bold text-on-surface">Tambah Jenis Surat</h2>
            <button onclick="document.getElementById('modalTambah').classList.add('hidden')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.surat.jenis.store') }}" class="p-lg space-y-lg">
            @csrf
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Kode Surat</label>
                <input type="text" name="kode" required maxlength="20" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Contoh: SKDU">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Nama Surat</label>
                <input type="text" name="nama" required maxlength="200" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Surat Keterangan Domisili Usaha">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Deskripsi</label>
                <textarea name="deskripsi" rows="2" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant"></textarea>
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Syarat & Dokumen Pendukung</label>
                <textarea name="syarat" rows="3" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Fotokopi KK, Fotokopi KTP, Pas Foto"></textarea>
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Masa Berlaku (hari)</label>
                <input type="number" name="masa_berlaku" min="1" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Kosongkan jika tidak terbatas">
            </div>
            <div class="flex items-center gap-md">
                <input type="checkbox" name="butuh_ttd_fisik" id="butuh_ttd_fisik" value="1" checked class="w-4 h-4 accent-[#51007a]">
                <label for="butuh_ttd_fisik" class="text-label-sm font-bold text-on-surface">Butuh tanda tangan fisik Kepala Desa</label>
            </div>
            <div class="flex gap-md justify-end pt-md border-t border-surface-variant/30">
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all">Batal</button>
                <button type="submit" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

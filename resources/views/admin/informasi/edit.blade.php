@extends('layouts.admin')

@section('title', 'Edit Informasi - SILAPU')

@section('content')
<div class="flex flex-col gap-lg max-w-2xl">
    <div>
        <h1 class="text-headline-md font-bold text-on-surface">Edit Informasi</h1>
        <p class="text-body-sm text-on-surface-variant">Perbarui berita, pengumuman, atau agenda desa</p>
    </div>

    @if ($errors->any())
        <div class="bg-error/10 border border-error/20 text-error px-lg py-3 rounded-xl">
            <p class="text-label-md font-bold mb-xs">Periksa kembali data berikut:</p>
            <ul class="list-disc list-inside text-body-sm space-y-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.informasi.update', $informasi) }}" enctype="multipart/form-data" class="bg-surface-container-lowest rounded-xl shadow-sm p-lg space-y-lg">
        @csrf @method('PATCH')
        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Kategori</label>
            <select name="kategori" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                <option value="berita" {{ old('kategori', $informasi->kategori) === 'berita' ? 'selected' : '' }}>Berita</option>
                <option value="pengumuman" {{ old('kategori', $informasi->kategori) === 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                <option value="agenda" {{ old('kategori', $informasi->kategori) === 'agenda' ? 'selected' : '' }}>Agenda</option>
            </select>
            @error('kategori') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Judul</label>
            <input type="text" name="judul" value="{{ old('judul', $informasi->judul) }}" required maxlength="200" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            @error('judul') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Isi</label>
            <textarea name="isi" rows="8" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">{{ old('isi', $informasi->isi) }}</textarea>
            @error('isi') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
        </div>
        @if ($informasi->gambar)
            <div class="flex items-center gap-md">
                <img src="{{ Storage::url($informasi->gambar) }}" class="w-20 h-20 rounded-lg object-cover">
                <span class="text-label-sm text-on-surface-variant">Gambar saat ini</span>
            </div>
        @endif
        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Gambar (biarkan kosong jika tidak diganti, maks. 5MB)</label>
            <input type="file" name="gambar" accept="image/*" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none border border-outline-variant file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-label-sm file:font-bold file:bg-primary/10 file:text-primary">
            @error('gambar') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
        </div>
        <div class="grid grid-cols-2 gap-lg">
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Tanggal Kegiatan</label>
                <input type="date" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan', $informasi->tanggal_kegiatan?->format('Y-m-d')) }}" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                @error('tanggal_kegiatan') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Lokasi</label>
                <input type="text" name="lokasi" value="{{ old('lokasi', $informasi->lokasi) }}" maxlength="255" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                @error('lokasi') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
        </div>
        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Target Wilayah RT (opsional)</label>
            <div>
                <select name="rt" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    <option value="">Seluruh RT (Seluruh Desa)</option>
                    @for ($i = 1; $i <= 19; $i++)
                        <option value="{{ str_pad((string) $i, 2, '0', STR_PAD_LEFT) }}" {{ old('rt', $informasi->rt) == str_pad((string) $i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>RT {{ str_pad((string) $i, 2, '0', STR_PAD_LEFT) }}</option>
                    @endfor
                </select>
                <input type="hidden" name="rw" value="01">
                @error('rt') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
            <p class="text-[10px] text-on-surface-variant mt-xs">Kosongkan jika informasi berlaku untuk seluruh desa. Pilih RT jika hanya untuk wilayah tertentu.</p>
        </div>
        <div class="flex items-center gap-md">
            <input type="checkbox" name="publish" id="publish" value="1" {{ old('publish', $informasi->published) ? 'checked' : '' }} class="rounded border-outline-variant text-primary focus:ring-primary">
            <label for="publish" class="text-body-md text-on-surface">Publikasikan</label>
        </div>
        <div class="flex gap-md justify-end pt-md border-t border-surface-variant/30">
            <a href="{{ route('admin.informasi.index') }}" class="px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all">Batal</a>
            <button type="submit" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all">Simpan</button>
        </div>
    </form>
</div>
@endsection

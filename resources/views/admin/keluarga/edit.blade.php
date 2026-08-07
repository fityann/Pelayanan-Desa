@extends('layouts.admin')

@section('title', 'Edit Keluarga - SILAPU')

@section('content')
<div class="flex flex-col gap-lg max-w-2xl">
    <div>
        <h1 class="text-headline-md font-bold text-on-surface">Edit Data Keluarga: {{ $keluarga->no_kk }}</h1>
        <p class="text-body-sm text-on-surface-variant">Perbarui data keluarga</p>
    </div>

    @if ($errors->any())
        <div class="bg-error/10 border border-error/20 text-error px-lg py-3 rounded-xl">
            <ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.keluarga.update', $keluarga) }}" class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
        @csrf @method('PATCH')
        <div class="space-y-lg">
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">No. KK (16 digit)</label>
                <input type="text" name="no_kk" value="{{ old('no_kk', $keluarga->no_kk) }}" required maxlength="16" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Kepala Keluarga</label>
                <input type="text" name="kepala_keluarga" value="{{ old('kepala_keluarga', $keluarga->kepala_keluarga) }}" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Alamat</label>
                <textarea name="alamat" rows="2" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">{{ old('alamat', $keluarga->alamat) }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-lg">
                <div>
                    <label class="text-label-sm font-bold text-on-surface block mb-xs">RT</label>
                    <input type="text" name="rt" value="{{ old('rt', $keluarga->rt) }}" maxlength="3" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                </div>
                <div>
                    <label class="text-label-sm font-bold text-on-surface block mb-xs">RW</label>
                    <input type="text" name="rw" value="{{ old('rw', $keluarga->rw) }}" maxlength="3" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-lg">
                <div>
                    <label class="text-label-sm font-bold text-on-surface block mb-xs">Desa</label>
                    <input type="text" name="desa" value="{{ old('desa', $keluarga->desa) }}" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                </div>
                <div>
                    <label class="text-label-sm font-bold text-on-surface block mb-xs">Kecamatan</label>
                    <input type="text" name="kecamatan" value="{{ old('kecamatan', $keluarga->kecamatan) }}" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                </div>
                <div>
                    <label class="text-label-sm font-bold text-on-surface block mb-xs">Kabupaten</label>
                    <input type="text" name="kabupaten" value="{{ old('kabupaten', $keluarga->kabupaten) }}" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                </div>
                <div>
                    <label class="text-label-sm font-bold text-on-surface block mb-xs">Provinsi</label>
                    <input type="text" name="provinsi" value="{{ old('provinsi', $keluarga->provinsi) }}" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                </div>
            </div>
        </div>
        <div class="flex gap-md justify-end mt-lg pt-md border-t border-surface-variant/30">
            <a href="{{ route('admin.keluarga.index') }}" class="px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all">Batal</a>
            <button type="submit" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all">Simpan</button>
        </div>
    </form>
</div>
@endsection

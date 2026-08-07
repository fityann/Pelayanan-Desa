@extends('layouts.admin')

@section('title', 'Buat Permohonan Pencairan Dana - SILAPU')

@section('content')
<div class="flex flex-col gap-lg max-w-3xl">
    <div>
        <h1 class="text-headline-md font-bold text-on-surface">Buat Permohonan Pencairan Dana</h1>
        <p class="text-body-sm text-on-surface-variant">Ajukan permohonan pencairan dana desa</p>
    </div>

    <form method="POST" action="{{ route('admin.pencairan-dana.store') }}" class="bg-surface-container-lowest rounded-xl shadow-sm p-lg space-y-lg">
        @csrf

        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Nama Kegiatan <span class="text-error">*</span></label>
            <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required maxlength="500" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Contoh: Pembangunan drainase RT 01">
            @error('nama_kegiatan') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Jumlah Pencairan <span class="text-error">*</span></label>
                <input type="number" name="jumlah_pencairan" value="{{ old('jumlah_pencairan') }}" required min="0" step="0.01" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="25000000">
                @error('jumlah_pencairan') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Sumber Dana <span class="text-error">*</span></label>
                <select name="sumber_dana" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    @foreach (['APBDes' => 'APBDes', 'DD' => 'Dana Desa (DD)', 'ADD' => 'Alokasi Dana Desa (ADD)', 'Lainnya'] as $key => $label)
                        <option value="{{ $key }}" {{ old('sumber_dana', 'APBDes') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('sumber_dana') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Jenis Pencairan <span class="text-error">*</span></label>
            <select name="jenis_pencairan" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                @foreach (['rutin' => 'Rutin', 'insidentil' => 'Insidentil', 'proyek' => 'Proyek', 'lainnya' => 'Lainnya'] as $key => $label)
                    <option value="{{ $key }}" {{ old('jenis_pencairan') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('jenis_pencairan') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-md justify-end pt-md border-t border-surface-variant/30">
            <a href="{{ route('admin.pencairan-dana.index') }}" class="px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all">Batal</a>
            <button type="submit" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all">Simpan Permohonan</button>
        </div>
    </form>
</div>
@endsection
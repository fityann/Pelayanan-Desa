@extends('layouts.admin')

@section('title', 'Edit APBDes - SILAPU')

@section('content')
<div class="flex flex-col gap-lg">
    <div class="flex items-center gap-sm">
        <a href="{{ route('admin.apbdes.index') }}" class="w-10 h-10 rounded-full hover:bg-surface-container flex items-center justify-center text-on-surface transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Edit Data APBDes</h1>
            <p class="text-body-sm text-on-surface-variant">Ubah rincian Anggaran Pendapatan dan Belanja Desa</p>
        </div>
    </div>

    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
        <form action="{{ route('admin.apbdes.update', $apbde) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-lg mb-lg">
                <!-- Tahun Anggaran -->
                <div>
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">
                        Tahun Anggaran <span class="text-error">*</span>
                    </label>
                    <input type="number" name="tahun" value="{{ old('tahun', $apbde->tahun) }}" min="2020" max="2030" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none" required>
                    @error('tahun') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Kategori -->
                <div>
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">
                        Kategori <span class="text-error">*</span>
                    </label>
                    <select name="kategori" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none" required>
                        <option value="Pendapatan" {{ old('kategori', $apbde->kategori) == 'Pendapatan' ? 'selected' : '' }}>Pendapatan</option>
                        <option value="Belanja" {{ old('kategori', $apbde->kategori) == 'Belanja' ? 'selected' : '' }}>Belanja</option>
                        <option value="Pembiayaan" {{ old('kategori', $apbde->kategori) == 'Pembiayaan' ? 'selected' : '' }}>Pembiayaan</option>
                    </select>
                    @error('kategori') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Bidang -->
                <div>
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">
                        Bidang / Sub Bidang
                    </label>
                    <input type="text" name="bidang" value="{{ old('bidang', $apbde->bidang) }}" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                    @error('bidang') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Uraian -->
                <div class="md:col-span-2">
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">
                        Uraian Kegiatan <span class="text-error">*</span>
                    </label>
                    <textarea name="uraian" rows="3" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none resize-none" required>{{ old('uraian', $apbde->uraian) }}</textarea>
                    @error('uraian') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Anggaran -->
                <div>
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">
                        Anggaran (Rp) <span class="text-error">*</span>
                    </label>
                    <input type="number" name="anggaran" value="{{ old('anggaran', $apbde->anggaran) }}" step="0.01" min="0" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none" required>
                    @error('anggaran') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Realisasi -->
                <div>
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">
                        Realisasi (Rp)
                    </label>
                    <input type="number" name="realisasi" value="{{ old('realisasi', $apbde->realisasi) }}" step="0.01" min="0" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                    @error('realisasi') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-sm pt-md border-t border-outline-variant/20">
                <a href="{{ route('admin.apbdes.index') }}" class="px-lg py-3 rounded-lg text-label-sm font-medium text-on-surface hover:bg-surface-container-high transition-colors border border-outline-variant">
                    Batal
                </a>
                <button type="submit" class="bg-primary text-on-primary hover:bg-primary/90 px-lg py-3 rounded-lg text-label-sm font-medium transition-colors flex items-center gap-xs">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

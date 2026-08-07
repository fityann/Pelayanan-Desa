@extends('layouts.admin')

@section('title', 'Buat Usulan Musrenbang - SILAPU')

@section('content')
<div class="flex flex-col gap-lg max-w-3xl">
    <div>
        <h1 class="text-headline-md font-bold text-on-surface">Buat Usulan Musrenbang</h1>
        <p class="text-body-sm text-on-surface-variant">Ajukan usulan kegiatan untuk perencanaan pembangunan desa</p>
    </div>

    <form method="POST" action="{{ route('admin.musrenbang.store') }}" enctype="multipart/form-data" class="bg-surface-container-lowest rounded-xl shadow-sm p-lg space-y-lg">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Tahun <span class="text-error">*</span></label>
                <select name="tahun" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    @foreach (range(date('Y'), date('Y') - 3, -1) as $t)
                        <option value="{{ $t }}" {{ $t == date('Y') ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
                @error('tahun') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Jenis Kegiatan <span class="text-error">*</span></label>
                <select name="jenis_kegiatan" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    @foreach (['fisik' => 'Fisik', 'non-fisik' => 'Non Fisik', 'sosial' => 'Sosial', 'ekonomi' => 'Ekonomi'] as $key => $label)
                        <option value="{{ $key }}" {{ old('jenis_kegiatan') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('jenis_kegiatan') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Judul Kegiatan <span class="text-error">*</span></label>
            <input type="text" name="judul_kegiatan" value="{{ old('judul_kegiatan') }}" required maxlength="500" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Contoh: Pengerasan jalan lingkungan RT 01">
            @error('judul_kegiatan') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Deskripsi Kegiatan <span class="text-error">*</span></label>
            <textarea name="deskripsi_kegiatan" rows="4" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">{{ old('deskripsi_kegiatan') }}</textarea>
            @error('deskripsi_kegiatan') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Estimasi Biaya <span class="text-error">*</span></label>
                <input type="number" name="estimasi_biaya" value="{{ old('estimasi_biaya') }}" required min="0" step="0.01" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="10000000">
                @error('estimasi_biaya') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Sumber Dana <span class="text-error">*</span></label>
                <select name="sumber_dana" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    @foreach (['APBDes' => 'APBDes', 'DD' => 'Dana Desa (DD)', 'ADD' => 'Alokasi Dana Desa (ADD)', 'BUMDes' => 'BUMDes', 'Lainnya'] as $key => $label)
                        <option value="{{ $key }}" {{ old('sumber_dana', 'APBDes') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('sumber_dana') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Prioritas <span class="text-error">*</span></label>
            <select name="prioritas" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                @foreach (['rendah' => 'Rendah', 'sedang' => 'Sedang', 'tinggi' => 'Tinggi', 'sangat_tinggi' => 'Sangat Tinggi'] as $key => $label)
                    <option value="{{ $key }}" {{ old('prioritas') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('prioritas') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Jadwal Musrenbang (opsional)</label>
            <input type="date" name="tanggal_musrenbang" value="{{ old('tanggal_musrenbang') }}" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            @error('tanggal_musrenbang') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Dokumen Perencanaan (RKPD/RKP, opsional, maks. 5)</label>
            <div class="space-y-sm">
                <div class="border border-outline-variant rounded-xl p-md">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-sm">
                        <input type="file" name="dokumen[]" class="md:col-span-2 text-body-sm file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-label-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                        <select name="tipe_dokumen[]" class="bg-surface-container rounded-lg px-md py-2 text-body-sm border border-outline-variant">
                            @foreach (['proposal' => 'Proposal', 'rkpd' => 'RKPD', 'rkp' => 'RKP', 'foto' => 'Foto', 'lain' => 'Lainnya'] as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <p class="text-[10px] text-on-surface-variant mt-xs">Format: PDF, JPG, PNG, DOC, XLS. Maks. 5MB per file.</p>
            @error('dokumen.*') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-md justify-end pt-md border-t border-surface-variant/30">
            <a href="{{ route('admin.musrenbang.index') }}" class="px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all">Batal</a>
            <button type="submit" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all">Ajukan Usulan</button>
        </div>
    </form>
</div>
@endsection
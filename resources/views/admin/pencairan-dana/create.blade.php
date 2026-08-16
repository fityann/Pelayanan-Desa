@extends('layouts.admin')

@section('title', 'Buat Permohonan Pencairan Dana - SILAPU')

@section('content')
<div class="flex flex-col gap-lg max-w-4xl mx-auto">
    <div class="flex items-center gap-md">
        <a href="{{ route('admin.pencairan-dana.index') }}" class="w-10 h-10 rounded-full bg-surface-container hover:bg-surface-container-high flex items-center justify-center text-on-surface-variant transition-colors">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Buat Permohonan Pencairan Dana</h1>
            <p class="text-body-sm text-on-surface-variant">Isi detail permohonan dengan cermat</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.pencairan-dana.store') }}" class="bg-surface-container-lowest rounded-3xl shadow-sm border border-outline-variant/20 overflow-hidden">
        @csrf

        <div class="p-lg md:p-xl space-y-lg">
            <!-- Alert Info -->
            <div class="bg-primary/5 border border-primary/20 rounded-2xl p-md flex gap-md">
                <span class="material-symbols-outlined text-primary">info</span>
                <div>
                    <h3 class="text-label-md font-bold text-primary">Informasi Pengajuan</h3>
                    <p class="text-body-sm text-on-surface-variant mt-xs">Pengajuan yang dibuat akan masuk ke tahap verifikasi oleh verifikator keuangan (Sekretaris/Bendahara). Pastikan nama kegiatan dan jumlah dana sudah sesuai dengan pagu APBDes.</p>
                </div>
            </div>

            <!-- Formulir Utama -->
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-sm">Nama Kegiatan <span class="text-error">*</span></label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/50">engineering</span>
                    <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required maxlength="500" class="w-full bg-surface-container rounded-2xl pl-12 pr-lg py-3.5 text-body-md outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary border border-outline-variant transition-all" placeholder="Contoh: Pembangunan Drainase RT 01 / Operasional Desa">
                </div>
                @error('nama_kegiatan') <p class="text-error text-label-sm mt-xs flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span> {{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-lg border-t border-outline-variant/20 pt-lg">
                <div>
                    <label class="text-label-sm font-bold text-on-surface block mb-sm">Jumlah Pencairan (Rp) <span class="text-error">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-on-surface-variant">Rp</span>
                        <input type="number" name="jumlah_pencairan" value="{{ old('jumlah_pencairan') }}" required min="0" step="0.01" class="w-full bg-surface-container rounded-2xl pl-12 pr-lg py-3.5 text-body-md outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary border border-outline-variant transition-all" placeholder="0">
                    </div>
                    @error('jumlah_pencairan') <p class="text-error text-label-sm mt-xs flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span> {{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="text-label-sm font-bold text-on-surface block mb-sm">Sumber Dana <span class="text-error">*</span></label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/50">account_balance_wallet</span>
                        <select name="sumber_dana" required class="w-full bg-surface-container rounded-2xl pl-12 pr-lg py-3.5 text-body-md outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary border border-outline-variant transition-all appearance-none">
                            @foreach (['APBDes' => 'APBDes', 'DD' => 'Dana Desa (DD)', 'ADD' => 'Alokasi Dana Desa (ADD)', 'PAD' => 'Pendapatan Asli Desa (PAD)', 'Lainnya' => 'Sumber Lainnya'] as $key => $label)
                                <option value="{{ $key }}" {{ old('sumber_dana', 'APBDes') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant/50 pointer-events-none">expand_more</span>
                    </div>
                    @error('sumber_dana') <p class="text-error text-label-sm mt-xs flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span> {{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-sm">Jenis Pencairan <span class="text-error">*</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-md">
                    @foreach (['rutin' => ['event_repeat', 'Rutin'], 'insidentil' => ['crisis_alert', 'Insidentil'], 'proyek' => ['architecture', 'Proyek Fisik'], 'lainnya' => ['category', 'Lainnya']] as $key => $data)
                        <label class="cursor-pointer">
                            <input type="radio" name="jenis_pencairan" value="{{ $key }}" class="peer sr-only" {{ old('jenis_pencairan', 'rutin') === $key ? 'checked' : '' }} required>
                            <div class="border border-outline-variant bg-surface-container hover:bg-surface-container-high peer-checked:bg-primary/10 peer-checked:border-primary peer-checked:text-primary rounded-2xl p-md flex flex-col items-center justify-center gap-xs text-on-surface-variant transition-all text-center h-full">
                                <span class="material-symbols-outlined text-[28px]">{{ $data[0] }}</span>
                                <span class="text-label-sm font-bold">{{ $data[1] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('jenis_pencairan') <p class="text-error text-label-sm mt-xs flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span> {{ $message }}</p> @enderror
            </div>
        </div>

        <div class="bg-surface-container-low p-lg border-t border-outline-variant/20 flex gap-md justify-end items-center">
            <a href="{{ route('admin.pencairan-dana.index') }}" class="px-lg py-2.5 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container-high transition-all">Batalkan</a>
            <button type="submit" class="bg-primary text-on-primary px-xl py-2.5 rounded-full text-label-md font-bold shadow-sm shadow-primary/30 hover:bg-primary-dark hover:-translate-y-0.5 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">save</span>
                Ajukan Permohonan
            </button>
        </div>
    </form>
</div>
@endsection
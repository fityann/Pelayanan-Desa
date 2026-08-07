@extends('layouts.admin')

@section('title', 'Input APBDes - SILAPU')

@section('content')
<div class="flex flex-col gap-lg max-w-2xl">
    <div>
        <h1 class="text-headline-md font-bold text-on-surface">Input Data APBDes</h1>
        <p class="text-body-sm text-on-surface-variant">Tambahkan data anggaran pendapatan dan belanja desa</p>
    </div>

    <form method="POST" action="{{ route('admin.apbdes.store') }}" class="bg-surface-container-lowest rounded-xl shadow-sm p-lg space-y-lg">
        @csrf
        <div class="grid grid-cols-2 gap-lg">
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Tahun</label>
                <input type="text" name="tahun" value="{{ old('tahun', date('Y')) }}" required maxlength="4" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Kategori</label>
                <select name="kategori" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    <option value="Pendapatan">Pendapatan</option>
                    <option value="Belanja">Belanja</option>
                    <option value="Pembiayaan">Pembiayaan</option>
                </select>
            </div>
        </div>
        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Bidang</label>
            <input type="text" name="bidang" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Contoh: Penyelenggaraan Pemerintahan Desa">
        </div>
        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Uraian</label>
            <textarea name="uraian" rows="3" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Uraian anggaran..."></textarea>
        </div>
        <div class="grid grid-cols-2 gap-lg">
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Anggaran (Rp)</label>
                <input type="number" name="anggaran" value="0" required min="0" step="0.01" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Realisasi (Rp)</label>
                <input type="number" name="realisasi" value="0" min="0" step="0.01" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
        </div>
        <div class="flex gap-md justify-end pt-md border-t border-surface-variant/30">
            <a href="{{ route('admin.apbdes.index') }}" class="px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all">Batal</a>
            <button type="submit" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all">Simpan</button>
        </div>
    </form>
</div>
@endsection

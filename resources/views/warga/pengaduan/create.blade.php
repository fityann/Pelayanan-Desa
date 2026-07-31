@extends('layouts.admin')

@section('title', 'Buat Pengaduan - SIPANDA')

@section('content')
<div class="flex flex-col gap-lg max-w-2xl">
    <div class="text-center">
        <div class="w-14 h-14 rounded-2xl bg-error/10 flex items-center justify-center text-error mx-auto mb-md">
            <span class="material-symbols-outlined text-[28px]">campaign</span>
        </div>
        <h1 class="text-headline-md font-bold text-on-surface">Sampaikan Pengaduan</h1>
        <p class="text-body-sm text-on-surface-variant">Terima kasih atas partisipasi Anda membangun Desa Puspamukti</p>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('pengaduan.store') }}" enctype="multipart/form-data" class="bg-surface-container-lowest rounded-xl shadow-sm p-lg space-y-lg">
        @csrf
        <input type="hidden" name="qr" value="{{ request()->query('qr', '0') }}">
        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Kategori</label>
            <select name="kategori" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                <option value="">Pilih Kategori</option>
                <option value="Infrastruktur">Infrastruktur</option>
                <option value="Kebersihan">Kebersihan</option>
                <option value="Pelayanan">Pelayanan</option>
                <option value="Keamanan">Keamanan</option>
                <option value="Sosial">Sosial</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>
        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Judul</label>
            <input type="text" name="judul" required maxlength="200" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Judul pengaduan...">
        </div>
        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Deskripsi</label>
            <textarea name="deskripsi" rows="5" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Jelaskan pengaduan secara detail..."></textarea>
        </div>
        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Foto (opsional)</label>
            <input type="file" name="foto" accept="image/*" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none border border-outline-variant file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-label-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all">
            <p class="text-[10px] text-on-surface-variant mt-xs">Maks. 2MB. Format: JPG, PNG, JPEG</p>
        </div>
        <div class="flex gap-md justify-end pt-md border-t border-surface-variant/30">
            <a href="{{ url('/') }}" class="px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all">Batal</a>
            <button type="submit" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all">Kirim Pengaduan</button>
        </div>
    </form>
</div>
@endsection

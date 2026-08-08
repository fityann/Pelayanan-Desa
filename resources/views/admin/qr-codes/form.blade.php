@extends('layouts.admin')

@section('title', ($rtQrCode ? 'Edit Wilayah' : 'Tambah Wilayah') . ' - SILAPU')

@section('content')
<div class="flex flex-col gap-lg max-w-2xl">
    <div>
        <h1 class="text-headline-md font-bold text-on-surface">
            {{ $rtQrCode ? "Edit Wilayah RT {$rtQrCode->rt}" : 'Tambah Wilayah Baru' }}
        </h1>
        <p class="text-body-sm text-on-surface-variant">{{ $rtQrCode ? 'Perbarui informasi wilayah dan status QR' : 'Daftarkan wilayah RT baru untuk dibuatkan QR Code' }}</p>
    </div>

    @if ($errors->any())
        <div class="bg-error/10 border border-error/20 text-error px-lg py-3 rounded-xl">
            <ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST"
          action="{{ $rtQrCode ? route('admin.qr-links.update', $rtQrCode) : route('admin.qr-links.store') }}"
          class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
        @csrf
        @if ($rtQrCode) @method('PUT') @endif

        <div class="space-y-lg">
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Nomor RT <span class="text-error">*</span></label>
                <input type="text" name="rt" value="{{ old('rt', $rtQrCode?->rt ?? request('rt')) }}" required maxlength="3" inputmode="numeric" placeholder="cth: 01"
                       class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                <input type="hidden" name="rw" value="{{ old('rw', $rtQrCode?->rw ?? '01') }}">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Nama Wilayah / Nama Ketua RT</label>
                <input type="text" name="nama_rt" value="{{ old('nama_rt', $rtQrCode?->nama_rt) }}" maxlength="100" placeholder="cth: RT 01 Desa Puspamukti"
                       class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Deskripsi</label>
                <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat wilayah (opsional)"
                          class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">{{ old('deskripsi', $rtQrCode?->deskripsi) }}</textarea>
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Status</label>
                <select name="status" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    <option value="aktif" @selected(old('status', $rtQrCode?->status ?? 'aktif') === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(old('status', $rtQrCode?->status) === 'nonaktif')>Nonaktif</option>
                </select>
            </div>

            @if ($rtQrCode && $rtQrCode->qr_code_path)
                <div class="bg-surface-container rounded-xl p-md flex items-center gap-md">
                    <img src="{{ asset('storage/' . $rtQrCode->qr_code_path) }}" alt="QR" class="w-16 h-16 rounded bg-white border border-outline-variant/30 object-contain">
                    <div class="flex-1">
                        <p class="text-label-sm font-semibold text-on-surface">QR Code sudah dibuat</p>
                        <p class="text-body-sm text-on-surface-variant">Digenerate {{ $rtQrCode->tanggal_generate?->translatedFormat('d M Y H:i') }}. Jika RT diubah, QR lama akan dihapus dan harus digenerate ulang.</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="flex gap-md justify-end mt-lg pt-md border-t border-surface-variant/30">
            <a href="{{ route('admin.qr-links.index') }}" class="px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all">Batal</a>
            <button type="submit" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all">Simpan</button>
        </div>
    </form>
</div>
@endsection
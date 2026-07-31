@extends('layouts.admin')

@section('title', 'Ajukan Surat - SIPANDA')

@section('content')
<div class="flex flex-col gap-lg max-w-2xl">
    <div>
        <h1 class="text-headline-md font-bold text-on-surface">Ajukan Surat</h1>
        <p class="text-body-sm text-on-surface-variant">{{ $jenisSurat->nama }}</p>
    </div>

    <form method="POST" action="{{ route('warga.surat.store', $jenisSurat) }}" enctype="multipart/form-data" class="bg-surface-container-lowest rounded-xl shadow-sm p-lg space-y-lg">
        @csrf

        <div class="bg-primary-fixed/20 border border-primary/20 rounded-xl p-md text-body-sm text-on-surface">
            <strong>Data pemohon diambil otomatis dari data kependudukan desa:</strong>
            <ul class="mt-sm space-y-1 text-on-surface-variant">
                <li>Nama: <strong class="text-on-surface">{{ auth()->user()->name }}</strong></li>
                <li>NIK: <strong class="text-on-surface">{{ auth()->user()->nik }}</strong></li>
                @php $pd = auth()->user()->penduduk; @endphp
                @if ($pd)
                    <li>Alamat: {{ $pd->alamat }} RT {{ $pd->rt }}/RW {{ $pd->rw }}</li>
                @endif
            </ul>
            @if (!$pd)
                <p class="text-label-sm text-warning mt-sm">Data penduduk belum terhubung dengan akun Anda. Hubungi admin desa bila data tidak sesuai.</p>
            @endif
        </div>

        @if ($jenisSurat->syarat)
            <div class="bg-surface-container rounded-xl p-md">
                <p class="text-label-sm font-bold text-on-surface mb-xs">Syarat & Dokumen Pendukung</p>
                <p class="text-body-sm text-on-surface-variant whitespace-pre-line">{{ $jenisSurat->syarat }}</p>
            </div>
        @endif

        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Keperluan</label>
            <textarea name="keterangan" rows="4" required maxlength="1000" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Contoh: untuk keperluan pengajuan beasiswa anak..."></textarea>
        </div>

        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Dokumen Pendukung (opsional)</label>
            <input type="file" name="file_pendukung" accept=".pdf,image/jpeg,image/png" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none border border-outline-variant file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-label-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all">
            <p class="text-[10px] text-on-surface-variant mt-xs">Maks. 2MB. Format: PDF, JPG, PNG</p>
        </div>

        <div class="flex gap-md justify-end pt-md border-t border-surface-variant/30">
            <a href="{{ route('warga.surat.index') }}" class="px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all">Batal</a>
            <button type="submit" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all">Kirim Pengajuan</button>
        </div>
    </form>
</div>
@endsection

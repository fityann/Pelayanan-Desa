@extends('layouts.admin')

@section('title', 'Import Penduduk - SILAPU')

@section('content')
<div class="flex flex-col gap-lg max-w-2xl">
    <div>
        <h1 class="text-headline-md font-bold text-on-surface">Import Data Penduduk dari Excel</h1>
        <p class="text-body-sm text-on-surface-variant">Upload file Excel untuk mengimport data penduduk secara massal</p>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-error/10 border border-error/20 text-error px-lg py-3 rounded-xl">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="bg-error/10 border border-error/20 text-error px-lg py-3 rounded-xl">
            <ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
        <div class="mb-lg p-lg bg-primary/5 border border-primary/20 rounded-xl text-body-sm text-on-surface">
            <strong>Petunjuk:</strong> Upload file Excel (.xlsx, .xls) dengan kolom: <strong>nik, nama, tempat_lahir, tanggal_lahir, jenis_kelamin (L/P), alamat, rt, rw, agama, status_perkawinan, pekerjaan, kewarganegaraan, pendidikan_terakhir, no_kk, hubungan_keluarga</strong>. Baris pertama harus berupa header (nama kolom).
        </div>

        <form method="POST" action="{{ route('admin.penduduk.import.store') }}" enctype="multipart/form-data">
            @csrf
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">File Excel</label>
                <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none border border-outline-variant file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-label-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all">
            </div>
            <div class="flex gap-md justify-end mt-lg pt-md border-t border-surface-variant/30">
                <a href="{{ route('admin.penduduk.index') }}" class="px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all">Kembali</a>
                <button type="submit" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all">Import</button>
            </div>
        </form>
    </div>
</div>
@endsection

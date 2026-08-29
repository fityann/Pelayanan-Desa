@extends('layouts.admin')

@section('title', 'Tambah Penduduk - SILAPU')

@section('content')
<div class="flex flex-col gap-lg max-w-4xl">
    <div>
        <h1 class="text-headline-md font-bold text-on-surface">Tambah Penduduk</h1>
        <p class="text-body-sm text-on-surface-variant">Input data penduduk baru</p>
    </div>

    @if(request('no_kk'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold">
                    <span class="material-symbols-outlined text-xl">group_add</span>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-emerald-900">Mode Tambah Anggota KK</h4>
                    <p class="text-xs font-semibold text-emerald-700 mt-0.5">
                        Menambahkan Anggota Keluarga untuk KK No: <span class="font-mono font-bold underline">{{ request('no_kk') }}</span>
                    </p>
                </div>
            </div>
            <span class="bg-emerald-100 text-emerald-800 text-[11px] font-bold px-3 py-1 rounded-full">
                Auto-filled
            </span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-error/10 border border-error/20 text-error px-lg py-3 rounded-xl">
            <ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.penduduk.store') }}" class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">NIK (16 digit)</label>
                <input type="text" name="nik" value="{{ old('nik') }}" required maxlength="16" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    <option value="">-- Pilih --</option>
                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Agama</label>
                <select name="agama" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    <option value="">-- Pilih --</option>
                    @foreach (['Islam','Kristen Protestan','Katolik','Hindu','Budha','Konghucu'] as $a)
                        <option value="{{ $a }}" {{ old('agama') == $a ? 'selected' : '' }}>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Status Perkawinan</label>
                <select name="status_perkawinan" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    <option value="">-- Pilih --</option>
                    @foreach (['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'] as $s)
                        <option value="{{ $s }}" {{ old('status_perkawinan') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Pekerjaan</label>
                <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Pendidikan Terakhir</label>
                <select name="pendidikan_terakhir" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    <option value="">-- Pilih --</option>
                    @foreach (['Tidak Sekolah','SD/Sederajat','SMP/Sederajat','SMA/Sederajat','D1','D2','D3','D4/S1','S2','S3'] as $p)
                        <option value="{{ $p }}" {{ old('pendidikan_terakhir') == $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Kewarganegaraan</label>
                <input type="text" name="kewarganegaraan" value="{{ old('kewarganegaraan', 'WNI') }}" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">RT</label>
                <input type="text" name="rt" value="{{ old('rt', request('rt')) }}" maxlength="3" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">RW</label>
                <input type="text" name="rw" value="{{ old('rw', request('rw')) }}" maxlength="3" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">No. KK</label>
                <input type="text" name="no_kk" value="{{ old('no_kk', request('no_kk')) }}" maxlength="16" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Hubungan Keluarga</label>
                <select name="hubungan_keluarga" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    <option value="">-- Pilih --</option>
                    @foreach (['Kepala Keluarga','Istri','Anak','Menantu','Cucu','Orang Tua','Mertua','Famili Lain','Lainnya'] as $h)
                        <option value="{{ $h }}" {{ old('hubungan_keluarga') == $h ? 'selected' : '' }}>{{ $h }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Keluarga (KK Terhubung)</label>
                <select name="keluarga_id" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    <option value="">-- Pilih KK --</option>
                    @foreach ($keluargaList as $k)
                        <option value="{{ $k->id }}" {{ old('keluarga_id', request('keluarga_id')) == $k->id ? 'selected' : '' }}>{{ $k->no_kk }} - {{ $k->kepala_keluarga }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Alamat</label>
                <textarea name="alamat" rows="2" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">{{ old('alamat', request('alamat')) }}</textarea>
            </div>
        </div>
        <div class="flex gap-md justify-end mt-lg pt-md border-t border-surface-variant/30">
            <a href="{{ route('admin.penduduk.index') }}" class="px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all">Batal</a>
            <button type="submit" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all">Simpan</button>
        </div>
    </form>
</div>
@endsection

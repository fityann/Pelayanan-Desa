@extends('layouts.admin')

@section('title', 'Edit Penduduk - SIPANDA')

@section('content')
<div class="flex flex-col gap-lg max-w-4xl">
    <div>
        <h1 class="text-headline-md font-bold text-on-surface">Edit Penduduk: {{ $penduduk->nama }}</h1>
        <p class="text-body-sm text-on-surface-variant">Perbarui data penduduk</p>
    </div>

    @if ($errors->any())
        <div class="bg-error/10 border border-error/20 text-error px-lg py-3 rounded-xl">
            <ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.penduduk.update', $penduduk) }}" class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
        @csrf @method('PATCH')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">NIK (16 digit)</label>
                <input type="text" name="nik" value="{{ old('nik', $penduduk->nik) }}" required maxlength="16" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama', $penduduk->nama) }}" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $penduduk->tempat_lahir) }}" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $penduduk->tanggal_lahir?->format('Y-m-d')) }}" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    <option value="">-- Pilih --</option>
                    <option value="L" {{ old('jenis_kelamin', $penduduk->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $penduduk->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Agama</label>
                <select name="agama" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    <option value="">-- Pilih --</option>
                    @foreach (['Islam','Kristen Protestan','Katolik','Hindu','Budha','Konghucu'] as $a)
                        <option value="{{ $a }}" {{ old('agama', $penduduk->agama) == $a ? 'selected' : '' }}>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Status Perkawinan</label>
                <select name="status_perkawinan" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    <option value="">-- Pilih --</option>
                    @foreach (['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'] as $s)
                        <option value="{{ $s }}" {{ old('status_perkawinan', $penduduk->status_perkawinan) == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Pekerjaan</label>
                <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $penduduk->pekerjaan) }}" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Pendidikan Terakhir</label>
                <select name="pendidikan_terakhir" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    <option value="">-- Pilih --</option>
                    @foreach (['Tidak Sekolah','SD/Sederajat','SMP/Sederajat','SMA/Sederajat','D1','D2','D3','D4/S1','S2','S3'] as $p)
                        <option value="{{ $p }}" {{ old('pendidikan_terakhir', $penduduk->pendidikan_terakhir) == $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Kewarganegaraan</label>
                <input type="text" name="kewarganegaraan" value="{{ old('kewarganegaraan', $penduduk->kewarganegaraan) }}" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">RT</label>
                <input type="text" name="rt" value="{{ old('rt', $penduduk->rt) }}" maxlength="3" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">RW</label>
                <input type="text" name="rw" value="{{ old('rw', $penduduk->rw) }}" maxlength="3" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">No. KK</label>
                <input type="text" name="no_kk" value="{{ old('no_kk', $penduduk->no_kk) }}" maxlength="16" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Hubungan Keluarga</label>
                <select name="hubungan_keluarga" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    <option value="">-- Pilih --</option>
                    @foreach (['Kepala Keluarga','Istri','Anak','Menantu','Cucu','Orang Tua','Mertua','Famili Lain','Lainnya'] as $h)
                        <option value="{{ $h }}" {{ old('hubungan_keluarga', $penduduk->hubungan_keluarga) == $h ? 'selected' : '' }}>{{ $h }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">ID Keluarga (opsional)</label>
                <select name="keluarga_id" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    <option value="">-- Pilih KK --</option>
                    @foreach ($keluargaList as $k)
                        <option value="{{ $k->id }}" {{ old('keluarga_id', $penduduk->keluarga_id) == $k->id ? 'selected' : '' }}>{{ $k->no_kk }} - {{ $k->kepala_keluarga }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Tautkan ke Akun Pengguna</label>
                <select name="user_id" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    <option value="">-- Tidak ditautkan --</option>
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}" {{ old('user_id', $penduduk->user_id) == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->nik }})</option>
                    @endforeach
                </select>
                <p class="text-label-sm text-on-surface-variant mt-xs">Tautkan agar data penduduk otomatis saat mengajukan layanan</p>
            </div>
            <div class="md:col-span-2">
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Alamat</label>
                <textarea name="alamat" rows="2" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">{{ old('alamat', $penduduk->alamat) }}</textarea>
            </div>
        </div>
        <div class="flex gap-md justify-end mt-lg pt-md border-t border-surface-variant/30">
            <a href="{{ route('admin.penduduk.index') }}" class="px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all">Batal</a>
            <button type="submit" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all">Simpan</button>
        </div>
    </form>
</div>
@endsection

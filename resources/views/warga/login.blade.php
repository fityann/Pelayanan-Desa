@extends('layouts.warga')

@section('title', "Masuk Warga - RT $rt RW $rw")

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-2xl shadow-sm p-6 md:p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="bg-emerald-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-emerald-600 text-3xl">person</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Masuk Warga</h1>
            <p class="text-sm text-gray-500 mt-2">Untuk mengajukan surat dan memantau status, silakan verifikasi identitas Anda.</p>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg flex items-center space-x-3 mb-6">
                <span class="material-symbols-outlined text-green-600">check_circle</span>
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg flex items-start space-x-3 mb-6">
                <span class="material-symbols-outlined text-red-500 mt-0.5">error</span>
                <div>
                    @foreach ($errors->all() as $error)
                        <p class="text-sm text-red-800">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('warga.rt.login.authenticate', ['rt' => $rt, 'rw' => $rw]) }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    NIK <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nik" value="{{ old('nik') }}" required inputmode="numeric" maxlength="16"
                       pattern="\d{16}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                       placeholder="Masukkan 16 digit NIK">
                <p class="text-xs text-gray-500 mt-1">NIK yang Anda gunakan harus sudah terdaftar di panel admin desa.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama') }}" required maxlength="255"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                       placeholder="Nama sesuai KTP">
            </div>

            <button type="submit"
                    class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-emerald-700 hover:to-teal-700 transition-all flex items-center justify-center space-x-2 shadow-lg shadow-emerald-600/20">
                <span class="material-symbols-outlined">login</span>
                <span>Masuk</span>
            </button>
        </form>

        <div class="mt-6 pt-5 border-t border-gray-200 text-center">
            <p class="text-xs text-gray-500">
                Tidak terdaftar? Silakan hubungi petugas desa untuk mendaftarkan data kependudukan Anda.
            </p>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('warga.rt.landing', ['rt' => $rt, 'rw' => $rw]) }}"
           class="inline-flex items-center space-x-1 text-gray-500 hover:text-gray-700 text-sm font-medium">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            <span>Kembali ke Beranda RT {{ $rt }}</span>
        </a>
    </div>
</div>
@endsection

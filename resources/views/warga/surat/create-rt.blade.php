@extends('layouts.warga')

@section('title', "Ajukan Surat - RT $rt")

@section('content')
<div class="space-y-6 max-w-3xl">
    <!-- Header -->
    <div>
        <a href="{{ route('warga.rt.surat.index', ['rt' => $rt]) }}"
           class="inline-flex items-center space-x-1 text-gray-500 hover:text-gray-700 text-sm font-medium mb-4">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            <span>Pilih jenis surat lain</span>
        </a>
        <div class="flex items-center space-x-3">
            <div class="bg-emerald-100 p-3 rounded-xl">
                <span class="material-symbols-outlined text-emerald-600 text-2xl">edit_note</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Ajukan Surat</h1>
                <p class="text-sm text-gray-500">{{ $jenisSurat->nama }} · RT {{ $rt }}</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('warga.rt.surat.store', ['rt' => $rt, 'jenisSurat' => $jenisSurat]) }}"
          enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm p-6 md:p-8 space-y-6">
        @csrf

        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-sm text-emerald-800">
            <strong>Data Pemohon</strong>
            <p class="text-emerald-700 mt-1">Isi data diri Anda di bawah ini.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">NIK <span class="text-red-500">*</span></label>
                <input type="text" name="nik" value="{{ old('nik', auth()->check() ? auth()->user()->nik : '') }}" required pattern="\d{16}" maxlength="16" inputmode="numeric"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                       placeholder="16 digit NIK">
                @error('nik') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', auth()->check() ? auth()->user()->name : '') }}" required maxlength="100"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                       placeholder="Nama sesuai KTP">
                @error('nama') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">No. WhatsApp <span class="text-red-500">*</span></label>
                <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp') }}" required maxlength="20"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                       placeholder="Contoh: 081234567890">
                @error('no_whatsapp') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                <input type="text" name="alamat" value="{{ old('alamat', auth()->check() ? auth()->user()->penduduk?->alamat : '') }}" maxlength="255"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                       placeholder="Alamat lengkap / RT">
                @error('alamat') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        @if ($jenisSurat->syarat)
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm font-bold text-gray-900 mb-1">Syarat & Dokumen Pendukung</p>
                <p class="text-sm text-gray-600 whitespace-pre-line">{{ $jenisSurat->syarat }}</p>
            </div>
        @endif

        @php
            $placeholderKeperluan = match($jenisSurat->kode) {
                'SKD' => 'Contoh: untuk keperluan administrasi kependudukan / pembuatan rekening...',
                'SKU' => 'Contoh: untuk keperluan pengajuan pinjaman bank / KUR...',
                'SKTM' => 'Contoh: untuk keperluan pengajuan beasiswa / bantuan sosial...',
                'SPN' => 'Contoh: untuk persyaratan pendaftaran pernikahan di KUA...',
                'SKematian' => 'Contoh: untuk keperluan mengurus asuransi / warisan / BPJS...',
                'SKBB' => 'Contoh: untuk persyaratan melamar pekerjaan...',
                'SKCerai' => 'Contoh: untuk keperluan administrasi pengadilan agama...',
                'SKWali' => 'Contoh: untuk persyaratan pernikahan anak / beasiswa yatim...',
                'SKKB' => 'Contoh: untuk pengantar pembuatan SKCK di kepolisian...',
                'SPPD' => 'Contoh: untuk permohonan pembuatan KTP baru / KK...',
                'SKKehilangan' => 'Contoh: untuk pengantar pembuatan laporan kehilangan di kepolisian...',
                'SPPAD', 'SPPAK', 'SPPDK' => 'Contoh: untuk keperluan administrasi kepindahan alamat domisili...',
                default => 'Contoh: untuk keperluan administrasi / pengajuan dokumen...',
            };
        @endphp
        @if (!empty($jenisSurat->form_fields) && count($jenisSurat->form_fields) > 0)
            <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                <p class="text-sm font-bold text-emerald-900 mb-1">Data Isian Tambahan</p>
                <p class="text-xs text-emerald-700">Lengkapi data berikut sesuai dengan persyaratan {{ $jenisSurat->nama }}.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach ($jenisSurat->form_fields as $field)
                    @php $isTextarea = isset($field['type']) && $field['type'] === 'textarea'; @endphp
                    <div class="{{ $isTextarea ? 'md:col-span-2' : '' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ $field['label'] }} @if($field['required'])<span class="text-red-500">*</span>@endif</label>
                        @if ($isTextarea)
                            <textarea name="data_isian[{{ $field['name'] }}]" rows="3" {{ $field['required'] ? 'required' : '' }}
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-none transition-all"
                                      placeholder="Masukkan {{ strtolower($field['label']) }}">{{ old('data_isian.'.$field['name']) }}</textarea>
                        @else
                            <input type="{{ $field['type'] ?? 'text' }}" name="data_isian[{{ $field['name'] }}]" value="{{ old('data_isian.'.$field['name']) }}" {{ $field['required'] ? 'required' : '' }}
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                   placeholder="Masukkan {{ strtolower($field['label']) }}">
                        @endif
                        @error('data_isian.'.$field['name']) <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                @endforeach
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Keperluan <span class="text-red-500">*</span></label>
                <textarea name="keterangan" rows="3" required maxlength="1000"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-none transition-all"
                          placeholder="{{ $placeholderKeperluan }}">{{ old('keterangan', $jenisSurat->nama) }}</textarea>
                @error('keterangan') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        @else
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Keperluan <span class="text-red-500">*</span></label>
                <textarea name="keterangan" rows="4" required maxlength="1000"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-none transition-all"
                          placeholder="{{ $placeholderKeperluan }}">{{ old('keterangan') }}</textarea>
                @error('keterangan') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        @endif

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Dokumen Pendukung (opsional)</label>
            <input type="file" name="file_pendukung" accept=".pdf,image/jpeg,image/png"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl outline-none text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200 transition-all">
            <p class="text-xs text-gray-500 mt-1">Maks. 15MB. Format: PDF, JPG, PNG</p>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800 flex space-x-3">
            <span class="material-symbols-outlined text-blue-500">info</span>
            <p>
                @if ($jenisSurat->butuh_ttd_fisik)
                    Surat ini memerlukan <strong class="text-blue-900">tanda tangan fisik Kepala Desa</strong>. Setelah disetujui, sistem membuat draft PDF siap cetak — Anda/Admin cukup mencetaknya, lalu menyerahkan ke Kepala Desa hanya untuk tanda tangan.
                @else
                    Surat ini tidak memerlukan tanda tangan fisik — setelah disetujui Anda dapat langsung mengunduh PDF.
                @endif
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-end pt-4 border-t border-gray-200">
            <a href="{{ route('warga.rt.surat.index', ['rt' => $rt]) }}"
               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium text-center hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit"
                    class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-emerald-700 hover:to-teal-700 transition-all flex items-center justify-center space-x-2">
                <span class="material-symbols-outlined">send</span>
                <span>Kirim Pengajuan</span>
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nikInput = document.querySelector('input[name="nik"]');
    const namaInput = document.querySelector('input[name="nama"]');
    const alamatInput = document.querySelector('input[name="alamat"]');

    if (nikInput) {
        nikInput.addEventListener('input', function() {
            const nik = this.value;
            if (nik.length === 16) {
                fetch(`/cek-nik/${nik}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.found) {
                            if (namaInput) namaInput.value = data.data.nama;
                            if (alamatInput) alamatInput.value = data.data.alamat;
                        }
                    })
                    .catch(err => console.error('Error fetching NIK data:', err));
            }
        });
    }
});
</script>
@endsection

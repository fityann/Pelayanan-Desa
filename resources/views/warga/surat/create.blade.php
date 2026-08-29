@extends('layouts.admin')

@section('title', 'Ajukan Surat - SILAPU')

@section('content')
<div class="flex flex-col gap-lg max-w-2xl">
    <div>
        <h1 class="text-headline-md font-bold text-on-surface">Ajukan Surat</h1>
        <p class="text-body-sm text-on-surface-variant">{{ $jenisSurat->nama }}</p>
    </div>

    <form method="POST" action="{{ route('warga.surat.store', $jenisSurat) }}" enctype="multipart/form-data" class="bg-surface-container-lowest rounded-xl shadow-sm p-lg space-y-lg">
        @csrf

        <div class="bg-primary-fixed/20 border border-primary/20 rounded-xl p-md text-body-sm text-on-surface">
            <strong>Data Pemohon</strong>
            <p class="text-on-surface-variant mt-xs">Isi data diri Anda di bawah ini.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">NIK <span class="text-error">*</span></label>
                <input type="text" name="nik" value="{{ old('nik', (auth()->check() && auth()->user()->hasRole('Warga')) ? auth()->user()->nik : '') }}" required pattern="\d{16}" maxlength="16" inputmode="numeric" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="16 digit NIK">
                @error('nik') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Nama Lengkap <span class="text-error">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', (auth()->check() && auth()->user()->hasRole('Warga')) ? auth()->user()->name : '') }}" required maxlength="100" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Nama sesuai KTP">
                @error('nama') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">No. WhatsApp <span class="text-error">*</span></label>
                <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp') }}" required maxlength="20" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Contoh: 081234567890">
                @error('no_whatsapp') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Alamat</label>
                <input type="text" name="alamat" value="{{ old('alamat', (auth()->check() && auth()->user()->hasRole('Warga')) ? auth()->user()->penduduk?->alamat : '') }}" maxlength="255" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Alamat lengkap / RT">
                @error('alamat') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
        </div>

        @if ($jenisSurat->syarat)
            <div class="bg-surface-container rounded-xl p-md">
                <p class="text-label-sm font-bold text-on-surface mb-xs">Syarat & Dokumen Pendukung</p>
                <p class="text-body-sm text-on-surface-variant whitespace-pre-line">{{ $jenisSurat->syarat }}</p>
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
            <div class="bg-primary-fixed/20 border border-primary/20 rounded-xl p-md text-body-sm text-on-surface">
                <strong>Data Isian Tambahan</strong>
                <p class="text-on-surface-variant mt-xs">Lengkapi data berikut sesuai dengan persyaratan {{ $jenisSurat->nama }}.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                @foreach ($jenisSurat->form_fields as $field)
                    @php $isTextarea = isset($field['type']) && $field['type'] === 'textarea'; @endphp
                    <div class="{{ $isTextarea ? 'md:col-span-2' : '' }}">
                        <label class="text-label-sm font-bold text-on-surface block mb-xs">{{ $field['label'] }} @if($field['required'])<span class="text-error">*</span>@endif</label>
                        @if ($isTextarea)
                            <textarea name="data_isian[{{ $field['name'] }}]" rows="3" {{ $field['required'] ? 'required' : '' }} class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant resize-none" placeholder="Masukkan {{ strtolower($field['label']) }}">{{ old('data_isian.'.$field['name']) }}</textarea>
                        @else
                            <input type="{{ $field['type'] ?? 'text' }}" name="data_isian[{{ $field['name'] }}]" value="{{ old('data_isian.'.$field['name']) }}" {{ $field['required'] ? 'required' : '' }} class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Masukkan {{ strtolower($field['label']) }}">
                        @endif
                        @error('data_isian.'.$field['name']) <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
                    </div>
                @endforeach
            </div>
            
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Keperluan <span class="text-error">*</span></label>
                <textarea name="keterangan" rows="3" required maxlength="1000" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="{{ $placeholderKeperluan }}">{{ old('keterangan', $jenisSurat->nama) }}</textarea>
                @error('keterangan') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
        @else
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Keperluan <span class="text-error">*</span></label>
                <textarea name="keterangan" rows="4" required maxlength="1000" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="{{ $placeholderKeperluan }}">{{ old('keterangan') }}</textarea>
                @error('keterangan') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
        @endif

        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Dokumen Pendukung (opsional)</label>
            <input type="file" name="file_pendukung" accept=".pdf,image/jpeg,image/png" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none border border-outline-variant file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-label-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all">
            <p class="text-[10px] text-on-surface-variant mt-xs">Maks. 15MB. Format: PDF, JPG, PNG</p>
        </div>

        <div class="bg-on-tertiary-container/5 border border-on-tertiary-container/20 rounded-xl p-md text-body-sm text-on-surface-variant flex gap-md">
            <span class="material-symbols-outlined text-on-tertiary-container">info</span>
            <p>
                @if ($jenisSurat->butuh_ttd_fisik)
                    Surat ini memerlukan <strong class="text-on-surface">tanda tangan fisik Kepala Desa</strong>. Setelah disetujui, sistem membuat draft PDF siap cetak — Anda/Admin cukup mencetaknya, lalu menyerahkan ke Kepala Desa hanya untuk tanda tangan.
                @else
                    Surat ini tidak memerlukan tanda tangan fisik — setelah disetujui Anda dapat langsung mengunduh PDF.
                @endif
            </p>
        </div>

        <div class="flex gap-md justify-end pt-md border-t border-surface-variant/30">
            <a href="{{ route('warga.surat.index') }}" class="px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all">Batal</a>
            <button type="submit" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all">Kirim Pengajuan</button>
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

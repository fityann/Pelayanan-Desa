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
                <label class="text-label-sm font-bold text-on-surface block mb-xs">NIK</label>
                <input type="text" name="nik" value="{{ old('nik', auth()->check() ? auth()->user()->nik : '') }}" required pattern="\d{16}" maxlength="16" inputmode="numeric" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="16 digit NIK">
                @error('nik') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama', auth()->check() ? auth()->user()->name : '') }}" required maxlength="100" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Nama sesuai KTP">
                @error('nama') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">No. WhatsApp</label>
                <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp') }}" required maxlength="20" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Contoh: 081234567890">
                @error('no_whatsapp') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Alamat</label>
                <input type="text" name="alamat" value="{{ old('alamat', auth()->check() ? auth()->user()->penduduk?->alamat : '') }}" maxlength="255" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Alamat lengkap / RT">
                @error('alamat') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
        </div>

        @if ($jenisSurat->syarat)
            <div class="bg-surface-container rounded-xl p-md">
                <p class="text-label-sm font-bold text-on-surface mb-xs">Syarat & Dokumen Pendukung</p>
                <p class="text-body-sm text-on-surface-variant whitespace-pre-line">{{ $jenisSurat->syarat }}</p>
            </div>
        @endif

        @if ($jenisSurat->kode === 'SKU')
            <div class="bg-primary-fixed/20 border border-primary/20 rounded-xl p-md text-body-sm text-on-surface">
                <strong>Data Usaha</strong>
                <p class="text-on-surface-variant mt-xs">Isi detail usaha Anda untuk Surat Keterangan Usaha.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <div>
                    <label class="text-label-sm font-bold text-on-surface block mb-xs">Bentuk Perusahaan</label>
                    <input type="text" name="data_isian[bentuk_perusahaan]" value="{{ old('data_isian.bentuk_perusahaan') }}" required maxlength="100" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Contoh: Perorangan, PT, CV, Toko...">
                </div>
                <div>
                    <label class="text-label-sm font-bold text-on-surface block mb-xs">Nomor NPWP</label>
                    <input type="text" name="data_isian[npwp]" value="{{ old('data_isian.npwp') }}" required maxlength="30" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Contoh: 12.345.678.9-012.000">
                </div>
                <div class="md:col-span-2">
                    <label class="text-label-sm font-bold text-on-surface block mb-xs">Alamat Perusahaan</label>
                    <input type="text" name="data_isian[alamat_perusahaan]" value="{{ old('data_isian.alamat_perusahaan') }}" required maxlength="255" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Alamat lengkap tempat usaha">
                </div>
                <div>
                    <label class="text-label-sm font-bold text-on-surface block mb-xs">Bidang Usaha</label>
                    <input type="text" name="data_isian[bidang_usaha]" value="{{ old('data_isian.bidang_usaha') }}" required maxlength="100" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Contoh: Perdagangan, Jasa, Kuliner...">
                </div>
                <div>
                    <label class="text-label-sm font-bold text-on-surface block mb-xs">Jenis Barang/Jasa Utama</label>
                    <input type="text" name="data_isian[jenis_barang]" value="{{ old('data_isian.jenis_barang') }}" required maxlength="100" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Contoh: Sembako, Pakaian, Makanan Ringan...">
                </div>
                <div>
                    <label class="text-label-sm font-bold text-on-surface block mb-xs">Lama Usaha</label>
                    <input type="text" name="data_isian[lama_usaha]" value="{{ old('data_isian.lama_usaha') }}" required maxlength="50" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Contoh: 2 Tahun, 6 Bulan...">
                </div>
            </div>
            
            <input type="hidden" name="keterangan" value="Surat Keterangan Usaha">
        @else
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Keperluan</label>
                <textarea name="keterangan" rows="4" required maxlength="1000" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Contoh: untuk keperluan pengajuan beasiswa anak...">{{ old('keterangan') }}</textarea>
                @error('keterangan') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
        @endif

        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Dokumen Pendukung (opsional)</label>
            <input type="file" name="file_pendukung" accept=".pdf,image/jpeg,image/png" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none border border-outline-variant file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-label-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all">
            <p class="text-[10px] text-on-surface-variant mt-xs">Maks. 2MB. Format: PDF, JPG, PNG</p>
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

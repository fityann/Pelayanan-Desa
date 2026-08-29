@extends('layouts.admin')

@section('title', 'Jenis Surat - SILAPU')

@section('content')
<div class="flex flex-col gap-lg" x-data='jenisSuratData(@json($presets))'>
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Jenis Surat</h1>
            <p class="text-body-sm text-on-surface-variant">Kelola jenis surat desa beserta form isian dinamisnya</p>
        </div>
        
        <div class="flex items-center gap-4">
            <!-- Search Input -->
            <div class="relative w-full sm:w-72">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input type="text" x-model="search" placeholder="Cari jenis surat..." class="w-full pl-10 pr-4 py-2 bg-surface-container rounded-xl border-none focus:ring-2 focus:ring-primary focus:outline-none transition-shadow text-body-md placeholder:text-on-surface-variant/70">
            </div>
            
            <button @click="openCreate()" class="bg-primary hover:bg-primary/90 text-on-primary px-4 py-2 rounded-xl text-body-md font-bold flex items-center gap-2 transition-colors">
                <span class="material-symbols-outlined text-[20px]">add</span>
                <span>Tambah Baru</span>
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-error/10 border border-error/20 text-error px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">error</span>
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-error/10 border border-error/20 text-error px-lg py-3 rounded-xl flex flex-col gap-1">
            @foreach ($errors->all() as $error)
                <p class="text-sm font-medium flex items-center gap-2"><span class="material-symbols-outlined text-sm">warning</span>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg">
        @foreach ($jenisSurat as $jenis)
            <div x-show="search === '' || '{{ strtolower(addslashes($jenis->nama)) }}'.includes(search.toLowerCase()) || '{{ strtolower(addslashes($jenis->kode)) }}'.includes(search.toLowerCase())" 
                 class="bg-surface-container-lowest rounded-xl shadow-sm p-lg hover:shadow-md transition-all {{ $jenis->aktif ? '' : 'opacity-60' }}">
                <div class="flex items-start justify-between mb-md">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">description</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $jenis->aktif ? 'bg-success/10 text-success' : 'bg-surface-variant/30 text-on-surface-variant' }}">
                            {{ $jenis->aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        <!-- Actions -->
                        <div class="flex items-center gap-1">
                            <button type="button" data-jenis="{{ json_encode($jenis) }}" @click="openEdit(JSON.parse($el.dataset.jenis))" class="w-7 h-7 rounded-lg bg-surface-container hover:bg-surface-container-highest flex items-center justify-center text-on-surface-variant transition-colors" title="Edit">
                                <span class="material-symbols-outlined text-[16px]">edit</span>
                            </button>
                            <form action="{{ route('admin.surat.jenis.destroy', $jenis) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus jenis surat ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-7 h-7 rounded-lg bg-error/10 hover:bg-error/20 flex items-center justify-center text-error transition-colors" title="Hapus">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('admin.surat.jenis.preview', $jenis) }}" target="_blank" class="text-title-md font-bold text-blue-600 hover:text-blue-800 hover:underline mb-1 flex items-center gap-1 w-fit" title="Preview Template PDF">
                    {{ $jenis->nama }}
                    <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                </a>
                <p class="text-label-sm text-on-surface-variant mb-md">Kode: <span class="font-mono font-bold">{{ $jenis->kode }}</span></p>
                
                @if ($jenis->deskripsi)
                    <p class="text-body-sm text-on-surface-variant mb-md line-clamp-2">{{ $jenis->deskripsi }}</p>
                @endif
                
                @if ($jenis->syarat)
                    <div class="bg-surface-container rounded-lg p-md mb-md">
                        <p class="text-label-sm font-bold text-on-surface mb-xs">Syarat:</p>
                        <p class="text-body-sm text-on-surface-variant whitespace-pre-line">{{ $jenis->syarat }}</p>
                    </div>
                @endif
                
                @if ($jenis->form_fields && count($jenis->form_fields) > 0)
                    <div class="mb-md">
                        <p class="text-label-sm font-bold text-on-surface mb-xs">Form Dinamis:</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach ($jenis->form_fields as $field)
                                <span class="inline-block px-2 py-1 bg-primary/5 text-primary text-[10px] rounded font-medium">{{ $field['label'] }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($jenis->masa_berlaku)
                    <p class="text-label-sm text-on-surface-variant">Masa berlaku: {{ $jenis->masa_berlaku }} hari</p>
                @endif
                <p class="text-label-sm {{ $jenis->butuh_ttd_fisik ? 'text-on-surface-variant' : 'text-success' }} mt-xs">
                    {{ $jenis->butuh_ttd_fisik ? 'Butuh TTD fisik Kepala Desa' : 'Tanpa TTD fisik (langsung final)' }}
                </p>
            </div>
        @endforeach
    </div>

    <!-- Modal Form -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" style="display: none;">
        <div @click.away="showModal = false" class="bg-surface-container-lowest rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
            <div class="p-6 border-b border-surface-variant/30 flex items-center justify-between">
                <h3 class="text-title-lg font-bold text-on-surface" x-text="isEdit ? 'Edit Jenis Surat' : 'Tambah Jenis Surat'"></h3>
                <button @click="showModal = false" class="text-on-surface-variant hover:text-on-surface"><span class="material-symbols-outlined">close</span></button>
            </div>
            
            <form :action="isEdit ? '{{ url('admin/surat/jenis') }}/' + formData.id : '{{ route('admin.surat.jenis.store') }}'" method="POST" class="overflow-y-auto flex-1 p-6 space-y-6">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <input type="hidden" name="form_fields" :value="JSON.stringify(formFields)">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-label-md font-bold text-on-surface mb-2">Kode Surat <span class="text-error">*</span></label>
                        <input type="text" name="kode" x-model="formData.kode" required class="w-full bg-surface-container rounded-xl border border-surface-variant/50 px-4 py-2 text-body-md focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-label-md font-bold text-on-surface mb-2">Nama Surat <span class="text-error">*</span></label>
                        <input type="text" name="nama" x-model="formData.nama" required class="w-full bg-surface-container rounded-xl border border-surface-variant/50 px-4 py-2 text-body-md focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-label-md font-bold text-on-surface mb-2">Deskripsi Singkat</label>
                    <textarea name="deskripsi" x-model="formData.deskripsi" rows="2" class="w-full bg-surface-container rounded-xl border border-surface-variant/50 px-4 py-2 text-body-md focus:ring-2 focus:ring-primary focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-label-md font-bold text-on-surface mb-2">Persyaratan Pengajuan (Opsional)</label>
                    <p class="text-label-sm text-on-surface-variant mb-2">Syarat dokumen atau langkah-langkah yang harus disiapkan warga. (Gunakan enter untuk baris baru)</p>
                    <textarea name="syarat" x-model="formData.syarat" rows="3" class="w-full bg-surface-container rounded-xl border border-surface-variant/50 px-4 py-2 text-body-md focus:ring-2 focus:ring-primary focus:outline-none"></textarea>
                </div>

                <!-- Form Builder -->
                <div class="bg-surface-container-low p-4 rounded-xl border border-surface-variant/50">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-title-md font-bold text-on-surface">Form Isian Tambahan (Dinamis)</h4>
                            <p class="text-label-sm text-on-surface-variant">Tambahkan field yang wajib diisi pemohon (misal: NPWP, Bidang Usaha).</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="applyPreset()" x-show="presetAvailable" class="bg-on-tertiary-container/10 hover:bg-on-tertiary-container/20 text-on-tertiary-container px-3 py-1.5 rounded-lg text-label-sm font-bold flex items-center gap-1 transition-colors">
                                <span class="material-symbols-outlined text-[16px]">auto_awesome</span>
                                Isi dari template <span x-text="'(' + presetCount + ' field)'"></span>
                            </button>
                            <button type="button" @click="addField()" class="bg-primary hover:bg-primary/90 text-on-primary px-3 py-1.5 rounded-lg text-label-sm font-bold flex items-center gap-1 transition-colors">
                                <span class="material-symbols-outlined text-[16px]">add</span> Tambah Field
                            </button>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(field, index) in formFields" :key="index">
                            <div class="flex items-start gap-3 bg-surface-container p-3 rounded-lg border border-surface-variant/50">
                                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[10px] font-bold text-on-surface-variant uppercase mb-1">Nama Field (sistem)</label>
                                        <input type="text" x-model="field.name" placeholder="cth: npwp" @input="field.name = field.name.toLowerCase().replace(/[^a-z0-9_]/g, '_')" required class="w-full bg-surface-container-lowest rounded-lg border border-surface-variant/50 px-3 py-1.5 text-body-sm focus:ring-2 focus:ring-primary focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-on-surface-variant uppercase mb-1">Label (tampilan)</label>
                                        <input type="text" x-model="field.label" placeholder="cth: Nomor NPWP" required class="w-full bg-surface-container-lowest rounded-lg border border-surface-variant/50 px-3 py-1.5 text-body-sm focus:ring-2 focus:ring-primary focus:outline-none">
                                    </div>
                                </div>
                                
                                <div class="flex flex-col items-center gap-2">
                                    <label class="flex items-center gap-1 cursor-pointer">
                                        <input type="checkbox" x-model="field.required" class="rounded text-primary focus:ring-primary h-4 w-4">
                                        <span class="text-label-sm">Wajib</span>
                                    </label>
                                    <button type="button" @click="removeField(index)" class="text-error hover:bg-error/10 p-1 rounded-md transition-colors" title="Hapus field">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </div>
                            </div>
                        </template>
                        <template x-if="formFields.length === 0">
                            <div class="text-center py-6 border-2 border-dashed border-surface-variant/50 rounded-lg">
                                <span class="material-symbols-outlined text-surface-variant mb-2">post_add</span>
                                <p class="text-body-sm text-on-surface-variant">Belum ada field tambahan.</p>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-label-md font-bold text-on-surface mb-2">Masa Berlaku (Hari)</label>
                        <input type="number" name="masa_berlaku" x-model="formData.masa_berlaku" class="w-full bg-surface-container rounded-xl border border-surface-variant/50 px-4 py-2 text-body-md focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="butuh_ttd_fisik" x-model="formData.butuh_ttd_fisik" value="1" class="rounded text-primary focus:ring-primary h-5 w-5">
                        <span class="text-body-md font-medium text-on-surface">Butuh TTD Fisik Kepala Desa</span>
                    </label>
                    <template x-if="isEdit">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="aktif" x-model="formData.aktif" value="1" class="rounded text-primary focus:ring-primary h-5 w-5">
                            <span class="text-body-md font-medium text-on-surface">Status Aktif</span>
                        </label>
                    </template>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-surface-variant/30">
                    <button type="button" @click="showModal = false" class="px-5 py-2.5 rounded-xl text-label-md font-bold text-on-surface-variant bg-surface-container hover:bg-surface-variant transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="bg-primary hover:bg-primary/90 text-on-primary px-5 py-2.5 rounded-xl text-label-md font-bold shadow-sm flex items-center gap-2 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">save</span>
                        <span x-text="isEdit ? 'Simpan Perubahan' : 'Tambah Jenis Surat'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function jenisSuratData(presets = {}) {
    return {
        search: '',
        showModal: false,
        isEdit: false,
        presets: presets,
        formData: {
            id: '', kode: '', nama: '', deskripsi: '', syarat: '', masa_berlaku: '', butuh_ttd_fisik: true, aktif: true
        },
        formFields: [],

        get presetAvailable() {
            return this.presetCount > 0;
        },

        get presetCount() {
            const kode = (this.formData.kode || '').trim().toUpperCase();
            return Array.isArray(this.presets[kode]) ? this.presets[kode].length : 0;
        },

        openCreate() {
            this.isEdit = false;
            this.formData = { id: '', kode: '', nama: '', deskripsi: '', syarat: '', masa_berlaku: '', butuh_ttd_fisik: true, aktif: true };
            this.formFields = [];
            this.showModal = true;
        },
        
        openEdit(jenis) {
            this.isEdit = true;
            this.formData = { ...jenis, butuh_ttd_fisik: !!jenis.butuh_ttd_fisik, aktif: !!jenis.aktif };
            this.formFields = jenis.form_fields ? (typeof jenis.form_fields === 'string' ? JSON.parse(jenis.form_fields) : jenis.form_fields) : [];
            this.showModal = true;
        },
        
        addField() {
            this.formFields.push({ name: '', label: '', type: 'text', required: true });
        },

        applyPreset() {
            const preset = this.presets[(this.formData.kode || '').trim().toUpperCase()] ?? [];
            preset.forEach(f => {
                if (!this.formFields.some(x => x.name === f.name)) {
                    this.formFields.push({ ...f });
                }
            });
        },
        
        removeField(index) {
            this.formFields.splice(index, 1);
        }
    }
}
</script>
@endsection

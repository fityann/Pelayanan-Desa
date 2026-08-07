@extends('layouts.admin')

@section('title', 'Buat Belanja - SILAPU')

@section('content')
<div class="flex flex-col gap-lg max-w-3xl">
    <div>
        <h1 class="text-headline-md font-bold text-on-surface">Buat Belanja</h1>
        <p class="text-body-sm text-on-surface-variant">Catat pengadaan barang dan jasa desa</p>
    </div>

    <form method="POST" action="{{ route('admin.belanja.store') }}" class="bg-surface-container-lowest rounded-xl shadow-sm p-lg space-y-lg">
        @csrf

        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Permohonan Pencairan Dana <span class="text-error">*</span></label>
            <select name="pencairan_dana_id" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                @php $pencairanList = \App\Models\PencairanDana::orderByDesc('created_at')->limit(50)->get(); @endphp
                @foreach ($pencairanList as $pd)
                    <option value="{{ $pd->id }}" {{ old('pencairan_dana_id') == $pd->id ? 'selected' : '' }}>{{ $pd->nomor_permohonan }} - {{ $pd->nama_kegiatan }}</option>
                @endforeach
            </select>
            @error('pencairan_dana_id') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            @if ($pencairanList->isEmpty())
                <p class="text-on-surface-variant text-label-sm mt-xs">Belum ada permohonan pencairan dana. Buat terlebih dahulu di menu Pencairan Dana.</p>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Jenis Belanja <span class="text-error">*</span></label>
                <select name="jenis_belanja" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    @foreach (['barang' => 'Barang', 'jasa' => 'Jasa', 'modal' => 'Modal', 'lainnya' => 'Lainnya'] as $key => $label)
                        <option value="{{ $key }}" {{ old('jenis_belanja') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('jenis_belanja') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Metode Pengadaan <span class="text-error">*</span></label>
                <select name="metode_pengadaan" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                    @foreach (['langsung' => 'Pengadaan Langsung', 'tender' => 'Tender', 'seleksi' => 'Seleksi', 'lainnya' => 'Lainnya'] as $key => $label)
                        <option value="{{ $key }}" {{ old('metode_pengadaan') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('metode_pengadaan') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Nama Barang/Jasa <span class="text-error">*</span></label>
            <input type="text" name="nama_barang_jasa" value="{{ old('nama_barang_jasa') }}" required maxlength="500" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Contoh: Semen 50kg">
            @error('nama_barang_jasa') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-3 gap-md">
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Kuantitas <span class="text-error">*</span></label>
                <input type="number" name="kuantitas" value="{{ old('kuantitas', 1) }}" required min="1" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                @error('kuantitas') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Satuan <span class="text-error">*</span></label>
                <input type="text" name="satuan" value="{{ old('satuan') }}" required maxlength="50" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="sak / unit / hari">
                @error('satuan') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Harga Satuan <span class="text-error">*</span></label>
                <input type="number" name="harga_satuan" value="{{ old('harga_satuan') }}" required min="0" step="0.01" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="55000">
                @error('harga_satuan') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex gap-md justify-end pt-md border-t border-surface-variant/30">
            <a href="{{ route('admin.belanja.index') }}" class="px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all">Batal</a>
            <button type="submit" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all">Simpan Belanja</button>
        </div>
    </form>
</div>
@endsection
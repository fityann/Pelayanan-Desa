@extends('layouts.admin')

@section('title', 'Edit Aset - SILAPU')

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.assets.index') }}" class="p-2 hover:bg-slate-200 rounded-full transition-colors">
            <span class="material-symbols-outlined text-slate-600">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Aset</h1>
            <p class="text-sm text-slate-500">Perbarui data inventaris atau aset</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-4 rounded-lg flex flex-col gap-1">
            <div class="flex items-center gap-2 font-bold">
                <span class="material-symbols-outlined">error</span>
                Terdapat kesalahan input:
            </div>
            <ul class="list-disc list-inside text-sm ml-6">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 max-w-4xl">
        <form action="{{ route('admin.assets.update', $asset) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Aset <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $asset->name) }}" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="asset_category_id" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoriAsets as $kat)
                            <option value="{{ $kat->id }}" {{ old('asset_category_id', $asset->asset_category_id) == $kat->id ? 'selected' : '' }}>{{ $kat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Lokasi</label>
                    <input type="text" name="location" value="{{ old('location', $asset->location) }}" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kondisi <span class="text-red-500">*</span></label>
                    <select name="condition" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">-- Pilih Kondisi --</option>
                        @foreach(\App\Enums\KondisiAset::cases() as $kondisi)
                            <option value="{{ $kondisi->value }}" {{ old('condition', $asset->condition->value ?? '') == $kondisi->value ? 'selected' : '' }}>{{ $kondisi->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tahun Perolehan</label>
                    <input type="number" name="acquisition_year" value="{{ old('acquisition_year', $asset->acquisition_year) }}" min="1900" max="{{ date('Y') + 1 }}" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Sumber Perolehan</label>
                    <input type="text" name="acquisition_source" value="{{ old('acquisition_source', $asset->acquisition_source) }}" placeholder="Contoh: Dana Desa, Hibah..." class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nilai Aset (Rp)</label>
                    <input type="number" step="0.01" name="value" value="{{ old('value', $asset->value) }}" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        @foreach(\App\Enums\StatusAset::cases() as $status)
                            <option value="{{ $status->value }}" {{ old('status', $asset->status->value ?? '') == $status->value ? 'selected' : '' }}>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi / Keterangan</label>
                    <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">{{ old('description', $asset->description) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Foto Aset</label>
                    @if($asset->photo)
                        <div class="mb-3">
                            <img src="{{ Storage::url($asset->photo) }}" alt="Foto {{ $asset->name }}" class="w-32 h-32 object-cover rounded-lg border border-slate-200">
                        </div>
                    @endif
                    <input type="file" name="photo" accept="image/jpeg,image/png,image/jpg" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    <p class="text-xs text-slate-500 mt-1">Biarkan kosong jika tidak ingin mengubah foto. Format: JPG/PNG, Maks: 2MB.</p>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.assets.index') }}" class="px-5 py-2.5 rounded-lg font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 rounded-lg font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-colors">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

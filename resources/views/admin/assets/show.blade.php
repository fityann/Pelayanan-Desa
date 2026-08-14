@extends('layouts.admin')

@section('title', 'Detail Aset - SILAPU')

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.assets.index') }}" class="p-2 hover:bg-slate-200 rounded-full transition-colors">
            <span class="material-symbols-outlined text-slate-600">arrow_back</span>
        </a>
        <div class="flex-1 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Detail Aset</h1>
                <p class="text-sm text-slate-500">{{ $asset->name }}</p>
            </div>
            <a href="{{ route('admin.assets.edit', $asset) }}" class="bg-orange-100 text-orange-700 px-4 py-2 rounded-lg font-semibold hover:bg-orange-200 transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">edit</span>
                Edit Aset
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 max-w-4xl">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-1">
                @if($asset->photo)
                    <img src="{{ Storage::url($asset->photo) }}" alt="{{ $asset->name }}" class="w-full h-auto aspect-square object-cover rounded-xl border border-slate-200 shadow-sm">
                @else
                    <div class="w-full aspect-square bg-slate-100 rounded-xl flex flex-col items-center justify-center border border-slate-200 text-slate-400">
                        <span class="material-symbols-outlined text-4xl mb-2">image_not_supported</span>
                        <span class="text-sm">Tidak ada foto</span>
                    </div>
                @endif

                <div class="mt-6 flex flex-col gap-3">
                    <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg border border-slate-100">
                        <span class="text-sm text-slate-500 font-medium">Status</span>
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $asset->status->color() }}">
                            {{ $asset->status->label() }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg border border-slate-100">
                        <span class="text-sm text-slate-500 font-medium">Kondisi</span>
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $asset->condition->color() }}">
                            {{ $asset->condition->label() }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 space-y-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-800 mb-1">{{ $asset->name }}</h2>
                    <p class="text-blue-600 font-semibold">{{ $asset->kategori->name ?? 'Tanpa Kategori' }}</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8">
                    <div>
                        <div class="text-sm text-slate-500 mb-1">Lokasi Aset</div>
                        <div class="font-medium text-slate-800">{{ $asset->location ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-slate-500 mb-1">Tahun Perolehan</div>
                        <div class="font-medium text-slate-800">{{ $asset->acquisition_year ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-slate-500 mb-1">Sumber Perolehan</div>
                        <div class="font-medium text-slate-800">{{ $asset->acquisition_source ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-slate-500 mb-1">Nilai Aset</div>
                        <div class="font-medium text-slate-800">{{ $asset->value ? 'Rp ' . number_format($asset->value, 2, ',', '.') : '-' }}</div>
                    </div>
                </div>

                <hr class="border-slate-100">

                <div>
                    <div class="text-sm text-slate-500 mb-2">Deskripsi / Keterangan</div>
                    @if($asset->description)
                        <p class="text-slate-700 whitespace-pre-wrap">{{ $asset->description }}</p>
                    @else
                        <p class="text-slate-400 italic">Tidak ada deskripsi.</p>
                    @endif
                </div>
                
                <div class="text-xs text-slate-400 mt-8 pt-4 border-t border-slate-50">
                    <div>Ditambahkan: {{ $asset->created_at->format('d M Y H:i') }}</div>
                    <div>Terakhir diubah: {{ $asset->updated_at->format('d M Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.warga')

@section('title', 'Aset Desa - Transparansi Publik')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 md:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <span class="material-symbols-outlined text-blue-600 text-2xl">inventory_2</span>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Aset Desa</h1>
                        <p class="text-gray-600 text-sm mt-1">Inventaris dan Kekayaan Desa Puspamukti</p>
                    </div>
                </div>
                <p class="text-xs text-gray-500 flex items-center mt-2">
                    <span class="material-symbols-outlined text-sm mr-1">verified</span>
                    Data publik untuk transparansi pengelolaan kekayaan desa.
                </p>
            </div>
            
            <form method="GET" class="flex flex-wrap items-center gap-3 bg-white p-3 rounded-xl shadow-sm w-full md:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aset..." class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-blue-400 w-full sm:w-auto">
                <select name="kategori" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-blue-400 flex-1 sm:flex-none">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriAsets as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>{{ $kat->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors hidden sm:block">Cari</button>
            </form>
        </div>
    </div>

    <!-- Assets Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($asets as $aset)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow flex flex-col h-full">
                <div class="aspect-video w-full bg-slate-100 relative">
                    @if($aset->photo)
                        <img src="{{ Storage::url($aset->photo) }}" alt="{{ $aset->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl mb-2">image_not_supported</span>
                            <span class="text-xs">Tidak ada foto</span>
                        </div>
                    @endif
                    <div class="absolute top-3 left-3 flex gap-2">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-white/90 backdrop-blur text-slate-700 shadow-sm">
                            {{ $aset->kategori->name ?? 'Lainnya' }}
                        </span>
                    </div>
                </div>
                
                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="text-lg font-bold text-gray-900 mb-1 leading-tight">{{ $aset->name }}</h3>
                    
                    <div class="flex items-center text-sm text-gray-500 mb-4 mt-2">
                        <span class="material-symbols-outlined text-[16px] mr-1.5">location_on</span>
                        {{ $aset->location ?? 'Lokasi tidak disebutkan' }}
                    </div>
                    
                    <div class="mt-auto space-y-3 pt-4 border-t border-slate-50">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Kondisi</span>
                            <span class="px-2 py-1 rounded-md text-xs font-bold {{ $aset->condition->color() }}">
                                {{ $aset->condition->label() }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Tahun</span>
                            <span class="font-medium text-gray-800">{{ $aset->acquisition_year ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 flex flex-col items-center text-center text-slate-500 bg-white rounded-2xl border border-slate-100 border-dashed">
                <span class="material-symbols-outlined text-5xl mb-3 text-slate-300">search_off</span>
                <p class="font-medium text-lg">Tidak ada aset ditemukan.</p>
                <p class="text-sm">Coba ubah kata kunci atau kategori pencarian.</p>
            </div>
        @endforelse
    </div>

    @if($asets->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $asets->links() }}
        </div>
    @endif
</div>
@endsection

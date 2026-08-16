@extends('layouts.admin')

@section('title', 'Manajemen Aset - SILAPU')

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Aset</h1>
            <p class="text-sm text-slate-500">Pencatatan inventaris dan aset desa</p>
        </div>
        <a href="{{ route('admin.assets.create') }}" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Tambah Aset
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 text-red-800 p-4 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined">error</span>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <!-- Filter -->
        <form method="GET" class="flex flex-wrap gap-4 mb-6 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau lokasi..." class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm">
            </div>
            <div class="w-full sm:w-auto">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori</label>
                <select name="kategori" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriAsets as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>{{ $kat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-auto">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kondisi</label>
                <select name="kondisi" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm">
                    <option value="">Semua Kondisi</option>
                    @foreach(\App\Enums\KondisiAset::cases() as $kondisi)
                        <option value="{{ $kondisi->value }}" {{ request('kondisi') == $kondisi->value ? 'selected' : '' }}>{{ $kondisi->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-auto">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Status</label>
                <select name="status" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm">
                    <option value="">Semua Status</option>
                    @foreach(\App\Enums\StatusAset::cases() as $status)
                        <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-700 transition-colors">Filter</button>
            <a href="{{ route('admin.assets.index') }}" class="text-slate-600 hover:text-slate-900 text-sm font-medium ml-2">Reset</a>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="py-3 px-4 text-sm font-semibold text-slate-600">Foto</th>
                        <th class="py-3 px-4 text-sm font-semibold text-slate-600">Nama Aset</th>
                        <th class="py-3 px-4 text-sm font-semibold text-slate-600">Kategori</th>
                        <th class="py-3 px-4 text-sm font-semibold text-slate-600">Lokasi</th>
                        <th class="py-3 px-4 text-sm font-semibold text-slate-600">Kondisi</th>
                        <th class="py-3 px-4 text-sm font-semibold text-slate-600">Status</th>
                        <th class="py-3 px-4 text-sm font-semibold text-slate-600 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($asets as $aset)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4">
                                @if($aset->photo)
                                    <img src="{{ Storage::url($aset->photo) }}" alt="Foto" class="w-12 h-12 object-cover rounded-md border border-slate-200">
                                @else
                                    <div class="w-12 h-12 bg-slate-100 rounded-md flex items-center justify-center border border-slate-200 text-slate-400">
                                        <span class="material-symbols-outlined text-xl">image_not_supported</span>
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-800">{{ $aset->name }}</div>
                                <div class="text-xs text-slate-500">Tahun: {{ $aset->acquisition_year ?? '-' }}</div>
                            </td>
                            <td class="py-3 px-4 text-sm text-slate-700">{{ $aset->kategori->name ?? '-' }}</td>
                            <td class="py-3 px-4 text-sm text-slate-700">{{ $aset->location ?? '-' }}</td>
                            <td class="py-3 px-4 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-bold {{ $aset->condition->color() }}">
                                    {{ $aset->condition->label() }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-bold {{ $aset->status->color() }}">
                                    {{ $aset->status->label() }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.assets.show', $aset) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.assets.edit', $aset) }}" class="p-1.5 text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                    <form action="{{ route('admin.assets.destroy', $aset) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus aset ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">
                                <span class="material-symbols-outlined text-4xl mb-2 text-slate-300">inventory_2</span>
                                <p>Belum ada data aset.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $asets->links() }}
        </div>
    </div>
</div>
@endsection

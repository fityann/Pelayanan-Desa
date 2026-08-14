@extends('layouts.admin')

@section('title', 'Kategori Aset - SILAPU')

@section('content')
<div class="flex flex-col gap-6" x-data="{ showModal: false, isEdit: false, editId: '', editName: '' }">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Kategori Aset</h1>
            <p class="text-sm text-slate-500">Kelola master data kategori aset desa</p>
        </div>
        <button @click="showModal = true; isEdit = false; editName = ''" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Tambah Kategori
        </button>
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
    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-4 rounded-lg flex flex-col gap-1">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="py-3 px-4 text-sm font-semibold text-slate-600 w-16">No</th>
                        <th class="py-3 px-4 text-sm font-semibold text-slate-600">Nama Kategori</th>
                        <th class="py-3 px-4 text-sm font-semibold text-slate-600 text-center">Jumlah Aset</th>
                        <th class="py-3 px-4 text-sm font-semibold text-slate-600 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kategoriAsets as $index => $kategori)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4 text-slate-500">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 font-bold text-slate-800">{{ $kategori->name }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">{{ $kategori->asets_count }} Aset</span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button @click="showModal = true; isEdit = true; editId = '{{ $kategori->id }}'; editName = '{{ addslashes($kategori->name) }}'" class="p-1.5 text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </button>
                                    <form action="{{ route('admin.kategori-aset.destroy', $kategori) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus" {{ $kategori->asets_count > 0 ? 'disabled' : '' }} style="{{ $kategori->asets_count > 0 ? 'opacity: 0.5; cursor: not-allowed;' : '' }}">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-500">
                                <span class="material-symbols-outlined text-4xl mb-2 text-slate-300">category</span>
                                <p>Belum ada kategori aset.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form Kategori -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Overlay -->
        <div x-show="showModal" x-transition.opacity @click="showModal = false" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40"></div>
        
        <!-- Modal Dialog -->
        <div x-show="showModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="bg-white rounded-2xl shadow-xl w-full max-w-md z-50 overflow-hidden relative">
            
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="text-lg font-bold text-slate-800" x-text="isEdit ? 'Edit Kategori' : 'Tambah Kategori'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form :action="isEdit ? '{{ route('admin.kategori-aset.index') }}/' + editId : '{{ route('admin.kategori-aset.store') }}'" method="POST">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                
                <div class="p-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Kategori</label>
                    <input type="text" name="name" x-model="editName" required class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Contoh: Tanah, Kendaraan, Elektronik...">
                </div>
                
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

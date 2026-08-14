@extends('layouts.admin')

@section('title', 'Data Keluarga - SILAPU')

@section('content')
<div class="flex flex-col gap-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Data Keluarga (KK)</h1>
            <p class="text-body-sm text-on-surface-variant">Kelola data keluarga Desa Puspamukti</p>
        </div>
        <div class="flex gap-sm">
            <button onclick="showKeluargaModal()" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all flex items-center gap-sm shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Tambah Keluarga</span>
            </button>
            <button onclick="toggleFilterPanel()" class="bg-surface-container text-on-surface px-lg py-2 rounded-full text-label-md font-bold hover:bg-surface-container-high transition-all flex items-center gap-sm">
                <span class="material-symbols-outlined text-[18px]">filter_list</span>
                Filter
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Panel -->
    <div id="filterPanel" class="bg-surface-container-lowest p-lg rounded-xl shadow-sm border border-outline-variant/20 hidden">
        <form id="filterForm" method="GET" class="space-y-md">
            <!-- Search Box -->
            <div>
                <label class="block text-label-sm font-medium text-on-surface mb-xs">Cari Data</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                           placeholder="Cari berdasarkan NIK, nama, atau alamat">
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">
                        <span class="material-symbols-outlined text-base">search</span>
                    </div>
                </div>
            </div>
            
            <!-- RT Filter -->
            <div>
                <label class="block text-label-sm font-medium text-on-surface mb-xs">RT</label>
                <select name="rt" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-body-sm">
                    <option value="">Semua RT</option>
                    @foreach($rtList ?? [] as $rt)
                        <option value="{{ $rt }}" {{ request('rt') == $rt ? 'selected' : '' }}>
                            RT {{ $rt }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Jumlah Anggota -->
            <div class="grid grid-cols-2 gap-md">
                <div>
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">Min Anggota</label>
                    <input type="number" name="min_anggota" value="{{ request('min_anggota') }}" 
                           min="0" max="20"
                           class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-body-sm">
                </div>
                <div>
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">Max Anggota</label>
                    <input type="number" name="max_anggota" value="{{ request('max_anggota') }}" 
                           min="0" max="20"
                           class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-body-sm">
                </div>
            </div>
            
            <!-- Sorting -->
            <div class="grid grid-cols-2 gap-md">
                <div>
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">Urutkan Berdasarkan</label>
                    <select name="sort_by" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-body-sm">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Input</option>
                        <option value="no_kk" {{ request('sort_by') == 'no_kk' ? 'selected' : '' }}>Nomor KK</option>
                        <option value="kepala_keluarga" {{ request('sort_by') == 'kepala_keluarga' ? 'selected' : '' }}>Nama KK</option>
                        <option value="penduduk_count" {{ request('sort_by') == 'penduduk_count' ? 'selected' : '' }}>Jumlah Anggota</option>
                        <option value="rt" {{ request('sort_by') == 'rt' ? 'selected' : '' }}>RT</option>
                        <option value="rw" {{ request('sort_by') == 'rw' ? 'selected' : '' }}>RW</option>
                    </select>
                </div>
                <div>
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">Urutan</label>
                    <select name="sort_order" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-body-sm">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Terbaru / Terbanyak</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Terlama / Terdikit</option>
                    </select>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-sm pt-sm">
                <button type="submit" 
                        class="flex-1 bg-primary text-on-primary hover:bg-primary/90 px-lg py-3 rounded-lg text-label-sm font-medium transition-colors flex items-center justify-center gap-sm">
                    <span class="material-symbols-outlined">filter_alt</span>
                    Terapkan Filter
                </button>
                <button type="button" onclick="resetFilter()"
                        class="flex-1 bg-surface border border-outline-variant text-on-surface hover:bg-surface-container-high px-lg py-3 rounded-lg text-label-sm font-medium transition-colors flex items-center justify-center gap-sm">
                    <span class="material-symbols-outlined">refresh</span>
                    Reset Filter
                </button>
            </div>
        </form>
    </div>
    
    <!-- Active Filters Display -->
    @if(request()->hasAny(['search', 'rt', 'rw', 'min_anggota', 'max_anggota']))
    <div class="bg-surface-container p-md rounded-xl">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-sm">
                <span class="material-symbols-outlined text-primary">filter_alt</span>
                <span class="text-label-sm font-medium text-on-surface">Filter Aktif:</span>
            </div>
            <button onclick="resetFilter()" class="text-label-sm text-primary hover:text-primary/80">
                Hapus Semua
            </button>
        </div>
        <div class="flex flex-wrap gap-sm mt-sm">
            @if(request('search'))
                <span class="bg-primary/10 text-primary px-3 py-1.5 rounded-full text-[11px] flex items-center gap-xs">
                    Cari: "{{ request('search') }}"
                    <button onclick="removeFilter('search')" class="text-primary/70 hover:text-primary">
                        ×
                    </button>
                </span>
            @endif
            @if(request('rt'))
                <span class="bg-blue-500/10 text-blue-600 px-3 py-1.5 rounded-full text-[11px] flex items-center gap-xs">
                    RT: {{ request('rt') }}
                    <button onclick="removeFilter('rt')" class="text-blue-600/70 hover:text-blue-600">
                        ×
                    </button>
                </span>
            @endif
            @if(request('rw'))
                <span class="bg-green-500/10 text-green-600 px-3 py-1.5 rounded-full text-[11px] flex items-center gap-xs">
                    RW: {{ request('rw') }}
                    <button onclick="removeFilter('rw')" class="text-green-600/70 hover:text-green-600">
                        ×
                    </button>
                </span>
            @endif
            @if(request('min_anggota'))
                <span class="bg-yellow-500/10 text-yellow-600 px-3 py-1.5 rounded-full text-[11px] flex items-center gap-xs">
                    Min Anggota: {{ request('min_anggota') }}
                    <button onclick="removeFilter('min_anggota')" class="text-yellow-600/70 hover:text-yellow-600">
                        ×
                    </button>
                </span>
            @endif
            @if(request('max_anggota'))
                <span class="bg-purple-500/10 text-purple-600 px-3 py-1.5 rounded-full text-[11px] flex items-center gap-xs">
                    Max Anggota: {{ request('max_anggota') }}
                    <button onclick="removeFilter('max_anggota')" class="text-purple-600/70 hover:text-purple-600">
                        ×
                    </button>
                </span>
            @endif
        </div>
    </div>
    @endif

    <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-container/50">
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">No. KK</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Kepala Keluarga</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Alamat</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">RT</th>
                        <th class="text-center px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Anggota</th>
                        <th class="text-center px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant/20">
                    @foreach ($keluargaList as $k)
                        <tr class="hover:bg-surface-container/30 transition-colors">
                            <td class="px-lg py-4 text-body-sm font-mono text-on-surface">{{ $k->no_kk }}</td>
                            <td class="px-lg py-4 text-body-md font-semibold text-on-surface">{{ $k->kepala_keluarga }}</td>
                            <td class="px-lg py-4 text-body-sm text-on-surface-variant">{{ $k->alamat ?? '-' }}</td>
                            <td class="px-lg py-4 text-body-sm text-on-surface-variant font-semibold">RT {{ $k->rt }}</td>
                            <td class="px-lg py-4 text-center text-body-sm text-on-surface">{{ $k->penduduk_count }} org</td>
                            <td class="px-lg py-4 text-center">
                                <div class="flex items-center justify-center gap-xs">
                                    <button onclick="showKeluargaModal({
                                        id: '{{ $k->id }}',
                                        no_kk: '{{ $k->no_kk }}',
                                        kepala_keluarga: '{{ $k->kepala_keluarga }}',
                                        alamat: '{{ $k->alamat ?? '' }}',
                                        rt: '{{ $k->rt ?? '' }}',
                                        rw: '{{ $k->rw ?? '' }}',
                                        desa: '{{ $k->desa ?? '' }}',
                                        kecamatan: '{{ $k->kecamatan ?? '' }}'
                                    })" class="inline-flex items-center gap-1 bg-amber-500/10 text-amber-700 hover:bg-amber-500 hover:text-white px-2.5 py-1 rounded-lg text-xs font-bold transition-all">
                                        <span class="material-symbols-outlined text-[15px]">edit</span>
                                        <span>Edit</span>
                                    </button>
                                    <button onclick="confirmDeleteKeluarga('{{ route('admin.keluarga.destroy', $k) }}', '{{ addslashes($k->kepala_keluarga) }}', '{{ $k->no_kk }}')"
                                            type="button"
                                            class="inline-flex items-center gap-1 bg-red-500/10 text-red-600 hover:bg-red-600 hover:text-white px-2.5 py-1 rounded-lg text-xs font-bold transition-all">
                                        <span class="material-symbols-outlined text-[15px]">delete</span>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($keluargaList->hasPages())
            <div class="p-lg border-t border-surface-variant/20">{{ $keluargaList->links() }}</div>
        @endif
    </div>
</div>
@endsection

@include('components.keluarga-modal')

<!-- Custom Delete Modal Keluarga -->
<div id="deleteKeluargaModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-md flex items-center justify-center p-4">
    <div class="relative bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 transform transition-all text-center">
        <button onclick="closeDeleteKeluargaModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-1.5 rounded-full hover:bg-slate-100 transition-colors">
            <span class="material-symbols-outlined text-xl">close</span>
        </button>

        <div class="w-16 h-16 rounded-full bg-red-100 text-red-600 mx-auto mb-4 flex items-center justify-center border-4 border-red-50 shadow-inner">
            <span class="material-symbols-outlined text-3xl">delete_forever</span>
        </div>

        <h3 class="text-xl font-black text-slate-900 mb-1">Konfirmasi Hapus Data KK</h3>
        <p class="text-xs text-slate-500 mb-4">Apakah Anda yakin ingin menghapus data Kartu Keluarga berikut?</p>

        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 mb-6 text-left text-xs space-y-1.5">
            <div class="flex justify-between">
                <span class="text-slate-400 font-medium">Kepala Keluarga:</span>
                <span id="deleteKeluargaKepala" class="font-bold text-slate-900"></span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400 font-medium">No. KK:</span>
                <span id="deleteKeluargaNoKk" class="font-mono font-bold text-slate-900"></span>
            </div>
        </div>

        <form id="deleteKeluargaForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteKeluargaModal()" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 px-4 rounded-xl text-xs transition-all">
                    Batal
                </button>
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-extrabold py-3 px-4 rounded-xl text-xs shadow-lg shadow-red-600/30 transition-all flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-base">delete</span>
                    <span>Ya, Hapus Data</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function confirmDeleteKeluarga(url, kepala, noKk) {
    document.getElementById('deleteKeluargaForm').action = url;
    document.getElementById('deleteKeluargaKepala').textContent = kepala;
    document.getElementById('deleteKeluargaNoKk').textContent = noKk;
    document.getElementById('deleteKeluargaModal').classList.remove('hidden');
}

function closeDeleteKeluargaModal() {
    document.getElementById('deleteKeluargaModal').classList.add('hidden');
}
// Toggle filter panel
function toggleFilterPanel() {
    const panel = document.getElementById('filterPanel');
    panel.classList.toggle('hidden');
}

// Reset all filters
function resetFilter() {
    window.location.href = '{{ route("admin.keluarga.index") }}';
}

// Remove single filter
function removeFilter(filterName) {
    const url = new URL(window.location.href);
    url.searchParams.delete(filterName);
    window.location.href = url.toString();
}

// Auto submit filter form on change
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        // Submit form on enter in search field
        const searchInput = filterForm.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    filterForm.submit();
                }
            });
        }
        
        // Auto-submit for number inputs on blur
        const numberInputs = filterForm.querySelectorAll('input[type="number"]');
        numberInputs.forEach(input => {
            input.addEventListener('change', function() {
                filterForm.submit();
            });
        });
        
        // Auto-submit for selects on change
        const selects = filterForm.querySelectorAll('select');
        selects.forEach(select => {
            select.addEventListener('change', function() {
                filterForm.submit();
            });
        });
    }
    
    // Initialize tooltips for filter badges
    document.querySelectorAll('[title]').forEach(element => {
        element.addEventListener('mouseenter', function() {
            const title = this.getAttribute('title');
            if (title) {
                // You can add a custom tooltip here if needed
            }
        });
    });
});

// Toast notification function
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-lg py-md rounded-lg shadow-lg z-50 flex items-center gap-sm ${
        type === 'success' ? 'bg-green-50 text-green-700 border border-green-200' :
        type === 'error' ? 'bg-red-50 text-red-700 border border-red-200' :
        'bg-blue-50 text-blue-700 border border-blue-200'
    }`;
    
    const icon = type === 'success' ? 'check_circle' : type === 'error' ? 'error' : 'info';
    
    toast.innerHTML = `
        <span class="material-symbols-outlined">${icon}</span>
        <span class="text-label-sm font-medium">${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>
@endpush

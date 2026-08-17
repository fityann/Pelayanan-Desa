@extends('layouts.admin')

@section('title', 'Data Penduduk - SILAPU')

@section('content')
<div class="flex flex-col gap-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Data Penduduk</h1>
            <p class="text-body-sm text-on-surface-variant">Kelola data penduduk Desa Puspamukti</p>
        </div>
        <div class="flex gap-sm">
            <a href="{{ route('admin.penduduk.export', request()->query()) }}" class="bg-green-600 text-white px-lg py-2 rounded-full text-label-md font-bold hover:bg-green-700 transition-all flex items-center gap-sm shadow-sm">
                <span class="material-symbols-outlined text-[18px]">download</span>
                <span>Export Excel</span>
            </a>
            <button onclick="showPendudukModal()" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all flex items-center gap-sm shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Tambah Penduduk</span>
            </button>
            <button onclick="toggleFilterPanel('penduduk')" class="bg-surface-container text-on-surface px-lg py-2 rounded-full text-label-md font-bold hover:bg-surface-container-high transition-all flex items-center gap-sm">
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
    <div id="pendudukFilterPanel" class="bg-surface-container-lowest p-lg rounded-xl shadow-sm border border-outline-variant/20 hidden">
        <form id="pendudukFilterForm" method="GET" class="space-y-md">
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
            
            <!-- Jenis Kelamin & Status -->
            <div class="grid grid-cols-2 gap-md">
                <div>
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-body-sm">
                        <option value="">Semua</option>
                        <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">Status Perkawinan</label>
                    <select name="status_perkawinan" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-body-sm">
                        <option value="">Semua</option>
                        <option value="Belum Kawin" {{ request('status_perkawinan') == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                        <option value="Kawin" {{ request('status_perkawinan') == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                        <option value="Cerai Hidup" {{ request('status_perkawinan') == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                        <option value="Cerai Mati" {{ request('status_perkawinan') == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                    </select>
                </div>
            </div>
            
            <!-- Pendidikan & Agama -->
            <div class="grid grid-cols-2 gap-md">
                <div>
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">Pendidikan</label>
                    <select name="pendidikan" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-body-sm">
                        <option value="">Semua</option>
                        <option value="Tidak Sekolah" {{ request('pendidikan') == 'Tidak Sekolah' ? 'selected' : '' }}>Tidak Sekolah</option>
                        <option value="SD" {{ request('pendidikan') == 'SD' ? 'selected' : '' }}>SD</option>
                        <option value="SMP" {{ request('pendidikan') == 'SMP' ? 'selected' : '' }}>SMP</option>
                        <option value="SMA" {{ request('pendidikan') == 'SMA' ? 'selected' : '' }}>SMA</option>
                        <option value="D1/D2/D3" {{ request('pendidikan') == 'D1/D2/D3' ? 'selected' : '' }}>D1/D2/D3</option>
                        <option value="S1" {{ request('pendidikan') == 'S1' ? 'selected' : '' }}>S1</option>
                        <option value="S2" {{ request('pendidikan') == 'S2' ? 'selected' : '' }}>S2</option>
                        <option value="S3" {{ request('pendidikan') == 'S3' ? 'selected' : '' }}>S3</option>
                    </select>
                </div>
                <div>
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">Agama</label>
                    <select name="agama" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-body-sm">
                        <option value="">Semua</option>
                        <option value="Islam" {{ request('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                        <option value="Kristen" {{ request('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                        <option value="Katolik" {{ request('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                        <option value="Hindu" {{ request('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                        <option value="Buddha" {{ request('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                        <option value="Konghucu" {{ request('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                    </select>
                </div>
            </div>
            
            <!-- Sorting -->
            <div class="grid grid-cols-2 gap-md">
                <div>
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">Urutkan Berdasarkan</label>
                    <select name="sort_by" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-body-sm">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Input</option>
                        <option value="nama" {{ request('sort_by') == 'nama' ? 'selected' : '' }}>Nama</option>
                        <option value="nik" {{ request('sort_by') == 'nik' ? 'selected' : '' }}>NIK</option>
                        <option value="tanggal_lahir" {{ request('sort_by') == 'tanggal_lahir' ? 'selected' : '' }}>Tanggal Lahir</option>
                        <option value="rt" {{ request('sort_by') == 'rt' ? 'selected' : '' }}>RT</option>
                        <option value="rw" {{ request('sort_by') == 'rw' ? 'selected' : '' }}>RW</option>
                    </select>
                </div>
                <div>
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">Urutan</label>
                    <select name="sort_order" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-body-sm">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Terbaru / A-Z</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Terlama / Z-A</option>
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
                <button type="button" onclick="resetPendudukFilter()"
                        class="flex-1 bg-surface border border-outline-variant text-on-surface hover:bg-surface-container-high px-lg py-3 rounded-lg text-label-sm font-medium transition-colors flex items-center justify-center gap-sm">
                    <span class="material-symbols-outlined">refresh</span>
                    Reset Filter
                </button>
            </div>
        </form>
    </div>
    
    <!-- Active Filters Display -->
    @if(request()->hasAny(['search', 'rt', 'rw', 'jenis_kelamin', 'status_perkawinan', 'pendidikan', 'agama']))
    <div class="bg-surface-container p-md rounded-xl">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-sm">
                <span class="material-symbols-outlined text-primary">filter_alt</span>
                <span class="text-label-sm font-medium text-on-surface">Filter Aktif:</span>
            </div>
            <button onclick="resetPendudukFilter()" class="text-label-sm text-primary hover:text-primary/80">
                Hapus Semua
            </button>
        </div>
        <div class="flex flex-wrap gap-sm mt-sm">
            @if(request('search'))
                <span class="bg-primary/10 text-primary px-3 py-1.5 rounded-full text-[11px] flex items-center gap-xs">
                    Cari: "{{ request('search') }}"
                    <button onclick="removePendudukFilter('search')" class="text-primary/70 hover:text-primary">
                        ×
                    </button>
                </span>
            @endif
            @if(request('rt'))
                <span class="bg-blue-500/10 text-blue-600 px-3 py-1.5 rounded-full text-[11px] flex items-center gap-xs">
                    RT: {{ request('rt') }}
                    <button onclick="removePendudukFilter('rt')" class="text-blue-600/70 hover:text-blue-600">
                        ×
                    </button>
                </span>
            @endif
            @if(request('rw'))
                <span class="bg-green-500/10 text-green-600 px-3 py-1.5 rounded-full text-[11px] flex items-center gap-xs">
                    RW: {{ request('rw') }}
                    <button onclick="removePendudukFilter('rw')" class="text-green-600/70 hover:text-green-600">
                        ×
                    </button>
                </span>
            @endif
            @if(request('jenis_kelamin'))
                <span class="bg-pink-500/10 text-pink-600 px-3 py-1.5 rounded-full text-[11px] flex items-center gap-xs">
                    Jenis Kelamin: {{ request('jenis_kelamin') == 'L' ? 'Laki-laki' : 'Perempuan' }}
                    <button onclick="removePendudukFilter('jenis_kelamin')" class="text-pink-600/70 hover:text-pink-600">
                        ×
                    </button>
                </span>
            @endif
            @if(request('status_perkawinan'))
                <span class="bg-purple-500/10 text-purple-600 px-3 py-1.5 rounded-full text-[11px] flex items-center gap-xs">
                    Status: {{ request('status_perkawinan') }}
                    <button onclick="removePendudukFilter('status_perkawinan')" class="text-purple-600/70 hover:text-purple-600">
                        ×
                    </button>
                </span>
            @endif
            @if(request('pendidikan'))
                <span class="bg-yellow-500/10 text-yellow-600 px-3 py-1.5 rounded-full text-[11px] flex items-center gap-xs">
                    Pendidikan: {{ request('pendidikan') }}
                    <button onclick="removePendudukFilter('pendidikan')" class="text-yellow-600/70 hover:text-yellow-600">
                        ×
                    </button>
                </span>
            @endif
            @if(request('agama'))
                <span class="bg-red-500/10 text-red-600 px-3 py-1.5 rounded-full text-[11px] flex items-center gap-xs">
                    Agama: {{ request('agama') }}
                    <button onclick="removePendudukFilter('agama')" class="text-red-600/70 hover:text-red-600">
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
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest w-12">No.</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">NIK</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Nama</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">L/P</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">RT</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">No. KK</th>
                        <th class="text-center px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant/20">
                    @foreach ($penduduk as $p)
                        <tr class="hover:bg-surface-container/30 transition-colors">
                            <td class="px-lg py-4 text-body-sm text-on-surface-variant font-medium">{{ $penduduk->firstItem() + $loop->iteration - 1 }}</td>
                            <td class="px-lg py-4 text-body-sm font-mono text-on-surface">{{ $p->nik }}</td>
                            <td class="px-lg py-4 text-body-md font-semibold text-on-surface">{{ $p->nama }}</td>
                            <td class="px-lg py-4 text-body-sm text-on-surface-variant">{{ $p->jenis_kelamin ?? '-' }}</td>
                            <td class="px-lg py-4 text-body-sm text-on-surface-variant font-semibold">RT {{ $p->rt }}</td>
                            <td class="px-lg py-4 text-body-sm text-on-surface-variant">{{ $p->no_kk ?? '-' }}</td>
                            <td class="px-lg py-4 text-center">
                                <div class="flex items-center justify-center gap-xs">
                                    <a href="{{ route('admin.penduduk.show', $p) }}" class="inline-flex items-center gap-1 bg-blue-500/10 text-blue-700 hover:bg-blue-500 hover:text-white px-2.5 py-1 rounded-lg text-xs font-bold transition-all">
                                        <span class="material-symbols-outlined text-[15px]">visibility</span>
                                        <span>Detail</span>
                                    </a>
                                    <button onclick="showPendudukModal({
                                        id: '{{ $p->id }}',
                                        nik: '{{ $p->nik }}',
                                        no_kk: '{{ $p->no_kk ?? '' }}',
                                        nama: '{{ $p->nama }}',
                                        jenis_kelamin: '{{ $p->jenis_kelamin ?? 'L' }}',
                                        tempat_lahir: '{{ $p->tempat_lahir ?? '' }}',
                                        tanggal_lahir: '{{ $p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->format('Y-m-d') : '' }}',
                                        alamat: '{{ $p->alamat ?? '' }}',
                                        rt: '{{ $p->rt ?? '' }}',
                                        rw: '{{ $p->rw ?? '' }}',
                                        agama: '{{ $p->agama ?? '' }}',
                                        status_perkawinan: '{{ $p->status_perkawinan ?? '' }}',
                                        pendidikan: '{{ $p->pendidikan_terakhir ?? '' }}',
                                        pekerjaan: '{{ $p->pekerjaan ?? '' }}',
                                        kewarganegaraan: '{{ $p->kewarganegaraan ?? 'WNI' }}'
                                    })" class="inline-flex items-center gap-1 bg-amber-500/10 text-amber-700 hover:bg-amber-500 hover:text-white px-2.5 py-1 rounded-lg text-xs font-bold transition-all">
                                        <span class="material-symbols-outlined text-[15px]">edit</span>
                                        <span>Edit</span>
                                    </button>
                                    <button onclick="confirmDeletePenduduk('{{ route('admin.penduduk.destroy', $p) }}', '{{ addslashes($p->nama) }}', '{{ $p->nik }}')"
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
        @if ($penduduk->hasPages())
            <div class="p-lg border-t border-surface-variant/20">{{ $penduduk->links() }}</div>
        @endif
    </div>

    @include('components.penduduk-modal')

    <!-- Custom Delete Modal Penduduk -->
    <div id="deletePendudukModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-md flex items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 transform transition-all text-center">
            <button onclick="closeDeletePendudukModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-1.5 rounded-full hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>

            <div class="w-16 h-16 rounded-full bg-red-100 text-red-600 mx-auto mb-4 flex items-center justify-center border-4 border-red-50 shadow-inner">
                <span class="material-symbols-outlined text-3xl">delete_forever</span>
            </div>

            <h3 class="text-xl font-black text-slate-900 mb-1">Konfirmasi Hapus Data</h3>
            <p class="text-xs text-slate-500 mb-4">Apakah Anda yakin ingin menghapus data penduduk berikut?</p>

            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 mb-6 text-left text-xs space-y-1.5">
                <div class="flex justify-between">
                    <span class="text-slate-400 font-medium">Nama Penduduk:</span>
                    <span id="deletePendudukNama" class="font-bold text-slate-900"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400 font-medium">NIK KTP:</span>
                    <span id="deletePendudukNik" class="font-mono font-bold text-slate-900"></span>
                </div>
            </div>

            <form id="deletePendudukForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeletePendudukModal()" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 px-4 rounded-xl text-xs transition-all">
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
</div>
@endsection

@push('scripts')
<script>
function confirmDeletePenduduk(url, nama, nik) {
    document.getElementById('deletePendudukForm').action = url;
    document.getElementById('deletePendudukNama').textContent = nama;
    document.getElementById('deletePendudukNik').textContent = nik;
    document.getElementById('deletePendudukModal').classList.remove('hidden');
}

function closeDeletePendudukModal() {
    document.getElementById('deletePendudukModal').classList.add('hidden');
}
// Toggle filter panel
function toggleFilterPanel(type = 'penduduk') {
    const panel = document.getElementById(`${type}FilterPanel`);
    panel.classList.toggle('hidden');
}

// Reset all filters for penduduk
function resetPendudukFilter() {
    window.location.href = '{{ route("admin.penduduk.index") }}';
}

// Remove single filter for penduduk
function removePendudukFilter(filterName) {
    const url = new URL(window.location.href);
    url.searchParams.delete(filterName);
    window.location.href = url.toString();
}

// Modal functions for penduduk
function showPendudukModal(penduduk = null) {
    const modal = document.getElementById('pendudukModal');
    const title = document.getElementById('pendudukModalTitle');
    const form = document.getElementById('pendudukForm');
    
    if (penduduk) {
        // Edit mode
        title.textContent = 'Edit Data Penduduk';
        document.getElementById('pendudukId').value = penduduk.id;
        document.getElementById('nik').value = penduduk.nik;
        document.getElementById('no_kk').value = penduduk.no_kk;
        document.getElementById('nama').value = penduduk.nama;
        
        // Set jenis kelamin radio
        const jkRadios = document.querySelectorAll('input[name="jenis_kelamin"]');
        jkRadios.forEach(radio => {
            radio.checked = radio.value === penduduk.jenis_kelamin;
        });
        
        document.getElementById('tempat_lahir').value = penduduk.tempat_lahir;
        document.getElementById('tanggal_lahir').value = penduduk.tanggal_lahir;
        document.getElementById('alamat').value = penduduk.alamat;
        document.getElementById('rt').value = penduduk.rt;
        document.getElementById('rw').value = penduduk.rw;
        document.getElementById('agama').value = penduduk.agama;
        document.getElementById('status_perkawinan').value = penduduk.status_perkawinan;
        document.getElementById('pendidikan').value = penduduk.pendidikan || penduduk.pendidikan_terakhir;
        document.getElementById('pekerjaan').value = penduduk.pekerjaan;
        
        // Set kewarganegaraan radio
        const kewarganegaraanRadios = document.querySelectorAll('input[name="kewarganegaraan"]');
        kewarganegaraanRadios.forEach(radio => {
            radio.checked = radio.value === penduduk.kewarganegaraan;
        });
        
        // Update form action for update
        form.action = `/admin/penduduk/${penduduk.id}`;
        
        let methodInput = form.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            form.appendChild(methodInput);
        }
        methodInput.value = 'PUT';
    } else {
        // Add mode
        title.textContent = 'Tambah Data Penduduk';
        form.reset();
        document.getElementById('pendudukId').value = '';
        
        // Reset form action for create
        form.action = '{{ route("admin.penduduk.store") }}';
        
        let methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) {
            methodInput.remove();
        }
    }
    
    // Reset error alert in modal
    const errorAlert = document.getElementById('modalErrorAlert');
    if (errorAlert) {
        errorAlert.classList.add('hidden');
        document.getElementById('modalErrorMsg').innerHTML = '';
    }

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closePendudukModal() {
    document.getElementById('pendudukModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

@if($errors->any())
document.addEventListener('DOMContentLoaded', function() {
    showPendudukModal();
    const errorAlert = document.getElementById('modalErrorAlert');
    if (errorAlert) {
        errorAlert.classList.remove('hidden');
    }
});
@endif

// Form submit loading indicator
document.addEventListener('DOMContentLoaded', function() {
    const pendudukForm = document.getElementById('pendudukForm');
    if (pendudukForm) {
        pendudukForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin text-sm">sync</span> Menyimpan...';
            }
        });
    }
});
    
    // Auto submit filter form on change
    const filterForm = document.getElementById('pendudukFilterForm');
    if (filterForm) {
        // Auto-submit for selects on change
        const selects = filterForm.querySelectorAll('select');
        selects.forEach(select => {
            select.addEventListener('change', function() {
                filterForm.submit();
            });
        });
    }
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

// Close modal with ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const keluargaModal = document.getElementById('keluargaModal');
        const pendudukModal = document.getElementById('pendudukModal');
        
        if (keluargaModal && !keluargaModal.classList.contains('hidden')) {
            closeKeluargaModal();
        }
        if (pendudukModal && !pendudukModal.classList.contains('hidden')) {
            closePendudukModal();
        }
    }
});

// Close modal with click outside
const keluargaModal = document.getElementById('keluargaModal');
if (keluargaModal) {
    keluargaModal.addEventListener('click', function(e) {
        if (e.target.id === 'keluargaModal') {
            closeKeluargaModal();
        }
    });
}

const pendudukModal = document.getElementById('pendudukModal');
if (pendudukModal) {
    pendudukModal.addEventListener('click', function(e) {
        if (e.target.id === 'pendudukModal') {
            closePendudukModal();
        }
    });
}
</script>
@endpush

@extends('layouts.admin')

@section('title', 'Dashboard Pengaduan')

@section('content')
<div class="space-y-lg">
    <!-- Header dengan Statistik -->
    <div class="bg-surface-container-low p-lg rounded-2xl shadow-sm border border-outline-variant/10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-md">
            <div>
                <h1 class="text-headline-md font-semibold text-on-surface mb-xs">Dashboard Pengaduan</h1>
                <p class="text-body-md text-on-surface-variant">Monitoring pengaduan dari warga via QR Code dan Web</p>
            </div>
            
            <div class="flex gap-sm">
                <button onclick="refreshData()" class="bg-surface border border-outline-variant text-on-surface hover:bg-surface-container-high px-lg py-2 rounded-lg text-label-sm font-medium transition-colors flex items-center gap-sm">
                    <span class="material-symbols-outlined">refresh</span>
                    Refresh
                </button>
                <button onclick="exportData()" class="bg-primary text-on-primary hover:bg-primary/90 px-lg py-2 rounded-lg text-label-sm font-medium transition-colors flex items-center gap-sm">
                    <span class="material-symbols-outlined">download</span>
                    Export
                </button>
            </div>
        </div>
        
        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-md mt-lg">
            <div class="bg-surface-container p-md rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-label-sm text-on-surface-variant">Total Pengaduan</p>
                        <h3 class="text-headline-md font-semibold text-on-surface mt-xs">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">campaign</span>
                    </div>
                </div>
                <p class="text-[11px] text-on-surface-variant mt-sm">{{ $stats['today'] }} hari ini</p>
            </div>
            
            <div class="bg-surface-container p-md rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-label-sm text-on-surface-variant">Diterima</p>
                        <h3 class="text-headline-md font-semibold text-blue-600 mt-xs">{{ $stats['diterima'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-500">inbox</span>
                    </div>
                </div>
                <p class="text-[11px] text-blue-600 mt-sm">{{ $stats['diterima_percent'] }}% dari total</p>
            </div>
            
            <div class="bg-surface-container p-md rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-label-sm text-on-surface-variant">Diproses</p>
                        <h3 class="text-headline-md font-semibold text-yellow-600 mt-xs">{{ $stats['diproses'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-yellow-500/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-yellow-500">pending</span>
                    </div>
                </div>
                <p class="text-[11px] text-yellow-600 mt-sm">{{ $stats['diproses_percent'] }}% dari total</p>
            </div>
            
            <div class="bg-surface-container p-md rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-label-sm text-on-surface-variant">Selesai</p>
                        <h3 class="text-headline-md font-semibold text-green-600 mt-xs">{{ $stats['selesai'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-500">check_circle</span>
                    </div>
                </div>
                <p class="text-[11px] text-green-600 mt-sm">{{ $stats['selesai_percent'] }}% dari total</p>
            </div>
        </div>
    </div>

    <!-- Filter dan Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
        <!-- Filter Panel -->
        <div class="bg-surface-container-low p-lg rounded-2xl border border-outline-variant/10">
            <h3 class="text-label-lg font-medium text-on-surface mb-md">Filter Data</h3>
            
            <form id="filterForm" class="space-y-md">
                <!-- Status Filter -->
                <div>
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">Status</label>
                    <div class="space-y-xs">
                        @foreach(['diterima', 'diproses', 'selesai'] as $status)
                            <label class="flex items-center gap-sm">
                                <input type="checkbox" name="status[]" value="{{ $status }}" 
                                       class="rounded border-outline-variant text-primary focus:ring-primary" 
                                       {{ in_array($status, request('status', ['diterima', 'diproses'])) ? 'checked' : '' }}>
                                <span class="text-label-sm text-on-surface capitalize">{{ $status }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <!-- Kategori Filter -->
                <div>
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">Kategori</label>
                    <select name="kategori" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-body-sm">
                        <option value="">Semua Kategori</option>
                        @foreach($kategories as $key => $label)
                            <option value="{{ $key }}" {{ request('kategori') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Sumber Akses -->
                <div>
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">Sumber</label>
                    <div class="space-y-xs">
                        <label class="flex items-center gap-sm">
                            <input type="checkbox" name="sumber[]" value="qr_code" 
                                   class="rounded border-outline-variant text-primary focus:ring-primary"
                                   {{ in_array('qr_code', request('sumber', ['qr_code', 'web'])) ? 'checked' : '' }}>
                            <span class="text-label-sm text-on-surface">QR Code</span>
                        </label>
                        <label class="flex items-center gap-sm">
                            <input type="checkbox" name="sumber[]" value="web" 
                                   class="rounded border-outline-variant text-primary focus:ring-primary"
                                   {{ in_array('web', request('sumber', ['qr_code', 'web'])) ? 'checked' : '' }}>
                            <span class="text-label-sm text-on-surface">Web</span>
                        </label>
                    </div>
                </div>
                
                <!-- Tanggal -->
                <div>
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">Tanggal</label>
                    <div class="grid grid-cols-2 gap-sm">
                        <input type="date" name="start_date" value="{{ request('start_date', date('Y-m-d', strtotime('-7 days'))) }}"
                               class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-body-sm">
                        <input type="date" name="end_date" value="{{ request('end_date', date('Y-m-d')) }}"
                               class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-body-sm">
                    </div>
                </div>
                
                <!-- RT/RW -->
                <div class="grid grid-cols-2 gap-sm">
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">RT</label>
                        <input type="number" name="rt" value="{{ request('rt') }}" min="1" max="99"
                               class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-body-sm">
                    </div>
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">RW</label>
                        <input type="number" name="rw" value="{{ request('rw') }}" min="1" max="99"
                               class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-body-sm">
                    </div>
                </div>
                
                <!-- Button Actions -->
                <div class="flex gap-sm pt-sm">
                    <button type="submit" 
                            class="flex-1 bg-primary text-on-primary hover:bg-primary/90 px-lg py-2 rounded-lg text-label-sm font-medium transition-colors">
                        Terapkan Filter
                    </button>
                    <button type="button" onclick="resetFilter()"
                            class="flex-1 bg-surface border border-outline-variant text-on-surface hover:bg-surface-container-high px-lg py-2 rounded-lg text-label-sm font-medium transition-colors">
                        Reset
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Charts -->
        <div class="lg:col-span-2 space-y-lg">
            <!-- Chart Kategori -->
            <div class="bg-surface-container-low p-lg rounded-2xl border border-outline-variant/10">
                <h3 class="text-label-lg font-medium text-on-surface mb-md">Pengaduan per Kategori</h3>
                <div class="h-48">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
            
            <!-- Chart Trend 7 Hari -->
            <div class="bg-surface-container-low p-lg rounded-2xl border border-outline-variant/10">
                <h3 class="text-label-lg font-medium text-on-surface mb-md">Trend 7 Hari Terakhir</h3>
                <div class="h-48">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Pengaduan -->
    <div class="bg-surface-container-low p-lg rounded-2xl border border-outline-variant/10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-md mb-md">
            <div>
                <h3 class="text-label-lg font-medium text-on-surface">Daftar Pengaduan</h3>
                <p class="text-body-sm text-on-surface-variant mt-xs">{{ $pengaduans->total() }} data ditemukan</p>
            </div>
            <div class="text-label-sm text-on-surface-variant">
                Sorted by: <select id="sortSelect" class="bg-transparent border-none text-on-surface font-medium outline-none">
                    <option value="terbaru">Terbaru</option>
                    <option value="terlama">Terlama</option>
                    <option value="prioritas">Prioritas Tinggi</option>
                </select>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-outline-variant/20">
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Tiket</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Pengadu</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Kategori</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Lokasi</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Status</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Waktu</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengaduans as $pengaduan)
                        <tr class="border-b border-outline-variant/10 hover:bg-surface-container transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex flex-col">
                                    <span class="text-label-sm font-medium text-on-surface">{{ $pengaduan->tiket_id }}</span>
                                    <span class="text-[11px] text-on-surface-variant">{{ $pengaduan->judul }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex flex-col">
                                    <span class="text-label-sm text-on-surface">{{ $pengaduan->nama_pelapor ?? ($pengaduan->user?->name ?? '-') }}</span>
                                    <span class="text-[11px] text-on-surface-variant">{{ $pengaduan->whatsapp ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-sm">
                                    @php
                                        $icons = [
                                            'sampah' => 'delete',
                                            'jalan' => 'directions',
                                            'drainase' => 'water_damage',
                                            'penerangan' => 'lightbulb',
                                            'air' => 'water_drop',
                                            'lainnya' => 'campaign'
                                        ];
                                    @endphp
                                    <span class="material-symbols-outlined text-base text-on-surface-variant">
                                        {{ $icons[$pengaduan->kategori] ?? 'campaign' }}
                                    </span>
                                    <span class="text-label-sm text-on-surface">{{ $pengaduan->kategori_display }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex flex-col">
                                    <span class="text-label-sm text-on-surface">{{ $pengaduan->lokasi_display }}</span>
                                    @if($pengaduan->sumber_akses == 'qr_code')
                                        <span class="text-[11px] text-green-600 flex items-center gap-xs">
                                            <span class="material-symbols-outlined text-xs">qr_code</span>
                                            QR Code
                                        </span>
                                    @else
                                        <span class="text-[11px] text-blue-600">Web</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                @php $status = $pengaduan->status_display; @endphp
                                <span class="text-label-sm px-2 py-1 rounded-full {{ $status['color'] }}">
                                    {{ $status['label'] }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex flex-col">
                                    <span class="text-label-sm text-on-surface">{{ $pengaduan->tanggal_diterima->format('d/m/Y') }}</span>
                                    <span class="text-[11px] text-on-surface-variant">{{ $pengaduan->waktu_lalu }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-xs">
                                    <button onclick="viewPengaduan({{ $pengaduan->id }})" 
                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-blue-600 hover:bg-blue-50 transition-colors"
                                            title="Detail">
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                    </button>
                                    @if($pengaduan->status == 'diterima')
                                        <button onclick="processPengaduan({{ $pengaduan->id }})" 
                                                class="w-8 h-8 flex items-center justify-center rounded-lg text-yellow-600 hover:bg-yellow-50 transition-colors"
                                                title="Proses">
                                            <span class="material-symbols-outlined text-sm">play_arrow</span>
                                        </button>
                                    @endif
                                    @if($pengaduan->status == 'diproses')
                                        <button onclick="completePengaduan({{ $pengaduan->id }})" 
                                                class="w-8 h-8 flex items-center justify-center rounded-lg text-green-600 hover:bg-green-50 transition-colors"
                                                title="Selesaikan">
                                            <span class="material-symbols-outlined text-sm">check</span>
                                        </button>
                                    @endif
                                    @if(count($pengaduan->foto_list) > 0)
                                        <button onclick="viewPhoto('{{ $pengaduan->foto_list[0] }}')" 
                                                class="w-8 h-8 flex items-center justify-center rounded-lg text-purple-600 hover:bg-purple-50 transition-colors"
                                                title="Lihat Foto ({{ count($pengaduan->foto_list) }})">
                                            <span class="material-symbols-outlined text-sm">photo</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 px-4 text-center">
                                <div class="flex flex-col items-center gap-sm">
                                    <span class="material-symbols-outlined text-4xl text-on-surface-variant">campaign</span>
                                    <p class="text-label-sm text-on-surface">Belum ada pengaduan</p>
                                    <p class="text-body-sm text-on-surface-variant">Data akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($pengaduans->hasPages())
            <div class="mt-lg pt-md border-t border-outline-variant/20">
                {{ $pengaduans->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 hidden">
    <div id="detailModalContainer" class="bg-surface-container w-full max-w-4xl rounded-2xl shadow-2xl max-h-[90vh] overflow-hidden">
        <!-- Modal content will be loaded via AJAX -->
    </div>
</div>

<!-- Modal Photo -->
<div id="photoModal" class="fixed inset-0 bg-black/90 flex items-center justify-center p-4 z-50 hidden">
    <div class="relative">
        <button onclick="closePhotoModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
            <span class="material-symbols-outlined text-2xl">close</span>
        </button>
        <img id="modalPhoto" class="max-w-full max-h-[80vh] rounded-lg">
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($chartData['categoryLabels']) !!},
            datasets: [{
                data: {!! json_encode($chartData['categoryData']) !!},
                backgroundColor: [
                    '#ef4444', '#f97316', '#eab308', '#22c55e', '#3b82f6', '#8b5cf6'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 12,
                        padding: 15
                    }
                }
            }
        }
    });
    
    // Trend Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    const trendChart = new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['trendLabels']) !!},
            datasets: [
                {
                    label: 'Diterima',
                    data: {!! json_encode($chartData['trendDiterima']) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Diproses',
                    data: {!! json_encode($chartData['trendDiproses']) !!},
                    borderColor: '#eab308',
                    backgroundColor: 'rgba(234, 179, 8, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Selesai',
                    data: {!! json_encode($chartData['trendSelesai']) !!},
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    
    // Filter Form
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });
    
    function applyFilters() {
        const formData = new FormData(this);
        const params = new URLSearchParams();
        
        // Collect all form data
        formData.forEach((value, key) => {
            if (Array.isArray(value)) {
                value.forEach(val => params.append(key + '[]', val));
            } else {
                params.append(key, value);
            }
        });
        
        window.location.search = params.toString();
    }
    
    function resetFilter() {
        window.location.search = '';
    }
    
    function refreshData() {
        window.location.reload();
    }
    
    function exportData() {
        const params = new URLSearchParams(window.location.search);
        window.open('/admin/pengaduan/export?' + params.toString(), '_blank');
    }
    
    // Detail View
    function viewPengaduan(id) {
        const modal = document.getElementById('detailModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        const target = modal.querySelector('.bg-surface-container');
        target.innerHTML = `
            <div class="p-lg text-center">
                <span class="material-symbols-outlined text-4xl text-on-surface-variant animate-spin mb-sm">progress_activity</span>
                <p class="text-label-sm text-on-surface-variant">Memuat detail...</p>
            </div>
        `;

        // Load detail via AJAX
        fetch(`/admin/pengaduan/${id}/detail`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.html) {
                    target.innerHTML = data.html;
                } else {
                    target.innerHTML = `
                        <div class="p-lg text-center">
                            <span class="material-symbols-outlined text-4xl text-red-500 mb-sm">error</span>
                            <p class="text-label-sm text-on-surface">${data.message || 'Gagal memuat data'}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading detail:', error);
                target.innerHTML = `
                    <div class="p-lg text-center">
                        <span class="material-symbols-outlined text-4xl text-red-500 mb-sm">error</span>
                        <p class="text-label-sm text-on-surface">Gagal memuat data</p>
                    </div>
                `;
            });
    }
    
    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // Photo View
    function viewPhoto(photoUrl) {
        const modal = document.getElementById('photoModal');
        const img = document.getElementById('modalPhoto');
        
        img.src = '/storage/' + photoUrl;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closePhotoModal() {
        document.getElementById('photoModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // Action Functions
    function processPengaduan(id) {
        if (confirm('Proses pengaduan ini?')) {
            fetch(`/admin/pengaduan/${id}/proses`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Pengaduan berhasil diproses', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast('Gagal memproses pengaduan', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan', 'error');
            });
        }
    }
    
    function completePengaduan(id) {
        if (confirm('Tandai pengaduan sebagai selesai?')) {
            fetch(`/admin/pengaduan/${id}/selesai`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Pengaduan berhasil diselesaikan', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast('Gagal menyelesaikan pengaduan', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan', 'error');
            });
        }
    }
    
    // Toast Notification
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast fixed top-4 right-4 px-lg py-md rounded-lg shadow-lg z-50 flex items-center gap-sm animate-slide-in ${
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
            toast.classList.add('animate-slide-out');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    // Close modals with ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDetailModal();
            closePhotoModal();
        }
    });
</script>
@endpush
@endsection
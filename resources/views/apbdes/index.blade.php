@extends('layouts.admin')

@section('title', 'Dashboard APBDes')

@section('content')
<div class="space-y-lg">
    <!-- Header dengan Filter -->
    <div class="bg-surface-container-low p-lg rounded-2xl shadow-sm border border-outline-variant/10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-md">
            <div>
                <h1 class="text-headline-md font-semibold text-on-surface mb-xs">Dashboard APBDes</h1>
                <p class="text-body-md text-on-surface-variant">Monitoring Anggaran Pendapatan dan Belanja Desa</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-sm w-full md:w-auto">
                <select id="tahunFilter" class="bg-surface-container border border-outline-variant rounded-lg px-md py-2 text-body-sm focus:ring-2 focus:ring-primary outline-none">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
                
                <select id="bidangFilter" class="bg-surface-container border border-outline-variant rounded-lg px-md py-2 text-body-sm focus:ring-2 focus:ring-primary outline-none">
                    <option value="">Semua Bidang</option>
                    @foreach($perBidang->keys() as $bidangItem)
                        <option value="{{ $bidangItem }}" {{ $bidang == $bidangItem ? 'selected' : '' }}>{{ $bidangItem }}</option>
                    @endforeach
                </select>
                
                <button onclick="exportData('pdf')" class="bg-primary text-on-primary px-lg py-2 rounded-lg hover:bg-primary/90 transition-colors text-label-sm font-medium">
                    Export PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Ringkasan Tahun -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
        @foreach(['Pendapatan', 'Belanja', 'Pembiayaan'] as $kategori)
            @php 
                $data = $summary[$kategori] ?? ['anggaran' => 0, 'realisasi' => 0, 'persentase' => 0];
                $color = $kategori == 'Pendapatan' ? 'green' : ($kategori == 'Belanja' ? 'red' : 'blue');
            @endphp
            <div class="bg-surface-container-low p-lg rounded-2xl border border-outline-variant/10">
                <div class="flex justify-between items-start mb-md">
                    <div>
                        <h3 class="text-label-lg font-medium text-on-surface">{{ $kategori }}</h3>
                        <p class="text-[12px] text-on-surface-variant">Anggaran vs Realisasi</p>
                    </div>
                    <div class="text-label-sm px-2 py-1 rounded-full bg-{{ $color }}-50 text-{{ $color }}-600">
                        {{ number_format($data['persentase'], 1) }}%
                    </div>
                </div>
                
                <div class="space-y-xs">
                    <div class="flex justify-between">
                        <span class="text-body-sm text-on-surface-variant">Anggaran</span>
                        <span class="text-label-sm font-medium text-on-surface">Rp {{ number_format($data['anggaran'], 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-body-sm text-on-surface-variant">Realisasi</span>
                        <span class="text-label-sm font-medium text-on-surface">Rp {{ number_format($data['realisasi'], 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-body-sm text-on-surface-variant">Sisa</span>
                        <span class="text-label-sm font-medium text-on-surface">Rp {{ number_format($data['anggaran'] - $data['realisasi'], 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="mt-md">
                    <div class="h-2 bg-outline-variant/20 rounded-full overflow-hidden">
                        <div class="h-full bg-{{ $color }}-500 rounded-full" style="width: {{ min($data['persentase'], 100) }}%"></div>
                    </div>
                    <div class="flex justify-between mt-xs">
                        <span class="text-[11px] text-on-surface-variant">0%</span>
                        <span class="text-[11px] text-on-surface-variant">{{ number_format($data['persentase'], 1) }}%</span>
                        <span class="text-[11px] text-on-surface-variant">100%</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Chart dan Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-lg">
        <!-- Grafik Serapan per Bidang -->
        <div class="bg-surface-container-low p-lg rounded-2xl border border-outline-variant/10">
            <h3 class="text-label-lg font-medium text-on-surface mb-md">Serapan Anggaran per Bidang</h3>
            <div class="h-64">
                <canvas id="absorptionChart"></canvas>
            </div>
        </div>

        <!-- Breakdown Sumber Dana -->
        <div class="bg-surface-container-low p-lg rounded-2xl border border-outline-variant/10">
            <h3 class="text-label-lg font-medium text-on-surface mb-md">Breakdown Sumber Dana</h3>
            <div class="space-y-sm">
                @foreach($sourceBreakdown as $source)
                    <div class="flex items-center justify-between p-sm hover:bg-surface-container rounded-lg transition-colors">
                        <div class="flex items-center gap-sm">
                            <div class="w-3 h-3 rounded-full bg-primary"></div>
                            <span class="text-body-sm text-on-surface">{{ $source['sumber_dana'] }}</span>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-label-sm font-medium text-on-surface">Rp {{ number_format($source['realisasi'], 0, ',', '.') }}</span>
                            <span class="text-[11px] text-on-surface-variant">{{ number_format($source['persentase'], 1) }}% dari Rp {{ number_format($source['anggaran'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Perbandingan Antar Tahun -->
    <div class="bg-surface-container-low p-lg rounded-2xl border border-outline-variant/10">
        <h3 class="text-label-lg font-medium text-on-surface mb-md">Perbandingan 5 Tahun Terakhir</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-outline-variant/20">
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Tahun</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Pendapatan</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Belanja</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Pembiayaan</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comparison as $year => $data)
                        @php
                            $pendapatan = $data['Pendapatan']['realisasi'] ?? 0;
                            $belanja = $data['Belanja']['realisasi'] ?? 0;
                            $pembiayaan = $data['Pembiayaan']['realisasi'] ?? 0;
                            $saldo = $pendapatan - $belanja + $pembiayaan;
                        @endphp
                        <tr class="border-b border-outline-variant/10 hover:bg-surface-container transition-colors">
                            <td class="py-3 px-4 text-label-sm text-on-surface font-medium">{{ $year }}</td>
                            <td class="py-3 px-4 text-label-sm text-on-surface">Rp {{ number_format($pendapatan, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-label-sm text-on-surface">Rp {{ number_format($belanja, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-label-sm text-on-surface">Rp {{ number_format($pembiayaan, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-label-sm {{ $saldo >= 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                Rp {{ number_format(abs($saldo), 0, ',', '.') }} {{ $saldo >= 0 ? '(Surplus)' : '(Defisit)' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Rincian per Kegiatan -->
    <div class="bg-surface-container-low p-lg rounded-2xl border border-outline-variant/10">
        <div class="flex justify-between items-center mb-md">
            <h3 class="text-label-lg font-medium text-on-surface">Rincian Kegiatan</h3>
            <div class="text-body-sm text-on-surface-variant">
                {{ $perBidang->count() }} Bidang • {{ $perBidang->flatten(1)->count() }} Kegiatan
            </div>
        </div>

        @foreach($perBidang as $namaBidang => $kegiatan)
            <div class="mb-lg">
                <div class="flex items-center justify-between p-md bg-surface-container rounded-lg mb-sm">
                    <h4 class="text-label-md font-medium text-on-surface">{{ $namaBidang }}</h4>
                    <div class="flex items-center gap-md">
                        <div class="text-label-sm text-on-surface-variant">
                            Rp {{ number_format($kegiatan->sum('anggaran'), 0, ',', '.') }} Anggaran
                        </div>
                        <div class="text-label-sm text-on-surface">
                            {{ number_format($kegiatan->avg('persentase_realisasi'), 1) }}% Realisasi
                        </div>
                    </div>
                </div>

                <div class="space-y-xs">
                    @foreach($kegiatan as $keg)
                        <div class="flex items-center justify-between p-sm hover:bg-surface-container rounded-lg transition-colors">
                            <div class="flex-1">
                                <div class="flex items-center gap-sm mb-xs">
                                    <span class="text-body-sm text-on-surface">{{ $keg->sub_bidang ? $keg->sub_bidang . ' - ' : '' }}{{ $keg->uraian }}</span>
                                    <span class="text-[11px] px-2 py-0.5 rounded-full 
                                        {{ $keg->status_kegiatan == 'selesai' ? 'bg-green-50 text-green-600' : 
                                           ($keg->status_kegiatan == 'proses' ? 'bg-yellow-50 text-yellow-600' : 
                                           'bg-gray-50 text-gray-600') }}">
                                        {{ $keg->status_kegiatan }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-md">
                                    <span class="text-[11px] text-on-surface-variant">
                                        Anggaran: Rp {{ number_format($keg->anggaran, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[11px] text-on-surface-variant">
                                        Realisasi: Rp {{ number_format($keg->realisasi, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex flex-col items-end">
                                <div class="w-32 h-2 bg-outline-variant/20 rounded-full overflow-hidden">
                                    <div class="h-full 
                                        {{ $keg->persentase_realisasi >= 90 ? 'bg-green-500' : 
                                           ($keg->persentase_realisasi >= 50 ? 'bg-yellow-500' : 
                                           'bg-red-500') }} rounded-full" 
                                        style="width: {{ min($keg->persentase_realisasi, 100) }}%">
                                    </div>
                                </div>
                                <span class="text-[11px] mt-xs text-on-surface-variant">
                                    {{ number_format($keg->persentase_realisasi, 1) }}%
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Dokumen Resmi -->
    <div class="bg-surface-container-low p-lg rounded-2xl border border-outline-variant/10">
        <h3 class="text-label-lg font-medium text-on-surface mb-md">Dokumen Resmi</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-md">
            <div class="bg-surface-container p-md rounded-xl hover:bg-surface-container-high transition-colors">
                <div class="flex items-center gap-md">
                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">description</span>
                    </div>
                    <div>
                        <h4 class="text-label-sm font-medium text-on-surface">Perdes APBDes {{ $tahun }}</h4>
                        <p class="text-[11px] text-on-surface-variant mt-xs">Peraturan Desa tentang APBDes</p>
                    </div>
                </div>
                <button class="w-full mt-md bg-primary/10 text-primary hover:bg-primary/20 px-md py-2 rounded-lg text-label-sm font-medium transition-colors">
                    Download PDF
                </button>
            </div>
            
            <div class="bg-surface-container p-md rounded-xl hover:bg-surface-container-high transition-colors">
                <div class="flex items-center gap-md">
                    <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-500">assessment</span>
                    </div>
                    <div>
                        <h4 class="text-label-sm font-medium text-on-surface">LPJ Triwulan</h4>
                        <p class="text-[11px] text-on-surface-variant mt-xs">Laporan Pertanggungjawaban</p>
                    </div>
                </div>
                <button class="w-full mt-md bg-green-500/10 text-green-600 hover:bg-green-500/20 px-md py-2 rounded-lg text-label-sm font-medium transition-colors">
                    Download PDF
                </button>
            </div>
            
            <div class="bg-surface-container p-md rounded-xl hover:bg-surface-container-high transition-colors">
                <div class="flex items-center gap-md">
                    <div class="w-12 h-12 bg-blue-500/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-500">photo_library</span>
                    </div>
                    <div>
                        <h4 class="text-label-sm font-medium text-on-surface">Dokumentasi Fisik</h4>
                        <p class="text-[11px] text-on-surface-variant mt-xs">Foto-foto Proyek Pembangunan</p>
                    </div>
                </div>
                <button class="w-full mt-md bg-blue-500/10 text-blue-600 hover:bg-blue-500/20 px-md py-2 rounded-lg text-label-sm font-medium transition-colors">
                    Lihat Album
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter tahun
        document.getElementById('tahunFilter').addEventListener('change', function() {
            updateFilter();
        });
        
        document.getElementById('bidangFilter').addEventListener('change', function() {
            updateFilter();
        });
        
        function updateFilter() {
            const tahun = document.getElementById('tahunFilter').value;
            const bidang = document.getElementById('bidangFilter').value;
            let url = new URL(window.location.href);
            url.searchParams.set('tahun', tahun);
            if (bidang) {
                url.searchParams.set('bidang', bidang);
            } else {
                url.searchParams.delete('bidang');
            }
            window.location.href = url.toString();
        }
        
        // Export function
        window.exportData = function(type) {
            const tahun = document.getElementById('tahunFilter').value;
            const bidang = document.getElementById('bidangFilter').value;
            
            let url = '/admin/apbdes/export?type=' + type + '&tahun=' + tahun;
            if (bidang) {
                url += '&bidang=' + bidang;
            }
            
            window.open(url, '_blank');
        };
        
        // Chart.js - Grafik Serapan
        const absorptionCtx = document.getElementById('absorptionChart').getContext('2d');
        const absorptionChart = new Chart(absorptionCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($absorptionChart->pluck('bidang')) !!},
                datasets: [{
                    label: 'Anggaran',
                    data: {!! json_encode($absorptionChart->pluck('total_anggaran')) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }, {
                    label: 'Realisasi',
                    data: {!! json_encode($absorptionChart->pluck('total_realisasi')) !!},
                    backgroundColor: 'rgba(34, 197, 94, 0.5)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
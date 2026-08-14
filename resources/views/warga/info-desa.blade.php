@extends('layouts.warga')

@section('title', "Info Desa - RT $rt")

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Informasi Desa Puspamukti</h1>
            <p class="text-gray-600 mt-2">Berita, APBDes, dan informasi penting untuk warga RT {{ $rt }}</p>
        </div>
        <button onclick="window.history.back()" 
                class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors flex items-center space-x-2">
            <span class="material-symbols-outlined">arrow_back</span>
            <span>Kembali</span>
        </button>
    </div>

    <!-- APBDes Section -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Anggaran Desa (APBDes)</h2>
                <p class="text-gray-600 text-sm">Transparansi penggunaan anggaran desa</p>
            </div>
            <a href="{{ route('apbdes.publik', ['rt' => $rt]) }}" 
               class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center space-x-1">
                <span>Lihat Detail</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
        
        @if($infoDesa['apbdes']->count() > 0)
            <div class="space-y-4">
                @foreach($infoDesa['apbdes'] as $apbdes)
                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900">APBDes Tahun {{ $apbdes->tahun }}</h3>
                            <p class="text-sm text-gray-600 mt-1">Anggaran: Rp {{ number_format($apbdes->anggaran, 0, ',', '.') }}</p>
                            <div class="flex items-center space-x-3 mt-2">
                                <span class="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-700">
                                    {{ $apbdes->kategori }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    Dipublikasikan: {{ $apbdes->tanggal_publikasi ? \Carbon\Carbon::parse($apbdes->tanggal_publikasi)->translatedFormat('d F Y') : '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($apbdes->realisasi > 0)
                            <div class="text-right">
                                <span class="text-sm font-medium text-green-600">
                                    {{ $apbdes->anggaran > 0 ? round(($apbdes->realisasi / $apbdes->anggaran) * 100, 1) : 0 }}%
                                </span>
                                <p class="text-xs text-gray-500">Realisasi</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <span class="material-symbols-outlined text-4xl text-gray-400 mb-3 block">account_balance</span>
                <p class="text-gray-600">Belum ada data APBDes yang dipublikasikan</p>
            </div>
        @endif
    </div>

    <!-- Berita & Pengumuman -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Berita & Pengumuman</h2>
                <p class="text-gray-600 text-sm">Informasi terbaru dari pemerintah desa</p>
            </div>
            <a href="{{ route('informasi.publik', ['rt' => $rt]) }}" 
               class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center space-x-1">
                <span>Semua Berita</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
        
        @if($infoDesa['berita']->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($infoDesa['berita'] as $berita)
                <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                    @if($berita->gambar)
                    <div class="h-40 bg-gray-200 overflow-hidden">
                        <img src="{{ Storage::url($berita->gambar) }}" alt="{{ $berita->judul }}" 
                             class="w-full h-full object-cover">
                    </div>
                    @endif
                    <div class="p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] uppercase tracking-wider font-semibold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full">{{ $berita->kategori }}</span>
                            @if($berita->rt)
                            <span class="text-[10px] uppercase tracking-wider font-semibold bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full">RT {{ $berita->rt }}</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2 line-clamp-2">{{ $berita->judul }}</h3>
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit(strip_tags($berita->isi ?? ''), 100) }}</p>
                        <div class="flex items-center text-xs text-gray-500">
                            <span>{{ $berita->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <span class="material-symbols-outlined text-4xl text-gray-400 mb-3 block">newspaper</span>
                <p class="text-gray-600">Belum ada berita untuk wilayah ini</p>
            </div>
        @endif
    </div>

    <!-- Pengumuman & Agenda -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-5 flex items-center">
                <span class="material-symbols-outlined text-yellow-600 mr-2">campaign</span>
                Pengumuman
            </h2>
            @if($infoDesa['pengumuman']->count() > 0)
                <div class="space-y-4">
                    @foreach($infoDesa['pengumuman'] as $pengumuman)
                    <div class="flex items-start space-x-3 border border-gray-100 rounded-xl p-4 hover:bg-gray-50 transition-colors">
                        <div class="bg-yellow-100 p-2.5 rounded-lg flex-shrink-0">
                            <span class="material-symbols-outlined text-yellow-600">campaign</span>
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-semibold text-gray-900 line-clamp-1">{{ $pengumuman->judul }}</h3>
                            <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ Str::limit(strip_tags($pengumuman->isi ?? ''), 120) }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ $pengumuman->published_at?->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <span class="material-symbols-outlined text-4xl text-gray-300 mb-2 block">campaign</span>
                    <p class="text-gray-500">Belum ada pengumuman</p>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-5 flex items-center">
                <span class="material-symbols-outlined text-emerald-600 mr-2">event</span>
                Agenda Kegiatan
            </h2>
            @if($infoDesa['agenda']->count() > 0)
                <div class="space-y-4">
                    @foreach($infoDesa['agenda'] as $agenda)
                    <div class="flex items-start space-x-3 border border-gray-100 rounded-xl p-4 hover:bg-gray-50 transition-colors">
                        <div class="bg-emerald-100 rounded-lg px-3 py-2 text-center flex-shrink-0">
                            <p class="text-lg font-bold text-emerald-700 leading-none">{{ $agenda->tanggal_kegiatan?->format('d') }}</p>
                            <p class="text-[10px] uppercase text-emerald-600 font-semibold">{{ $agenda->tanggal_kegiatan?->translatedFormat('M') }}</p>
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-semibold text-gray-900 line-clamp-1">{{ $agenda->judul }}</h3>
                            @if($agenda->lokasi)
                            <p class="text-xs text-gray-500 mt-1 flex items-center">
                                <span class="material-symbols-outlined text-xs mr-1">location_on</span>
                                {{ $agenda->lokasi }}
                            </p>
                            @endif
                            <p class="text-xs text-gray-500 mt-1">{{ $agenda->tanggal_kegiatan?->translatedFormat('H:i') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <span class="material-symbols-outlined text-4xl text-gray-300 mb-2 block">event</span>
                    <p class="text-gray-500">Belum ada agenda terdekat</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Layanan Desa -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Layanan Desa Tersedia</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($infoDesa['layanan'] as $layanan)
            <div class="border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-start space-x-4">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <span class="material-symbols-outlined text-blue-600 text-xl">{{ $layanan['icon'] }}</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 mb-2">{{ $layanan['nama'] }}</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ $layanan['desc'] }}</p>
                        @if($layanan['nama'] == 'Pengaduan')
                        <button onclick="window.location.href='{{ route('warga.rt.landing', ['rt' => $rt]) }}'"
                                class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center space-x-1">
                            <span>Akses Layanan</span>
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Kontak Penting -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Kontak Penting</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kontak Desa -->
            <div class="bg-white rounded-lg p-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <span class="material-symbols-outlined text-blue-600">location_city</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Kantor Desa</h3>
                        <p class="text-sm text-gray-600">Puspamukti</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center text-sm">
                        <span class="material-symbols-outlined text-gray-500 text-base mr-2">location_on</span>
                        <span class="text-gray-700">{{ $infoDesa['kontak_desa']['alamat'] }}</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <span class="material-symbols-outlined text-gray-500 text-base mr-2">call</span>
                        <span class="text-gray-700">{{ $infoDesa['kontak_desa']['telepon'] }}</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <span class="material-symbols-outlined text-gray-500 text-base mr-2">whatsapp</span>
                        <span class="text-gray-700">{{ $infoDesa['kontak_desa']['whatsapp'] }}</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <span class="material-symbols-outlined text-gray-500 text-base mr-2">mail</span>
                        <span class="text-gray-700">{{ $infoDesa['kontak_desa']['email'] }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Jam Operasional -->
            <div class="bg-white rounded-lg p-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <span class="material-symbols-outlined text-green-600">schedule</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Jam Operasional</h3>
                        <p class="text-sm text-gray-600">Layanan Kantor Desa</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center text-sm">
                        <span class="material-symbols-outlined text-gray-500 text-base mr-2">calendar_month</span>
                        <span class="text-gray-700">{{ $infoDesa['kontak_desa']['jam_operasional'] }}</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3">
                        <p class="text-sm font-medium text-gray-900 mb-2">Kontak RT</p>
                        <div class="space-y-2">
                            <div class="flex items-center text-sm">
                                <span class="material-symbols-outlined text-gray-500 text-base mr-2">person</span>
                                <span class="text-gray-700">{{ $infoDesa['kontak_desa']['ketua_rt']['umum'] }}</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <span class="material-symbols-outlined text-gray-500 text-base mr-2">person</span>
                                <span class="text-gray-700">{{ $infoDesa['kontak_desa']['ketua_rt']['khusus'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Darurat -->
    <div class="bg-gradient-to-r from-red-50 to-orange-50 rounded-2xl p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Nomor Darurat</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg p-4 text-center">
                <div class="bg-red-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-red-600">local_police</span>
                </div>
                <p class="font-bold text-gray-900">Polisi</p>
                <p class="text-lg font-bold text-red-600">110</p>
                <p class="text-xs text-gray-500 mt-1">Darurat Kriminal</p>
            </div>
            
            <div class="bg-white rounded-lg p-4 text-center">
                <div class="bg-orange-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-orange-600">local_fire_department</span>
                </div>
                <p class="font-bold text-gray-900">Pemadam</p>
                <p class="text-lg font-bold text-orange-600">113</p>
                <p class="text-xs text-gray-500 mt-1">Kebakaran</p>
            </div>
            
            <div class="bg-white rounded-lg p-4 text-center">
                <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-green-600">ambulance</span>
                </div>
                <p class="font-bold text-gray-900">Ambulans</p>
                <p class="text-lg font-bold text-green-600">119</p>
                <p class="text-xs text-gray-500 mt-1">Darurat Medis</p>
            </div>
            
            <div class="bg-white rounded-lg p-4 text-center">
                <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-blue-600">emergency</span>
                </div>
                <p class="font-bold text-gray-900">Posko COVID</p>
                <p class="text-lg font-bold text-blue-600">119</p>
                <p class="text-xs text-gray-500 mt-1">Bantuan COVID-19</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Function to copy contact info
function copyContact(phone, elementId) {
    navigator.clipboard.writeText(phone).then(() => {
        const element = document.getElementById(elementId);
        const original = element.innerHTML;
        element.innerHTML = '<span class="material-symbols-outlined text-sm">check</span> Tersalin';
        element.classList.add('text-green-600');
        setTimeout(() => {
            element.innerHTML = original;
            element.classList.remove('text-green-600');
        }, 2000);
    });
}

// Share this page
function shareInfoDesa() {
    if (navigator.share) {
        navigator.share({
            title: 'Informasi Desa Puspamukti - RT {{ $rt }}',
            text: 'Informasi penting desa dan layanan untuk warga',
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
        showToast('Link berhasil disalin ke clipboard', 'success');
    }
}

// Show toast
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 flex items-center space-x-2 ${
        type === 'success' ? 'bg-green-50 text-green-800 border border-green-200' :
        type === 'error' ? 'bg-red-50 text-red-800 border border-red-200' :
        'bg-blue-50 text-blue-800 border border-blue-200'
    }`;
    
    const icon = type === 'success' ? 'check_circle' : type === 'error' ? 'error' : 'info';
    
    toast.innerHTML = `
        <span class="material-symbols-outlined text-sm">${icon}</span>
        <span class="text-sm font-medium">${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>
@endpush
@endsection
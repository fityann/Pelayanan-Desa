@extends('layouts.warga')

@section('title', 'Usulan Kegiatan (Musrenbang) - Warga')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto py-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3 mb-3">
                <div class="bg-emerald-100 p-3 rounded-xl">
                    <span class="material-symbols-outlined text-emerald-600 text-2xl">architecture</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Usulan Kegiatan Desa</h1>
                    <p class="text-sm text-gray-500">Daftar usulan kegiatan (Musrenbang) dan aspirasi warga</p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('warga.musrenbang.index') }}" method="GET" class="flex items-center gap-2 bg-white p-2 rounded-xl shadow-sm border border-gray-200">
            <span class="material-symbols-outlined text-gray-400 ml-2">filter_list</span>
            <select name="tahun" class="bg-transparent border-none focus:ring-0 text-sm font-medium text-gray-700 w-full" onchange="this.form.submit()">
                <option value="">Semua Tahun</option>
                @for($i = date('Y') + 1; $i >= date('Y') - 2; $i--)
                    <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </form>
    </div>

    @if ($musrenbangs->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-gray-100">
            <span class="material-symbols-outlined text-5xl text-gray-300 block mb-3">inbox</span>
            <p class="text-gray-600">Belum ada usulan kegiatan untuk periode ini.</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($musrenbangs as $musrenbang)
            <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md hover:-translate-y-0.5 transition-all border border-gray-100 flex flex-col">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-emerald-100 w-12 h-12 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-emerald-600">architecture</span>
                    </div>
                    @php
                        $statusColors = [
                            'diusulkan' => 'bg-gray-100 text-gray-700',
                            'diverifikasi' => 'bg-blue-50 text-blue-700',
                            'direview' => 'bg-purple-50 text-purple-700',
                            'disetujui' => 'bg-emerald-50 text-emerald-700',
                            'ditolak' => 'bg-red-50 text-red-700',
                        ];
                        $badgeClass = $statusColors[$musrenbang->status_usulan] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                        {{ $musrenbang->status_usulan }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <span class="text-xs font-bold text-emerald-600">{{ $musrenbang->tahun }}</span>
                    <h3 class="text-lg font-bold text-gray-900 mt-1 line-clamp-2">{{ $musrenbang->judul_kegiatan }}</h3>
                </div>
                
                <p class="text-sm text-gray-600 mb-4 line-clamp-3 flex-1">{{ $musrenbang->deskripsi_kegiatan }}</p>
                
                <div class="bg-gray-50 rounded-xl p-4 mb-4">
                    <div class="flex items-center gap-2 text-gray-600 text-sm mb-2">
                        <span class="material-symbols-outlined text-[16px] text-gray-400">location_on</span>
                        <span>{{ $musrenbang->wilayah ? $musrenbang->wilayah->nama : 'Umum (Tingkat Desa)' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-600 text-sm mb-2">
                        <span class="material-symbols-outlined text-[16px] text-gray-400">payments</span>
                        <span>Rp {{ number_format($musrenbang->estimasi_biaya, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-600 text-sm">
                        <span class="material-symbols-outlined text-[16px] text-gray-400">group</span>
                        <span class="font-medium text-emerald-700">{{ $musrenbang->jumlah_pendukung }} Dukungan</span>
                    </div>
                </div>
                
                <a href="{{ route('warga.musrenbang.show', $musrenbang) }}" class="mt-auto bg-emerald-50 text-emerald-700 text-center px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-emerald-100 transition-all flex items-center justify-center gap-2">
                    <span>Lihat Detail & Voting</span>
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
            </div>
        @endforeach
    </div>
    
    <div class="mt-8">
        {{ $musrenbangs->links() }}
    </div>
</div>
@endsection

@extends('layouts.warga')

@section('title', 'APBDes - Transparansi Publik')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl p-6 md:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <div class="bg-emerald-100 p-3 rounded-xl">
                        <span class="material-symbols-outlined text-emerald-600 text-2xl">account_balance</span>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">APBDes {{ $tahun }}</h1>
                        <p class="text-gray-600 text-sm mt-1">Anggaran Pendapatan dan Belanja Desa Puspamukti</p>
                    </div>
                </div>
                <p class="text-xs text-gray-500 flex items-center mt-2">
                    <span class="material-symbols-outlined text-sm mr-1">verified</span>
                    Data bersumber dari SISKEUDES — Portal Transparansi, bukan pengganti sistem akuntansi resmi
                </p>
            </div>
            <form method="GET" class="flex items-center gap-3 bg-white px-4 py-3 rounded-xl shadow-sm">
                <label class="text-sm font-semibold text-gray-700">Tahun:</label>
                <select name="tahun" onchange="this.form.submit()" class="bg-emerald-50 rounded-lg px-3 py-2 text-sm outline-none border border-emerald-200">
                    @foreach ($tahunList as $t)
                        <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
                @if (request('rt'))
                    <input type="hidden" name="rt" value="{{ request('rt') }}">
                    <input type="hidden" name="rw" value="{{ request('rw') }}">
                @endif
            </form>
        </div>
    </div>

    <!-- Ringkasan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="stats-card p-6">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-widest mb-2">Pendapatan</p>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($ringkasan['pendapatan'], 0, ',', '.') }}</p>
            <p class="text-xs text-emerald-600 mt-1">Realisasi: Rp {{ number_format($ringkasan['realisasi_pendapatan'], 0, ',', '.') }}</p>
        </div>
        <div class="stats-card p-6">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-widest mb-2">Belanja</p>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($ringkasan['belanja'], 0, ',', '.') }}</p>
            <p class="text-xs text-red-600 mt-1">Realisasi: Rp {{ number_format($ringkasan['realisasi_belanja'], 0, ',', '.') }}</p>
        </div>
        <div class="stats-card p-6">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-widest mb-2">Pembiayaan</p>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($ringkasan['pembiayaan'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Grafik -->
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-8 mt-4">
        <h3 class="text-lg font-bold text-gray-900 mb-10">Grafik Pendapatan vs Belanja</h3>
        @php $maxVal = max($ringkasan['pendapatan'], $ringkasan['belanja'], 1); @endphp
        <div class="flex justify-around items-end h-64 px-4 sm:px-12 pb-2 border-b border-gray-100">
            <!-- Pendapatan -->
            <div class="w-20 sm:w-32 relative flex flex-col justify-end items-center" style="height: {{ ($ringkasan['pendapatan'] / $maxVal) * 100 }}%;">
                <span class="absolute -top-7 text-sm font-bold text-gray-900 whitespace-nowrap">Rp {{ number_format($ringkasan['pendapatan'] / 1000000, 1) }}jt</span>
                <div class="w-full h-full bg-emerald-500 rounded-t-xl transition-all duration-1000 shadow-sm"></div>
                <span class="absolute -bottom-8 text-sm font-semibold text-gray-500">Pendapatan</span>
            </div>

            <!-- Belanja -->
            <div class="w-20 sm:w-32 relative flex flex-col justify-end items-center" style="height: {{ ($ringkasan['belanja'] / $maxVal) * 100 }}%;">
                <span class="absolute -top-7 text-sm font-bold text-gray-900 whitespace-nowrap">Rp {{ number_format($ringkasan['belanja'] / 1000000, 1) }}jt</span>
                <div class="w-full h-full bg-teal-500 rounded-t-xl transition-all duration-1000 shadow-sm"></div>
                <span class="absolute -bottom-8 text-sm font-semibold text-gray-500">Belanja</span>
            </div>
        </div>
        <div class="h-8"></div>
    </div>

    <!-- Detail per kategori -->
    @foreach (['Pendapatan', 'Belanja', 'Pembiayaan'] as $kat)
        @if ($kat === 'Pendapatan') @php $items = $pendapatan; @endphp
        @elseif ($kat === 'Belanja') @php $items = $belanja; @endphp
        @else @php $items = $data->where('kategori', $kat); @endphp
        @endif
        @if ($items->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-emerald-50/50 border-b border-emerald-100">
                    <h3 class="text-lg font-bold text-gray-900">{{ $kat }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="text-left px-6 py-3 text-xs font-bold text-gray-500 uppercase">Bidang</th>
                                <th class="text-left px-6 py-3 text-xs font-bold text-gray-500 uppercase">Uraian</th>
                                <th class="text-right px-6 py-3 text-xs font-bold text-gray-500 uppercase">Anggaran</th>
                                <th class="text-right px-6 py-3 text-xs font-bold text-gray-500 uppercase">Realisasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($items as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3 text-sm text-gray-700">{{ $item->bidang ?? '-' }}</td>
                                    <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $item->uraian }}</td>
                                    <td class="px-6 py-3 text-sm text-right font-mono">Rp {{ number_format($item->anggaran, 0, ',', '.') }}</td>
                                    <td class="px-6 py-3 text-sm text-right font-mono">Rp {{ number_format($item->realisasi, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endforeach
</div>
@endsection
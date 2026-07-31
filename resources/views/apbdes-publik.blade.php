@extends('layouts.guest')

@section('title', 'APBDes - Transparansi Publik')

@section('content')
<div class="min-h-screen bg-surface">
    <div class="bg-gradient-to-br from-primary to-secondary text-on-primary">
        <div class="max-w-6xl mx-auto px-lg py-xl">
            <a href="/" class="inline-flex items-center gap-sm text-on-primary/80 hover:text-on-primary mb-lg">
                <span class="material-symbols-outlined">arrow_back</span>
                Kembali ke Beranda
            </a>
            <h1 class="text-headline-lg font-bold mb-sm">APBDes {{ $tahun }}</h1>
            <p class="text-body-md text-on-primary/80">Anggaran Pendapatan dan Belanja Desa Puspamukti</p>
            <p class="text-label-sm text-on-primary/60 mt-md">Data bersumber dari SISKEUDES — Portal Transparansi, bukan pengganti sistem akuntansi resmi</p>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-lg py-xl">
        <form method="GET" class="mb-xl">
            <div class="flex items-center gap-md">
                <label class="text-label-sm font-bold text-on-surface">Pilih Tahun:</label>
                <select name="tahun" onchange="this.form.submit()" class="bg-surface-container rounded-xl px-lg py-2 text-body-md outline-none border border-outline-variant">
                    @foreach ($tahunList as $t)
                        <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-lg mb-xl">
            <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
                <p class="text-label-sm text-on-surface-variant uppercase tracking-widest font-bold mb-sm">Pendapatan</p>
                <p class="font-headline-lg text-on-surface">Rp {{ number_format($ringkasan['pendapatan'], 0, ',', '.') }}</p>
                <p class="text-label-sm text-success">Realisasi: Rp {{ number_format($ringkasan['realisasi_pendapatan'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
                <p class="text-label-sm text-on-surface-variant uppercase tracking-widest font-bold mb-sm">Belanja</p>
                <p class="font-headline-lg text-on-surface">Rp {{ number_format($ringkasan['belanja'], 0, ',', '.') }}</p>
                <p class="text-label-sm text-error">Realisasi: Rp {{ number_format($ringkasan['realisasi_belanja'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
                <p class="text-label-sm text-on-surface-variant uppercase tracking-widest font-bold mb-sm">Pembiayaan</p>
                <p class="font-headline-lg text-on-surface">Rp {{ number_format($ringkasan['pembiayaan'], 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Grafik sederhana --}}
        <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg mb-xl">
            <h3 class="text-title-md font-bold text-on-surface mb-lg">Grafik Pendapatan vs Belanja</h3>
            @php $maxVal = max($ringkasan['pendapatan'], $ringkasan['belanja'], 1); @endphp
            <div class="flex items-end justify-around gap-lg h-48 px-lg">
                <div class="flex flex-col items-center gap-md">
                    <span class="text-label-sm font-bold text-on-surface">Rp {{ number_format($ringkasan['pendapatan'] / 1000000, 1) }}jt</span>
                    <div class="w-16 bg-on-tertiary-container rounded-t-lg" style="height: {{ ($ringkasan['pendapatan'] / $maxVal) * 100 }}%"></div>
                    <span class="text-label-sm font-bold text-on-surface-variant">Pendapatan</span>
                </div>
                <div class="flex flex-col items-center gap-md">
                    <span class="text-label-sm font-bold text-on-surface">Rp {{ number_format($ringkasan['belanja'] / 1000000, 1) }}jt</span>
                    <div class="w-16 bg-secondary rounded-t-lg" style="height: {{ ($ringkasan['belanja'] / $maxVal) * 100 }}%"></div>
                    <span class="text-label-sm font-bold text-on-surface-variant">Belanja</span>
                </div>
            </div>
        </div>

        {{-- Detail per kategori --}}
        @foreach (['Pendapatan', 'Belanja', 'Pembiayaan'] as $kat)
            @php $items = ${strtolower($kat)} ?? collect(); @endphp
            @if ($kat === 'Pendapatan') @php $items = $pendapatan; @endphp
            @elseif ($kat === 'Belanja') @php $items = $belanja; @endphp
            @else @php $items = $data->where('kategori', $kat); @endphp
            @endif
            @if ($items->count() > 0)
                <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden mb-lg">
                    <div class="px-lg py-4 bg-surface-container/30 border-b border-surface-variant/20">
                        <h3 class="text-title-md font-bold text-on-surface">{{ $kat }}</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-surface-container/20">
                                    <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase">Bidang</th>
                                    <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase">Uraian</th>
                                    <th class="text-right px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase">Anggaran</th>
                                    <th class="text-right px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase">Realisasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-surface-variant/20">
                                @foreach ($items as $item)
                                    <tr>
                                        <td class="px-lg py-3 text-body-sm text-on-surface">{{ $item->bidang ?? '-' }}</td>
                                        <td class="px-lg py-3 text-body-md text-on-surface">{{ $item->uraian }}</td>
                                        <td class="px-lg py-3 text-body-sm text-right font-mono">Rp {{ number_format($item->anggaran, 0, ',', '.') }}</td>
                                        <td class="px-lg py-3 text-body-sm text-right font-mono">Rp {{ number_format($item->realisasi, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <footer class="bg-surface-container-high py-lg text-center text-label-sm text-on-surface-variant">
        <p>Puspamukti Smart Village — Data APBDes bersumber dari SISKEUDES</p>
        <p class="mt-xs">© {{ date('Y') }} Pemerintah Desa Puspamukti</p>
    </footer>
</div>
@endsection

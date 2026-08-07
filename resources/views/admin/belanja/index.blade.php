@extends('layouts.admin')

@section('title', 'Belanja - SILAPU')

@section('content')
<div class="flex flex-col gap-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Belanja Desa</h1>
            <p class="text-body-sm text-on-surface-variant">Pengadaan barang dan jasa desa</p>
        </div>
        <a href="{{ route('admin.belanja.create') }}" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Buat Belanja
        </a>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
        <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest">Total Belanja</p>
            <p class="font-headline-lg text-on-surface">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest">Menunggu Proses</p>
            <p class="font-headline-lg text-on-surface">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest">Total Nilai</p>
            <p class="font-headline-md text-on-surface">Rp {{ number_format($stats['total_nilai'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Daftar -->
    <div class="bg-surface-container-lowest rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-outline-variant/20">
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Nomor</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Barang/Jasa</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Total Harga</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Penyedia</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Status</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant">Pemohon</th>
                        <th class="text-left py-3 px-4 text-label-sm font-medium text-on-surface-variant"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($belanja as $b)
                        @php
                            $statusClass = match($b->status_belanja) {
                                'selesai' => 'bg-success/10 text-success',
                                'ditolak' => 'bg-error/10 text-error',
                                'diproses', 'dikirim', 'diterima' => 'bg-secondary/10 text-secondary',
                                'diajukan' => 'bg-primary/10 text-primary',
                                default => 'bg-on-tertiary-container/10 text-on-tertiary-container',
                            };
                        @endphp
                        <tr class="border-b border-outline-variant/10 hover:bg-surface-container/40 transition-colors">
                            <td class="py-3 px-4 text-label-md font-mono font-semibold text-primary">{{ $b->nomor_belanja }}</td>
                            <td class="py-3 px-4">
                                <p class="text-label-sm font-semibold text-on-surface">{{ $b->nama_barang_jasa }}</p>
                                <p class="text-[11px] text-on-surface-variant">{{ $b->kuantitas }} {{ $b->satuan }}</p>
                            </td>
                            <td class="py-3 px-4 text-label-sm text-on-surface">Rp {{ number_format($b->total_harga, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-label-sm text-on-surface">{{ $b->penyedia ?? '-' }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold capitalize {{ $statusClass }}">{{ str_replace('_', ' ', $b->status_belanja) }}</span>
                            </td>
                            <td class="py-3 px-4 text-label-sm text-on-surface">{{ $b->pemohon?->name ?? '-' }}</td>
                            <td class="py-3 px-4">
                                @if (auth()->user()->hasPermissionTo('U APBDes'))
                                    <div class="flex gap-xs flex-wrap">
                                        @if ($b->status_belanja === 'draft')
                                            <form method="POST" action="{{ route('admin.belanja.approve', $b) }}">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 rounded-full text-[11px] font-bold bg-secondary text-on-secondary hover:bg-secondary/90 transition-colors">Setujui</button>
                                            </form>
                                        @endif
                                        @if ($b->status_belanja === 'diproses')
                                            <form method="POST" action="{{ route('admin.belanja.deliver', $b) }}">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 rounded-full text-[11px] font-bold bg-primary text-on-primary hover:bg-primary/90 transition-colors">Kirim</button>
                                            </form>
                                        @endif
                                        @if ($b->status_belanja === 'dikirim')
                                            <form method="POST" action="{{ route('admin.belanja.receive', $b) }}">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 rounded-full text-[11px] font-bold bg-secondary text-on-secondary hover:bg-secondary/90 transition-colors">Terima</button>
                                            </form>
                                        @endif
                                        @if ($b->status_belanja === 'diterima')
                                            <form method="POST" action="{{ route('admin.belanja.complete', $b) }}">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 rounded-full text-[11px] font-bold bg-success text-on-success hover:bg-success/90 transition-colors">Selesai</button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($belanja->isEmpty())
            <div class="p-xl text-center text-on-surface-variant/60">
                <span class="material-symbols-outlined text-[40px] block mb-md">shopping_cart</span>
                <p>Belum ada data belanja.</p>
            </div>
        @endif

        @if ($belanja->hasPages())
            <div class="p-lg">
                {{ $belanja->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
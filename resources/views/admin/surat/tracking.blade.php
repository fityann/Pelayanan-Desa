@extends('layouts.admin')

@section('title', 'Tracking Surat - SIPANDA')

@section('content')
<div class="flex flex-col gap-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Tracking Surat</h1>
            <p class="text-body-sm text-on-surface-variant">Cari status pengajuan surat</p>
        </div>
    </div>

    <form method="GET" class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
        <div class="flex gap-md">
            <div class="flex-1 relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input type="text" name="search" value="{{ $search }}" class="w-full bg-surface-container rounded-xl py-3 pl-12 pr-4 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Cari nomor surat atau nama pemohon...">
            </div>
            <button type="submit" class="bg-primary text-on-primary px-xl py-3 rounded-xl text-label-md font-bold hover:bg-primary/90 transition-all">Cari</button>
        </div>
    </form>

    <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-container/50">
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">No. Surat</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Pemohon</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Jenis</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Tgl Diajukan</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant/20">
                    @forelse ($pengajuan as $item)
                        <tr class="hover:bg-surface-container/30 transition-colors">
                            <td class="px-lg py-4">
                                <span class="text-body-md font-mono font-bold text-on-surface">{{ $item->nomor_surat ?? '-' }}</span>
                            </td>
                            <td class="px-lg py-4">
                                <span class="text-body-md text-on-surface">{{ $item->user->name }}</span>
                            </td>
                            <td class="px-lg py-4">
                                <span class="text-body-sm text-on-surface-variant">{{ $item->jenisSurat->nama }}</span>
                            </td>
                            <td class="px-lg py-4">
                                <span class="text-body-sm text-on-surface-variant">{{ $item->created_at->format('d/m/Y') }}</span>
                            </td>
                            <td class="px-lg py-4">
                                @php
                                    $cls = match($item->status) {
                                        'diajukan' => 'bg-on-tertiary-container/10 text-on-tertiary-container',
                                        'diproses' => 'bg-primary/10 text-primary',
                                        'disetujui' => 'bg-success/10 text-success',
                                        'ditolak' => 'bg-error/10 text-error',
                                        'siap_diambil' => 'bg-secondary/10 text-secondary',
                                        'selesai' => 'bg-surface-variant/30 text-on-surface-variant',
                                        default => 'bg-surface-variant/30 text-on-surface-variant'
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $cls }}">{{ $item->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-xl text-on-surface-variant">Data tidak ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($pengajuan->hasPages())
            <div class="p-lg border-t border-surface-variant/20">{{ $pengajuan->links() }}</div>
        @endif
    </div>
</div>
@endsection

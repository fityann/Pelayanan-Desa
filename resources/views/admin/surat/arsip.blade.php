@extends('layouts.admin')

@section('title', 'Arsip Surat - SIPANDA')

@section('content')
<div class="flex flex-col gap-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Arsip Surat</h1>
            <p class="text-body-sm text-on-surface-variant">Arsip surat yang sudah selesai diproses</p>
        </div>
        <div class="flex gap-sm">
            <a href="{{ route('admin.surat.tracking') }}" class="bg-surface-container-lowest px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all flex items-center gap-sm border border-outline-variant">
                <span class="material-symbols-outlined text-[18px]">search</span>
                Tracking
            </a>
        </div>
    </div>

    <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-container/50">
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">No. Surat</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Pemohon</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Jenis</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Tgl Disetujui</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Status</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant/20">
                    @forelse ($arsip as $item)
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
                                <span class="text-body-sm text-on-surface-variant">{{ $item->tanggal_disetujui?->format('d/m/Y') ?? '-' }}</span>
                            </td>
                            <td class="px-lg py-4">
                                @include('partials.surat-status-badge', ['status' => $item->status])
                            </td>
                            <td class="px-lg py-4">
                                <a href="{{ route('admin.surat.pdf', $item) }}" class="text-secondary text-label-sm font-bold hover:underline">PDF</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-xl text-on-surface-variant">Belum ada arsip surat</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($arsip->hasPages())
            <div class="p-lg border-t border-surface-variant/20">{{ $arsip->links() }}</div>
        @endif
    </div>
</div>
@endsection

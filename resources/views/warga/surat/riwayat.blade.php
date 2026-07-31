@extends('layouts.admin')

@section('title', 'Riwayat Pengajuan Saya - SIPANDA')

@section('content')
<div class="flex flex-col gap-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Riwayat Pengajuan Saya</h1>
            <p class="text-body-sm text-on-surface-variant">Pantau status pengajuan surat Anda</p>
        </div>
        <a href="{{ route('warga.surat.index') }}" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Ajukan Surat
        </a>
    </div>

    <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-container/50">
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Jenis Surat</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Nomor Surat</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Tanggal Ajukan</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Status</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant/20">
                    @forelse ($pengajuan as $item)
                        <tr class="hover:bg-surface-container/30 transition-colors">
                            <td class="px-lg py-4">
                                <span class="text-body-md text-on-surface">{{ $item->jenisSurat->nama }}</span>
                            </td>
                            <td class="px-lg py-4">
                                <span class="text-body-md font-mono text-on-surface-variant">{{ $item->nomor_surat ?? '-' }}</span>
                            </td>
                            <td class="px-lg py-4">
                                <span class="text-body-sm text-on-surface-variant">{{ $item->created_at->format('d/m/Y') }}</span>
                            </td>
                            <td class="px-lg py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-on-tertiary-container/10 text-on-tertiary-container">{{ $item->status }}</span>
                            </td>
                            <td class="px-lg py-4">
                                <a href="{{ route('warga.surat.status', $item) }}" class="text-primary text-label-sm font-bold hover:underline">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-xl text-on-surface-variant">Belum ada pengajuan. Mulai dari menu Layanan Surat.</td>
                        </tr>
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

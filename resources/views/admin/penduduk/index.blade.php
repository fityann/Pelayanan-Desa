@extends('layouts.admin')

@section('title', 'Data Penduduk - SIPANDA')

@section('content')
<div class="flex flex-col gap-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Data Penduduk</h1>
            <p class="text-body-sm text-on-surface-variant">Kelola data penduduk Desa Puspamukti</p>
        </div>
        <div class="flex gap-sm">
            <a href="{{ route('admin.penduduk.import') }}" class="bg-on-tertiary-container text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-on-tertiary-container/90 transition-all flex items-center gap-sm">
                <span class="material-symbols-outlined text-[18px]">upload_file</span>
                Import Excel
            </a>
            <a href="{{ route('admin.penduduk.create') }}" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all flex items-center gap-sm">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah Penduduk
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-container/50">
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">NIK</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Nama</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">L/P</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">RT/RW</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">No. KK</th>
                        <th class="text-center px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant/20">
                    @foreach ($penduduk as $p)
                        <tr class="hover:bg-surface-container/30 transition-colors">
                            <td class="px-lg py-4 text-body-sm font-mono text-on-surface">{{ $p->nik }}</td>
                            <td class="px-lg py-4 text-body-md font-semibold text-on-surface">{{ $p->nama }}</td>
                            <td class="px-lg py-4 text-body-sm text-on-surface-variant">{{ $p->jenis_kelamin ?? '-' }}</td>
                            <td class="px-lg py-4 text-body-sm text-on-surface-variant">{{ $p->rt }}/{{ $p->rw }}</td>
                            <td class="px-lg py-4 text-body-sm text-on-surface-variant">{{ $p->no_kk ?? '-' }}</td>
                            <td class="px-lg py-4 text-center">
                                <div class="flex items-center justify-center gap-sm">
                                    <a href="{{ route('admin.penduduk.edit', $p) }}" class="text-primary text-label-sm font-bold hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('admin.penduduk.destroy', $p) }}" class="inline" onsubmit="return confirm('Hapus data penduduk ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-error text-label-sm font-bold hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($penduduk->hasPages())
            <div class="p-lg border-t border-surface-variant/20">{{ $penduduk->links() }}</div>
        @endif
    </div>
</div>
@endsection

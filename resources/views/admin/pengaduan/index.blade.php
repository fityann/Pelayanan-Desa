@extends('layouts.admin')

@section('title', 'Pengaduan - SILAPU')

@section('content')
<div class="flex flex-col gap-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Pengaduan Masyarakat</h1>
            <p class="text-body-sm text-on-surface-variant">Kelola pengaduan warga desa</p>
        </div>
        <a href="{{ route('admin.pengaduan.create') }}" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Buat Pengaduan
        </a>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-lg">
        @foreach ($rekap as $r)
            <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
                <p class="text-label-sm text-on-surface-variant uppercase tracking-widest font-bold mb-sm">{{ $r->kategori }}</p>
                <div class="flex items-center gap-lg">
                    <span class="font-headline-lg text-on-surface">{{ $r->total }}</span>
                    <div class="flex gap-xs text-[10px]">
                        <span class="px-2 py-0.5 rounded bg-on-tertiary-container/10 text-on-tertiary-container">{{ $r->diterima }} Baru</span>
                        <span class="px-2 py-0.5 rounded bg-primary/10 text-primary">{{ $r->diproses }} Proses</span>
                        <span class="px-2 py-0.5 rounded bg-success/10 text-success">{{ $r->selesai }} OK</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-container/50">
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Pelapor</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Kategori</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Judul</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Tanggal</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Status</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant/20">
                    @forelse ($pengaduan as $p)
                        <tr class="hover:bg-surface-container/30 transition-colors">
                            <td class="px-lg py-4">
                                <div class="flex items-center gap-md">
                                    <div class="w-8 h-8 rounded-full bg-error/10 flex items-center justify-center text-error">
                                        <span class="material-symbols-outlined text-[18px]">person</span>
                                    </div>
                                    <span class="text-body-md text-on-surface">{{ $p->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-lg py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-surface-container text-on-surface-variant">{{ $p->kategori }}</span>
                            </td>
                            <td class="px-lg py-4">
                                <span class="text-body-md text-on-surface">{{ $p->judul }}</span>
                            </td>
                            <td class="px-lg py-4">
                                <span class="text-body-sm text-on-surface-variant">{{ $p->created_at->format('d/m/Y') }}</span>
                            </td>
                            <td class="px-lg py-4">
                                @php
                                    $sc = match($p->status) {
                                        'diterima' => 'bg-on-tertiary-container/10 text-on-tertiary-container',
                                        'diproses' => 'bg-primary/10 text-primary',
                                        'selesai' => 'bg-success/10 text-success',
                                        default => 'bg-surface-variant/30 text-on-surface-variant'
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $sc }}">{{ $p->status }}</span>
                            </td>
                            <td class="px-lg py-4">
                                <div class="flex items-center gap-sm">
                                    @if($p->status === 'diterima')
                                        <form method="POST" action="{{ route('admin.pengaduan.proses', $p) }}" class="inline">
                                            @csrf
                                            <button class="text-primary text-label-sm font-bold hover:underline">Proses</button>
                                        </form>
                                    @endif
                                    @if($p->status === 'diproses')
                                        <button onclick="openSelesaiModal({{ $p->id }})" class="text-success text-label-sm font-bold hover:underline">Selesai</button>
                                    @endif
                                    @if($p->status === 'selesai' && $p->tanggapan)
                                        <button onclick="alert('{{ $p->tanggapan }}')" class="text-on-surface-variant text-label-sm font-bold hover:underline">Lihat Tanggapan</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-xl text-on-surface-variant">Belum ada pengaduan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($pengaduan->hasPages())
            <div class="p-lg border-t border-surface-variant/20">{{ $pengaduan->links() }}</div>
        @endif
    </div>
</div>

<div id="modalSelesai" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center hidden">
    <div class="bg-surface-container-lowest rounded-2xl shadow-2xl w-full max-w-md mx-4">
        <div class="p-lg border-b border-surface-variant/30 flex items-center justify-between">
            <h2 class="text-title-md font-bold text-on-surface">Tanggapan Pengaduan</h2>
            <button onclick="document.getElementById('modalSelesai').classList.add('hidden')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="formSelesai" method="POST" class="p-lg space-y-lg">
            @csrf
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Tanggapan / Tindak Lanjut</label>
                <textarea name="tanggapan" rows="4" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Tulis tanggapan..."></textarea>
            </div>
            <div class="flex gap-md justify-end pt-md border-t border-surface-variant/30">
                <button type="button" onclick="document.getElementById('modalSelesai').classList.add('hidden')" class="px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all">Batal</button>
                <button type="submit" class="bg-success text-on-success px-lg py-2 rounded-full text-label-md font-bold hover:bg-success/90 transition-all">Selesaikan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openSelesaiModal(id) {
    document.getElementById('formSelesai').action = '/admin/pengaduan/' + id + '/selesai';
    document.getElementById('modalSelesai').classList.remove('hidden');
}
</script>
@endpush
@endsection

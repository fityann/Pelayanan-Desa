@extends('layouts.admin')

@section('title', 'QR & Link RT - SILAPU')

@section('content')
<div class="flex flex-col gap-lg">
    <!-- Header -->
    <div class="flex items-center justify-between flex-wrap gap-md">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">QR & Link RT Desa Puspamukti</h1>
            <p class="text-body-sm text-on-surface-variant">Buat QR Code, kelola, dan cetak untuk 19 RT di Desa Puspamukti</p>
        </div>
        <div class="flex items-center gap-sm">
            <div class="bg-primary-container/40 text-on-primary-container px-md py-2 rounded-lg text-label-sm flex items-center gap-sm">
                <span class="material-symbols-outlined text-base">info</span>
                Total {{ $list->count() }} RT
            </div>
            <a href="{{ route('admin.qr-links.cetak') }}" class="bg-surface-container-low text-on-surface px-md py-2 rounded-lg text-label-sm font-bold flex items-center gap-xs hover:bg-surface-container transition-all">
                <span class="material-symbols-outlined text-base">print</span> Cetak Semua QR
            </a>
            <a href="{{ route('admin.qr-links.create') }}" class="bg-primary text-on-primary px-md py-2 rounded-lg text-label-sm font-bold flex items-center gap-xs hover:bg-primary/90 transition-all">
                <span class="material-symbols-outlined text-base">add</span> Tambah RT Baru
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>{{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-error/10 border border-error/20 text-error px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">error</span>{{ session('error') }}
        </div>
    @endif

    <!-- Daftar QR/Link -->
    <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-container/50">
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Wilayah RT</th>
                        <th class="text-center px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">QR Code</th>
                        <th class="text-center px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Statistik</th>
                        <th class="text-center px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Status</th>
                        <th class="text-center px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant/20">
                    @forelse ($list as $item)
                        <tr class="hover:bg-surface-container/30 transition-colors">
                            <td class="px-lg py-4 align-top">
                                <div class="flex items-center gap-sm min-w-[200px]">
                                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary text-label-sm font-bold shrink-0">
                                        {{ $item['rt'] }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-body-sm font-bold text-on-surface">RT {{ $item['rt'] }}</p>
                                        <p class="text-[11px] text-on-surface-variant truncate">{{ $item['nama_rt'] ?? "RT {$item['rt']} Desa Puspamukti" }}</p>
                                        @if ($item['status'] === 'nonaktif')
                                            <span class="inline-flex items-center gap-1 mt-1 bg-error/10 text-error text-[10px] font-bold px-2 py-0.5 rounded-full">
                                                <span class="material-symbols-outlined text-[11px]">block</span> Nonaktif
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-lg py-4 align-top">
                                <div class="flex items-center justify-center">
                                    @if ($item['qr_image'])
                                        <div class="flex flex-col items-center gap-xs">
                                            <img src="{{ $item['qr_image'] }}" alt="QR RT {{ $item['rt'] }} RW {{ $item['rw'] }}" class="w-20 h-20 rounded bg-white border border-outline-variant/30 object-contain">
                                            <span class="text-[10px] text-on-surface-variant">
                                                {{ $item['tanggal_generate']?->translatedFormat('d M Y') ?? '-' }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="w-20 h-20 rounded bg-surface-container flex flex-col items-center justify-center gap-1 border border-dashed border-outline-variant/40 text-on-surface-variant">
                                            <span class="material-symbols-outlined text-2xl">qr_code</span>
                                            <span class="text-[10px]">Belum ada</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-lg py-4 text-center align-top">
                                <div class="flex flex-col gap-1 text-body-sm">
                                    <span class="text-on-surface-variant text-[11px] uppercase tracking-wide">Penduduk <b class="text-on-surface">{{ $item['penduduk_count'] }}</b></span>
                                    <span class="text-on-surface-variant text-[11px] uppercase tracking-wide">Pengaduan <b class="text-on-surface">{{ $item['pengaduan_count'] }}</b></span>
                                    <span class="text-on-surface-variant text-[11px] uppercase tracking-wide">Scan <b class="text-on-surface">{{ $item['scan_count'] }}</b></span>
                                </div>
                            </td>
                            <td class="px-lg py-4 text-center align-top">
                                <form method="POST" action="{{ route('admin.qr-links.status', ['rt' => $item['rt']]) }}">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-[11px] font-bold transition-all {{ $item['status'] === 'aktif' ? 'bg-success/10 text-success hover:bg-success/20' : 'bg-error/10 text-error hover:bg-error/20' }}"
                                            title="Klik untuk ubah status">
                                        <span class="material-symbols-outlined text-[13px]">{{ $item['status'] === 'aktif' ? 'visibility' : 'visibility_off' }}</span>
                                        {{ $item['status'] === 'aktif' ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-lg py-4 align-top">
                                <div class="flex flex-col gap-1.5 justify-center items-center">
                                    <a href="{{ $item['url'] }}" target="_blank" class="w-full text-center px-3 py-1.5 rounded-lg text-[11px] font-bold text-primary bg-primary/5 hover:bg-primary/10 transition-colors" title="Buka portal warga">
                                        Buka Portal
                                    </a>
                                    <a href="{{ $item['url'] }}" onclick="event.preventDefault(); copyLink(this, '{{ $item['url'] }}'); return false;" class="w-full text-center px-3 py-1.5 rounded-lg text-[11px] font-bold text-on-surface-variant bg-surface-container hover:bg-surface-container-high transition-colors" title="Salin link">
                                        Salin Link
                                    </a>
                                    @if ($item['qr'])
                                        <form method="POST" action="{{ route('admin.qr-links.generate', $item['qr']->id) }}" class="w-full">
                                            @csrf
                                            <button type="submit" class="w-full px-3 py-1.5 rounded-lg text-[11px] font-bold text-on-primary bg-primary hover:bg-primary/90 transition-colors">
                                                {{ $item['qr_image'] ? 'Generate Ulang' : 'Generate QR' }}
                                            </button>
                                        </form>
                                        @if ($item['qr_image'])
                                            <a href="{{ route('admin.qr-links.download', $item['qr']->id) }}" class="w-full text-center px-3 py-1.5 rounded-lg text-[11px] font-bold text-on-primary bg-secondary hover:bg-secondary/90 transition-colors">
                                                Unduh PNG
                                            </a>
                                        @endif
                                        <div class="flex w-full gap-1">
                                            <a href="{{ route('admin.qr-links.edit', $item['qr']->id) }}" class="flex-1 text-center px-3 py-1.5 rounded-lg text-[11px] font-bold text-on-surface-variant bg-surface-container hover:bg-surface-container-high transition-colors" title="Edit">
                                                <span class="material-symbols-outlined text-[13px] align-middle">edit</span>
                                            </a>
                                            <form method="POST" action="{{ route('admin.qr-links.destroy', $item['qr']->id) }}" class="flex-1"
                                                  onsubmit="return confirm('Hapus wilayah RT {{ $item['rt'] }} beserta QR-nya?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-full text-center px-3 py-1.5 rounded-lg text-[11px] font-bold text-error bg-error/5 hover:bg-error/10 transition-colors" title="Hapus">
                                                    <span class="material-symbols-outlined text-[13px] align-middle">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('admin.qr-links.generateByRtRw', ['rt' => $item['rt']]) }}" class="w-full">
                                            @csrf
                                            <button type="submit" class="w-full px-3 py-1.5 rounded-lg text-[11px] font-bold text-on-primary bg-primary hover:bg-primary/90 transition-colors">
                                                Generate QR
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.qr-links.create') }}?rt={{ $item['rt'] }}" class="w-full text-center px-3 py-1.5 rounded-lg text-[11px] font-bold text-on-surface-variant bg-surface-container hover:bg-surface-container-high transition-colors" title="Tambah detail wilayah">
                                            Kelola Wilayah
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 px-lg text-center">
                                <div class="flex flex-col items-center gap-sm">
                                    <span class="material-symbols-outlined text-4xl text-on-surface-variant">qr_code</span>
                                    <p class="text-label-sm text-on-surface">Belum ada wilayah terdeteksi</p>
                                    <p class="text-body-sm text-on-surface-variant">Tambahkan data keluarga/penduduk atau buat wilayah baru</p>
                                    <a href="{{ route('admin.qr-links.create') }}" class="mt-2 bg-primary text-on-primary px-lg py-2 rounded-full text-label-sm font-bold hover:bg-primary/90 transition-all">Tambah Wilayah</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyLink(btn, text) {
    navigator.clipboard.writeText(text).then(() => {
        const icon = btn.querySelector('.material-symbols-outlined');
        if (icon) {
            icon.textContent = 'check';
            btn.classList.add('text-success');
            btn.classList.remove('text-on-surface', 'text-on-surface-variant', 'text-primary');
        }
        setTimeout(() => {
            if (icon) {
                icon.textContent = 'link';
                btn.classList.remove('text-success');
                btn.classList.add('text-on-surface', 'text-on-surface-variant');
            }
        }, 1500);
    }).catch(() => {
        const ta = document.createElement('textarea');
        ta.value = text;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        alert('Link disalin ke clipboard');
    });
}
</script>
@endpush
@endsection
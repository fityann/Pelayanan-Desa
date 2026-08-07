@extends('layouts.admin')

@section('title', 'Pengajuan Surat - SILAPU')

@section('content')
<div class="flex flex-col gap-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Pengajuan Surat</h1>
            <p class="text-body-sm text-on-surface-variant">Verifikasi dan proses pengajuan surat warga</p>
        </div>
        <div class="flex gap-sm">
            <a href="{{ route('admin.surat.arsip') }}" class="bg-surface-container-lowest px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all flex items-center gap-sm border border-outline-variant">
                <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                Arsip
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
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Pemohon</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Jenis Surat</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Tanggal</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Status</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant/20">
                    @forelse ($pengajuan as $item)
                        <tr class="hover:bg-surface-container/30 transition-colors">
                            <td class="px-lg py-4">
                                <div class="flex items-center gap-md">
                                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                                        <span class="material-symbols-outlined text-[20px]">person</span>
                                    </div>
                                    <div>
                                        <p class="text-body-md font-semibold text-on-surface">{{ $item->pemohon_name }}</p>
                                        <p class="text-label-sm text-on-surface-variant">NIK: {{ $item->pemohon_nik }}</p>
                                        @if ($item->no_whatsapp)
                                            <p class="text-label-sm text-on-surface-variant">WA: {{ $item->no_whatsapp }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-lg py-4">
                                <span class="text-body-md text-on-surface">{{ $item->jenisSurat->nama }}</span>
                            </td>
                            <td class="px-lg py-4">
                                <span class="text-body-sm text-on-surface-variant">{{ $item->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="px-lg py-4">
                                @include('partials.surat-status-badge', ['status' => $item->status])
                            </td>
                            <td class="px-lg py-4">
                                <div class="flex items-center gap-sm flex-wrap">
                                    @if ($item->status === 'diajukan')
                                        <form method="POST" action="{{ route('admin.surat.verifikasi', $item) }}" class="inline">
                                            @csrf
                                            <button class="text-primary text-label-sm font-bold hover:underline">Verifikasi</button>
                                        </form>
                                    @endif
                                    @if ($item->status === 'diverifikasi_admin')
                                        <form method="POST" action="{{ route('admin.surat.approve', $item) }}" class="inline">
                                            @csrf
                                            <button class="text-success text-label-sm font-bold hover:underline" onclick="return confirm('Setujui pengajuan ini? Nomor surat akan digenerate otomatis.')">Setujui (Kades)</button>
                                        </form>
                                        <button onclick="openRejectModal({{ $item->id }})" class="text-error text-label-sm font-bold hover:underline">Tolak</button>
                                    @endif
                                    @if (in_array($item->status, ['disetujui_kades', 'menunggu_ttd_fisik']))
                                        <span class="text-label-sm text-on-surface-variant">No: {{ $item->nomor_surat }}</span>
                                        <a href="{{ route('admin.surat.pdf', $item) }}" class="text-secondary text-label-sm font-bold hover:underline">Download PDF</a>
                                    @endif
                                    @if ($item->status === 'menunggu_ttd_fisik')
                                        <form method="POST" action="{{ route('admin.surat.selesai', $item) }}" class="inline">
                                            @csrf
                                            <button class="text-on-surface-variant text-label-sm font-bold hover:underline" onclick="return confirm('Pastikan surat sudah ditandatangani Kepala Desa. Tandai selesai?')">Tandai Selesai</button>
                                        </form>
                                    @endif
                                    @if ($item->alasan_ditolak)
                                        <span class="text-label-sm text-error" title="{{ $item->alasan_ditolak }}">Alasan: {{ Str::limit($item->alasan_ditolak, 30) }}</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-xl text-on-surface-variant">Belum ada pengajuan surat</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($pengajuan->hasPages())
            <div class="p-lg border-t border-surface-variant/20">
                {{ $pengajuan->links() }}
            </div>
        @endif
    </div>
</div>

<div id="modalReject" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center hidden">
    <div class="bg-surface-container-lowest rounded-2xl shadow-2xl w-full max-w-md mx-4">
        <div class="p-lg border-b border-surface-variant/30 flex items-center justify-between">
            <h2 class="text-title-md font-bold text-on-surface">Alasan Penolakan</h2>
            <button onclick="document.getElementById('modalReject').classList.add('hidden')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="formReject" method="POST" class="p-lg space-y-lg">
            @csrf
            <div>
                <label class="text-label-sm font-bold text-on-surface block mb-xs">Alasan</label>
                <textarea name="alasan_ditolak" rows="4" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Jelaskan alasan penolakan..."></textarea>
            </div>
            <div class="flex gap-md justify-end pt-md border-t border-surface-variant/30">
                <button type="button" onclick="document.getElementById('modalReject').classList.add('hidden')" class="px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all">Batal</button>
                <button type="submit" class="bg-error text-on-error px-lg py-2 rounded-full text-label-md font-bold hover:bg-error/90 transition-all">Tolak Surat</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openRejectModal(id) {
    document.getElementById('formReject').action = '/admin/surat/' + id + '/reject';
    document.getElementById('modalReject').classList.remove('hidden');
}
</script>
@endpush
@endsection

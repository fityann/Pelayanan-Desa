@extends('layouts.admin')

@section('title', 'Arsip Surat - SILAPU')

@section('content')
<div class="flex flex-col gap-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Arsip Surat</h1>
            <p class="text-body-sm text-on-surface-variant">Arsip surat yang sudah selesai diproses</p>
        </div>
        <div class="flex gap-sm">
            <a href="{{ route('admin.surat.arsip.export', request()->query()) }}" class="bg-green-600 px-lg py-2 rounded-full text-label-md font-bold text-white hover:bg-green-700 transition-all flex items-center gap-sm shadow-sm">
                <span class="material-symbols-outlined text-[18px]">download</span>
                Export Excel
            </a>
            <a href="{{ route('admin.surat.tracking') }}" class="bg-surface-container-lowest px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all flex items-center gap-sm border border-outline-variant">
                <span class="material-symbols-outlined text-[18px]">search</span>
                Tracking
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
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">No. Surat</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Pemohon</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Jenis</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Tgl Disetujui</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Status</th>
                        <th class="text-center px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant/20">
                    @forelse ($arsip as $item)
                        <tr class="hover:bg-surface-container/30 transition-colors">
                            <td class="px-lg py-4">
                                <span class="text-body-md font-mono font-bold text-on-surface">{{ $item->nomor_surat ?? '-' }}</span>
                            </td>
                            <td class="px-lg py-4">
                                <span class="text-body-md text-on-surface font-semibold">{{ $item->pemohon_name }}</span>
                            </td>
                            <td class="px-lg py-4">
                                <span class="text-body-sm font-bold text-on-surface block">{{ $item->jenisSurat->nama }}</span>
                                @if ($item->keterangan || $item->keperluan)
                                    <span class="text-[11px] text-on-surface-variant block mt-0.5 mb-1">Keperluan: {{ $item->keterangan ?? $item->keperluan }}</span>
                                @endif
                                @if ($item->file_pendukung)
                                    <a href="{{ Storage::url($item->file_pendukung) }}" target="_blank" class="inline-flex items-center space-x-1 text-[11px] font-bold text-blue-700 hover:text-blue-800 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-200/60 mt-1">
                                        <span class="material-symbols-outlined text-[12px]">attach_file</span>
                                        <span>Lampiran</span>
                                    </a>
                                @endif
                            </td>
                            <td class="px-lg py-4">
                                <span class="text-body-sm text-on-surface-variant">{{ $item->tanggal_disetujui?->format('d/m/Y') ?? '-' }}</span>
                            </td>
                            <td class="px-lg py-4">
                                @include('partials.surat-status-badge', ['status' => $item->status])
                            </td>
                            <td class="px-lg py-4 text-center">
                                <div class="flex items-center justify-center gap-xs">
                                    <a href="{{ route('admin.surat.pdf', $item) }}" class="inline-flex items-center gap-1 bg-primary/10 text-primary hover:bg-primary hover:text-white px-2.5 py-1 rounded-lg text-xs font-bold transition-all">
                                        <span class="material-symbols-outlined text-[15px]">picture_as_pdf</span>
                                        <span>PDF</span>
                                    </a>
                                    <button onclick="confirmDeleteArsip('{{ route('admin.surat.arsip.destroy', $item) }}', '{{ addslashes($item->nomor_surat ?? $item->jenisSurat->nama) }}', '{{ addslashes($item->pemohon_name) }}')"
                                            type="button"
                                            class="inline-flex items-center gap-1 bg-red-500/10 text-red-600 hover:bg-red-600 hover:text-white px-2.5 py-1 rounded-lg text-xs font-bold transition-all">
                                        <span class="material-symbols-outlined text-[15px]">delete</span>
                                        <span>Hapus</span>
                                    </button>
                                </div>
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

<!-- Custom Delete Modal Arsip -->
<div id="deleteArsipModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-md flex items-center justify-center p-4">
    <div class="relative bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 transform transition-all text-center">
        <button onclick="closeDeleteArsipModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-1.5 rounded-full hover:bg-slate-100 transition-colors">
            <span class="material-symbols-outlined text-xl">close</span>
        </button>

        <div class="w-16 h-16 rounded-full bg-red-100 text-red-600 mx-auto mb-4 flex items-center justify-center border-4 border-red-50 shadow-inner">
            <span class="material-symbols-outlined text-3xl">delete_forever</span>
        </div>

        <h3 class="text-xl font-black text-slate-900 mb-1">Konfirmasi Hapus Arsip</h3>
        <p class="text-xs text-slate-500 mb-4">Apakah Anda yakin ingin menghapus arsip surat berikut dari sistem?</p>

        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 mb-6 text-left text-xs space-y-1.5">
            <div class="flex justify-between">
                <span class="text-slate-400 font-medium">Surat / No:</span>
                <span id="deleteArsipNomor" class="font-bold text-slate-900"></span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400 font-medium">Pemohon:</span>
                <span id="deleteArsipPemohon" class="font-semibold text-slate-900"></span>
            </div>
        </div>

        <form id="deleteArsipForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteArsipModal()" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 px-4 rounded-xl text-xs transition-all">
                    Batal
                </button>
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-extrabold py-3 px-4 rounded-xl text-xs shadow-lg shadow-red-600/30 transition-all flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-base">delete</span>
                    <span>Ya, Hapus Arsip</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function confirmDeleteArsip(url, nomor, pemohon) {
    document.getElementById('deleteArsipForm').action = url;
    document.getElementById('deleteArsipNomor').textContent = nomor;
    document.getElementById('deleteArsipPemohon').textContent = pemohon;
    document.getElementById('deleteArsipModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteArsipModal() {
    document.getElementById('deleteArsipModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('deleteArsipModal');
        if (modal && !modal.classList.contains('hidden')) {
            closeDeleteArsipModal();
        }
    }
});

const deleteArsipModal = document.getElementById('deleteArsipModal');
if (deleteArsipModal) {
    deleteArsipModal.addEventListener('click', function(e) {
        if (e.target.id === 'deleteArsipModal') {
            closeDeleteArsipModal();
        }
    });
}
</script>
@endpush
@endsection

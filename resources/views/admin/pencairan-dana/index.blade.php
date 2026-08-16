@extends('layouts.admin')

@section('title', 'Pencairan Dana - SILAPU')

@section('content')
<div x-data="pencairanDana()" class="flex flex-col gap-lg">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-md">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Pencairan Dana</h1>
            <p class="text-body-sm text-on-surface-variant">Manajemen permohonan pencairan dana desa</p>
        </div>
        <a href="{{ route('admin.pencairan-dana.create') }}" class="bg-primary text-on-primary px-lg py-2.5 rounded-full text-label-md font-bold hover:bg-primary-dark transition-all flex items-center gap-sm shadow-sm shadow-primary/30 hover:-translate-y-0.5 whitespace-nowrap self-start sm:self-auto">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Buat Permohonan
        </a>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-2xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>
            <p class="font-bold text-label-sm">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
        <div class="bg-surface-container-lowest rounded-3xl shadow-sm border border-outline-variant/20 p-lg relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary/5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-label-sm font-bold text-on-surface-variant mb-xs">Total Permohonan</p>
                    <p class="text-headline-lg font-black text-on-surface">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">folder_open</span>
                </div>
            </div>
        </div>
        <div class="bg-surface-container-lowest rounded-3xl shadow-sm border border-outline-variant/20 p-lg relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-secondary/5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-label-sm font-bold text-on-surface-variant mb-xs">Menunggu Proses</p>
                    <p class="text-headline-lg font-black text-on-surface">{{ $stats['pending'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-secondary/10 text-secondary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">hourglass_empty</span>
                </div>
            </div>
        </div>
        <div class="bg-surface-container-lowest rounded-3xl shadow-sm border border-outline-variant/20 p-lg relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-success/5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-label-sm font-bold text-on-surface-variant mb-xs">Total Dana Dicairkan</p>
                    <p class="text-headline-md font-black text-on-surface">Rp {{ number_format($stats['total_dicairkan'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-success/10 text-success flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">account_balance_wallet</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar -->
    <div class="bg-surface-container-lowest rounded-3xl shadow-sm border border-outline-variant/20 overflow-hidden mt-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container/50 border-b border-outline-variant/20">
                        <th class="py-4 px-6 text-label-sm font-bold text-on-surface-variant uppercase tracking-wider">Permohonan</th>
                        <th class="py-4 px-6 text-label-sm font-bold text-on-surface-variant uppercase tracking-wider">Anggaran</th>
                        <th class="py-4 px-6 text-label-sm font-bold text-on-surface-variant uppercase tracking-wider">Status</th>
                        <th class="py-4 px-6 text-label-sm font-bold text-on-surface-variant uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse ($pencairan as $p)
                        @php
                            $statusClass = match($p->status_pencairan) {
                                'dicairkan' => 'bg-success/10 text-success border-success/20',
                                'ditolak' => 'bg-error/10 text-error border-error/20',
                                'diverifikasi', 'disetujui' => 'bg-secondary/10 text-secondary-container border-secondary/20',
                                'diproses' => 'bg-primary/10 text-primary border-primary/20',
                                default => 'bg-surface-container-highest text-on-surface-variant border-outline-variant/50',
                            };
                            $icon = match($p->status_pencairan) {
                                'dicairkan' => 'task_alt',
                                'ditolak' => 'cancel',
                                'diverifikasi', 'disetujui' => 'gavel',
                                'diproses' => 'cached',
                                default => 'draft',
                            };
                        @endphp
                        <tr class="hover:bg-surface-container/30 transition-colors group">
                            <td class="py-4 px-6">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center text-primary mt-1 shrink-0">
                                        <span class="material-symbols-outlined text-[20px]">receipt_long</span>
                                    </div>
                                    <div>
                                        <p class="text-label-md font-bold text-on-surface group-hover:text-primary transition-colors line-clamp-2 md:line-clamp-1">{{ $p->nama_kegiatan }}</p>
                                        <div class="flex flex-wrap items-center gap-2 mt-1">
                                            <span class="font-mono text-label-sm text-primary bg-primary/5 px-2 py-0.5 rounded-md">{{ $p->nomor_permohonan }}</span>
                                            <span class="text-[11px] text-on-surface-variant hidden sm:inline">•</span>
                                            <span class="text-[11px] text-on-surface-variant font-medium">{{ $p->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <p class="text-label-md font-black text-on-surface whitespace-nowrap">Rp {{ number_format($p->jumlah_pencairan, 0, ',', '.') }}</p>
                                <div class="flex items-center gap-1 mt-1 text-[11px] text-on-surface-variant font-bold whitespace-nowrap">
                                    <span class="material-symbols-outlined text-[14px]">account_balance</span>
                                    {{ $p->sumber_dana }} ({{ ucfirst($p->jenis_pencairan) }})
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $statusClass }}">
                                    <span class="material-symbols-outlined text-[14px]">{{ $icon }}</span>
                                    <span class="uppercase tracking-wider">{{ str_replace('_', ' ', $p->status_pencairan) }}</span>
                                </div>
                                <p class="text-[10px] text-on-surface-variant mt-1.5 ml-1 font-medium whitespace-nowrap">Oleh: {{ $p->pemohon?->name ?? 'Admin' }}</p>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openDetail({{ json_encode($p) }})" class="w-9 h-9 rounded-full bg-surface-container hover:bg-surface-container-highest flex items-center justify-center text-on-surface-variant transition-colors tooltip-target shrink-0" title="Lihat Detail">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </button>
                                    
                                    @if (auth()->user()->hasPermissionTo('U APBDes'))
                                        @if ($p->status_pencairan === 'draft')
                                            <form method="POST" action="{{ route('admin.pencairan-dana.verify', $p) }}">
                                                @csrf
                                                <button type="submit" class="w-9 h-9 rounded-full bg-secondary/10 hover:bg-secondary/20 text-secondary flex items-center justify-center transition-colors tooltip-target shrink-0" title="Verifikasi">
                                                    <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                                                </button>
                                            </form>
                                        @elseif ($p->status_pencairan === 'diverifikasi')
                                            <form method="POST" action="{{ route('admin.pencairan-dana.approve', $p) }}">
                                                @csrf
                                                <button type="submit" class="w-9 h-9 rounded-full bg-success/10 hover:bg-success/20 text-success flex items-center justify-center transition-colors tooltip-target shrink-0" title="Setujui (Kades)">
                                                    <span class="material-symbols-outlined text-[18px]">thumb_up</span>
                                                </button>
                                            </form>
                                        @elseif ($p->status_pencairan === 'disetujui')
                                            <button @click="openProses({{ $p->id }})" class="w-9 h-9 rounded-full bg-primary/10 hover:bg-primary/20 text-primary flex items-center justify-center transition-colors tooltip-target shrink-0" title="Proses Transfer">
                                                <span class="material-symbols-outlined text-[18px]">account_balance</span>
                                            </button>
                                        @elseif ($p->status_pencairan === 'diproses')
                                            <button @click="openCairkan({{ $p->id }})" class="w-9 h-9 rounded-full bg-success/10 hover:bg-success/20 text-success flex items-center justify-center transition-colors tooltip-target shrink-0" title="Selesaikan Pencairan">
                                                <span class="material-symbols-outlined text-[18px]">done_all</span>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="py-12 text-center text-on-surface-variant flex flex-col items-center">
                                    <div class="w-16 h-16 bg-surface-container rounded-full flex items-center justify-center mb-4 text-on-surface-variant/50">
                                        <span class="material-symbols-outlined text-[32px]">inbox</span>
                                    </div>
                                    <p class="text-label-md font-bold text-on-surface">Belum Ada Permohonan</p>
                                    <p class="text-body-sm mt-1">Belum ada pengajuan pencairan dana yang terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pencairan->hasPages())
            <div class="p-lg border-t border-outline-variant/20 bg-surface-container-lowest">
                {{ $pencairan->links() }}
            </div>
        @endif
    </div>

    <!-- Modals -->
    <!-- Modal Detail -->
    <div x-show="modalDetail" style="display: none" class="fixed inset-0 z-[100] flex items-center justify-center px-4" x-transition.opacity>
        <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" @click="modalDetail = false"></div>
        <div class="relative bg-surface-container-lowest w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]" x-transition.scale.95>
            <div class="px-xl py-lg border-b border-outline-variant/20 flex items-center justify-between bg-surface-container/30">
                <div class="flex items-center gap-sm">
                    <span class="material-symbols-outlined text-primary">receipt_long</span>
                    <h3 class="text-headline-sm font-bold text-on-surface">Detail Permohonan</h3>
                </div>
                <button @click="modalDetail = false" class="w-8 h-8 rounded-full bg-surface-container hover:bg-surface-container-highest flex items-center justify-center text-on-surface-variant transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            
            <div class="p-xl overflow-y-auto" x-data="{ formatRp: (angka) => 'Rp ' + Number(angka || 0).toLocaleString('id-ID') }">
                <template x-if="selectedItem">
                    <div class="space-y-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-label-sm font-bold text-on-surface-variant uppercase tracking-wider mb-1">Nomor Permohonan</p>
                                <p class="font-mono text-label-lg text-primary bg-primary/5 inline-block px-3 py-1 rounded-lg" x-text="selectedItem.nomor_permohonan"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-label-sm font-bold text-on-surface-variant uppercase tracking-wider mb-1">Status</p>
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-surface-container border border-outline-variant/50" x-text="selectedItem.status_pencairan.replace('_', ' ')"></span>
                            </div>
                        </div>

                        <div class="bg-surface-container rounded-2xl p-lg">
                            <h4 class="text-label-lg font-bold text-on-surface mb-xs" x-text="selectedItem.nama_kegiatan"></h4>
                            <p class="text-body-sm text-on-surface-variant flex items-center gap-1.5 mt-2">
                                <span class="material-symbols-outlined text-[16px]">account_balance_wallet</span>
                                Sumber: <span class="font-bold" x-text="selectedItem.sumber_dana"></span> | Jenis: <span class="font-bold capitalize" x-text="selectedItem.jenis_pencairan"></span>
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-md">
                            <div class="border border-outline-variant/30 rounded-2xl p-md">
                                <p class="text-label-sm font-bold text-on-surface-variant mb-1">Jumlah Pengajuan</p>
                                <p class="text-headline-sm font-black text-on-surface truncate" x-text="formatRp(selectedItem.jumlah_pencairan)"></p>
                            </div>
                            <div class="border border-outline-variant/30 rounded-2xl p-md">
                                <p class="text-label-sm font-bold text-on-surface-variant mb-1">Tanggal Pengajuan</p>
                                <p class="text-label-md font-bold text-on-surface" x-text="new Date(selectedItem.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})"></p>
                            </div>
                        </div>

                        <template x-if="selectedItem.status_pencairan === 'diproses' || selectedItem.status_pencairan === 'dicairkan'">
                            <div class="border border-primary/20 bg-primary/5 rounded-2xl p-lg">
                                <div class="flex items-center gap-sm mb-md pb-md border-b border-primary/10">
                                    <span class="material-symbols-outlined text-primary">account_balance</span>
                                    <h4 class="text-label-md font-bold text-primary">Informasi Rekening & Transfer</h4>
                                </div>
                                <div class="grid grid-cols-2 gap-y-md">
                                    <div>
                                        <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Metode Pembayaran</p>
                                        <p class="text-label-sm font-bold text-on-surface capitalize" x-text="selectedItem.metode_pembayaran || '-'"></p>
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Bank</p>
                                        <p class="text-label-sm font-bold text-on-surface" x-text="selectedItem.nama_bank || '-'"></p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Nomor Rekening</p>
                                        <p class="font-mono text-label-md font-bold text-on-surface bg-surface-container inline-block px-2 py-0.5 rounded mt-1" x-text="selectedItem.nomor_rekening || '-'"></p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Atas Nama</p>
                                        <p class="text-label-sm font-bold text-on-surface" x-text="selectedItem.atas_nama || '-'"></p>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="selectedItem.status_pencairan === 'dicairkan'">
                            <div class="border border-success/20 bg-success/5 rounded-2xl p-lg">
                                <div class="flex items-center gap-sm mb-xs">
                                    <span class="material-symbols-outlined text-success">task_alt</span>
                                    <h4 class="text-label-md font-bold text-success">Telah Dicairkan</h4>
                                </div>
                                <p class="text-xs text-on-surface-variant font-medium mb-3" x-text="'Pada: ' + (selectedItem.tanggal_pencairan ? new Date(selectedItem.tanggal_pencairan).toLocaleString('id-ID') : '-')"></p>
                                <div class="bg-surface-container-lowest border border-success/10 rounded-xl p-md">
                                    <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Catatan Pencairan</p>
                                    <p class="text-label-sm text-on-surface whitespace-pre-line" x-text="selectedItem.catatan_pencairan || 'Tidak ada catatan.'"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
            
            <div class="px-xl py-md bg-surface-container/50 border-t border-outline-variant/20 text-right">
                <button @click="modalDetail = false" class="px-lg py-2 rounded-full bg-surface-container-high text-on-surface font-bold text-label-sm hover:bg-surface-container-highest transition-colors">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Modal Proses Transfer -->
    <div x-show="modalProses" style="display: none" class="fixed inset-0 z-[100] flex items-center justify-center px-4" x-transition.opacity>
        <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" @click="modalProses = false"></div>
        <form :action="`/admin/pencairan-dana/${actionId}/process`" method="POST" class="relative bg-surface-container-lowest w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]" x-transition.scale.95>
            @csrf
            <div class="px-xl py-lg border-b border-outline-variant/20 flex items-center justify-between bg-primary/5">
                <div class="flex items-center gap-sm">
                    <span class="material-symbols-outlined text-primary text-[28px]">account_balance</span>
                    <div>
                        <h3 class="text-title-md font-bold text-on-surface leading-none mb-1">Proses Transfer</h3>
                        <p class="text-label-sm text-on-surface-variant">Input data rekening tujuan pencairan</p>
                    </div>
                </div>
            </div>
            
            <div class="p-xl overflow-y-auto space-y-md">
                <div>
                    <label class="text-label-sm font-bold text-on-surface block mb-xs">Metode Pembayaran <span class="text-error">*</span></label>
                    <select name="metode_pembayaran" required class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant">
                        <option value="transfer">Transfer Bank</option>
                        <option value="tunai">Tunai</option>
                    </select>
                </div>
                <div>
                    <label class="text-label-sm font-bold text-on-surface block mb-xs">Nama Bank</label>
                    <input type="text" name="nama_bank" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Contoh: Bank bjb / BRI / BNI">
                </div>
                <div>
                    <label class="text-label-sm font-bold text-on-surface block mb-xs">Nomor Rekening</label>
                    <input type="text" name="nomor_rekening" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant font-mono" placeholder="0123456789">
                </div>
                <div>
                    <label class="text-label-sm font-bold text-on-surface block mb-xs">Atas Nama Rekening</label>
                    <input type="text" name="atas_nama" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Nama Pemilik Rekening">
                </div>
            </div>
            
            <div class="px-xl py-lg bg-surface-container/30 border-t border-outline-variant/20 flex items-center justify-end gap-md">
                <button type="button" @click="modalProses = false" class="px-lg py-2.5 rounded-full font-bold text-label-sm text-on-surface-variant hover:bg-surface-container transition-colors">Batal</button>
                <button type="submit" class="px-xl py-2.5 rounded-full bg-primary text-on-primary font-bold text-label-sm hover:bg-primary-dark transition-colors shadow-sm shadow-primary/30 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">send</span>
                    Simpan & Proses
                </button>
            </div>
        </form>
    </div>

    <!-- Modal Cairkan -->
    <div x-show="modalCairkan" style="display: none" class="fixed inset-0 z-[100] flex items-center justify-center px-4" x-transition.opacity>
        <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" @click="modalCairkan = false"></div>
        <form :action="`/admin/pencairan-dana/${actionId}/complete`" method="POST" class="relative bg-surface-container-lowest w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]" x-transition.scale.95>
            @csrf
            <div class="px-xl py-lg border-b border-outline-variant/20 flex items-center justify-between bg-success/5">
                <div class="flex items-center gap-sm">
                    <span class="material-symbols-outlined text-success text-[28px]">done_all</span>
                    <div>
                        <h3 class="text-title-md font-bold text-on-surface leading-none mb-1">Selesaikan Pencairan</h3>
                        <p class="text-label-sm text-on-surface-variant">Konfirmasi bahwa dana sudah diterima</p>
                    </div>
                </div>
            </div>
            
            <div class="p-xl overflow-y-auto space-y-md">
                <div class="bg-success/10 text-success-dark px-md py-3 rounded-xl flex items-start gap-3 border border-success/20 mb-sm">
                    <span class="material-symbols-outlined text-[20px] mt-0.5">info</span>
                    <p class="text-label-sm font-medium">Dengan menekan tombol Selesaikan, Anda mengkonfirmasi bahwa dana telah berhasil ditransfer atau diserahkan kepada pihak terkait.</p>
                </div>
                <div>
                    <label class="text-label-sm font-bold text-on-surface block mb-xs">Catatan Pencairan (Opsional)</label>
                    <textarea name="catatan_pencairan" rows="3" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-success/30 border border-outline-variant resize-none" placeholder="Masukkan catatan bukti transfer atau keterangan tambahan..."></textarea>
                </div>
            </div>
            
            <div class="px-xl py-lg bg-surface-container/30 border-t border-outline-variant/20 flex items-center justify-end gap-md">
                <button type="button" @click="modalCairkan = false" class="px-lg py-2.5 rounded-full font-bold text-label-sm text-on-surface-variant hover:bg-surface-container transition-colors">Batal</button>
                <button type="submit" class="px-xl py-2.5 rounded-full bg-success text-on-success font-bold text-label-sm hover:bg-success/90 transition-colors shadow-sm shadow-success/30 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    Selesaikan
                </button>
            </div>
        </form>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('pencairanDana', () => ({
        modalDetail: false,
        modalProses: false,
        modalCairkan: false,
        selectedItem: null,
        actionId: null,

        openDetail(item) {
            this.selectedItem = item;
            this.modalDetail = true;
        },
        openProses(id) {
            this.actionId = id;
            this.modalProses = true;
        },
        openCairkan(id) {
            this.actionId = id;
            this.modalCairkan = true;
        }
    }))
})
</script>
@endpush
@endsection
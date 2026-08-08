@extends('layouts.admin')

@section('title', 'Pengajuan Surat - SILAPU')

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">
        <div>
            <div class="flex items-center space-x-3 mb-1">
                <div class="w-10 h-10 rounded-xl bg-[#4B5D3A]/10 text-[#4B5D3A] flex items-center justify-center font-bold">
                    <span class="material-symbols-outlined text-2xl">mark_email_unread</span>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Pengajuan Surat Masuk</h1>
                    <p class="text-xs text-slate-500 font-medium">Verifikasi, persetujuan Kepala Desa, dan cetak fisik surat keterangan warga.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.surat.arsip') }}" class="bg-slate-100 hover:bg-slate-200 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 transition-all flex items-center gap-2 border border-slate-200 shadow-xs">
                <span class="material-symbols-outlined text-lg">inventory_2</span>
                <span>Arsip Surat Selesai</span>
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-300 text-emerald-900 px-5 py-3.5 rounded-2xl flex items-center gap-3 shadow-xs animate-fade-in">
            <span class="material-symbols-outlined text-emerald-600 font-bold">check_circle</span>
            <span class="text-xs sm:text-sm font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Data Table Container -->
    <div class="bg-white rounded-2xl shadow-md border border-slate-200/80 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4 bg-slate-50/50">
            <div class="flex items-center space-x-2">
                <span class="material-symbols-outlined text-[#4B5D3A] text-xl">view_list</span>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Daftar Pengajuan Surat Warga</h3>
            </div>
            <div class="text-xs font-semibold text-slate-500">
                Total: <span class="text-[#4B5D3A] font-extrabold">{{ $pengajuan->total() }}</span> pengajuan
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100/70 border-b border-slate-200 text-[11px] font-black text-slate-600 uppercase tracking-wider">
                        <th class="px-6 py-3.5">Pemohon</th>
                        <th class="px-6 py-3.5">Jenis Surat</th>
                        <th class="px-6 py-3.5">Tanggal</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($pengajuan as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Pemohon Column -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-[#4B5D3A]/10 text-[#4B5D3A] flex items-center justify-center font-bold flex-shrink-0 shadow-xs border border-[#4B5D3A]/20">
                                        <span class="material-symbols-outlined text-xl">person</span>
                                    </div>
                                    <div>
                                        <p class="font-extrabold text-slate-900 text-sm leading-tight">{{ $item->pemohon_name }}</p>
                                        <p class="text-[11px] text-slate-500 font-medium mt-0.5">NIK: <span class="font-mono font-bold text-slate-700">{{ $item->pemohon_nik }}</span></p>
                                        @if ($item->no_whatsapp)
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->no_whatsapp) }}" target="_blank" class="inline-flex items-center space-x-1 text-[11px] font-bold text-emerald-700 hover:text-emerald-800 mt-1 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200/60">
                                                <span class="material-symbols-outlined text-xs">chat</span>
                                                <span>WA: {{ $item->no_whatsapp }}</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Jenis Surat & Keperluan Column -->
                            <td class="px-6 py-4">
                                <span class="font-extrabold text-slate-900 text-sm block">{{ $item->jenisSurat->nama }}</span>
                                @if ($item->keterangan || $item->keperluan)
                                    <div class="mt-1.5 bg-amber-50/90 border border-amber-200/90 px-2.5 py-1 rounded-lg text-[11px] text-amber-900 font-medium inline-block max-w-xs leading-snug">
                                        <span class="text-amber-800 font-bold">Keperluan:</span> {{ $item->keterangan ?? $item->keperluan }}
                                    </div>
                                @endif
                            </td>

                            <!-- Tanggal Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs text-slate-600 font-medium">
                                    <p class="font-bold text-slate-800">{{ $item->created_at->format('d/m/Y') }}</p>
                                    <p class="text-[11px] text-slate-400 font-mono">{{ $item->created_at->format('H:i') }} WIB</p>
                                </div>
                            </td>

                            <!-- Status Column -->
                            <td class="px-6 py-4">
                                @include('partials.surat-status-badge', ['status' => $item->status])
                            </td>

                            <!-- Action Column -->
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2 flex-wrap">
                                    <!-- Action 1: Verifikasi (Admin) -->
                                    @if ($item->status === 'diajukan')
                                        <form method="POST" action="{{ route('admin.surat.verifikasi', $item) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-3.5 py-1.5 rounded-xl shadow-sm transition-all flex items-center space-x-1">
                                                <span class="material-symbols-outlined text-base">verified</span>
                                                <span>Verifikasi Admin</span>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Action 2: Setujui (Kades) & Tolak -->
                                    @if ($item->status === 'diverifikasi_admin')
                                        <button type="button" 
                                                onclick="openApproveModal({{ $item->id }}, '{{ addslashes($item->pemohon_name) }}', '{{ addslashes($item->jenisSurat->nama) }}')"
                                                class="bg-[#4B5D3A] hover:bg-[#364329] text-white text-xs font-bold px-3.5 py-1.5 rounded-xl shadow-sm hover:scale-[1.02] transition-all flex items-center space-x-1 border border-[#D8B84C]/40">
                                            <span class="material-symbols-outlined text-base text-[#F0D878]">verified_user</span>
                                            <span>Setujui (Kades)</span>
                                        </button>
                                        
                                        <button type="button" 
                                                onclick="openRejectModal({{ $item->id }}, '{{ addslashes($item->pemohon_name) }}')" 
                                                class="bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 text-xs font-bold px-3 py-1.5 rounded-xl shadow-xs transition-all flex items-center space-x-1">
                                            <span class="material-symbols-outlined text-base text-red-600">cancel</span>
                                            <span>Tolak</span>
                                        </button>
                                    @endif

                                    <!-- Action 3: Download PDF & Nomor Surat -->
                                    @if (in_array($item->status, ['disetujui_kades', 'menunggu_ttd_fisik']))
                                        <div class="inline-flex items-center space-x-2">
                                            <span class="text-[11px] font-mono font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                                                {{ $item->nomor_surat }}
                                            </span>
                                            <a href="{{ route('admin.surat.pdf', $item) }}" target="_blank" 
                                               class="bg-[#D8B84C] hover:bg-[#c9a73b] text-[#2A3520] text-xs font-extrabold px-3 py-1.5 rounded-xl shadow-sm transition-all flex items-center space-x-1">
                                                <span class="material-symbols-outlined text-base">picture_as_pdf</span>
                                                <span>Cetak PDF</span>
                                            </a>
                                        </div>
                                    @endif

                                    <!-- Action 4: Tandai Selesai -->
                                    @if ($item->status === 'menunggu_ttd_fisik')
                                        <button type="button" 
                                                onclick="openSelesaiModal({{ $item->id }}, '{{ addslashes($item->pemohon_name) }}')"
                                                class="bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-bold px-3 py-1.5 rounded-xl shadow-sm transition-all flex items-center space-x-1">
                                            <span class="material-symbols-outlined text-base text-emerald-300">task_alt</span>
                                            <span>Tandai Selesai</span>
                                        </button>
                                    @endif

                                    <!-- Reason if Rejected -->
                                    @if ($item->alasan_ditolak)
                                        <span class="text-xs text-red-600 font-bold bg-red-50 px-2.5 py-1 rounded-lg border border-red-200 flex items-center gap-1" title="{{ $item->alasan_ditolak }}">
                                            <span class="material-symbols-outlined text-sm">info</span>
                                            <span>Alasan: {{ Str::limit($item->alasan_ditolak, 25) }}</span>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-5xl text-slate-300 mb-2">inbox</span>
                                    <p class="text-sm font-bold text-slate-600">Belum ada pengajuan surat masuk</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Pengajuan dari warga akan tampil otomatis di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pengajuan->hasPages())
            <div class="p-5 border-t border-slate-100 bg-slate-50/50">
                {{ $pengajuan->links() }}
            </div>
        @endif
    </div>
</div>

<!-- ================= MODAL 1: CONFIRM APPROVE (KADES) ================= -->
<div id="modalApprove" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4 transition-all">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-200 animate-scale-up">
        <div class="bg-[#4B5D3A] p-6 text-white text-left relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-[#D8B84C]/20 rounded-full blur-2xl pointer-events-none"></div>
            <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-2xl text-[#F0D878]">verified_user</span>
            </div>
            <h3 class="text-lg font-black tracking-tight">Setujui Pengajuan Surat</h3>
            <p class="text-xs text-slate-200/90 mt-0.5">Konfirmasi persetujuan Kepala Desa untuk penerbitan surat resmi.</p>
        </div>

        <form id="formApprove" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 space-y-2 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-500 font-medium">Pemohon:</span>
                    <span id="approveName" class="font-extrabold text-slate-900"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 font-medium">Jenis Surat:</span>
                    <span id="approveSurat" class="font-bold text-[#4B5D3A]"></span>
                </div>
                <div class="pt-2 border-t border-slate-200 text-[11px] text-slate-500 flex items-start gap-1">
                    <span class="material-symbols-outlined text-sm text-[#D8B84C] mt-0.5">info</span>
                    <span>Nomor surat resmi akan digenerate otomatis oleh sistem secara berurutan.</span>
                </div>
            </div>

            <div class="flex gap-3 justify-end pt-2">
                <button type="button" onclick="closeApproveModal()" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all">
                    Batal
                </button>
                <button type="submit" class="bg-[#4B5D3A] hover:bg-[#364329] text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-md transition-all flex items-center space-x-1.5 border border-[#D8B84C]/40">
                    <span class="material-symbols-outlined text-base text-[#F0D878]">check_circle</span>
                    <span>Ya, Setujui Sekarang</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL 2: REJECT SURAT ================= -->
<div id="modalReject" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4 transition-all">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-200 animate-scale-up">
        <div class="bg-rose-700 p-6 text-white text-left relative overflow-hidden">
            <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-2xl text-white">cancel</span>
            </div>
            <h3 class="text-lg font-black tracking-tight">Tolak Pengajuan Surat</h3>
            <p class="text-xs text-rose-100 mt-0.5">Berikan alasan penolakan agar pemohon dapat memperbaiki data.</p>
        </div>

        <form id="formReject" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="text-xs font-bold text-slate-800 block mb-1.5">Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea name="alasan_ditolak" rows="3" required 
                          class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-900 font-medium placeholder-slate-400 focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all outline-none" 
                          placeholder="Jelaskan alasan penolakan secara jelas..."></textarea>
            </div>

            <div class="flex gap-3 justify-end pt-2">
                <button type="button" onclick="closeRejectModal()" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all">
                    Batal
                </button>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-md transition-all flex items-center space-x-1.5">
                    <span class="material-symbols-outlined text-base">block</span>
                    <span>Tolak Surat</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL 3: SELESAI SURAT ================= -->
<div id="modalSelesai" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4 transition-all">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-200 animate-scale-up">
        <div class="bg-emerald-800 p-6 text-white text-left relative overflow-hidden">
            <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-2xl text-emerald-300">task_alt</span>
            </div>
            <h3 class="text-lg font-black tracking-tight">Tandai Pengajuan Selesai</h3>
            <p class="text-xs text-emerald-100 mt-0.5">Konfirmasi bahwa dokumen fisik telah ditandatangani dan diserahkan.</p>
        </div>

        <form id="formSelesai" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="bg-emerald-50 p-4 rounded-2xl border border-emerald-200 text-xs text-emerald-900 space-y-1.5">
                <p class="font-bold flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-emerald-600 text-sm">verified</span>
                    <span>Verifikasi Penyerahan Dokumen</span>
                </p>
                <p class="text-[11px] text-emerald-800/90 leading-relaxed">
                    Pastikan lembar fisik surat pengajuan untuk <strong id="selesaiName"></strong> sudah mendapatkan Tanda Tangan Basah / Cap Stempel Kepala Desa Puspamukti.
                </p>
            </div>

            <div class="flex gap-3 justify-end pt-2">
                <button type="button" onclick="closeSelesaiModal()" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all">
                    Batal
                </button>
                <button type="submit" class="bg-emerald-800 hover:bg-emerald-900 text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-md transition-all flex items-center space-x-1.5">
                    <span class="material-symbols-outlined text-base text-emerald-300">check_circle</span>
                    <span>Ya, Tandai Selesai</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openApproveModal(id, pemohon, jenisSurat) {
    document.getElementById('formApprove').action = '/admin/surat/' + id + '/approve';
    document.getElementById('approveName').textContent = pemohon;
    document.getElementById('approveSurat').textContent = jenisSurat;
    document.getElementById('modalApprove').classList.remove('hidden');
}

function closeApproveModal() {
    document.getElementById('modalApprove').classList.add('hidden');
}

function openRejectModal(id, pemohon) {
    document.getElementById('formReject').action = '/admin/surat/' + id + '/reject';
    document.getElementById('modalReject').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('modalReject').classList.add('hidden');
}

function openSelesaiModal(id, pemohon) {
    document.getElementById('formSelesai').action = '/admin/surat/' + id + '/selesai';
    document.getElementById('selesaiName').textContent = pemohon;
    document.getElementById('modalSelesai').classList.remove('hidden');
}

function closeSelesaiModal() {
    document.getElementById('modalSelesai').classList.add('hidden');
}
</script>
@endpush
@endsection

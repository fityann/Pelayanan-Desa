@extends('layouts.admin')

@section('title', 'APBDes - SILAPU')

@section('content')
<div class="flex flex-col gap-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">APBDes</h1>
            <p class="text-body-sm text-on-surface-variant">Anggaran Pendapatan dan Belanja Desa</p>
        </div>
        <div class="flex gap-sm items-center">
            <form method="GET" class="flex items-center gap-sm">
                <select name="tahun" onchange="this.form.submit()" class="bg-surface-container rounded-xl px-lg py-2 text-body-md outline-none border border-outline-variant">
                    @foreach ($tahunList as $t)
                        <option value="{{ $t }}" {{ $t == $tahunDipilih ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                    <option value="{{ date('Y') }}" {{ !$tahunList->contains(date('Y')) && $tahunDipilih == date('Y') ? 'selected' : '' }}>{{ date('Y') }}</option>
                </select>
            </form>
            @if(auth()->user()->hasRole('Bendahara'))
                <button onclick="showApbdesModal()" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all flex items-center gap-sm">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Input Data
                </button>
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
        <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
            <div class="flex items-center gap-md mb-md">
                <div class="w-10 h-10 rounded-lg bg-on-tertiary-container/10 flex items-center justify-center text-on-tertiary-container">
                    <span class="material-symbols-outlined">trending_up</span>
                </div>
                <span class="text-label-sm text-on-surface-variant uppercase tracking-widest">Pendapatan</span>
            </div>
            <p class="font-headline-lg text-on-surface">Rp {{ number_format($ringkasan['pendapatan'], 0, ',', '.') }}</p>
            <p class="text-label-sm text-success">Realisasi: Rp {{ number_format($ringkasan['realisasi_pendapatan'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
            <div class="flex items-center gap-md mb-md">
                <div class="w-10 h-10 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined">trending_down</span>
                </div>
                <span class="text-label-sm text-on-surface-variant uppercase tracking-widest">Belanja</span>
            </div>
            <p class="font-headline-lg text-on-surface">Rp {{ number_format($ringkasan['belanja'], 0, ',', '.') }}</p>
            <p class="text-label-sm text-error">Realisasi: Rp {{ number_format($ringkasan['realisasi_belanja'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
            <div class="flex items-center gap-md mb-md">
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">account_balance</span>
                </div>
                <span class="text-label-sm text-on-surface-variant uppercase tracking-widest">Pembiayaan</span>
            </div>
            <p class="font-headline-lg text-on-surface">Rp {{ number_format($ringkasan['pembiayaan'], 0, ',', '.') }}</p>
        </div>
    </div>

    @foreach ($kategori as $kat => $items)
        <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
            <div class="px-lg py-4 bg-surface-container/30 border-b border-surface-variant/20">
                <h3 class="text-title-md font-bold text-on-surface">{{ $kat }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-surface-container/20">
                            <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase">Bidang</th>
                            <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase">Uraian</th>
                            <th class="text-right px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase">Anggaran</th>
                            <th class="text-right px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase">Realisasi</th>
                            <th class="text-center px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase">Status</th>
                            @if(auth()->user()->hasAnyRole(['Bendahara', 'Sekretaris Desa', 'Kepala Desa', 'Super Admin']))
                                <th class="text-center px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-variant/20">
                        @foreach ($items as $item)
                            <tr class="hover:bg-surface-container/30 transition-colors">
                                <td class="px-lg py-3 text-body-sm text-on-surface">{{ $item->bidang ?? '-' }}</td>
                                <td class="px-lg py-3 text-body-md text-on-surface">{{ $item->uraian }}</td>
                                <td class="px-lg py-3 text-body-sm text-on-surface text-right font-mono">Rp {{ number_format($item->anggaran, 0, ',', '.') }}</td>
                                <td class="px-lg py-3 text-body-sm text-on-surface text-right font-mono">Rp {{ number_format($item->realisasi, 0, ',', '.') }}</td>
                                <td class="px-lg py-3 text-center">
                                    @php
                                        $sc = match($item->status) {
                                            'draft' => 'bg-on-tertiary-container/10 text-on-tertiary-container',
                                            'direview' => 'bg-primary/10 text-primary',
                                            'dipublikasikan' => 'bg-success/10 text-success',
                                            default => 'bg-surface-variant/30 text-on-surface-variant'
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $sc }}">{{ $item->status }}</span>
                                </td>
                                @if(auth()->user()->hasAnyRole(['Bendahara', 'Sekretaris Desa', 'Kepala Desa', 'Super Admin']))
                                    <td class="px-lg py-3 text-center">
                                        <div class="flex items-center justify-center gap-sm">
                                            @if($item->status === 'draft' && auth()->user()->hasRole('Sekretaris Desa'))
                                                <form method="POST" action="{{ route('admin.apbdes.review', $item) }}" class="inline">
                                                    @csrf
                                                    <button class="text-primary text-label-sm font-bold hover:underline">Review</button>
                                                </form>
                                            @endif
                                            @if($item->status === 'direview' && auth()->user()->hasRole('Kepala Desa'))
                                                <form method="POST" action="{{ route('admin.apbdes.publish', $item) }}" class="inline">
                                                    @csrf
                                                    <button class="text-success text-label-sm font-bold hover:underline">Publish</button>
                                                </form>
                                            @endif
                                            @if($item->status === 'draft' && auth()->user()->hasRole('Bendahara'))
                                                <form method="POST" action="{{ route('admin.apbdes.destroy', $item) }}" class="inline" onsubmit="return confirm('Hapus data ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="text-error text-label-sm font-bold hover:underline">Hapus</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
@endsection

@include('components.apbdes-modal')

@push('scripts')
<script>
function showApbdesModal() {
    document.getElementById('apbdesModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeApbdesModal() {
    document.getElementById('apbdesModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    resetApbdesForm();
}

function resetApbdesForm() {
    document.getElementById('apbdesForm').reset();
    updatePersentase();
}

function updatePersentase() {
    const anggaran = parseFloat(document.getElementById('anggaran').value) || 0;
    const realisasi = parseFloat(document.getElementById('realisasi').value) || 0;
    
    let persentase = 0;
    if (anggaran > 0) {
        persentase = (realisasi / anggaran) * 100;
    }
    
    const roundedPersentase = Math.min(Math.round(persentase * 100) / 100, 100);
    
    document.getElementById('persentaseDisplay').textContent = roundedPersentase.toFixed(2) + '%';
    document.getElementById('persentaseBar').style.width = roundedPersentase + '%';
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Update persentase saat input berubah
    document.getElementById('anggaran').addEventListener('input', updatePersentase);
    document.getElementById('realisasi').addEventListener('input', updatePersentase);
    
    // Close modal dengan ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('apbdesModal').classList.contains('hidden')) {
            closeApbdesModal();
        }
    });
    
    // Close modal dengan klik di luar
    document.getElementById('apbdesModal').addEventListener('click', function(e) {
        if (e.target.id === 'apbdesModal') {
            closeApbdesModal();
        }
    });
});
</script>
@endpush

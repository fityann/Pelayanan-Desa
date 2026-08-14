@extends('layouts.warga')

@section('title', 'Profil Warga - SILAPU RT ' . $rt)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Profil -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-[#D8B84C]/30 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-[#F0D878]/20 to-[#6A3297]/5 rounded-bl-full -z-10"></div>
        
        <div class="flex items-center gap-4 sm:gap-6">
            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-[#6A3297] to-[#8347B3] rounded-2xl flex items-center justify-center shadow-lg text-white font-black text-2xl sm:text-3xl border-2 border-white/20">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">{{ $user->name }}</h1>
                <p class="text-slate-500 font-medium text-sm sm:text-base mt-0.5">NIK: {{ substr($user->nik, 0, 6) }}********</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Form Update Profil -->
        <div class="lg:col-span-5">
            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm h-full">
                <div class="flex items-center gap-2 mb-6">
                    <span class="material-symbols-outlined text-[#6A3297]">manage_accounts</span>
                    <h2 class="text-lg font-bold text-slate-800">Pengaturan Profil</h2>
                </div>

                <form action="{{ route('warga.rt.profil.update', ['rt' => $rt]) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#6A3297] focus:ring-2 focus:ring-[#6A3297]/20 transition-all font-medium">
                        @error('name')
                            <p class="text-red-500 text-xs font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">No. WhatsApp</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium text-sm"></span>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 0812..."
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#6A3297] focus:ring-2 focus:ring-[#6A3297]/20 transition-all font-medium">
                        </div>
                        <p class="text-xs text-slate-500 mt-1.5 font-medium">Opsional. Digunakan untuk notifikasi via WA jika tersedia.</p>
                        @error('phone')
                            <p class="text-red-500 text-xs font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-[#6A3297] hover:bg-[#4E2472] text-white font-bold py-3 rounded-xl shadow-md transition-all mt-2 text-sm flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <!-- Riwayat Aktivitas -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Riwayat Pengaduan -->
            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-orange-500">campaign</span>
                        <h2 class="text-lg font-bold text-slate-800">Riwayat Pengaduan</h2>
                    </div>
                </div>

                @if($pengaduans->isEmpty())
                    <div class="text-center py-6 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <span class="material-symbols-outlined text-4xl text-slate-300 mb-2 block">speaker_notes_off</span>
                        <p class="text-sm font-medium text-slate-500">Belum ada pengaduan yang diajukan.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($pengaduans as $pengaduan)
                            <div class="flex items-start justify-between p-3.5 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-100 transition-colors">
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800 line-clamp-1 mb-1">{{ $pengaduan->judul }}</h3>
                                    <div class="flex items-center gap-3 text-[11px] font-medium text-slate-500">
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">calendar_today</span> {{ $pengaduan->created_at->format('d M Y') }}</span>
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">category</span> {{ $pengaduan->kategori }}</span>
                                    </div>
                                </div>
                                <div class="px-2.5 py-1 rounded-lg text-[10px] font-bold tracking-wide shadow-sm
                                    {{ $pengaduan->status === 'selesai' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 
                                      ($pengaduan->status === 'proses' ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-red-100 text-red-700 border border-red-200') }}">
                                    {{ strtoupper($pengaduan->status) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Riwayat Pengajuan Surat -->
            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-500">draft</span>
                        <h2 class="text-lg font-bold text-slate-800">Pengajuan Surat Terakhir</h2>
                    </div>
                    <a href="{{ route('warga.rt.surat.riwayat', ['rt' => $rt]) }}" class="text-sm font-bold text-[#6A3297] hover:text-[#4E2472] flex items-center gap-1">
                        Lihat Semua <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </a>
                </div>

                @if($surats->isEmpty())
                    <div class="text-center py-6 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <span class="material-symbols-outlined text-4xl text-slate-300 mb-2 block">folder_off</span>
                        <p class="text-sm font-medium text-slate-500">Belum ada pengajuan surat.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($surats as $surat)
                            <div class="flex items-start justify-between p-3.5 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-100 transition-colors">
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800 line-clamp-1 mb-1">{{ $surat->jenis_surat }}</h3>
                                    <div class="flex items-center gap-3 text-[11px] font-medium text-slate-500">
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">calendar_today</span> {{ $surat->created_at->format('d M Y') }}</span>
                                        <span class="font-mono text-slate-600 bg-white px-1.5 rounded">{{ $surat->kode_surat }}</span>
                                    </div>
                                </div>
                                @php
                                    $statusColor = match($surat->status) {
                                        'Selesai' => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
                                        'Diproses' => 'bg-amber-100 text-amber-700 border border-amber-200',
                                        'Ditolak' => 'bg-red-100 text-red-700 border border-red-200',
                                        default => 'bg-slate-100 text-slate-700 border border-slate-200',
                                    };
                                @endphp
                                <div class="px-2.5 py-1 rounded-lg text-[10px] font-bold tracking-wide shadow-sm {{ $statusColor }}">
                                    {{ strtoupper($surat->status) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.warga')

@section('title', "SILAPU - Sistem Layanan Puspamukti | RT $rt")

@section('content')
<div class="space-y-8">
    @php
        $hour = now()->hour;
        $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
        $prosesSelesai = ($pengaduanStats['total_pengaduan'] ?? 0) > 0 ? round((($pengaduanStats['pengaduan_selesai'] ?? 0) / $pengaduanStats['total_pengaduan']) * 100) : 0;
    @endphp

    <!-- Welcome Hero Section (Purple Theme with Gold Border) -->
    <section class="relative overflow-hidden rounded-3xl bg-[#6A3297] text-white border-4 border-[#D8B84C] shadow-2xl shadow-[#6A3297]/30">
        <div class="relative p-6 md:p-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-8">
                <div class="flex-1">
                    <div class="flex items-center space-x-4 mb-5">
                        <div class="bg-[#D8B84C] text-[#2A3520] p-3.5 rounded-2xl shadow-md border border-[#F7F0D4]/50">
                            <span class="material-symbols-outlined text-3xl font-bold">qr_code_scanner</span>
                        </div>
                        <div>
                            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full bg-[#D8B84C] text-[#2A3520] text-xs font-black tracking-wide uppercase shadow-sm border border-[#F7F0D4]/40">
                                Portal Warga Desa Puspamukti
                            </span>
                        </div>
                    </div>

                    <h1 class="text-3xl md:text-4xl font-black leading-tight mb-2 tracking-tight">
                        {{ $greeting }}! 👋
                    </h1>

                    <p class="text-slate-100 text-base md:text-lg mb-1 font-medium">
                        Selamat datang di <span class="font-bold text-[#F0D878]">{{ trim(preg_replace('/RW\s*\d+/i', '', $qrCode->nama_rt ?? "RT $rt")) }}</span>
                    </p>


                    <!-- Hero meta chips -->
                    <div class="flex flex-wrap gap-3 mt-6">

                        <div class="flex items-center space-x-2 bg-[#2A3520]/70 border border-slate-400/30 backdrop-blur px-4 py-2 rounded-full text-slate-200">
                            <span class="material-symbols-outlined text-sm">calendar_today</span>
                            <span class="text-sm font-semibold">{{ now()->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="flex items-center space-x-2 bg-[#2A3520]/70 border border-slate-400/30 backdrop-blur px-4 py-2 rounded-full text-slate-200">
                            <span class="material-symbols-outlined text-sm">visibility</span>
                            <span class="text-sm font-semibold">{{ $stats['total_scans'] ?? 0 }} kali dibuka</span>
                        </div>
                    </div>

                    <!-- Quick actions -->
                    <div class="flex flex-wrap gap-3 mt-7">
                        <button onclick="showPengaduanModal()"
                                class="inline-flex items-center space-x-2 bg-[#D8B84C] hover:bg-[#c9a73b] text-[#2A3520] px-6 py-3.5 rounded-xl font-black shadow-lg hover:scale-105 transition-all">
                            <span class="material-symbols-outlined font-bold">campaign</span>
                            <span>Buat Pengaduan</span>
                        </button>
                        <button onclick="sharePage()"
                                class="inline-flex items-center space-x-2 bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur px-5 py-3.5 rounded-xl font-semibold transition-all">
                            <span class="material-symbols-outlined">share</span>
                            <span>Bagikan</span>
                        </button>
                    </div>
                </div>

                <!-- Location Confirmation Badge -->
                <div class="flex-shrink-0">
                    <div class="bg-[#2A3520]/80 backdrop-blur-xl border border-[#D8B84C]/40 rounded-2xl p-6 w-full md:w-64 shadow-xl">
                        <div class="flex items-center space-x-3">
                            <div class="bg-[#D8B84C] text-[#2A3520] p-2.5 rounded-full shadow-md">
                                <span class="material-symbols-outlined font-bold">check_circle</span>
                            </div>
                            <div>
                                <p class="text-[11px] uppercase tracking-wider text-[#F0D878] font-bold">Lokasi Terhubung</p>
                                <p class="text-2xl font-black text-white">RT {{ $rt }}</p>
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-200/80 mt-3 font-medium">Pengaduan Anda otomatis tercatat di wilayah ini</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="stats-card p-6 relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-full h-1 bg-red-500"></div>
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-gray-500">Pengaduan Masuk</p>
                <div class="bg-red-100 p-3 rounded-xl">
                    <span class="material-symbols-outlined text-red-600">campaign</span>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <h3 class="text-3xl font-extrabold text-gray-900">{{ $pengaduanStats['total_pengaduan'] ?? 0 }}</h3>
                <div class="flex items-center space-x-2">
                    <span class="text-xs text-green-700 bg-green-50 px-2.5 py-1 rounded-full font-semibold">
                        {{ $pengaduanStats['pengaduan_selesai'] ?? 0 }} selesai
                    </span>
                    <span class="text-xs text-yellow-700 bg-yellow-50 px-2.5 py-1 rounded-full font-semibold">
                        {{ $pengaduanStats['pengaduan_diproses'] ?? 0 }} proses
                    </span>
                </div>
            </div>
        </div>

        <div class="stats-card p-6 relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-full h-1 bg-blue-500"></div>
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-gray-500">Kategori Teratas</p>
                <div class="bg-blue-100 p-3 rounded-xl">
                    <span class="material-symbols-outlined text-blue-600">trending_up</span>
                </div>
            </div>
            <h3 class="text-xl font-extrabold text-gray-900 capitalize">
                {{ $pengaduanStats['kategori_top']->kategori ?? 'Belum Ada' }}
            </h3>
            <p class="text-sm text-gray-500 mt-1">
                {{ $pengaduanStats['kategori_top']->jumlah ?? 0 }} laporan di kategori ini
            </p>
        </div>

        <div class="stats-card p-6 relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-full h-1 bg-emerald-500"></div>
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-gray-500">Scan QR Hari Ini</p>
                <div class="bg-emerald-100 p-3 rounded-xl">
                    <span class="material-symbols-outlined text-emerald-600">qr_code_scanner</span>
                </div>
            </div>
            <h3 class="text-3xl font-extrabold text-gray-900">{{ $stats['today_scans'] ?? 0 }}</h3>
            <p class="text-sm text-gray-500 mt-1">
                Terakhir: {{ !empty($stats['last_scan']) ? \Carbon\Carbon::parse($stats['last_scan'])->diffForHumans() : 'Belum ada' }}
            </p>
        </div>
    </div>

    <!-- Pengaduan selesai progress -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-bold text-gray-900">Penyelesaian Pengaduan</h3>
            <span class="text-sm font-extrabold text-emerald-600">{{ $prosesSelesai }}%</span>
        </div>
        <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full transition-all duration-700"
                 style="width: {{ $prosesSelesai }}%"></div>
        </div>
        <div class="flex justify-between mt-2 text-xs text-gray-400">
            <span>Dari {{ $pengaduanStats['total_pengaduan'] ?? 0 }} laporan</span>
            <span>{{ $pengaduanStats['pengaduan_selesai'] ?? 0 }} terselesaikan</span>
        </div>
    </div>

    <!-- Services Grid -->
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-gray-900">Layanan Warga</h2>
                <p class="text-sm text-gray-500 mt-1">Akses cepat layanan digital untuk warga RT {{ $rt }}</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Pengaduan -->
            <div class="service-card p-6 cursor-default">
                <div class="flex items-start space-x-4">
                    <div class="bg-red-100 p-3 rounded-xl">
                        <span class="material-symbols-outlined text-red-600 text-2xl">campaign</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 mb-2">Buat Pengaduan</h3>
                        <p class="text-sm text-gray-600 mb-3">Laporkan masalah di lingkungan RT {{ $rt }}</p>
                        <div class="flex items-center text-xs text-gray-500">
                            <span class="material-symbols-outlined text-xs mr-1">timer</span>
                            <span>Proses cepat, respon 1x24 jam</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Info Desa -->
            <div class="service-card p-6 cursor-default">
                <div class="flex items-start space-x-4">
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <span class="material-symbols-outlined text-blue-600 text-2xl">newspaper</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 mb-2">Info Desa</h3>
                        <p class="text-sm text-gray-600 mb-3">Berita, APBDes, dan informasi desa terbaru</p>
                        <div class="flex items-center text-xs text-gray-500">
                            <span class="material-symbols-outlined text-xs mr-1">update</span>
                            <span>Update terbaru setiap minggu</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Surat Online -->
            <div class="service-card p-6 cursor-default">
                <div class="flex items-start space-x-4">
                    <div class="bg-green-100 p-3 rounded-xl">
                        <span class="material-symbols-outlined text-green-600 text-2xl">edit_note</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 mb-2">Surat Online</h3>
                        <p class="text-sm text-gray-600 mb-3">Ajukan surat keterangan tanpa antri</p>
                        <div class="flex items-center text-xs text-gray-500">
                            <span class="material-symbols-outlined text-xs mr-1">download</span>
                            <span>Download langsung setelah disetujui</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Data Penduduk -->
            <div class="service-card p-6 cursor-default text-left block">
                <div class="flex items-start space-x-4">
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <span class="material-symbols-outlined text-purple-600 text-2xl">groups</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 mb-2">Data Penduduk</h3>
                        <p class="text-sm text-gray-600 mb-3">Informasi data penduduk RT {{ $rt }}</p>
                        <div class="flex items-center text-xs text-gray-500">
                            <span class="material-symbols-outlined text-xs mr-1">verified</span>
                            <span>Data terverifikasi pemerintah desa</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Kegiatan RT -->
            <div class="service-card p-6 cursor-default text-left w-full block">
                <div class="flex items-start space-x-4">
                    <div class="bg-amber-100 p-3 rounded-xl">
                        <span class="material-symbols-outlined text-amber-700 text-2xl">event</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 mb-2">Kegiatan RT</h3>
                        <p class="text-sm text-gray-600 mb-3">Jadwal kegiatan dan pertemuan RT {{ $rt }}</p>
                        <div class="flex items-center text-xs text-gray-500">
                            <span class="material-symbols-outlined text-xs mr-1">calendar_month</span>
                            <span>Arisan, kerja bakti, pengajian, remaja</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Kontak Darurat -->
            <div class="service-card p-6 cursor-default text-left w-full block">
                <div class="flex items-start space-x-4">
                    <div class="bg-rose-100 p-3 rounded-xl">
                        <span class="material-symbols-outlined text-rose-600 text-2xl">emergency</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 mb-2">Kontak Darurat</h3>
                        <p class="text-sm text-gray-600 mb-3">Nomor penting untuk keadaan darurat</p>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="bg-red-50 rounded-lg py-2 text-center">
                                <span class="material-symbols-outlined text-red-500 text-base block">local_police</span>
                                <span class="text-xs font-semibold text-gray-700">110</span>
                            </div>
                            <div class="bg-orange-50 rounded-lg py-2 text-center">
                                <span class="material-symbols-outlined text-orange-500 text-base block">local_fire_department</span>
                                <span class="text-xs font-semibold text-gray-700">113</span>
                            </div>
                            <div class="bg-emerald-50 rounded-lg py-2 text-center">
                                <span class="material-symbols-outlined text-emerald-500 text-base block">ambulance</span>
                                <span class="text-xs font-semibold text-gray-700">119</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Berita & Agenda Wilayah -->
    @if($beritaTerbaru->count() > 0 || $agendaTerdekat->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @if($beritaTerbaru->count() > 0)
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900">Berita Terbaru</h2>
                    <p class="text-sm text-gray-500 mt-1">Informasi terkini untuk warga RT {{ $rt }}</p>
                </div>
                <a href="{{ route('warga.rt.info', ['rt' => $rt]) }}"
                   class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center space-x-1 whitespace-nowrap">
                    <span>Semua</span>
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($beritaTerbaru as $berita)
                <a href="{{ route('informasi.publik', ['rt' => $rt]) }}" class="group block border border-gray-100 rounded-xl overflow-hidden hover:shadow-md transition-all">
                    @if($berita->gambar)
                    <div class="h-28 bg-gray-200 overflow-hidden">
                        <img src="{{ Storage::url($berita->gambar) }}" alt="{{ $berita->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    @else
                    <div class="h-28 bg-gradient-to-br from-emerald-50 to-teal-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl text-emerald-300">newspaper</span>
                    </div>
                    @endif
<div class="p-4">
                        <span class="text-[10px] uppercase tracking-wider font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Berita</span>
                        <h3 class="font-semibold text-gray-900 mt-2 line-clamp-2 group-hover:text-emerald-700 transition-colors">{{ $berita->judul }}</h3>
                        <p class="text-sm text-gray-600 mt-1.5 line-clamp-2">{{ Str::limit(strip_tags($berita->isi ?? ''), 100) }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ $berita->published_at?->diffForHumans() }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        @if($agendaTerdekat->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Agenda Terdekat</h2>
                    <p class="text-sm text-gray-500 mt-1">Kegiatan RT {{ $rt }}</p>
                </div>
            </div>
            <div class="space-y-4">
                @foreach($agendaTerdekat as $agenda)
                <div class="flex items-start space-x-3 border border-gray-100 rounded-xl p-3 hover:bg-gray-50 transition-colors">
                    <div class="bg-emerald-100 rounded-lg px-3 py-2 text-center flex-shrink-0">
                        <p class="text-lg font-bold text-emerald-700 leading-none">{{ $agenda->tanggal_kegiatan?->format('d') }}</p>
                        <p class="text-[10px] uppercase text-emerald-600 font-semibold">{{ $agenda->tanggal_kegiatan?->translatedFormat('M') }}</p>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-semibold text-gray-900 text-sm line-clamp-1">{{ $agenda->judul }}</h3>
                        @if($agenda->lokasi)
                        <p class="text-xs text-gray-500 mt-1 flex items-center">
                            <span class="material-symbols-outlined text-xs mr-1">location_on</span>
                            {{ $agenda->lokasi }}
                        </p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Recent Pengaduan -->
    @php
        $recentPengaduan = \App\Models\Pengaduan::where('rt', $rt)
            ->where('rw', $rw)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    @endphp
    
    @if($recentPengaduan->count() > 0)
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-gray-900">Pengaduan Terbaru</h2>
                <p class="text-sm text-gray-500 mt-1">Laporan terkini warga RT {{ $rt }}</p>
            </div>
        </div>
        <div class="space-y-4">
            @foreach($recentPengaduan as $pengaduan)
            <div class="flex items-start space-x-4 p-4 border border-gray-100 rounded-xl hover:bg-gray-50 hover:border-gray-200 transition-all">
                <div class="flex-shrink-0">
                    <div class="{{ $pengaduan->status == 'selesai' ? 'bg-green-100 text-green-600' : ($pengaduan->status == 'diproses' ? 'bg-yellow-100 text-yellow-600' : 'bg-blue-100 text-blue-600') }} p-2.5 rounded-xl">
                        <span class="material-symbols-outlined">
                            {{ $pengaduan->kategori == 'sampah' ? 'delete' : 
                               ($pengaduan->kategori == 'jalan' ? 'directions' : 
                               ($pengaduan->kategori == 'drainase' ? 'water_damage' : 
                               ($pengaduan->kategori == 'penerangan' ? 'lightbulb' :
                               ($pengaduan->kategori == 'air' ? 'water_drop' : 'campaign')))) }}
                        </span>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1 gap-3">
                        <h4 class="font-semibold text-gray-900 truncate">{{ $pengaduan->judul }}</h4>
                        <span class="text-xs px-2.5 py-1 rounded-full whitespace-nowrap {{ $pengaduan->status == 'selesai' ? 'bg-green-50 text-green-700' : ($pengaduan->status == 'diproses' ? 'bg-yellow-50 text-yellow-700' : 'bg-blue-50 text-blue-700') }}">
                            {{ ucfirst($pengaduan->status) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2 line-clamp-2">{{ Str::limit($pengaduan->deskripsi, 100) }}</p>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500">
                        <span class="flex items-center">
                            <span class="material-symbols-outlined text-xs mr-1">person</span>
                            {{ $pengaduan->nama_pelapor }}
                        </span>
                        <span class="flex items-center">
                            <span class="material-symbols-outlined text-xs mr-1">confirmation_number</span>
                            {{ $pengaduan->tiket_id }}
                        </span>
                        <span class="flex items-center">
                            <span class="material-symbols-outlined text-xs mr-1">schedule</span>
                            {{ $pengaduan->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if($recentPengaduan->count() >= 5)
        <div class="text-center mt-6">
            <p class="text-xs text-gray-400">Menampilkan 5 pengaduan terbaru wilayah ini</p>
        </div>
        @endif
    </div>
    @endif

    <!-- Announcement -->
    <div class="relative overflow-hidden bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-2xl p-6 md:p-8">
        <div class="absolute -right-8 -top-8 w-40 h-40 bg-yellow-100 rounded-full opacity-60 blur-2xl"></div>
        <div class="relative flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-5">
            <div class="bg-white p-3.5 rounded-2xl shadow-sm">
                <span class="material-symbols-outlined text-yellow-600 text-3xl">info</span>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-gray-900 mb-2 text-lg">Layanan Terhubung dengan Wilayah Anda</h3>
                <p class="text-sm text-gray-700 mb-3">
                    Anda mengakses layanan ini melalui QR Code khas <strong>RT {{ $rt }}</strong>. 
                    Pengaduan yang Anda kirim akan langsung tercatat di wilayah tersebut dan diproses pemerintah desa.
                </p>
                <p class="text-xs text-gray-500 flex items-center">
                    <span class="material-symbols-outlined text-sm mr-1">verified</span>
                    Data lokasi otomatis terisi — Anda tidak perlu mengisi RT secara manual.
                </p>
            </div>
            <button onclick="showPengaduanModal()"
                    class="inline-flex items-center space-x-2 bg-gradient-to-r from-red-600 to-red-700 text-white px-5 py-3 rounded-xl font-semibold hover:from-red-700 hover:to-red-800 transition-all whitespace-nowrap">
                <span class="material-symbols-outlined">campaign</span>
                <span>Lapor Sekarang</span>
            </button>
        </div>
    </div>
</div>

@push('modals')
<!-- Pengaduan Modal -->
<div id="pengaduanModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-md items-center justify-center p-4" onclick="if(event.target === this) closePengaduanModal()">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="material-symbols-outlined">campaign</span>
                    <div>
                        <h3 class="text-lg font-bold">Buat Pengaduan Baru</h3>
                        <p class="text-sm text-white/80">RT {{ $rt }}</p>
                    </div>
                </div>
                <button type="button" onclick="closePengaduanModal()" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>
        
        <!-- Modal Content -->
        <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 120px)">
            <form id="pengaduanForm" onsubmit="submitPengaduan(event)" class="space-y-6">
                @csrf
                
                <!-- Nama & WhatsApp -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none transition-all"
                               placeholder="Masukkan nama lengkap">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="whatsapp" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none transition-all"
                               placeholder="0812-3456-7890">
                    </div>
                </div>
                
                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Kategori Pengaduan <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach(['sampah' => 'Sampah', 'jalan' => 'Jalan', 'drainase' => 'Drainase', 'penerangan' => 'Penerangan', 'air' => 'Air', 'lainnya' => 'Lainnya'] as $key => $label)
                        <label class="relative">
                            <input type="radio" name="kategori" value="{{ $key }}" 
                                   class="sr-only peer" {{ $key == 'sampah' ? 'checked' : '' }}>
                            <div class="border border-gray-300 rounded-lg p-4 text-center cursor-pointer peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-700 peer-checked:font-semibold transition-all">
                                <span class="text-sm font-medium">{{ $label }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                
                <!-- Judul -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Pengaduan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none transition-all"
                           placeholder="Contoh: Sampah menumpuk di gang RT {{ $rt }}">
                </div>
                
                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea name="deskripsi" rows="4" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none resize-none"
                              placeholder="Jelaskan masalah secara detail..."></textarea>
                </div>
                
                <!-- Foto -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Foto Bukti (Maks. 5 foto, opsional)
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-red-300 transition-colors">
                        <input type="file" name="foto[]" id="fotoInput" accept="image/*" capture="environment" multiple class="hidden">
                        <label for="fotoInput" class="cursor-pointer">
                            <span class="material-symbols-outlined text-4xl text-gray-400 mb-2 block">add_photo_alternate</span>
                            <p class="text-sm text-gray-600 mb-2">Ambil beberapa foto langsung dari kamera</p>
                            <p class="text-xs text-gray-500">Maksimal 5 foto, masing-masing 5MB, format: JPG, PNG, WEBP</p>
                        </label>
                    </div>
                    <div id="fotoPreview" class="mt-3 hidden grid grid-cols-3 gap-2"></div>
                </div>
                
                <!-- Submit Button -->
                <div class="flex space-x-3 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closePengaduanModal()"
                            class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-3 rounded-lg font-medium hover:from-red-700 hover:to-red-800 transition-all flex items-center justify-center space-x-2">
                        <span class="material-symbols-outlined">send</span>
                        <span>Kirim Pengaduan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Kegiatan RT -->
<div id="kegiatanRtModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-md items-center justify-center p-4" onclick="if(event.target === this) closeKegiatanRtModal()">
    <div class="relative bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden transform transition-all text-left border border-slate-100">
        <!-- Header Banner -->
        <div class="bg-[#6A3297] text-white px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-2 rounded-xl">
                    <span class="material-symbols-outlined text-2xl text-white">event</span>
                </div>
                <div>
                    <h3 class="text-lg font-black leading-tight">Jadwal Kegiatan RT {{ $rt }}</h3>
                    <p class="text-xs text-white/80 font-medium">Agenda rutin dan pertemuan warga RT {{ $rt }} Desa Puspamukti</p>
                </div>
            </div>
            <button type="button" onclick="closeKegiatanRtModal()" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors">
                <span class="material-symbols-outlined text-2xl">close</span>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-3 max-h-[70vh] overflow-y-auto">
            <div class="bg-amber-50/80 border border-amber-200 rounded-xl p-4 flex items-start space-x-3">
                <div class="bg-[#6A3297] text-white rounded-xl px-3 py-2 text-center flex-shrink-0">
                    <span class="text-[10px] font-black block">MINGGU</span>
                    <span class="text-base font-black leading-none">08:00</span>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-black text-slate-900">Kerja Bakti Masal RT {{ $rt }}</h4>
                    <p class="text-xs text-slate-600 mt-0.5">Pembersihan selokan drainase & fasilitas umum lingkungan.</p>
                    <div class="flex items-center space-x-1.5 mt-2 text-[11px] text-amber-900 font-bold">
                        <span class="material-symbols-outlined text-sm text-amber-700">location_on</span>
                        <span>Area Balai RT {{ $rt }} & Lapangan Warga</span>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex items-start space-x-3">
                <div class="bg-amber-600 text-white rounded-xl px-3 py-2 text-center flex-shrink-0">
                    <span class="text-[10px] font-black block">BULANAN</span>
                    <span class="text-base font-black leading-none">19:30</span>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-black text-slate-900">Arisan & Pengajian Bulanan Warga</h4>
                    <p class="text-xs text-slate-600 mt-0.5">Silaturahmi warga, pembacaan yasin, dan diskusi lingkungan RT.</p>
                    <div class="flex items-center space-x-1.5 mt-2 text-[11px] text-slate-500 font-bold">
                        <span class="material-symbols-outlined text-sm text-slate-400">home</span>
                        <span>Rumah Bapak Ketua RT {{ $rt }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex items-start space-x-3">
                <div class="bg-blue-600 text-white rounded-xl px-3 py-2 text-center flex-shrink-0">
                    <span class="text-[10px] font-black block">SABTU</span>
                    <span class="text-base font-black leading-none">20:00</span>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-black text-slate-900">Kumpul Karang Taruna & Remaja RT {{ $rt }}</h4>
                    <p class="text-xs text-slate-600 mt-0.5">Pertemuan pemuda-pemudi untuk kegiatan olahraga dan seni desa.</p>
                    <div class="flex items-center space-x-1.5 mt-2 text-[11px] text-slate-500 font-bold">
                        <span class="material-symbols-outlined text-sm text-slate-400">location_on</span>
                        <span>Pos Ronda Utama RT {{ $rt }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-slate-50 px-6 py-3 border-t border-slate-100 text-right">
            <button type="button" onclick="closeKegiatanRtModal()" class="bg-[#6A3297] text-white font-bold px-5 py-2 rounded-xl text-xs hover:bg-[#4E2472] transition-all">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Kontak Darurat -->
<div id="kontakDaruratModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-md items-center justify-center p-4" onclick="if(event.target === this) closeKontakDaruratModal()">
    <div class="relative bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden transform transition-all text-left border border-slate-100">
        <!-- Red Header Banner -->
        <div class="bg-[#c82333] text-white px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-2 rounded-xl">
                    <span class="material-symbols-outlined text-2xl text-white">emergency</span>
                </div>
                <div>
                    <h3 class="text-lg font-black leading-tight">Kontak & Nomor Darurat</h3>
                    <p class="text-xs text-white/80 font-medium">Bantuan cepat 24 jam warga RT {{ $rt }} Desa Puspamukti</p>
                </div>
            </div>
            <button type="button" onclick="closeKontakDaruratModal()" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors">
                <span class="material-symbols-outlined text-2xl">close</span>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
            <!-- Ketua RT Card -->
            <div class="border border-red-200 bg-red-50/50 rounded-xl p-4 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center font-bold flex-shrink-0">
                        <span class="material-symbols-outlined text-xl">person_alert</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-red-600 tracking-wider">Ketua RT {{ $rt }}</span>
                        <h4 class="text-sm font-black text-slate-900">Bapak Ketua RT {{ $rt }}</h4>
                        <p class="text-xs font-mono font-bold text-slate-600 mt-0.5">0813-1234-567</p>
                    </div>
                </div>
                <a href="tel:08131234567" class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded-xl text-xs flex items-center space-x-1 shadow-md transition-all">
                    <span class="material-symbols-outlined text-base">call</span>
                    <span>Panggil</span>
                </a>
            </div>

            <!-- Kantor Desa Card -->
            <div class="border border-blue-200 bg-blue-50/50 rounded-xl p-4 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold flex-shrink-0">
                        <span class="material-symbols-outlined text-xl">holiday_village</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-blue-600 tracking-wider">Pemerintah Desa</span>
                        <h4 class="text-sm font-black text-slate-900">Kantor Desa Puspamukti</h4>
                        <p class="text-xs font-mono font-bold text-slate-600 mt-0.5">(0265) 123456</p>
                    </div>
                </div>
                <a href="tel:0265123456" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-xl text-xs flex items-center space-x-1 shadow-md transition-all">
                    <span class="material-symbols-outlined text-base">call</span>
                    <span>Panggil</span>
                </a>
            </div>

            <!-- Layanan Darurat Publik -->
            <div class="pt-2">
                <p class="text-xs font-black text-slate-700 uppercase tracking-wider mb-2.5">Layanan Darurat Publik 24 Jam</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <a href="tel:110" class="border border-slate-200 hover:border-red-500 bg-white hover:bg-red-50/50 p-3.5 rounded-xl text-center block transition-all group">
                        <span class="material-symbols-outlined text-red-600 text-2xl block mb-1 group-hover:scale-110 transition-transform">local_police</span>
                        <span class="text-xs font-bold text-slate-800 block">Kepolisian</span>
                        <span class="text-base font-mono font-black text-red-600 mt-0.5">110</span>
                    </a>
                    <a href="tel:113" class="border border-slate-200 hover:border-orange-500 bg-white hover:bg-orange-50/50 p-3.5 rounded-xl text-center block transition-all group">
                        <span class="material-symbols-outlined text-orange-600 text-2xl block mb-1 group-hover:scale-110 transition-transform">local_fire_department</span>
                        <span class="text-xs font-bold text-slate-800 block">Damkar</span>
                        <span class="text-base font-mono font-black text-orange-600 mt-0.5">113</span>
                    </a>
                    <a href="tel:119" class="border border-slate-200 hover:border-emerald-500 bg-white hover:bg-emerald-50/50 p-3.5 rounded-xl text-center block transition-all group">
                        <span class="material-symbols-outlined text-emerald-600 text-2xl block mb-1 group-hover:scale-110 transition-transform">ambulance</span>
                        <span class="text-xs font-bold text-slate-800 block">Ambulans</span>
                        <span class="text-base font-mono font-black text-emerald-600 mt-0.5">119</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-slate-50 px-6 py-3 border-t border-slate-100 text-right">
            <button onclick="closeKontakDaruratModal()" class="bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold px-5 py-2 rounded-xl text-xs transition-all">
                Tutup
            </button>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
window.showKegiatanRtModal = function() {
    const m = document.getElementById('kegiatanRtModal');
    if (m) {
        m.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
};
window.closeKegiatanRtModal = function() {
    const m = document.getElementById('kegiatanRtModal');
    if (m) {
        m.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
};
window.showKontakDaruratModal = function() {
    const m = document.getElementById('kontakDaruratModal');
    if (m) {
        m.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
};
window.closeKontakDaruratModal = function() {
    const m = document.getElementById('kontakDaruratModal');
    if (m) {
        m.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
};

// Handle Foto Preview (multiple images)
document.getElementById('fotoInput')?.addEventListener('change', function(e) {
    const files = Array.from(e.target.files || []);
    const preview = document.getElementById('fotoPreview');
    preview.innerHTML = '';

    if (files.length === 0) {
        preview.classList.add('hidden');
        return;
    }

    files.slice(0, 5).forEach((file) => {
        if (!file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = function(ev) {
            const wrap = document.createElement('div');
            wrap.className = 'relative rounded-lg overflow-hidden border border-gray-200';
            wrap.innerHTML = `
                <img src="${ev.target.result}" class="h-24 w-full object-cover" alt="Preview">
                <span class="absolute top-1 right-1 bg-black/50 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">${'✓'}</span>
            `;
            preview.appendChild(wrap);
        };
        reader.readAsDataURL(file);
    });

    preview.classList.remove('hidden');
});

// Submit Pengaduan Form
async function submitPengaduan(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="animate-spin">↻</span> Mengirim...';
    
    try {
        const response = await fetch('{{ route("warga.rt.createPengaduan", ["rt" => $rt]) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (response.ok) {
            showToast(`Pengaduan berhasil! No. Tiket: ${result.tiket_id}`, 'success');
            closePengaduanModal();
            form.reset();
            document.getElementById('fotoPreview').classList.add('hidden');
            document.getElementById('fotoPreview').innerHTML = '';
            
            // Reload page after delay to show new data
            setTimeout(() => location.reload(), 2000);
        } else {
            let errorMsg = 'Terjadi kesalahan';
            if (result.message) errorMsg = result.message;
            if (result.errors) errorMsg = Object.values(result.errors).join(', ');
            showToast(errorMsg, 'error');
        }
    } catch (error) {
        showToast('Koneksi bermasalah, coba lagi', 'error');
        console.error('Pengaduan error:', error);
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

// Show toast function
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 flex items-center space-x-2 animate-slide-in ${
        type === 'success' ? 'bg-green-50 text-green-800 border border-green-200' :
        type === 'error' ? 'bg-red-50 text-red-800 border border-red-200' :
        'bg-blue-50 text-blue-800 border border-blue-200'
    }`;
    
    const icon = type === 'success' ? 'check_circle' : type === 'error' ? 'error' : 'info';
    
    toast.innerHTML = `
        <span class="material-symbols-outlined text-sm">${icon}</span>
        <span class="text-sm font-medium">${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slide-out 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// CSS animation for slide-out
const style = document.createElement('style');
style.textContent = `
@keyframes slide-in {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
@keyframes slide-out {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(100%); opacity: 0; }
}
.animate-slide-in { animation: slide-in 0.3s ease; }
`;
document.head.appendChild(style);
</script>
@endpush
@endsection
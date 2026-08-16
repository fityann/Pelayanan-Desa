@extends('layouts.warga')

@section('title', "SILAPU - Sistem Layanan Puspamukti | RT $rt")

@section('content')
<div class="space-y-8">
    @php
        $hour = now()->hour;
        $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
        $prosesSelesai = ($pengaduanStats['total_pengaduan'] ?? 0) > 0 ? round((($pengaduanStats['pengaduan_selesai'] ?? 0) / $pengaduanStats['total_pengaduan']) * 100) : 0;
    @endphp

    <!-- Welcome Hero Section -->
    <section class="relative overflow-hidden rounded-[28px] bg-[#6A3297] text-white shadow-xl mb-4 mt-2">
        <div class="p-5">
            <div class="flex items-start gap-4 mb-5">
                <div class="bg-white p-1.5 rounded-2xl flex-shrink-0">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=WargaPuspamukti" class="w-[60px] h-[60px] object-cover rounded-xl" alt="QR Code">
                </div>
                <div class="flex-1 min-w-0 pt-1">
                    <p class="text-[13px] text-white/90 font-medium mb-0.5">{{ $greeting }}!</p>
                    <h2 class="text-[19px] font-extrabold leading-tight tracking-wide truncate">
                        HAPUNTEN BARAYA
                    </h2>
                    <p class="text-[13px] text-white/90 mt-0.5">
                        Selamat datang di {{ trim(preg_replace('/RW\s*\d+/i', '', $qrCode->nama_rt ?? "RT $rt")) }}
                    </p>
                </div>
            </div>

            <!-- Hero meta chips -->
            <div class="flex items-center gap-4 mb-6 text-[12px] text-white/90 font-medium">
                <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                    <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                    <span>{{ $stats['total_scans'] ?? 0 }} kali dibuka</span>
                </div>
            </div>

            <!-- Quick actions -->
            <div class="flex flex-col gap-3">
                <button onclick="showPengaduanModal()"
                        class="w-full flex items-center justify-center bg-[#E4C04A] hover:bg-[#d4b03a] text-[#2A3520] py-3.5 rounded-2xl font-bold shadow-md transition-all text-[15px]">
                    Buat Pengaduan
                </button>
                <button onclick="sharePage()"
                        class="w-full flex items-center justify-center bg-transparent border border-white/40 hover:bg-white/10 text-white py-3.5 rounded-2xl font-medium transition-all text-[15px]">
                    Bagikan
                </button>
            </div>
        </div>
    </section>

    <!-- Quick Stats -->
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-white rounded-2xl p-3 text-center border border-gray-100 shadow-sm flex flex-col justify-center">
            <p class="text-[11px] font-bold text-gray-800 mb-2 leading-tight">Pengaduan Masuk</p>
            <h3 class="text-2xl font-black text-gray-900 leading-none mb-1">{{ $pengaduanStats['total_pengaduan'] ?? 0 }}</h3>
            <div class="text-[9px] text-gray-500 font-medium leading-tight">
                <p>{{ $pengaduanStats['pengaduan_selesai'] ?? 0 }} selesai</p>
                <p>{{ $pengaduanStats['pengaduan_diproses'] ?? 0 }} proses</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-3 text-center border border-gray-100 shadow-sm flex flex-col justify-center">
            <p class="text-[11px] font-bold text-gray-800 mb-2 leading-tight">Kategori Teratas</p>
            <h3 class="text-[17px] font-black text-gray-900 leading-tight capitalize truncate mb-1">
                {{ $pengaduanStats['kategori_top']->kategori ?? 'Sampah' }}
            </h3>
            <div class="text-[9px] text-gray-500 font-medium leading-tight">
                <p>{{ $pengaduanStats['kategori_top']->jumlah ?? 0 }} laporan</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-3 text-center border border-gray-100 shadow-sm flex flex-col justify-center">
            <p class="text-[11px] font-bold text-gray-800 mb-2 leading-tight">Scan QR Hari Ini</p>
            <h3 class="text-2xl font-black text-gray-900 leading-none mb-1">{{ $stats['today_scans'] ?? 0 }}</h3>
            <div class="text-[9px] text-gray-500 font-medium leading-tight">
                <p>Terakhir:</p>
                <p>{{ !empty($stats['last_scan']) ? \Carbon\Carbon::parse($stats['last_scan'])->diffForHumans(null, true) . ' ago' : 'Belum ada' }}</p>
            </div>
        </div>
    </div>

    <!-- Services Grid -->
    <div class="bg-white rounded-[24px] p-5 shadow-sm border border-gray-100">
        <h2 class="text-[16px] font-bold text-gray-900 mb-5">Layanan Warga</h2>
        
        <div class="grid grid-cols-3 gap-y-6 gap-x-2">
            <!-- Buat Pengaduan -->
            <div class="flex flex-col items-center text-center cursor-pointer" onclick="showPengaduanModal()">
                <div class="w-[52px] h-[52px] bg-[#f5eef9] rounded-[16px] flex items-center justify-center mb-2">
                    <span class="material-symbols-outlined text-[#6A3297] text-[24px]">edit_square</span>
                </div>
                <span class="text-[11px] font-bold text-gray-800 leading-tight">Buat Pengaduan</span>
            </div>
            
            <!-- Info Desa -->
            <a href="{{ route('warga.rt.info', ['rt' => $rt]) }}" class="flex flex-col items-center text-center">
                <div class="w-[52px] h-[52px] bg-[#f5eef9] rounded-[16px] flex items-center justify-center mb-2">
                    <span class="material-symbols-outlined text-[#6A3297] text-[24px]">info</span>
                </div>
                <span class="text-[11px] font-bold text-gray-800 leading-tight">Info Desa</span>
            </a>
            
            <!-- Surat Online -->
            <a href="{{ route('warga.rt.surat.index', ['rt' => $rt]) }}" class="flex flex-col items-center text-center">
                <div class="w-[52px] h-[52px] bg-[#f5eef9] rounded-[16px] flex items-center justify-center mb-2">
                    <span class="material-symbols-outlined text-[#6A3297] text-[24px]">description</span>
                </div>
                <span class="text-[11px] font-bold text-gray-800 leading-tight">Surat Online</span>
            </a>
            
            <!-- Data Penduduk -->
            <div class="flex flex-col items-center text-center">
                <div class="w-[52px] h-[52px] bg-[#f5eef9] rounded-[16px] flex items-center justify-center mb-2">
                    <span class="material-symbols-outlined text-[#6A3297] text-[24px]">group</span>
                </div>
                <span class="text-[11px] font-bold text-gray-800 leading-tight">Data Penduduk</span>
            </div>
            
            <!-- Kegiatan RT -->
            <div class="flex flex-col items-center text-center cursor-pointer" onclick="showKegiatanRtModal()">
                <div class="w-[52px] h-[52px] bg-[#f5eef9] rounded-[16px] flex items-center justify-center mb-2">
                    <span class="material-symbols-outlined text-[#6A3297] text-[24px]">calendar_month</span>
                </div>
                <span class="text-[11px] font-bold text-gray-800 leading-tight">Kegiatan RT</span>
            </div>
            
            <!-- Kontak Darurat -->
            <div class="flex flex-col items-center text-center cursor-pointer" onclick="showKontakDaruratModal()">
                <div class="w-[52px] h-[52px] bg-[#f5eef9] rounded-[16px] flex items-center justify-center mb-2">
                    <span class="material-symbols-outlined text-[#6A3297] text-[24px]">call</span>
                </div>
                <span class="text-[11px] font-bold text-gray-800 leading-tight">Kontak Darurat</span>
            </div>
            
            <!-- Musrenbang -->
            <a href="{{ route('warga.musrenbang.index') }}" class="flex flex-col items-center text-center">
                <div class="w-[52px] h-[52px] bg-[#f5eef9] rounded-[16px] flex items-center justify-center mb-2">
                    <span class="material-symbols-outlined text-[#6A3297] text-[24px]">how_to_vote</span>
                </div>
                <span class="text-[11px] font-bold text-gray-800 leading-tight">Musrenbang</span>
            </a>
        </div>
    </div>

    <!-- Berita & Agenda Wilayah -->
    <div class="grid grid-cols-2 gap-4">
        <!-- Berita Terbaru -->
        <div>
            <h2 class="text-[15px] font-bold text-gray-900 mb-3">Berita Terbaru</h2>
            @if($beritaTerbaru->count() > 0)
                @php $berita = $beritaTerbaru->first(); @endphp
                <a href="{{ route('informasi.publik', ['rt' => $rt]) }}" class="block bg-white rounded-[20px] overflow-hidden shadow-sm border border-gray-100 relative group">
                    <div class="h-32 w-full bg-gray-200">
                        @if($berita->gambar)
                            <img src="{{ Storage::url($berita->gambar) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=500&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @endif
                    </div>
                    <div class="absolute top-2 left-2 bg-[#E4C04A] text-[#2A3520] text-[10px] font-bold px-2 py-0.5 rounded-md shadow-sm">
                        Featured
                    </div>
                    <div class="absolute bottom-0 left-0 w-full p-2.5 bg-gradient-to-t from-black/90 via-black/50 to-transparent">
                        <p class="text-white text-[11px] font-medium line-clamp-2 leading-snug">{{ $berita->judul }}</p>
                    </div>
                </a>
            @else
                <div class="bg-white rounded-[20px] h-32 flex items-center justify-center border border-gray-100 shadow-sm">
                    <p class="text-[11px] text-gray-400">Belum ada berita</p>
                </div>
            @endif
        </div>

        <!-- Agenda Terdekat -->
        <div>
            <h2 class="text-[15px] font-bold text-gray-900 mb-3">Agenda Terdekat</h2>
            <div class="bg-white rounded-[20px] p-3.5 shadow-sm border border-gray-100 h-32 flex flex-col justify-center">
                @if($agendaTerdekat->count() > 0)
                    @php $agenda = $agendaTerdekat->first(); @endphp
                    <p class="text-[11px] text-gray-500 mb-1.5 font-medium">{{ $agenda->tanggal_kegiatan?->translatedFormat('d F Y') ?? now()->translatedFormat('d F Y') }}</p>
                    <h3 class="font-bold text-[13px] text-gray-900 leading-snug line-clamp-2">{{ $agenda->judul }}</h3>
                    <p class="text-[10px] text-gray-400 mt-auto">{{ $agenda->tanggal_kegiatan?->translatedFormat('d F Y') ?? now()->translatedFormat('d F Y') }}</p>
                @else
                    <p class="text-[11px] text-gray-500 mb-1.5 font-medium">{{ now()->translatedFormat('d F Y') }}</p>
                    <h3 class="font-bold text-[13px] text-gray-900 leading-snug line-clamp-2">Sistem Layanan Puspamukti</h3>
                    <p class="text-[10px] text-gray-400 mt-auto">{{ now()->translatedFormat('d F Y') }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Pengaduan -->
    @php
        $recentPengaduan = \App\Models\Pengaduan::where('rt', $rt)
            ->where('rw', $rw)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    @endphp
    
    @if($recentPengaduan->count() > 0)
    <div class="bg-white rounded-[24px] shadow-sm p-5 border border-gray-100 mt-2">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-[16px] font-bold text-gray-900">Pengaduan Terbaru</h2>
                <p class="text-[11px] text-gray-500 mt-0.5">Laporan terkini warga RT {{ $rt }}</p>
            </div>
        </div>
        <div class="space-y-3">
            @foreach($recentPengaduan as $pengaduan)
            <div class="flex items-start space-x-3 p-3.5 border border-gray-100 rounded-[16px] hover:bg-gray-50 transition-all">
                <div class="flex-shrink-0">
                    <div class="{{ $pengaduan->status == 'selesai' ? 'bg-[#eef8f2] text-[#2e8b57]' : ($pengaduan->status == 'diproses' ? 'bg-[#fff8e6] text-[#b8860b]' : 'bg-[#eef4ff] text-[#4169e1]') }} p-2.5 rounded-[12px]">
                        <span class="material-symbols-outlined text-[20px]">
                            {{ $pengaduan->kategori == 'sampah' ? 'delete' : 
                               ($pengaduan->kategori == 'jalan' ? 'directions' : 
                               ($pengaduan->kategori == 'drainase' ? 'water_damage' : 
                               ($pengaduan->kategori == 'penerangan' ? 'lightbulb' :
                               ($pengaduan->kategori == 'air' ? 'water_drop' : 'campaign')))) }}
                        </span>
                    </div>
                </div>
                <div class="flex-1 min-w-0 pt-0.5">
                    <div class="flex items-center justify-between mb-1 gap-2">
                        <h4 class="font-bold text-[13px] text-gray-900 truncate">{{ $pengaduan->judul }}</h4>
                        <span class="text-[10px] px-2 py-0.5 font-bold rounded-full whitespace-nowrap {{ $pengaduan->status == 'selesai' ? 'bg-[#eef8f2] text-[#2e8b57]' : ($pengaduan->status == 'diproses' ? 'bg-[#fff8e6] text-[#b8860b]' : 'bg-[#eef4ff] text-[#4169e1]') }}">
                            {{ ucfirst($pengaduan->status) }}
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-600 mb-1.5 line-clamp-2 leading-snug">{{ Str::limit($pengaduan->deskripsi, 80) }}</p>
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[10px] text-gray-500 font-medium">
                        <span class="flex items-center">
                            <span class="material-symbols-outlined text-[12px] mr-1">person</span>
                            {{ $pengaduan->nama_pelapor }}
                        </span>
                        <span class="flex items-center">
                            <span class="material-symbols-outlined text-[12px] mr-1">schedule</span>
                            {{ $pengaduan->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if($recentPengaduan->count() >= 5)
        <div class="text-center mt-4">
            <p class="text-[10px] text-gray-400 font-medium">Menampilkan 5 pengaduan terbaru wilayah ini</p>
        </div>
        @endif
    </div>
    @endif

    <!-- Announcement -->
    <div class="relative overflow-hidden bg-gradient-to-r from-[#f3e8f8] to-[#eaddf5] border border-[#d6bdec] rounded-[24px] p-5 mt-2">
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-white rounded-full opacity-40 blur-2xl"></div>
        <div class="relative flex flex-col md:flex-row items-start md:items-center gap-4">
            <div class="bg-white p-3 rounded-[16px] shadow-sm">
                <span class="material-symbols-outlined text-[#6A3297] text-[24px]">info</span>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-gray-900 mb-1.5 text-[14px]">Layanan Terhubung Wilayah</h3>
                <p class="text-[11px] text-gray-700 mb-2.5 leading-relaxed">
                    Pengaduan Anda melalui QR Code <strong>RT {{ $rt }}</strong> otomatis tercatat di wilayah ini.
                </p>
                <p class="text-[10px] text-gray-500 flex items-center font-medium">
                    <span class="material-symbols-outlined text-[12px] mr-1 text-green-600">verified</span>
                    Data otomatis terisi.
                </p>
            </div>
            <button onclick="showPengaduanModal()"
                    class="w-full md:w-auto inline-flex items-center justify-center space-x-1.5 bg-[#E4C04A] text-[#2A3520] px-4 py-2.5 rounded-[14px] font-bold shadow-sm transition-all text-[12px]">
                <span class="material-symbols-outlined text-[16px]">campaign</span>
                <span>Lapor Sekarang</span>
            </button>
        </div>
    </div>
</div>

@push('modals')
<!-- Pengaduan Modal -->
<div id="pengaduanModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-md items-center justify-center p-4" onclick="if(event.target === this) closePengaduanModal()">
    <div class="relative bg-white rounded-[24px] shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-[#6A3297] text-white px-6 py-4">
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
                            class="flex-1 bg-[#6A3297] hover:bg-[#4E2472] text-white px-6 py-3 rounded-lg font-medium transition-all flex items-center justify-center space-x-2">
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
    <div class="relative bg-white rounded-[24px] max-w-lg w-full shadow-2xl overflow-hidden transform transition-all text-left border border-slate-100">
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
    <div class="relative bg-white rounded-[24px] max-w-lg w-full shadow-2xl overflow-hidden transform transition-all text-left border border-slate-100">
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
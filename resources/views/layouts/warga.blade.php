<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="theme-color" content="#15803d"/>
    <meta name="description" content="SILAPU - Sistem Layanan Puspamukti. Layanan digital mudah untuk warga RT {{ $rt ?? '' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SILAPU - Sistem Layanan Puspamukti')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>

    <style>
        @php
            $rt = $rt ?? session('warga_rt', '01');
            $rw = $rw ?? session('warga_rw', '01');
            $isRtScoped = true;
            $currentRoute = request()->route()?->getName();
        @endphp
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(160deg, #f4fbf7 0%, #fefce8 45%, #ecfdf5 100%);
            min-height: 100vh;
        }
        [x-cloak] { display: none !important; }

        .masy-navbar {
            background: #4B5D3A;
            border-bottom: 3px solid #D8B84C;
            box-shadow: 0 6px 24px rgba(75, 93, 58, 0.35);
        }

        .desktop-menu {
            display: flex !important;
            align-items: center;
            overflow-x: auto !important;
            overflow-y: hidden;
            white-space: nowrap;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE and Edge */
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            padding: 4px 6px;
        }
        .desktop-menu::-webkit-scrollbar {
            display: none;
            width: 0;
            height: 0;
        }

        .masy-navlink {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            flex-shrink: 0;
            color: rgba(255, 255, 255, 0.9);
            transition: all .2s ease;
        }
        .masy-navlink:hover {
            background: linear-gradient(135deg, #F0D878 0%, #D8B84C 50%, #C4A23A 100%);
            color: #2A3520;
            font-weight: 800;
            box-shadow: 0 4px 12px rgba(216, 184, 76, 0.4);
        }
        .masy-navlink.active {
            background: linear-gradient(135deg, #F0D878 0%, #D8B84C 50%, #C4A23A 100%);
            color: #2A3520;
            font-weight: 800;
            box-shadow: 0 4px 14px rgba(216, 184, 76, 0.35);
        }

        .service-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid rgba(216, 184, 76, 0.25);
            transition: all .3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
        }
        .service-card:hover {
            transform: translateY(-4px);
            border-color: #D8B84C;
            box-shadow: 0 12px 28px rgba(216, 184, 76, 0.3);
        }

        .stats-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid rgba(216, 184, 76, 0.25);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transition: all .3s ease;
        }
        .stats-card:hover {
            border-color: #D8B84C;
            box-shadow: 0 8px 20px rgba(216, 184, 76, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #4B5D3A 0%, #364329 100%);
            color: #fff;
            font-weight: 700;
            border: 1px solid rgba(216, 184, 76, 0.35);
            transition: all .3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #F0D878 0%, #D8B84C 50%, #C4A23A 100%);
            color: #2A3520;
            border-color: #F7F0D4;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(216, 184, 76, 0.5);
        }

        .floating-action-btn {
            position: fixed;
            bottom: 92px;
            right: 24px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #F0D878 0%, #D8B84C 50%, #C4A23A 100%);
            color: #2A3520;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 20px rgba(216, 184, 76, 0.5);
            z-index: 900;
            border: 2px solid #F7F0D4;
            transition: all .3s ease;
        }
        .floating-action-btn:hover {
            background: linear-gradient(135deg, #F7F0D4 0%, #F0D878 50%, #D8B84C 100%);
            color: #2A3520;
            box-shadow: 0 8px 25px rgba(216, 184, 76, 0.65);
            transform: scale(1.12) rotate(6deg);
        }

        .floating-action-btn {
            position: fixed;
            bottom: 92px;
            right: 24px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%);
            color: #064e3b;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 20px rgba(217, 119, 6, 0.5);
            z-index: 900;
            border: 2px solid #fef3c7;
            transition: all .3s ease;
        }
        .floating-action-btn:hover {
            background: linear-gradient(135deg, #fef08a 0%, #fbbf24 50%, #f59e0b 100%);
            color: #064e3b;
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.65);
            transform: scale(1.12) rotate(6deg);
        }

        /* Bottom navigation (mobile only) */
        @media (max-width: 767.98px) {
            body { padding-bottom: 76px; }
            .bottom-nav {
                position: fixed;
                bottom: 0; left: 0; right: 0;
                background: #fff;
                border-top: 1px solid #e5e7eb;
                box-shadow: 0 -4px 16px rgba(0, 0, 0, 0.06);
                z-index: 950;
                display: flex;
                padding-bottom: env(safe-area-inset-bottom);
            }
            .bottom-nav a {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 3px;
                padding: 9px 4px 8px;
                font-size: 10.5px;
                font-weight: 600;
                color: #6b7280;
                text-decoration: none;
                transition: color .2s ease;
            }
            .bottom-nav a .material-symbols-outlined { font-size: 22px; }
            .bottom-nav a.active { color: #15803d; }
        }
        @media (min-width: 768px) {
            .bottom-nav { display: none !important; }
        }

        .modal-overlay { background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px); }

        .alert-success { animation: slideDown .3s ease; }

        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }
        .animate-pulse { animation: pulse 2s cubic-bezier(.4, 0, .6, 1) infinite; }

        .qr-code-container {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .desktop-menu {
            display: flex;
            align-items: center;
            gap: 4px;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .desktop-menu::-webkit-scrollbar { display: none; }

        @media (max-width: 768px) {
            .desktop-menu { display: none !important; }
            .mobile-stack { flex-direction: column !important; }
            .floating-action-btn { bottom: 88px; right: 18px; }
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Header / Navbar -->
    <header class="masy-navbar sticky top-0 z-50 text-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">
                <!-- Brand -->
                <a href="{{ $isRtScoped ? route('warga.rt.landing', ['rt' => $rt]) : '/' }}" class="flex items-center space-x-3 flex-shrink-0">
                    <div class="bg-white/20 p-2 rounded-xl">
                        <span class="material-symbols-outlined text-white">holiday_village</span>
                    </div>
                    <div>
                        <h1 class="text-base sm:text-lg font-bold leading-tight">SILAPU</h1>
                        <p class="text-[11px] sm:text-sm text-white/80 leading-tight hidden sm:block">
                            @if ($isRtScoped)
                                Sistem Layanan Puspamukti · RT {{ $rt }}
                            @else
                                Sistem Layanan Puspamukti
                            @endif
                        </p>
                    </div>
                </a>

                <!-- Desktop menu (Scrollable Kanan Kiri) -->
                <nav class="desktop-menu flex-1 items-center space-x-1.5 min-w-0 overflow-x-auto">
                    @php
                        $inMenu = fn($name) => $currentRoute === $name;
                        $rtQuery = $isRtScoped ? ['rt' => $rt] : [];
                    @endphp
                    <a class="masy-navlink {{ $inMenu('warga.rt.landing') || $inMenu('dashboard') ? 'active' : '' }}"
                       href="{{ $isRtScoped ? route('warga.rt.landing', ['rt' => $rt]) : '/' }}">
                        <span class="material-symbols-outlined text-[18px]">home</span> Beranda
                    </a>
                    <a class="masy-navlink {{ $inMenu('warga.rt.info') || $inMenu('informasi.publik') ? 'active' : '' }}"
                       href="{{ $isRtScoped ? route('warga.rt.info', ['rt' => $rt]) : route('informasi.publik', $rtQuery) }}">
                        <span class="material-symbols-outlined text-[18px]">info</span> Info Desa
                    </a>
                    <a class="masy-navlink {{ $inMenu('apbdes.publik') ? 'active' : '' }}" href="{{ route('apbdes.publik', $rtQuery) }}">
                        <span class="material-symbols-outlined text-[18px]">account_balance</span> APBDes
                    </a>
                    <a class="masy-navlink {{ $inMenu('informasi.publik') ? 'active' : '' }}" href="{{ route('informasi.publik', $rtQuery) }}">
                        <span class="material-symbols-outlined text-[18px]">newspaper</span> Berita
                    </a>
                    @if ($isRtScoped || auth()->check())
                        <a class="masy-navlink {{ $inMenu('warga.rt.surat.index') || $inMenu('warga.surat.index') ? 'active' : '' }}" href="{{ $isRtScoped ? route('warga.rt.surat.index', ['rt' => $rt]) : route('warga.surat.index') }}">
                            <span class="material-symbols-outlined text-[18px]">edit_note</span> Surat
                        </a>
                        <a class="masy-navlink {{ $inMenu('warga.rt.surat.riwayat') ? 'active' : '' }}" href="{{ $isRtScoped ? route('warga.rt.surat.riwayat', ['rt' => $rt]) : route('warga.surat.index') }}">
                            <span class="material-symbols-outlined text-[18px]">lan</span> Tracking Surat
                        </a>
                    @endif
                    <a class="masy-navlink {{ $inMenu('warga.musrenbang.index') || $inMenu('warga.musrenbang.show') ? 'active' : '' }}" href="{{ route('warga.musrenbang.index') }}">
                        <span class="material-symbols-outlined text-[18px]">architecture</span> Musrenbang
                    </a>
                    @if ($isRtScoped)
                        <a class="masy-navlink {{ $inMenu('warga.rt.chat') ? 'active' : '' }}" href="{{ route('warga.rt.chat', ['rt' => $rt]) }}">
                            <span class="material-symbols-outlined text-[18px]">forum</span> Chat Admin
                        </a>
                    @endif
                </nav>

                <!-- Right actions -->
                <div class="flex items-center space-x-2 flex-shrink-0">                    @auth('warga')
                        @if ($isRtScoped)
                            <div x-data="wargaNotif()" x-init="init()" class="relative">
                                <button @click="toggle()" class="bg-white/10 hover:bg-white/20 p-2 rounded-lg transition-colors relative" title="Notifikasi">
                                    <span class="material-symbols-outlined">notifications</span>
                                    <span x-show="unread > 0" x-cloak
                                          class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center">
                                        <span x-text="unread > 99 ? '99+' : unread"></span>
                                    </span>
                                </button>

                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-100"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     @click.outside="open = false"
                                     class="absolute right-0 mt-2 w-80 max-h-[70vh] flex flex-col bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden z-50">
                                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
                                        <h3 class="text-sm font-bold text-gray-800">Notifikasi</h3>
                                        <button @click="markAll(); $event.stopPropagation()"
                                                class="text-xs font-medium text-emerald-600 hover:text-emerald-700">Tandai sudah dibaca</button>
                                    </div>
                                    <div class="flex-1 overflow-y-auto p-2 space-y-1.5 max-h-[380px]" x-ref="notifList">
                                        <template x-if="items.length === 0">
                                            <div class="p-6 text-center text-xs font-semibold text-slate-400">
                                                <span class="material-symbols-outlined text-3xl text-slate-300 block mb-1">notifications_off</span>
                                                Belum ada notifikasi
                                            </div>
                                        </template>
                                        <template x-for="item in items" :key="item.id">
                                            <a :href="item.link || '#'"
                                               @click="if(!item.is_read) markRead(item.id)"
                                               :class="{'bg-[#4B5D3A]/10 border border-[#4B5D3A]/20': !item.is_read, 'bg-white border border-slate-100 hover:bg-slate-50': item.is_read}"
                                               class="flex items-start gap-3 p-3 rounded-xl transition-all shadow-xs block group">
                                                <div class="p-2 rounded-xl flex-shrink-0 flex items-center justify-center"
                                                     :class="item.warna || 'bg-[#4B5D3A]/10 text-[#4B5D3A]'">
                                                    <span class="material-symbols-outlined text-lg" x-text="item.icon || 'notifications'"></span>
                                                </div>
                                                <div class="flex-1 min-w-0 text-left">
                                                    <div class="flex items-center justify-between gap-1 mb-0.5">
                                                        <p class="text-xs font-black text-slate-900 truncate group-hover:text-[#4B5D3A] transition-colors" x-text="item.judul"></p>
                                                        <span x-show="!item.is_read" class="w-2 h-2 rounded-full bg-[#D8B84C] flex-shrink-0"></span>
                                                    </div>
                                                    <p class="text-[11px] text-slate-600 font-medium leading-snug line-clamp-2" x-text="item.pesan"></p>
                                                    <span class="text-[10px] text-slate-400 font-semibold block mt-1" x-text="item.waktu"></span>
                                                </div>
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <button onclick="window.location.href='{{ route('warga.rt.surat.index', ['rt' => $rt]) }}'"
                                    class="bg-white/20 hover:bg-white/30 px-3 py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors flex items-center space-x-1 flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">person</span>
                                <span class="hidden lg:inline">{{ auth('warga')->user()->name }}</span>
                            </button>
                            
                            <form method="POST" action="{{ route('warga.rt.logout', ['rt' => $rt]) }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-white/10 hover:bg-white/20 p-2 rounded-lg transition-colors" title="Keluar">
                                    <span class="material-symbols-outlined">logout</span>
                                </button>
                            </form>
                        @else
                            <button onclick="window.location.href='{{ route('warga.rt.surat.index', ['rt' => $rt ?? '01']) }}'"
                                    class="bg-white/20 hover:bg-white/30 px-3 py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors flex items-center space-x-1 flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">person</span>
                                <span class="hidden lg:inline">{{ auth('warga')->user()->name }}</span>
                            </button>
                        @endif
                    @else
                        <button onclick="window.location.href='{{ route('warga.rt.login', ['rt' => $rt ?? '01']) }}'"
                                class="bg-white/20 hover:bg-white/30 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center space-x-1.5 shadow-sm flex-shrink-0">
                            <span class="material-symbols-outlined text-sm">badge</span>
                            <span>Masuk</span>
                        </button>
                    @endauth
                    <button onclick="window.location.href='{{ $isRtScoped ? route('warga.rt.landing', ['rt' => $rt]) : '/' }}'" class="bg-white/10 hover:bg-white/20 p-2 rounded-lg transition-colors" title="{{ $isRtScoped ? 'Kembali ke Beranda RT ' . $rt : 'Kembali ke Beranda' }}">
                        <span class="material-symbols-outlined">home</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        @if(session('success'))
            <div class="alert-success bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-green-500 mr-3">check_circle</span>
                    <div class="flex-1"><p class="text-sm font-medium text-green-800">{{ session('success') }}</p></div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-green-500 hover:text-green-700">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-red-500 mr-3">error</span>
                    <div class="flex-1"><p class="text-sm font-medium text-red-800">{{ session('error') }}</p></div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Floating Action Button (Pengaduan) -->
    @if ($isRtScoped)
        <button onclick="showPengaduanModal()" class="floating-action-btn" title="Buat Pengaduan Baru">
            <span class="material-symbols-outlined text-2xl">add</span>
        </button>
    @endif

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">Desa Puspamukti</h3>
                    <p class="text-gray-300 text-sm mb-4">
                        Jl. Raya Puspamukti No. 1<br>Kecamatan Bojong, Kabupaten Tegal
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center text-sm text-gray-300">
                            <span class="material-symbols-outlined text-base mr-2">call</span><span>(0281) 123456</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-300">
                            <span class="material-symbols-outlined text-base mr-2">whatsapp</span><span>0812-3456-7890</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold mb-4">Jam Operasional</h3>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li class="flex items-center">
                            <span class="material-symbols-outlined text-base mr-2">schedule</span><span>Senin - Jumat: 08:00 - 16:00</span>
                        </li>
                        <li class="flex items-center">
                            <span class="material-symbols-outlined text-base mr-2">schedule</span><span>Sabtu: 08:00 - 12:00</span>
                        </li>
                        <li class="flex items-center">
                            <span class="material-symbols-outlined text-base mr-2">event_busy</span><span>Minggu & Hari Libur: Tutup</span>
                        </li>
                    </ul>
                </div>

                @if ($isRtScoped)
                    <div>
                        <h3 class="text-lg font-bold mb-4">Kontak RT</h3>
                        <ul class="space-y-2 text-sm text-gray-300">
                            <li class="flex items-center">
                                <span class="material-symbols-outlined text-base mr-2">person</span><span>Ketua RT {{ $rt }}: 0813-1234-567</span>
                            </li>
                            <li class="flex items-center">
                                <span class="material-symbols-outlined text-base mr-2">info</span><span>Untuk keadaan darurat segera hubungi</span>
                            </li>
                        </ul>
                    </div>
                @else
                    <div>
                        <h3 class="text-lg font-bold mb-4">Kontak Desa</h3>
                        <ul class="space-y-2 text-sm text-gray-300">
                            <li class="flex items-center">
                                <span class="material-symbols-outlined text-base mr-2">person</span><span>Kepala Desa: 0813-1234-567</span>
                            </li>
                            <li class="flex items-center">
                                <span class="material-symbols-outlined text-base mr-2">person</span><span>Sekretaris Desa: 0821-9876-543</span>
                            </li>
                            <li class="flex items-center">
                                <span class="material-symbols-outlined text-base mr-2">info</span><span>Untuk informasi lebih lanjut</span>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>

            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} SILAPU - Sistem Layanan Puspamukti. Hak Cipta Dilindungi.</p>
                <p class="mt-1">Sistem Layanan Digital Desa Puspamukti</p>
            </div>
        </div>
    </footer>

    <!-- Bottom Navigation (mobile) -->
    <nav class="bottom-nav md:hidden">
        <a href="{{ $isRtScoped ? route('warga.rt.landing', ['rt' => $rt]) : '/' }}" class="{{ $inMenu('warga.rt.landing') || $inMenu('dashboard') ? 'active' : '' }}">
            <span class="material-symbols-outlined">home</span> Beranda
        </a>
        <a href="{{ $isRtScoped ? route('warga.rt.info', ['rt' => $rt]) : route('informasi.publik') }}" class="{{ $inMenu('warga.rt.info') ? 'active' : '' }}">
            <span class="material-symbols-outlined">info</span> Info Desa
        </a>

        @if ($isRtScoped)
            <a href="{{ route('warga.rt.surat.index', ['rt' => $rt]) }}" class="{{ $inMenu('warga.rt.surat.index') ? 'active' : '' }}">
                <span class="material-symbols-outlined">edit_note</span> Surat
            </a>
            <a onclick="showPengaduanModal()" style="cursor:pointer" class="{{ $inMenu('warga.rt.landing') ? '' : '' }}">
                <span class="material-symbols-outlined">campaign</span> Lapor
            </a>
            @auth
                <a href="{{ route('warga.rt.chat', ['rt' => $rt]) }}" class="{{ $inMenu('warga.rt.chat') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">forum</span> Chat
                </a>
            @else
                <a href="{{ route('warga.rt.login', ['rt' => $rt]) }}" class="{{ $inMenu('warga.rt.login') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">login</span> Masuk
                </a>
            @endauth
        @else
            <a href="{{ route('apbdes.publik') }}" class="{{ $inMenu('apbdes.publik') ? 'active' : '' }}">
                <span class="material-symbols-outlined">account_balance</span> APBDes
            </a>
            <a href="{{ route('informasi.publik') }}" class="{{ $inMenu('informasi.publik') ? 'active' : '' }}">
                <span class="material-symbols-outlined">newspaper</span> Berita
            </a>
            <a href="{{ route('warga.musrenbang.index') }}" class="{{ $inMenu('warga.musrenbang.index') ? 'active' : '' }}">
                <span class="material-symbols-outlined">architecture</span> Musrenbang
            </a>
            <a href="{{ route('login') }}">
                <span class="material-symbols-outlined">login</span> Login
            </a>
        @endif
    </nav>

    <script>
        function wargaNotif() {
            return {
                open: false,
                loading: false,
                unread: 0,
                items: [],
                timer: null,
                init() {
                    this.load();
                    this.timer = setInterval(() => this.load(), 45000);
                },
                toggle() {
                    this.open = !this.open;
                    if (this.open) this.load();
                },
                load() {
                    this.loading = true;
                    fetch('{{ route('warga.rt.notif.data', ['rt' => $rt]) }}', {
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(r => r.json())
                    .then(data => {
                        this.unread = data.unread || 0;
                        this.items = data.items || [];
                    })
                    .catch(() => {})
                    .finally(() => { this.loading = false; });
                },
                markRead(id) {
                    fetch('{{ route('warga.rt.notif.read', ['rt' => $rt, 'id' => ':id']) }}'.replace(':id', id), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            'Accept': 'application/json'
                        }
                    })
                    .then(() => { this.load(); });
                },
                markAll() {
                    fetch('{{ route('warga.rt.notif.read-all', ['rt' => $rt]) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            'Accept': 'application/json'
                        }
                    })
                    .then(() => { this.load(); });
                }
            };
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const icon = document.getElementById('mobileMenuIcon');
            menu.classList.toggle('hidden');
            icon.textContent = menu.classList.contains('hidden') ? 'menu' : 'close';
        }

        function showPengaduanModal() {
            const modal = document.getElementById('pengaduanModal');
            if (modal) { modal.classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
        }

        function closePengaduanModal() {
            const modal = document.getElementById('pengaduanModal');
            if (modal) { modal.classList.add('hidden'); document.body.style.overflow = 'auto'; }
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closePengaduanModal();
            });

            document.querySelectorAll('.modal-overlay').forEach(overlay => {
                overlay.addEventListener('click', function (e) {
                    if (e.target.classList.contains('modal-overlay')) {
                        const modal = e.target.closest('.fixed');
                        if (modal) { modal.classList.add('hidden'); document.body.style.overflow = 'auto'; }
                    }
                });
            });

            setTimeout(() => {
                document.querySelectorAll('.alert-success, .bg-red-50').forEach(alert => {
                    alert.style.transition = 'opacity 0.3s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                });
            }, 5000);
        });

        function copyToClipboard(text, elementId = null) {
            navigator.clipboard.writeText(text).then(() => {
                if (elementId) {
                    const el = document.getElementById(elementId);
                    if (el) {
                        const original = el.textContent;
                        el.textContent = 'Tersalin!';
                        el.classList.add('text-green-600');
                        setTimeout(() => { el.textContent = original; el.classList.remove('text-green-600'); }, 2000);
                    }
                } else {
                    showToast('Berhasil disalin ke clipboard', 'success');
                }
            }).catch(() => showToast('Gagal menyalin', 'error'));
        }

        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-[999] flex items-center space-x-2 ${
                type === 'success' ? 'bg-green-50 text-green-800 border border-green-200' :
                type === 'error' ? 'bg-red-50 text-red-800 border border-red-200' : 'bg-blue-50 text-blue-800 border border-blue-200'
            }`;
            const icon = type === 'success' ? 'check_circle' : type === 'error' ? 'error' : 'info';
            toast.innerHTML = `<span class="material-symbols-outlined text-sm">${icon}</span><span class="text-sm font-medium">${message}</span>`;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        function scrollToTop() { window.scrollTo({ top: 0, behavior: 'smooth' }); }

        function sharePage() {
            const text = '{{ $isRtScoped ? "Lihat portal warga RT $rt Desa Puspamukti" : "Portal Masyarakat Desa Puspamukti" }}';
            if (navigator.share) {
                navigator.share({ title: document.title, text, url: window.location.href })
                    .catch(() => {});
            } else {
                copyToClipboard(window.location.href);
            }
        }
    </script>

    @stack('modals')
    @stack('scripts')
</body>
</html>
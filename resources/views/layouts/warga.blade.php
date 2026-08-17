<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="theme-color" content="#15803d"/>
    <meta name="description" content="SILAPU - Sistem Layanan Puspamukti. Layanan digital mudah untuk warga RT {{ $rt ?? '' }}">
    <title>@yield('title', 'SILAPU - Sistem Layanan Puspamukti')</title>

    <!-- Favicon / Logo Tab -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-desa-puspamukti.jpg') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo-desa-puspamukti.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-desa-puspamukti.jpg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

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
            background: #F4F6F2;
            min-height: 100vh;
        }
        [x-cloak] { display: none !important; }

        .masy-navbar {
            background: #6A3297;
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
            background: linear-gradient(135deg, #6A3297 0%, #4E2472 100%);
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
                background: #6A3297;
                border-top: none;
                box-shadow: 0 -4px 16px rgba(106, 50, 151, 0.2);
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
                color: rgba(255, 255, 255, 0.6);
                text-decoration: none;
                transition: color .2s ease;
            }
            .bottom-nav a .material-symbols-outlined { font-size: 22px; }
            .bottom-nav a.active { color: #ffffff; }
            .bottom-nav a.active .material-symbols-outlined { font-variation-settings: 'FILL' 1; }
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
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">
                <!-- Brand -->
                <a href="{{ $isRtScoped ? route('warga.rt.landing', ['rt' => $rt]) : '/' }}" class="flex items-center space-x-3 flex-shrink-0">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-[16px] bg-white border-2 border-[#D8B84C] p-1 shadow-md shadow-black/25 flex items-center justify-center flex-shrink-0">
                        <img src="{{ asset('images/logo-desa-puspamukti.jpg') }}" alt="Logo Puspamukti" class="w-full h-full object-contain rounded-[10px]">
                    </div>
                    <div>
                        <h1 class="text-[19px] sm:text-lg font-bold leading-tight tracking-wide">SILAPU</h1>
                        <p class="text-[12px] sm:text-sm text-white/80 leading-tight mt-0.5">
                            Sistem Layanan Puspamukti
                        </p>
                    </div>
                </a>

                <!-- Desktop menu (Scrollable Kanan Kiri) -->
                <nav class="desktop-menu flex-1 items-center space-x-1.5 min-w-0 overflow-x-auto hidden md:flex">
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
                    <a class="masy-navlink {{ $inMenu('aset.publik') ? 'active' : '' }}" href="{{ route('aset.publik') }}">
                        <span class="material-symbols-outlined text-[18px]">inventory_2</span> Aset Desa
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
                <div class="flex items-center space-x-2 flex-shrink-0">
                    @auth('warga')
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
                                    <div x-data="{ perm: ('Notification' in window) ? Notification.permission : 'denied' }"
                                         x-show="perm === 'default'"
                                         class="bg-amber-50 border-b border-amber-200 px-3.5 py-2 text-[11px] text-amber-900 flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-1.5 font-bold">
                                            <span class="material-symbols-outlined text-amber-700 text-sm">notifications_active</span>
                                            <span>Aktifkan Notifikasi Desktop/HP?</span>
                                        </div>
                                        <button @click="window.SilapuPushNotification?.requestPermission((p) => { perm = p; })"
                                                class="bg-[#6A3297] hover:bg-[#4E2472] text-white font-black px-2.5 py-1 rounded-lg text-[10px] shadow-xs transition-all">
                                            Izinkan
                                        </button>
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
                                               :class="{'bg-[#6A3297]/10 border border-[#6A3297]/20': !item.is_read, 'bg-white border border-slate-100 hover:bg-slate-50': item.is_read}"
                                               class="flex items-start gap-3 p-3 rounded-xl transition-all shadow-xs block group">
                                                <div class="p-2 rounded-xl flex-shrink-0 flex items-center justify-center"
                                                     :class="item.warna || 'bg-[#6A3297]/10 text-[#6A3297]'">
                                                    <span class="material-symbols-outlined text-lg" x-text="item.icon || 'notifications'"></span>
                                                </div>
                                                <div class="flex-1 min-w-0 text-left">
                                                    <div class="flex items-center justify-between gap-1 mb-0.5">
                                                        <p class="text-xs font-black text-slate-900 truncate group-hover:text-[#6A3297] transition-colors" x-text="item.judul"></p>
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

                            <div x-data="{ openProfile: false }" class="relative inline-block text-left">
                                <button @click="openProfile = !openProfile" @click.outside="openProfile = false"
                                        class="bg-white/20 hover:bg-white/30 px-3 py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors flex items-center space-x-1 flex-shrink-0">
                                    <span class="material-symbols-outlined text-sm">person</span>
                                    <span class="hidden lg:inline">{{ auth('warga')->user()->name }}</span>
                                    <span class="material-symbols-outlined text-sm transition-transform duration-200" :class="{ 'rotate-180': openProfile }">expand_more</span>
                                </button>

                                <div x-show="openProfile" x-cloak
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 overflow-hidden z-50">
                                    <div class="py-1">
                                        <a href="{{ route('warga.rt.profil', ['rt' => $rt]) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-[#6A3297] transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">manage_accounts</span> Profil
                                        </a>
                                        <a href="{{ route('warga.rt.surat.riwayat', ['rt' => $rt]) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-[#6A3297] transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">history</span> Riwayat
                                        </a>
                                        <div class="border-t border-slate-100 my-1"></div>
                                        <form method="POST" action="{{ route('warga.rt.logout', ['rt' => $rt]) }}">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors text-left">
                                                <span class="material-symbols-outlined text-[18px]">logout</span> Keluar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div x-data="{ openProfile: false }" class="relative inline-block text-left">
                                <button @click="openProfile = !openProfile" @click.outside="openProfile = false"
                                        class="bg-white/20 hover:bg-white/30 px-3 py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors flex items-center space-x-1 flex-shrink-0">
                                    <span class="material-symbols-outlined text-sm">person</span>
                                    <span class="hidden lg:inline">{{ auth('warga')->user()->name }}</span>
                                    <span class="material-symbols-outlined text-sm transition-transform duration-200" :class="{ 'rotate-180': openProfile }">expand_more</span>
                                </button>

                                <div x-show="openProfile" x-cloak
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 overflow-hidden z-50">
                                    <div class="py-1">
                                        <a href="{{ route('warga.rt.profil', ['rt' => $rt ?? '01']) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-[#6A3297] transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">manage_accounts</span> Profil
                                        </a>
                                        <div class="border-t border-slate-100 my-1"></div>
                                        <form method="POST" action="{{ route('warga.rt.logout', ['rt' => $rt ?? '01']) }}">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors text-left">
                                                <span class="material-symbols-outlined text-[18px]">logout</span> Keluar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <button onclick="window.location.href='{{ route('warga.rt.login', ['rt' => $rt ?? '01']) }}'"
                                class="bg-white/20 hover:bg-white/30 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center space-x-1.5 shadow-sm flex-shrink-0">
                            <span class="material-symbols-outlined text-sm">badge</span>
                            <span>Masuk</span>
                        </button>
                    @endauth

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



    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-base font-bold mb-3">Desa Puspamukti</h3>
                    <p class="text-gray-300 text-xs mb-3 leading-relaxed">
                        Desa Puspamukti, Kecamatan Cigalontang, Kabupaten Tasikmalaya, Jawa Barat 46463
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center text-xs text-gray-300">
                            <span class="material-symbols-outlined text-sm mr-2">call</span><span>(0281) 123456</span>
                        </div>
                        <div class="flex items-center text-xs text-gray-300">
                            <span class="material-symbols-outlined text-sm mr-2">chat</span><a href="https://wa.me/62853351165000" target="_blank" class="hover:text-green-400 transition-colors">0853351165000</a>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-base font-bold mb-3">Jam Operasional</h3>
                    <ul class="space-y-2 text-xs text-gray-300">
                        <li class="flex items-center">
                            <span class="material-symbols-outlined text-sm mr-2">schedule</span><span>Senin - Jumat: 08:00 - 16:00</span>
                        </li>
                        <li class="flex items-center">
                            <span class="material-symbols-outlined text-sm mr-2">event_busy</span><span>Sabtu & Minggu: Tutup</span>
                        </li>
                    </ul>
                </div>

                @if ($isRtScoped)
                    <div>
                        <h3 class="text-base font-bold mb-3">Kontak RT</h3>
                        <ul class="space-y-2 text-xs text-gray-300">
                            <li class="flex items-center">
                                <span class="material-symbols-outlined text-sm mr-2">person</span><span>Ketua RT {{ $rt }}: 0853351165000</span>
                            </li>
                            <li class="flex items-center">
                                <span class="material-symbols-outlined text-sm mr-2">info</span><span>Untuk keadaan darurat segera hubungi</span>
                            </li>
                        </ul>
                    </div>
                @else
                    <div>
                        <h3 class="text-base font-bold mb-3">Kontak Desa</h3>
                        <ul class="space-y-2 text-xs text-gray-300">
                            <li class="flex items-center">
                                <span class="material-symbols-outlined text-sm mr-2">person</span><span>Kantor Desa: 0853351165000</span>
                            </li>
                            <li class="flex items-center">
                                <span class="material-symbols-outlined text-sm mr-2">info</span><span>Untuk informasi & pelayanan warga</span>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>

            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400 text-xs">
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
        <a href="{{ route('informasi.publik', $isRtScoped ? ['rt' => $rt] : []) }}" class="{{ $inMenu('informasi.publik') ? 'active' : '' }}">
            <span class="material-symbols-outlined">newspaper</span> Berita
        </a>

        @if ($isRtScoped)
            <a href="{{ route('warga.rt.surat.index', ['rt' => $rt]) }}" class="{{ $inMenu('warga.rt.surat.index') ? 'active' : '' }}">
                <span class="material-symbols-outlined">edit_note</span> Surat
            </a>
            <button type="button" onclick="showPengaduanModal()" class="{{ $inMenu('warga.rt.landing') ? '' : '' }} flex flex-col items-center justify-center">
                <span class="material-symbols-outlined">campaign</span> Lapor
            </button>
            <a href="{{ route('warga.rt.chat', ['rt' => $rt]) }}" class="{{ $inMenu('warga.rt.chat') ? 'active' : '' }}">
                <span class="material-symbols-outlined">forum</span> Chat Admin
            </a>
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
                        if (window.SilapuPushNotification) {
                            window.SilapuPushNotification.processItems(this.items);
                        }
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

        window.openSilapuModal = function(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                modal.style.removeProperty('display');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        };

        window.closeSilapuModal = function(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        };

        window.showPengaduanModal = function() {
            const modal = document.getElementById('pengaduanModal');
            if (modal) {
                window.openSilapuModal('pengaduanModal');
            } else {
                window.location.href = "{{ $isRtScoped ? route('warga.rt.landing', ['rt' => $rt]) : '/' }}?lapor=1";
            }
        };

        window.closePengaduanModal = function() {
            window.closeSilapuModal('pengaduanModal');
        };

        window.showKegiatanRtModal = function() {
            const modal = document.getElementById('kegiatanRtModal');
            if (modal) {
                window.openSilapuModal('kegiatanRtModal');
            } else {
                window.location.href = "{{ $isRtScoped ? route('warga.rt.landing', ['rt' => $rt]) : '/' }}?kegiatan=1";
            }
        };

        window.closeKegiatanRtModal = function() {
            window.closeSilapuModal('kegiatanRtModal');
        };

        window.showKontakDaruratModal = function() {
            const modal = document.getElementById('kontakDaruratModal');
            if (modal) {
                window.openSilapuModal('kontakDaruratModal');
            } else {
                window.location.href = "{{ $isRtScoped ? route('warga.rt.landing', ['rt' => $rt]) : '/' }}?kontak=1";
            }
        };

        window.closeKontakDaruratModal = function() {
            window.closeSilapuModal('kontakDaruratModal');
        };

        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closePengaduanModal();
                    closeKegiatanRtModal();
                    closeKontakDaruratModal();
                }
            });

            // Close modal when clicking backdrop
            ['pengaduanModal', 'kegiatanRtModal', 'kontakDaruratModal'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('click', function(e) {
                        if (e.target === this) {
                            window.closeSilapuModal(id);
                        }
                    });
                }
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

        // Web Push Notification Helper
        window.SilapuPushNotification = {
            shownIds: new Set(JSON.parse(localStorage.getItem('silapu_notif_shown') || '[]')),
            
            init() {
                if ('serviceWorker' in navigator) {
                    navigator.serviceWorker.register('/sw.js').catch(() => {});
                }
            },

            requestPermission(callback) {
                if ('Notification' in window) {
                    Notification.requestPermission().then(permission => {
                        if (permission === 'granted') {
                            try {
                                new Notification('Notifikasi SILAPU Aktif 🔔', {
                                    body: 'Notifikasi browser telah diaktifkan! Anda akan menerima pemberitahuan otomatis pengajuan surat.',
                                    icon: '/images/logo-desa-puspamukti.jpg'
                                });
                            } catch(e) {}
                            if (typeof showToast === 'function') showToast('Notifikasi browser berhasil diizinkan! 🔔', 'success');
                        } else if (permission === 'denied') {
                            if (typeof showToast === 'function') showToast('Izin notifikasi diblokir di browser. Mohon izinkan dari setelan situs.', 'error');
                        }
                        if (callback) callback(permission);
                    });
                } else {
                    alert('Browser Anda tidak mendukung Notifikasi.');
                }
            },

            processItems(items) {
                if (!('Notification' in window) || Notification.permission !== 'granted') return;
                
                let newlyShown = false;
                (items || []).forEach(item => {
                    if (!item.is_read && !this.shownIds.has(item.id)) {
                        this.shownIds.add(item.id);
                        newlyShown = true;
                        
                        try {
                            const notif = new Notification(item.judul || 'Notifikasi SILAPU', {
                                body: item.pesan || 'Ada pemberitahuan baru.',
                                icon: '/images/logo-desa-puspamukti.jpg',
                                tag: 'silapu-notif-' + item.id,
                                renotify: true
                            });
                            
                            if (item.link) {
                                notif.onclick = function() {
                                    window.focus();
                                    window.location.href = item.link;
                                };
                            }
                        } catch(e) {}
                    }
                });
                
                if (newlyShown) {
                    const idsArray = Array.from(this.shownIds).slice(-100);
                    localStorage.setItem('silapu_notif_shown', JSON.stringify(idsArray));
                }
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            window.SilapuPushNotification.init();
        });
    </script>

    @stack('modals')
    @stack('scripts')
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>@yield('title', 'SILAPU - Sistem Layanan Puspamukti')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @layer base {
            html,body { margin:0; padding:0; }
            body { overscroll-behavior:none; }
            main > :first-child { margin-top:0!important; }
            main > :last-child { margin-bottom:0!important; }
        }
        ::-webkit-scrollbar { display:none; }
        .sidebar-scrollbar::-webkit-scrollbar { display:none; }
        .sidebar-scrollbar { scrollbar-width:none; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
</head>
<body class="bg-surface font-body-md text-on-surface">
    <!-- Sidebar -->
    <aside class="fixed left-0 top-0 h-full w-72 bg-primary-container z-50 flex flex-col shadow-xl sidebar-scrollbar overflow-y-auto">
        <div class="p-lg flex items-center gap-md border-b border-primary/20 mb-md">
            <div class="w-10 h-10 rounded-lg bg-on-primary/20 flex items-center justify-center text-on-primary font-bold text-title-md">S</div>
            <div class="flex flex-col">
                <span class="text-on-primary font-headline-sm tracking-tight">SILAPU</span>
                <span class="text-on-primary-container text-label-sm uppercase opacity-80">Puspamukti Admin</span>
            </div>
        </div>
        <nav class="flex-1 px-md space-y-xs pb-lg">
            @php
                $currentPath = request()->path();
                $isActive = fn($paths) => collect((array)$paths)->contains(fn($p) => str_starts_with($currentPath, $p));
                $user = auth()->user();
                $isGuest = is_null($user);
                $isSuperAdmin = $user?->hasRole('Super Admin');
                $isAdmin = $user?->hasAnyRole(['Super Admin', 'Admin Desa']);
                $isBendahara = $user?->hasRole('Bendahara');
                $isKades = $user?->hasRole('Kepala Desa');
                $isSekdes = $user?->hasRole('Sekretaris Desa');
                $isStaff = !$isGuest && ($isSuperAdmin || $isAdmin || $isBendahara || $isKades || $isSekdes);
                $isWarga = $isGuest || $user->hasRole('Warga');
                $chatUnread = $isStaff ? \App\Models\Chat::unreadAdminCount() : 0;
            @endphp

            @if (!$isGuest)
            <a class="flex items-center gap-md px-md py-3 rounded-lg transition-all {{ $isActive('dashboard') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="text-label-md">Dashboard</span>
            </a>
            @endif

            @if ($isWarga)
                <div x-data="{ open: {{ $isActive(['layanan/surat', 'pengaduan/buat', 'informasi.publik', 'layanan/musrenbang']) ? 'true' : 'false' }} }" class="rounded-xl">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-md py-3 rounded-lg transition-all {{ $isActive(['layanan/surat', 'pengaduan/buat', 'layanan/musrenbang']) ? 'text-on-primary-container' : 'text-on-primary-container opacity-70 hover:opacity-100 hover:bg-primary/40' }}">
                        <span class="flex items-center gap-md">
                            <span class="material-symbols-outlined">holiday_village</span>
                            <span class="text-label-md">Layanan Warga</span>
                        </span>
                        <span class="material-symbols-outlined transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    <div x-show="open"
                         x-collapse
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-1"
                         class="space-y-xs mt-xs pl-md">
                        <a class="flex items-center gap-md px-md py-2.5 rounded-lg transition-all {{ $isActive('layanan/surat') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('warga.surat.index') }}">
                            <span class="material-symbols-outlined text-[18px]">edit_note</span>
                            <span class="text-label-md">Ajukan Surat</span>
                        </a>
                        <a class="flex items-center gap-md px-md py-2.5 rounded-lg transition-all {{ $isActive('layanan/surat/cek') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('warga.surat.cek') }}">
                            <span class="material-symbols-outlined text-[18px]">manage_search</span>
                            <span class="text-label-md">Cek Status Surat</span>
                        </a>
                        <a class="flex items-center gap-md px-md py-2.5 rounded-lg transition-all {{ $isActive('pengaduan/buat') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('pengaduan.buat') }}">
                            <span class="material-symbols-outlined text-[18px]">campaign</span>
                            <span class="text-label-md">Buat Pengaduan</span>
                        </a>
                        <a class="flex items-center gap-md px-md py-2.5 rounded-lg transition-all {{ $isActive('layanan/musrenbang') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('warga.musrenbang.index') }}">
                            <span class="material-symbols-outlined text-[18px]">architecture</span>
                            <span class="text-label-md">Usulan Kegiatan</span>
                        </a>
                        <a class="flex items-center gap-md px-md py-2.5 rounded-lg transition-all text-on-primary-container hover:bg-primary/40" href="{{ route('informasi.publik') }}">
                            <span class="material-symbols-outlined text-[18px]">newspaper</span>
                            <span class="text-label-md">Info Desa</span>
                        </a>
                    </div>
                </div>
            @endif

            @if ($isStaff)
            {{-- ===================== KEPENDUDUKAN ===================== --}}
            <div x-data="{ open: {{ $isActive(['admin/penduduk', 'admin/keluarga']) ? 'true' : 'false' }} }" class="rounded-xl">
                <button @click="open = !open" class="w-full flex items-center justify-between px-md py-3 rounded-lg transition-all {{ $isActive(['admin/penduduk', 'admin/keluarga']) ? 'text-on-primary-container' : 'text-on-primary-container opacity-70 hover:opacity-100 hover:bg-primary/40' }}">
                    <span class="flex items-center gap-md">
                        <span class="material-symbols-outlined">group</span>
                        <span class="text-label-md">Kependudukan</span>
                    </span>
                    <span class="material-symbols-outlined transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="open"
                     x-collapse
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     class="space-y-xs mt-xs pl-md">
                    <a class="flex items-center gap-md px-md py-2.5 rounded-lg transition-all {{ $isActive('admin/penduduk') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.penduduk.index') }}">
                        <span class="material-symbols-outlined text-[18px]">groups</span>
                        <span class="text-label-md">Data Penduduk</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 rounded-lg transition-all {{ $isActive('admin/keluarga') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.keluarga.index') }}">
                        <span class="material-symbols-outlined text-[18px]">family_restroom</span>
                        <span class="text-label-md">Data Keluarga</span>
                    </a>
                </div>
            </div>

            {{-- ===================== LAYANAN ===================== --}}
            <div x-data="{ open: {{ $isActive(['admin/surat']) ? 'true' : 'false' }} }" class="rounded-xl">
                <button @click="open = !open" class="w-full flex items-center justify-between px-md py-3 rounded-lg transition-all {{ $isActive(['admin/surat']) ? 'text-on-primary-container' : 'text-on-primary-container opacity-70 hover:opacity-100 hover:bg-primary/40' }}">
                    <span class="flex items-center gap-md">
                        <span class="material-symbols-outlined">description</span>
                        <span class="text-label-md">Layanan Surat</span>
                    </span>
                    <span class="material-symbols-outlined transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="open"
                     x-collapse
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     class="space-y-xs mt-xs pl-md">
                    <a class="flex items-center gap-md px-md py-2.5 rounded-lg transition-all {{ $isActive('admin/surat/jenis') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.surat.jenis') }}">
                        <span class="material-symbols-outlined text-[18px]">description</span>
                        <span class="text-label-md">Jenis Surat</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 rounded-lg transition-all {{ $isActive('admin/surat/pengajuan') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.surat.pengajuan') }}">
                        <span class="material-symbols-outlined text-[18px]">forward_to_inbox</span>
                        <span class="text-label-md">Pengajuan Masuk</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 rounded-lg transition-all {{ $isActive('admin/surat/arsip') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.surat.arsip') }}">
                        <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                        <span class="text-label-md">Arsip Surat</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 rounded-lg transition-all {{ $isActive('admin/surat/tracking') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.surat.tracking') }}">
                        <span class="material-symbols-outlined text-[18px]">lan</span>
                        <span class="text-label-md">Tracking</span>
                    </a>
                </div>
            </div>

            {{-- ===================== PERENCANAAN ===================== --}}
            <div x-data="{ open: {{ $isActive(['admin/musrenbang']) ? 'true' : 'false' }} }" class="rounded-xl">
                <button @click="open = !open" class="w-full flex items-center justify-between px-md py-3 rounded-lg transition-all {{ $isActive(['admin/musrenbang']) ? 'text-on-primary-container' : 'text-on-primary-container opacity-70 hover:opacity-100 hover:bg-primary/40' }}">
                    <span class="flex items-center gap-md">
                        <span class="material-symbols-outlined">how_to_vote</span>
                        <span class="text-label-md">Perencanaan</span>
                    </span>
                    <span class="material-symbols-outlined transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="open"
                     x-collapse
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     class="space-y-xs mt-xs pl-md">
                    <a class="flex items-center gap-md px-md py-2.5 rounded-lg transition-all {{ $isActive('admin/musrenbang') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.musrenbang.index') }}">
                        <span class="material-symbols-outlined text-[18px]">how_to_vote</span>
                        <span class="text-label-md">Musrenbang</span>
                    </a>
                </div>
            </div>

            {{-- ===================== KEUANGAN ===================== --}}
            <div x-data="{ open: {{ $isActive(['admin/apbdes', 'admin/pencairan-dana', 'admin/belanja']) ? 'true' : 'false' }} }" class="rounded-xl">
                <button @click="open = !open" class="w-full flex items-center justify-between px-md py-3 rounded-lg transition-all {{ $isActive(['admin/apbdes', 'admin/pencairan-dana', 'admin/belanja']) ? 'text-on-primary-container' : 'text-on-primary-container opacity-70 hover:opacity-100 hover:bg-primary/40' }}">
                    <span class="flex items-center gap-md">
                        <span class="material-symbols-outlined">account_balance</span>
                        <span class="text-label-md">Keuangan</span>
                    </span>
                    <span class="material-symbols-outlined transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="open"
                     x-collapse
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     class="space-y-xs mt-xs pl-md">
                    <a class="flex items-center gap-md px-md py-2.5 rounded-lg transition-all {{ $isActive('admin/apbdes') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.apbdes.index') }}">
                        <span class="material-symbols-outlined text-[18px]">account_balance</span>
                        <span class="text-label-md">APBDes Ringkasan</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 rounded-lg transition-all {{ $isActive('admin/apbdes/dashboard') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.apbdes.dashboard') }}">
                        <span class="material-symbols-outlined text-[18px]">analytics</span>
                        <span class="text-label-md">Laporan Keuangan</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 rounded-lg transition-all {{ $isActive('admin/pencairan-dana') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.pencairan-dana.index') }}">
                        <span class="material-symbols-outlined text-[18px]">payments</span>
                        <span class="text-label-md">Pencairan Dana</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 rounded-lg transition-all {{ $isActive('admin/belanja') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.belanja.index') }}">
                        <span class="material-symbols-outlined text-[18px]">shopping_cart</span>
                        <span class="text-label-md">Belanja Desa</span>
                    </a>
                </div>
            </div>

            {{-- ===================== KOMUNIKASI ===================== --}}
            <div x-data="{ open: {{ $isActive(['admin/pengaduan', 'admin/informasi', 'admin/qr-links', 'admin/chat']) ? 'true' : 'false' }} }" class="rounded-xl">
                <button @click="open = !open" class="w-full flex items-center justify-between px-md py-3 rounded-lg transition-all {{ $isActive(['admin/pengaduan', 'admin/informasi', 'admin/qr-links', 'admin/chat']) ? 'text-on-primary-container' : 'text-on-primary-container opacity-70 hover:opacity-100 hover:bg-primary/40' }}">
                    <span class="flex items-center gap-md">
                        <span class="material-symbols-outlined">campaign</span>
                        <span class="text-label-md">Komunikasi</span>
                    </span>
                    <span class="material-symbols-outlined transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="open"
                     x-collapse
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     class="space-y-xs mt-xs pl-md">
                    <a class="flex items-center gap-md px-md py-2.5 rounded-lg transition-all {{ $isActive('admin/pengaduan') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.pengaduan.index') }}">
                        <span class="material-symbols-outlined text-[18px]">campaign</span>
                        <span class="text-label-md">Pengaduan</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 rounded-lg transition-all {{ $isActive('admin/chat') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.chat.index') }}">
                        <span class="material-symbols-outlined text-[18px]">forum</span>
                        <span class="text-label-md">Chat Warga</span>
                        <span x-data="chatUnread()" x-init="init()" class="ml-auto">
                            <span x-show="count > 0" x-cloak class="min-w-[20px] h-5 px-1.5 rounded-full bg-error text-on-error text-[11px] font-bold flex items-center justify-center">
                                <span x-text="count"></span>
                            </span>
                        </span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 rounded-lg transition-all {{ $isActive('admin/informasi') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.informasi.index') }}">
                        <span class="material-symbols-outlined text-[18px]">newspaper</span>
                        <span class="text-label-md">Berita & Agenda</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 rounded-lg transition-all {{ $isActive('admin/qr-links') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.qr-links.index') }}">
                        <span class="material-symbols-outlined text-[18px]">qr_code_2</span>
                        <span class="text-label-md">QR & Link Wilayah</span>
                    </a>
                </div>
            </div>
            @endif

            <div class="pt-md border-t border-primary/20 mt-md">
                @if($isAdmin)
                <a class="flex items-center gap-md px-md py-3 rounded-lg transition-all {{ $isActive('admin/roles') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.roles.index') }}">
                    <span class="material-symbols-outlined">manage_accounts</span>
                    <span class="text-label-md">User & Role</span>
                </a>
                @endif
            </div>
        </nav>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="pl-72 flex flex-col min-h-screen bg-gradient-to-br from-surface-container-low via-surface to-surface-container-low">
        <!-- Header -->
        <header class="fixed top-0 right-0 left-72 h-16 bg-surface/80 backdrop-blur-lg shadow-[0_1px_8px_rgba(39,0,90,0.05)] z-40 px-lg flex items-center justify-between border-b border-outline-variant/10">
            <div class="flex-1 max-w-xl relative group">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">search</span>
                <input class="w-full bg-surface-container border-none rounded-full py-2 pl-10 pr-4 text-body-sm focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="Cari data penduduk atau layanan..." type="text"/>
            </div>
            <div class="flex items-center gap-md">
                @auth
                <div x-data="notificationBell()" class="relative">
                    <button @click="toggle(); load()" class="w-10 h-10 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container transition-colors relative">
                        <span class="material-symbols-outlined">notifications</span>
                        <span x-show="unread > 0" x-cloak
                              class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-error text-on-error text-[10px] font-bold flex items-center justify-center">
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
                         class="absolute right-0 mt-2 w-80 max-h-[70vh] flex flex-col bg-surface-container shadow-lg rounded-xl border border-outline-variant overflow-hidden z-50">
                        <div class="flex items-center justify-between px-lg py-3 border-b border-outline-variant/20">
                            <h3 class="text-label-md font-bold text-on-surface">Notifikasi</h3>
                            <button @click="markAll(); $event.stopPropagation()"
                                    x-show="unread > 0"
                                    class="text-label-sm font-semibold text-primary hover:underline">
                                Tandai semua
                            </button>
                        </div>

                        <div class="flex-1 overflow-y-auto max-h-[420px]">
                            <template x-if="loading">
                                <div class="p-lg text-center text-on-surface-variant">
                                    <span class="material-symbols-outlined animate-spin block text-2xl mb-sm">progress_activity</span>
                                    <span class="text-label-sm">Memuat...</span>
                                </div>
                            </template>

                            <template x-if="!loading && items.length === 0">
                                <div class="p-lg text-center text-on-surface-variant/60">
                                    <span class="material-symbols-outlined text-[36px] block mb-sm">notifications_none</span>
                                    <span class="text-label-sm">Belum ada notifikasi</span>
                                </div>
                            </template>

                            <template x-for="item in items" :key="item.id">
                                <a :href="item.link || '#'"
                                   @click="item.link ? markRead(item.id) : null"
                                   class="flex items-start gap-md px-lg py-3 border-b border-outline-variant/10 hover:bg-surface-container-high transition-colors"
                                   :class="!item.is_read ? 'bg-primary-fixed/10' : ''">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                         :class="item.warna || 'bg-primary/10 text-primary'">
                                        <span class="material-symbols-outlined text-base" x-text="item.icon || 'notifications'"></span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-label-sm font-bold text-on-surface truncate" x-text="item.judul"></p>
                                        <p class="text-[11px] text-on-surface-variant line-clamp-2" x-text="item.pesan"></p>
                                        <span class="text-[10px] text-on-surface-variant/60" x-text="item.waktu"></span>
                                    </div>
                                    <span x-show="!item.is_read" class="w-2 h-2 rounded-full bg-error mt-2 flex-shrink-0"></span>
                                </a>
                            </template>
                        </div>

                        <a href="{{ route('admin.notifications.index') }}"
                           class="block text-center py-3 text-label-sm font-bold text-primary border-t border-outline-variant/20 hover:bg-surface-container-high transition-colors">
                            Lihat Semua Notifikasi
                        </a>
                    </div>
                </div>
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-sm bg-surface-container-high/50 px-3 py-1.5 rounded-full border border-outline-variant hover:bg-surface-container transition-colors">
                        <div class="flex flex-col items-end hidden md:flex">
                            <span class="text-label-sm text-on-surface">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] uppercase text-on-surface-variant font-bold tracking-tighter">{{ auth()->user()->roles->first()?->name ?? 'Warga' }}</span>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-label-sm font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <svg class="w-4 h-4 text-on-surface-variant" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         @click.outside="open = false"
                         class="absolute right-0 mt-2 w-56 bg-surface-container shadow-lg rounded-lg border border-outline-variant py-2 z-50">
                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-on-surface hover:bg-surface-container-high text-label-sm">
                            <span class="material-symbols-outlined mr-3 text-on-surface-variant text-base">person</span>
                            <span>Profile</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-on-surface hover:bg-surface-container-high text-label-sm">
                            <span class="material-symbols-outlined mr-3 text-on-surface-variant text-base">settings</span>
                            <span>Pengaturan</span>
                        </a>
                        <div class="border-t border-outline-variant my-1"></div>
                        <form method="POST" action="{{ route('logout') }}" class="inline w-full">
                            @csrf
                            <button type="submit" class="flex items-center px-4 py-2 text-error hover:bg-error-container text-label-sm w-full text-left">
                                <span class="material-symbols-outlined mr-3 text-base">logout</span>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
                @else
                    <a href="{{ route('login') }}" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all">Masuk</a>
                @endauth
            </div>
        </header>

        <!-- Page Content -->
        <main class="relative pt-16 flex-1 px-lg py-lg">
            @yield('content')
        </main>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
    <script>
        function notificationBell() {
            return {
                open: false,
                loading: false,
                unread: 0,
                items: [],
                init() {
                    this.timer = setInterval(() => this.load(), 60000);
                },
                toggle() {
                    this.open = !this.open;
                    if (this.open) this.load();
                },
                load() {
                    this.loading = true;
                    fetch('{{ route('admin.notifications.data') }}', {
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
                    fetch('{{ route('admin.notifications.read', ':id') }}'.replace(':id', id), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(() => { this.load(); });
                },
                markAll() {
                    fetch('{{ route('admin.notifications.read-all') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(() => { this.load(); });
                }
            };
        }

        function chatUnread() {
            return {
                count: {{ $chatUnread }},
                init() {
                    this.timer = setInterval(() => this.muat(), 60000);
                },
                muat() {
                    fetch('{{ route('admin.chat.unread') }}', {
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(r => r.json())
                    .then(d => { this.count = d.count || 0; })
                    .catch(() => {});
                }
            };
        }

        // Muat jumlah notifikasi belum dibaca saat halaman dibuka
        document.addEventListener('DOMContentLoaded', function () {
            const bell = document.querySelector('[x-data="notificationBell()"]');
            if (bell && typeof Alpine !== 'undefined') {
                setTimeout(() => {
                    if (bell._x_dataStack) {
                        const data = bell._x_dataStack[0];
                        if (data && typeof data.load === 'function') data.load();
                    }
                }, 300);
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

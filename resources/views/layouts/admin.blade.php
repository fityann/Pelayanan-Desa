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
<body class="bg-slate-50 font-body-md text-slate-800">
    <!-- Sidebar (Solid #6A3297 Green Theme) -->
    <aside class="fixed left-0 top-0 h-full w-72 bg-[#6A3297] border-r border-[#D8B84C]/30 z-50 flex flex-col shadow-2xl sidebar-scrollbar overflow-y-auto">
        <div class="p-lg flex items-center gap-md border-b border-[#D8B84C]/25 mb-md">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D8B84C] to-[#F0D878] p-0.5 shadow-lg shadow-[#D8B84C]/20 flex items-center justify-center">
                <img src="{{ asset('images/logo-desa-puspamukti.jpg') }}" alt="Logo Puspamukti" class="w-full h-full object-contain rounded-lg bg-white p-0.5">
            </div>
            <div class="flex flex-col">
                <span class="text-white font-black text-headline-sm tracking-tight">SILAPU</span>
                <span class="text-[#D8B84C] text-label-sm font-black uppercase tracking-widest text-[10px]">Puspamukti Admin</span>
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
                $activeLinkClass = 'bg-gradient-to-r from-[#D8B84C] via-[#E5C968] to-[#F0D878] text-[#2A3520] font-black shadow-lg shadow-[#D8B84C]/30 border-l-4 border-[#F7F0D4] rounded-xl';
                $inactiveLinkClass = 'text-slate-200 hover:text-[#D8B84C] hover:bg-gradient-to-r hover:from-[#D8B84C]/20 hover:to-[#E5C968]/10 hover:border-l-4 hover:border-[#D8B84C] rounded-xl transition-all font-semibold';
                $dropdownHeaderClass = 'text-slate-200 opacity-90 hover:opacity-100 hover:text-[#D8B84C] hover:bg-[#D8B84C]/20 rounded-xl font-bold text-xs uppercase tracking-wider transition-all';
            @endphp

            @if (!$isGuest)
            <a class="flex items-center gap-md px-md py-3 transition-all {{ $isActive('dashboard') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="text-label-md">Dashboard</span>
            </a>
            @endif

            @if ($isWarga)
                <div x-data="{ open: {{ $isActive(['layanan/surat', 'pengaduan/buat', 'informasi.publik', 'layanan/musrenbang']) ? 'true' : 'false' }} }" class="rounded-xl">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-md py-3 transition-all {{ $isActive(['layanan/surat', 'pengaduan/buat', 'layanan/musrenbang']) ? 'text-white font-bold' : $dropdownHeaderClass }}">
                        <span class="flex items-center gap-md">
                            <span class="material-symbols-outlined text-emerald-400">holiday_village</span>
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
                         class="space-y-xs mt-xs pl-md border-l border-slate-800 ml-4">
                        <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $isActive('layanan/surat') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('warga.surat.index') }}">
                            <span class="material-symbols-outlined text-[18px]">edit_note</span>
                            <span class="text-label-md">Ajukan Surat</span>
                        </a>
                        <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $isActive('layanan/surat/cek') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('warga.surat.cek') }}">
                            <span class="material-symbols-outlined text-[18px]">manage_search</span>
                            <span class="text-label-md">Cek Status Surat</span>
                        </a>
                        <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $isActive('pengaduan/buat') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('pengaduan.buat') }}">
                            <span class="material-symbols-outlined text-[18px]">campaign</span>
                            <span class="text-label-md">Buat Pengaduan</span>
                        </a>
                        <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $isActive('layanan/musrenbang') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('warga.musrenbang.index') }}">
                            <span class="material-symbols-outlined text-[18px]">architecture</span>
                            <span class="text-label-md">Usulan Kegiatan</span>
                        </a>
                        <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $inactiveLinkClass }}" href="{{ route('informasi.publik') }}">
                            <span class="material-symbols-outlined text-[18px]">newspaper</span>
                            <span class="text-label-md">Info Desa</span>
                        </a>
                    </div>
                </div>
            @endif

            @if ($isStaff)
            {{-- ===================== KEPENDUDUKAN ===================== --}}
            <div x-data="{ open: {{ $isActive(['admin/penduduk', 'admin/keluarga']) ? 'true' : 'false' }} }" class="rounded-xl">
                <button @click="open = !open" class="w-full flex items-center justify-between px-md py-3 transition-all {{ $isActive(['admin/penduduk', 'admin/keluarga']) ? 'text-white font-bold' : $dropdownHeaderClass }}">
                    <span class="flex items-center gap-md">
                        <span class="material-symbols-outlined text-amber-400">group</span>
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
                     class="space-y-xs mt-xs pl-md border-l border-slate-800 ml-4">
                    <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $isActive('admin/penduduk') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('admin.penduduk.index') }}">
                        <span class="material-symbols-outlined text-[18px]">groups</span>
                        <span class="text-label-md">Data Penduduk</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $isActive('admin/keluarga') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('admin.keluarga.index') }}">
                        <span class="material-symbols-outlined text-[18px]">family_restroom</span>
                        <span class="text-label-md">Data Keluarga</span>
                    </a>
                </div>
            </div>

            {{-- ===================== LAYANAN ===================== --}}
            <div x-data="{ open: {{ $isActive(['admin/surat']) ? 'true' : 'false' }} }" class="rounded-xl">
                <button @click="open = !open" class="w-full flex items-center justify-between px-md py-3 transition-all {{ $isActive(['admin/surat']) ? 'text-amber-300 font-extrabold' : $dropdownHeaderClass }}">
                    <span class="flex items-center gap-md">
                        <span class="material-symbols-outlined text-amber-400">description</span>
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
                     class="space-y-xs mt-xs pl-md border-l border-slate-800 ml-4">
                    <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $isActive('admin/surat/jenis') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('admin.surat.jenis') }}">
                        <span class="material-symbols-outlined text-[18px]">description</span>
                        <span class="text-label-md">Jenis Surat</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $isActive('admin/surat/pengajuan') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('admin.surat.pengajuan') }}">
                        <span class="material-symbols-outlined text-[18px]">forward_to_inbox</span>
                        <span class="text-label-md">Pengajuan Masuk</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $isActive('admin/surat/arsip') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('admin.surat.arsip') }}">
                        <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                        <span class="text-label-md">Arsip Surat</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $isActive('admin/surat/tracking') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('admin.surat.tracking') }}">
                        <span class="material-symbols-outlined text-[18px]">lan</span>
                        <span class="text-label-md">Tracking</span>
                    </a>
                </div>
            </div>

            {{-- ===================== PERENCANAAN ===================== --}}
            <div x-data="{ open: {{ $isActive(['admin/musrenbang']) ? 'true' : 'false' }} }" class="rounded-xl">
                <button @click="open = !open" class="w-full flex items-center justify-between px-md py-3 transition-all {{ $isActive(['admin/musrenbang']) ? 'text-amber-300 font-extrabold' : $dropdownHeaderClass }}">
                    <span class="flex items-center gap-md">
                        <span class="material-symbols-outlined text-amber-400">how_to_vote</span>
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
                     class="space-y-xs mt-xs pl-md border-l border-slate-800 ml-4">
                    <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $isActive('admin/musrenbang') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('admin.musrenbang.index') }}">
                        <span class="material-symbols-outlined text-[18px]">how_to_vote</span>
                        <span class="text-label-md">Musrenbang</span>
                    </a>
                </div>
            </div>

            {{-- ===================== KEUANGAN & ASET ===================== --}}
            <div x-data="{ open: {{ $isActive(['admin/apbdes', 'admin/pencairan-dana', 'admin/belanja', 'admin/assets', 'admin/kategori-aset']) ? 'true' : 'false' }} }" class="rounded-xl">
                <button @click="open = !open" class="w-full flex items-center justify-between px-md py-3 transition-all {{ $isActive(['admin/apbdes', 'admin/pencairan-dana', 'admin/belanja', 'admin/assets', 'admin/kategori-aset']) ? 'text-amber-300 font-extrabold' : $dropdownHeaderClass }}">
                    <span class="flex items-center gap-md">
                        <span class="material-symbols-outlined text-amber-400">account_balance</span>
                        <span class="text-label-md">Keuangan & Aset</span>
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
                     class="space-y-xs mt-xs pl-md border-l border-slate-800 ml-4">
                    <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $isActive('admin/apbdes') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('admin.apbdes.index') }}">
                        <span class="material-symbols-outlined text-[18px]">account_balance</span>
                        <span class="text-label-md">APBDes Ringkasan</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $isActive('admin/apbdes/dashboard') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('admin.apbdes.dashboard') }}">
                        <span class="material-symbols-outlined text-[18px]">analytics</span>
                        <span class="text-label-md">Laporan Keuangan</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $isActive('admin/pencairan-dana') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('admin.pencairan-dana.index') }}">
                        <span class="material-symbols-outlined text-[18px]">payments</span>
                        <span class="text-label-md">Pencairan Dana</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $isActive('admin/belanja') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('admin.belanja.index') }}">
                        <span class="material-symbols-outlined text-[18px]">shopping_cart</span>
                        <span class="text-label-md">Belanja Desa</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $isActive('admin/assets') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('admin.assets.index') }}">
                        <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                        <span class="text-label-md">Aset Desa</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $isActive('admin/kategori-aset') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('admin.kategori-aset.index') }}">
                        <span class="material-symbols-outlined text-[18px]">category</span>
                        <span class="text-label-md">Kategori Aset</span>
                    </a>
                </div>
            </div>

            {{-- ===================== KOMUNIKASI ===================== --}}
            <div x-data="{ open: {{ $isActive(['admin/pengaduan', 'admin/informasi', 'admin/qr-links', 'admin/chat']) ? 'true' : 'false' }} }" class="rounded-xl">
                <button @click="open = !open" class="w-full flex items-center justify-between px-md py-3 transition-all {{ $isActive(['admin/pengaduan', 'admin/informasi', 'admin/qr-links', 'admin/chat']) ? 'text-amber-300 font-extrabold' : $dropdownHeaderClass }}">
                    <span class="flex items-center gap-md">
                        <span class="material-symbols-outlined text-amber-400">campaign</span>
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
                     class="space-y-xs mt-xs pl-md border-l border-slate-800 ml-4">
                    <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $isActive('admin/pengaduan') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('admin.pengaduan.index') }}">
                        <span class="material-symbols-outlined text-[18px]">campaign</span>
                        <span class="text-label-md">Pengaduan</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $isActive('admin/chat') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('admin.chat.index') }}">
                        <span class="material-symbols-outlined text-[18px]">forum</span>
                        <span class="text-label-md">Chat Warga</span>
                        <span x-data="chatUnread()" x-init="init()" class="ml-auto">
                            <span x-show="count > 0" x-cloak class="min-w-[20px] h-5 px-1.5 rounded-full bg-rose-600 text-white text-[11px] font-bold flex items-center justify-center">
                                <span x-text="count"></span>
                            </span>
                        </span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $isActive('admin/informasi') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('admin.informasi.index') }}">
                        <span class="material-symbols-outlined text-[18px]">newspaper</span>
                        <span class="text-label-md">Berita & Agenda</span>
                    </a>
                    <a class="flex items-center gap-md px-md py-2.5 transition-all {{ $isActive('admin/qr-links') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('admin.qr-links.index') }}">
                        <span class="material-symbols-outlined text-[18px]">qr_code_2</span>
                        <span class="text-label-md">QR & Link RT</span>
                    </a>
                </div>
            </div>
            @endif

            <div class="pt-md border-t border-slate-800/80 mt-md">
                @if($isAdmin)
                <a class="flex items-center gap-md px-md py-3 transition-all {{ $isActive('admin/roles') ? $activeLinkClass : $inactiveLinkClass }}" href="{{ route('admin.roles.index') }}">
                    <span class="material-symbols-outlined">manage_accounts</span>
                    <span class="text-label-md">User & Role</span>
                </a>
                @endif
            </div>
        </nav>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="pl-72 flex flex-col min-h-screen bg-slate-50">
        <!-- Header -->
        <header class="fixed top-0 right-0 left-72 h-16 bg-white/85 backdrop-blur-xl border-b border-slate-200/80 z-40 px-lg flex items-center justify-between shadow-xs">
            <div x-data="globalLiveSearch()" class="flex-1 max-w-xl relative group">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-600 transition-colors pointer-events-none text-xl">search</span>
                <input x-model="q"
                       @input.debounce.250ms="doSearch()"
                       @focus="open = true"
                       @keydown.escape="open = false"
                       class="w-full bg-slate-100/90 border border-slate-200/80 rounded-full py-2 pl-11 pr-10 text-body-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all placeholder-slate-400 font-medium"
                       placeholder="Cari NIK, Nama Penduduk, Surat, Pengaduan, Berita, APBDes..."
                       type="text"/>
                <button x-show="q.length > 0"
                        @click="q = ''; results = []; open = false"
                        x-cloak
                        class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 flex items-center justify-center w-5 h-5 rounded-full hover:bg-slate-200/60 transition-colors">
                    <span class="material-symbols-outlined text-base">close</span>
                </button>

                {{-- Live Search Result Dropdown Card --}}
                <div x-show="open && (loading || results.length > 0 || (q.length >= 2 && results.length === 0))"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.outside="open = false"
                     x-cloak
                     class="absolute left-0 right-0 mt-2 bg-white shadow-2xl rounded-2xl border border-slate-200/90 overflow-hidden z-50 max-h-[75vh] flex flex-col backdrop-blur-2xl">

                    <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-emerald-600 text-base">manage_search</span>
                            Hasil Pencarian Real-Time
                        </span>
                        <span x-show="!loading" class="text-[11px] font-semibold text-slate-400" x-text="results.length + ' hasil ditemukan'"></span>
                    </div>

                    <div class="overflow-y-auto flex-1 divide-y divide-slate-100">
                        {{-- Loading State --}}
                        <template x-if="loading">
                            <div class="p-6 text-center text-slate-500">
                                <span class="material-symbols-outlined animate-spin block text-2xl text-emerald-600 mb-2">progress_activity</span>
                                <span class="text-xs font-semibold">Mencari data di seluruh sistem...</span>
                            </div>
                        </template>

                        {{-- Empty State --}}
                        <template x-if="!loading && q.length >= 2 && results.length === 0">
                            <div class="p-8 text-center text-slate-400">
                                <span class="material-symbols-outlined text-4xl block mb-2 text-slate-300">search_off</span>
                                <p class="text-xs font-bold text-slate-600">Tidak ada data yang cocok dengan "<span x-text="q"></span>"</p>
                                <p class="text-[11px] text-slate-400 mt-1">Coba kata kunci lain seperti NIK, Nama Penduduk, Kode Surat, atau Kata Kunci Pengaduan.</p>
                            </div>
                        </template>

                        {{-- Result Items --}}
                        <template x-for="(item, idx) in results" :key="idx">
                            <a :href="item.url"
                               class="flex items-start gap-3.5 px-4 py-3 hover:bg-slate-50 transition-colors group/item">
                                <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 group-hover/item:bg-emerald-600 group-hover/item:text-white transition-colors shadow-xs">
                                    <span class="material-symbols-outlined text-lg" x-text="item.icon"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 mb-0.5">
                                        <h4 class="text-xs font-bold text-slate-900 truncate group-hover/item:text-emerald-700 transition-colors" x-text="item.title"></h4>
                                        <span class="px-2 py-0.5 rounded-md text-[9px] font-extrabold uppercase tracking-wider shrink-0" :class="item.badge_color" x-text="item.category"></span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 line-clamp-1 font-medium" x-text="item.subtitle"></p>
                                </div>
                                <span class="material-symbols-outlined text-slate-300 group-hover/item:text-emerald-600 text-base self-center opacity-0 group-hover/item:opacity-100 transition-all -translate-x-1 group-hover/item:translate-x-0">chevron_right</span>
                            </a>
                        </template>
                    </div>

                    <div class="px-4 py-2 bg-slate-50 border-t border-slate-100 text-center text-[10px] text-slate-400 font-semibold">
                        Tekan <kbd class="px-1.5 py-0.5 bg-white border border-slate-200 rounded text-[9px] font-mono text-slate-600 shadow-2xs">ESC</kbd> untuk menutup
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-md">
                @auth
                <div x-data="notificationBell()" class="relative">
                    <button @click="toggle(); load()" class="w-10 h-10 flex items-center justify-center rounded-full text-slate-600 hover:bg-slate-100 transition-colors relative">
                        <span class="material-symbols-outlined">notifications</span>
                        <span x-show="unread > 0" x-cloak
                              class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-600 text-white text-[10px] font-bold flex items-center justify-center">
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
                         class="absolute right-0 mt-2 w-80 max-h-[70vh] flex flex-col bg-white shadow-xl rounded-2xl border border-slate-200/80 overflow-hidden z-50">
                        <div class="flex items-center justify-between px-lg py-3 border-b border-slate-100 bg-slate-50/80">
                            <h3 class="text-label-md font-bold text-slate-900">Notifikasi</h3>
                            <button @click="markAll(); $event.stopPropagation()"
                                    x-show="unread > 0"
                                    class="text-label-sm font-semibold text-emerald-600 hover:underline">
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
                           class="block text-center py-3 text-label-sm font-bold text-emerald-600 border-t border-slate-100 hover:bg-slate-50 transition-colors">
                            Lihat Semua Notifikasi
                        </a>
                    </div>
                </div>
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2.5 bg-slate-100/90 hover:bg-slate-200/80 px-3.5 py-1.5 rounded-full border border-slate-200/80 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-600 to-teal-500 flex items-center justify-center text-white text-label-sm font-extrabold shadow-sm">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="flex flex-col items-start hidden md:flex">
                            <span class="text-label-sm font-bold text-slate-900 leading-tight">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] uppercase text-emerald-700 font-extrabold tracking-wider leading-tight">{{ auth()->user()->roles->first()?->name ?? 'Warga' }}</span>
                        </div>
                        <span class="material-symbols-outlined text-slate-500 text-base">expand_more</span>
                    </button>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         @click.outside="open = false"
                         class="absolute right-0 mt-2 w-56 bg-white shadow-xl rounded-2xl border border-slate-200/80 py-2 z-50">
                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2.5 text-slate-700 hover:bg-slate-50 font-semibold text-label-sm transition-colors">
                            <span class="material-symbols-outlined mr-3 text-slate-400 text-base">person</span>
                            <span>Profil Saya</span>
                        </a>
                        <div class="border-t border-slate-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}" class="inline w-full">
                            @csrf
                            <button type="submit" class="flex items-center px-4 py-2.5 text-rose-600 hover:bg-rose-50 font-semibold text-label-sm w-full text-left transition-colors">
                                <span class="material-symbols-outlined mr-3 text-base">logout</span>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
                @else
                    <a href="{{ route('login') }}" class="bg-gradient-to-r from-emerald-600 to-teal-500 text-white px-lg py-2 rounded-full text-label-md font-bold hover:from-emerald-700 hover:to-teal-600 transition-all shadow-md">Masuk</a>
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
                        if (window.SilapuPushNotification) {
                            window.SilapuPushNotification.processItems(this.items);
                        }
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

        function globalLiveSearch() {
            return {
                q: '',
                open: false,
                loading: false,
                results: [],
                doSearch() {
                    if (this.q.trim().length < 2) {
                        this.results = [];
                        this.open = false;
                        return;
                    }
                    this.loading = true;
                    this.open = true;
                    fetch('{{ route('admin.search') }}?q=' + encodeURIComponent(this.q), {
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(r => r.json())
                    .then(data => {
                        this.results = data.results || [];
                    })
                    .catch(() => { this.results = []; })
                    .finally(() => { this.loading = false; });
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

        // Web Push Notification Helper for Admin
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
                            new Notification('Notifikasi SILAPU Admin Aktif 🔔', {
                                body: 'Notifikasi browser telah diaktifkan. Anda akan menerima notifikasi pengajuan surat & pengaduan baru.',
                                icon: '/images/logo-desa-puspamukti.jpg'
                            });
                        }
                        if (callback) callback(permission);
                    });
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
                            const notif = new Notification(item.judul || 'Notifikasi Admin SILAPU', {
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

    @stack('scripts')
</body>
</html>

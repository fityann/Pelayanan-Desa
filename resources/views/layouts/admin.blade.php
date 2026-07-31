<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'SIPANDA - Puspamukti')</title>
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
                <span class="text-on-primary font-headline-sm tracking-tight">SIPANDA</span>
                <span class="text-on-primary-container text-label-sm uppercase opacity-80">Puspamukti Admin</span>
            </div>
        </div>
        <nav class="flex-1 px-md space-y-xs pb-lg">
            @php
                $currentPath = request()->path();
                $isActive = fn($paths) => collect((array)$paths)->contains(fn($p) => str_starts_with($currentPath, $p));
                $user = auth()->user();
                $isSuperAdmin = $user->hasRole('Super Admin');
                $isAdmin = $user->hasAnyRole(['Super Admin', 'Admin Desa']);
                $isBendahara = $user->hasRole('Bendahara');
                $isKades = $user->hasRole('Kepala Desa');
                $isSekdes = $user->hasRole('Sekretaris Desa');
            @endphp

            <a class="flex items-center gap-md px-md py-3 rounded-lg transition-all {{ $isActive('dashboard') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="text-label-md">Dashboard</span>
            </a>

            <div class="pt-md pb-xs px-md text-on-primary-container opacity-50 text-label-sm uppercase tracking-widest">Kependudukan</div>
            <a class="flex items-center gap-md px-md py-3 rounded-lg transition-all {{ $isActive('admin/penduduk') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.penduduk.index') }}">
                <span class="material-symbols-outlined">groups</span>
                <span class="text-label-md">Data Penduduk</span>
            </a>
            <a class="flex items-center gap-md px-md py-3 rounded-lg transition-all {{ $isActive('admin/keluarga') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.keluarga.index') }}">
                <span class="material-symbols-outlined">family_restroom</span>
                <span class="text-label-md">Data Keluarga</span>
            </a>

            <div class="pt-md pb-xs px-md text-on-primary-container opacity-50 text-label-sm uppercase tracking-widest">Layanan</div>
            <a class="flex items-center gap-md px-md py-3 rounded-lg transition-all {{ $isActive('admin/surat/jenis') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.surat.jenis') }}">
                <span class="material-symbols-outlined">description</span>
                <span class="text-label-md">Jenis Surat</span>
            </a>
            <a class="flex items-center gap-md px-md py-3 rounded-lg transition-all {{ $isActive('admin/surat/pengajuan') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.surat.pengajuan') }}">
                <span class="material-symbols-outlined">forward_to_inbox</span>
                <span class="text-label-md">Pengajuan Masuk</span>
            </a>
            <a class="flex items-center gap-md px-md py-3 rounded-lg transition-all {{ $isActive('admin/surat/arsip') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.surat.arsip') }}">
                <span class="material-symbols-outlined">inventory_2</span>
                <span class="text-label-md">Arsip Surat</span>
            </a>
            <a class="flex items-center gap-md px-md py-3 rounded-lg transition-all {{ $isActive('admin/surat/tracking') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.surat.tracking') }}">
                <span class="material-symbols-outlined">lan</span>
                <span class="text-label-md">Tracking</span>
            </a>

            <div class="pt-md pb-xs px-md text-on-primary-container opacity-50 text-label-sm uppercase tracking-widest">Keuangan</div>
            <a class="flex items-center gap-md px-md py-3 rounded-lg transition-all {{ $isActive('admin/apbdes') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.apbdes.index') }}">
                <span class="material-symbols-outlined">account_balance</span>
                <span class="text-label-md">APBDes Ringkasan</span>
            </a>
            <a class="flex items-center gap-md px-md py-3 rounded-lg transition-all text-on-primary-container hover:bg-primary/40" href="{{ route('admin.apbdes.index') }}?tahun={{ date('Y') }}">
                <span class="material-symbols-outlined">analytics</span>
                <span class="text-label-md">Laporan Keuangan</span>
            </a>

            <div class="pt-md pb-xs px-md text-on-primary-container opacity-50 text-label-sm uppercase tracking-widest">Komunikasi</div>
            <a class="flex items-center gap-md px-md py-3 rounded-lg transition-all {{ $isActive('admin/pengaduan') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.pengaduan.index') }}">
                <span class="material-symbols-outlined">campaign</span>
                <span class="text-label-md">Pengaduan</span>
            </a>
            <a class="flex items-center gap-md px-md py-3 rounded-lg transition-all {{ $isActive('admin/informasi') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.informasi.index') }}">
                <span class="material-symbols-outlined">newspaper</span>
                <span class="text-label-md">Berita & Agenda</span>
            </a>

            <div class="pt-md border-t border-primary/20 mt-md">
                @if($isAdmin)
                <a class="flex items-center gap-md px-md py-3 rounded-lg transition-all {{ $isActive('admin/roles') ? 'bg-secondary-container text-on-secondary-container font-semibold border-l-4 border-on-tertiary-container' : 'text-on-primary-container hover:bg-primary/40' }}" href="{{ route('admin.roles.index') }}">
                    <span class="material-symbols-outlined">manage_accounts</span>
                    <span class="text-label-md">User & Role</span>
                </a>
                @endif
                <a class="flex items-center gap-md px-md py-3 rounded-lg transition-all text-on-primary-container hover:bg-primary/40" href="{{ route('profile.edit') }}">
                    <span class="material-symbols-outlined">settings</span>
                    <span class="text-label-md">Pengaturan</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline w-full">
                    @csrf
                    <button type="submit" class="flex items-center gap-md px-md py-3 rounded-lg w-full text-on-primary-container hover:bg-primary/40 transition-all text-left">
                        <span class="material-symbols-outlined">logout</span>
                        <span class="text-label-md">Keluar</span>
                    </button>
                </form>
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
                <button class="w-10 h-10 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container transition-colors relative">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <div class="flex items-center gap-sm bg-surface-container-high/50 px-3 py-1.5 rounded-full border border-outline-variant">
                    <div class="flex flex-col items-end hidden md:flex">
                        <span class="text-label-sm text-on-surface">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] uppercase text-on-surface-variant font-bold tracking-tighter">{{ auth()->user()->roles->first()?->name ?? 'Warga' }}</span>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-label-sm font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="relative pt-16 flex-1 px-lg py-lg">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>

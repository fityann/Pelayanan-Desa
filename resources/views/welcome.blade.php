<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>SIPANDA - Puspamukti Smart Village</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
</head>
<body class="bg-surface font-body-md text-on-surface">
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-surface/80 backdrop-blur-md border-b border-outline-variant/30">
        <div class="max-w-6xl mx-auto px-lg h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-md">
                <div class="w-9 h-9 rounded-lg bg-primary flex items-center justify-center text-on-primary font-bold text-title-md">S</div>
                <span class="font-headline-sm text-on-surface font-bold">SIPANDA</span>
            </a>
            <div class="flex items-center gap-md">
                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all flex items-center gap-sm">
                        <span class="material-symbols-outlined text-[18px]">dashboard</span>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-label-md font-bold text-on-surface-variant hover:text-on-surface transition-all px-lg py-2">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all">Daftar</a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="min-h-screen flex items-center relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary/5 via-surface to-secondary/5"></div>
        <div class="max-w-6xl mx-auto px-lg py-32 relative z-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-xl items-center">
                <div>
                    <div class="inline-flex items-center gap-sm px-lg py-2 bg-primary/10 rounded-full text-primary text-label-sm font-bold mb-lg">
                        <span class="material-symbols-outlined text-[16px]">verified</span>
                        Sistem Informasi Desa Terpadu
                    </div>
                    <h1 class="text-display-md font-extrabold text-on-surface leading-tight mb-lg">
                        Puspamukti<br/>
                        <span class="text-primary">Smart Village</span>
                    </h1>
                    <p class="text-body-lg text-on-surface-variant mb-xl max-w-lg leading-relaxed">
                        Platform digital terpadu untuk pelayanan administrasi desa, transparansi APBDes, 
                        pengaduan masyarakat, dan informasi desa — Desa Puspamukti, Kecamatan Cigalontang, Kabupaten Tasikmalaya.
                    </p>
                    <div class="flex flex-wrap gap-lg">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-primary text-on-primary px-xl py-3 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all flex items-center gap-sm shadow-lg shadow-primary/20">
                                <span class="material-symbols-outlined text-[20px]">dashboard</span>
                                Buka Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="bg-primary text-on-primary px-xl py-3 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all flex items-center gap-sm shadow-lg shadow-primary/20">
                                <span class="material-symbols-outlined text-[20px]">person_add</span>
                                Daftar Sekarang
                            </a>
                            <a href="{{ route('login') }}" class="border-2 border-outline-variant text-on-surface px-xl py-3 rounded-full text-label-md font-bold hover:bg-surface-container transition-all flex items-center gap-sm">
                                <span class="material-symbols-outlined text-[20px]">login</span>
                                Masuk
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="hidden lg:flex items-center justify-center">
                    <div class="grid grid-cols-2 gap-lg">
                        <div class="bg-surface-container-lowest rounded-2xl shadow-sm p-xl text-center hover:shadow-md transition-all">
                            <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center text-primary mx-auto mb-lg">
                                <span class="material-symbols-outlined text-[32px]">description</span>
                            </div>
                            <p class="font-headline-sm font-bold text-on-surface">5+</p>
                            <p class="text-label-sm text-on-surface-variant">Jenis Surat</p>
                        </div>
                        <div class="bg-surface-container-lowest rounded-2xl shadow-sm p-xl text-center hover:shadow-md transition-all mt-xl">
                            <div class="w-14 h-14 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary mx-auto mb-lg">
                                <span class="material-symbols-outlined text-[32px]">account_balance</span>
                            </div>
                            <p class="font-headline-sm font-bold text-on-surface">Transparan</p>
                            <p class="text-label-sm text-on-surface-variant">APBDes Publik</p>
                        </div>
                        <div class="bg-surface-container-lowest rounded-2xl shadow-sm p-xl text-center hover:shadow-md transition-all">
                            <div class="w-14 h-14 rounded-xl bg-error/10 flex items-center justify-center text-error mx-auto mb-lg">
                                <span class="material-symbols-outlined text-[32px]">campaign</span>
                            </div>
                            <p class="font-headline-sm font-bold text-on-surface">Aspirasi</p>
                            <p class="text-label-sm text-on-surface-variant">Pengaduan Online</p>
                        </div>
                        <div class="bg-surface-container-lowest rounded-2xl shadow-sm p-xl text-center hover:shadow-md transition-all mt-xl">
                            <div class="w-14 h-14 rounded-xl bg-on-tertiary-container/10 flex items-center justify-center text-on-tertiary-container mx-auto mb-lg">
                                <span class="material-symbols-outlined text-[32px]">newspaper</span>
                            </div>
                            <p class="font-headline-sm font-bold text-on-surface">Informasi</p>
                            <p class="text-label-sm text-on-surface-variant">Berita & Agenda</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur -->
    <section class="py-32 bg-surface-container-low">
        <div class="max-w-6xl mx-auto px-lg">
            <div class="text-center mb-xl">
                <h2 class="text-display-sm font-bold text-on-surface mb-md">Fitur Unggulan</h2>
                <p class="text-body-lg text-on-surface-variant max-w-2xl mx-auto">Layanan digital yang memudahkan warga dan perangkat desa dalam pengelolaan administrasi</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-lg">
                <div class="bg-surface-container-lowest rounded-xl shadow-sm p-xl">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary mb-lg">
                        <span class="material-symbols-outlined">groups</span>
                    </div>
                    <h3 class="text-title-md font-bold text-on-surface mb-sm">Kependudukan</h3>
                    <p class="text-body-sm text-on-surface-variant">Data penduduk dan keluarga terintegrasi, import Excel, update otomatis</p>
                </div>
                <div class="bg-surface-container-lowest rounded-xl shadow-sm p-xl">
                    <div class="w-12 h-12 rounded-xl bg-on-tertiary-container/10 flex items-center justify-center text-on-tertiary-container mb-lg">
                        <span class="material-symbols-outlined">description</span>
                    </div>
                    <h3 class="text-title-md font-bold text-on-surface mb-sm">Pelayanan Surat</h3>
                    <p class="text-body-sm text-on-surface-variant">Ajukan surat online, tracking status, cetak PDF dengan kop resmi</p>
                </div>
                <div class="bg-surface-container-lowest rounded-xl shadow-sm p-xl">
                    <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary mb-lg">
                        <span class="material-symbols-outlined">account_balance</span>
                    </div>
                    <h3 class="text-title-md font-bold text-on-surface mb-sm">APBDes Transparan</h3>
                    <p class="text-body-sm text-on-surface-variant">Anggaran desa terbuka untuk publik, grafik pendapatan & belanja</p>
                </div>
                <div class="bg-surface-container-lowest rounded-xl shadow-sm p-xl">
                    <div class="w-12 h-12 rounded-xl bg-error/10 flex items-center justify-center text-error mb-lg">
                        <span class="material-symbols-outlined">campaign</span>
                    </div>
                    <h3 class="text-title-md font-bold text-on-surface mb-sm">Pengaduan</h3>
                    <p class="text-body-sm text-on-surface-variant">Lapor dan pantau pengaduan, dari diterima hingga selesai ditindaklanjuti</p>
                </div>
            </div>

            <div class="flex justify-center gap-lg mt-xl flex-wrap">
                <a href="{{ route('informasi.publik') }}" class="bg-surface-container-lowest border border-outline-variant text-on-surface px-xl py-3 rounded-full text-label-md font-bold hover:bg-surface-container transition-all flex items-center gap-sm">
                    <span class="material-symbols-outlined text-[18px]">newspaper</span>
                    Informasi Desa
                </a>
                <a href="{{ route('apbdes.publik') }}" class="bg-surface-container-lowest border border-outline-variant text-on-surface px-xl py-3 rounded-full text-label-md font-bold hover:bg-surface-container transition-all flex items-center gap-sm">
                    <span class="material-symbols-outlined text-[18px]">account_balance</span>
                    APBDes Publik
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary text-on-primary py-xl">
        <div class="max-w-6xl mx-auto px-lg text-center">
            <div class="flex items-center justify-center gap-md mb-lg">
                <div class="w-10 h-10 rounded-lg bg-on-primary/20 flex items-center justify-center font-bold text-title-md">S</div>
                <span class="font-headline-sm font-bold">SIPANDA</span>
            </div>
            <p class="text-body-sm text-on-primary/80 mb-md">Sistem Informasi Puspamukti Smart Village</p>
            <p class="text-label-sm text-on-primary/60">Desa Puspamukti, Kecamatan Cigalontang, Kabupaten Tasikmalaya</p>
            <p class="text-label-sm text-on-primary/60 mt-xs">© {{ date('Y') }} KKN 05 — All rights reserved</p>
        </div>
    </footer>
</body>
</html>

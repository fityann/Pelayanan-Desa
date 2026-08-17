<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <title>SILAPU - Puspamukti Smart Village</title>

    <!-- Favicon / Logo Tab -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-desa-puspamukti.jpg') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo-desa-puspamukti.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-desa-puspamukti.jpg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
</head>
<body class="bg-slate-50 font-sans text-slate-800 min-h-screen">
    <!-- Navbar -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-emerald-900/90 backdrop-blur-xl border-b border-emerald-700/40 shadow-lg shadow-emerald-950/20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 rounded-[16px] bg-white border-2 border-[#D8B84C] p-1 shadow-md shadow-black/20 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                    <img src="{{ asset('images/logo-desa-puspamukti.jpg') }}" alt="Logo Puspamukti" class="w-full h-full object-contain rounded-[10px]">
                </div>
                <div>
                    <span class="font-black text-lg text-white tracking-tight leading-none block">SILAPU</span>
                    <span class="text-[11px] font-medium text-emerald-300/80 leading-none block">Puspamukti Smart Village</span>
                </div>
            </a>

            <div class="flex items-center space-x-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-4 py-2 rounded-xl text-xs sm:text-sm font-bold hover:from-emerald-600 hover:to-teal-600 transition-all flex items-center space-x-1.5 shadow-md shadow-emerald-500/30">
                        <span class="material-symbols-outlined text-base">dashboard</span>
                        <span>Dashboard Admin</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-600 text-white px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold hover:from-emerald-600 hover:to-teal-600 transition-all flex items-center space-x-2 shadow-lg shadow-emerald-500/25">
                        <span class="material-symbols-outlined text-base">badge</span>
                        <span>Masuk Warga (NIK & Nama)</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-28 pb-20 md:pt-36 md:pb-28 bg-gradient-to-br from-emerald-950 via-teal-900 to-slate-900 text-white overflow-hidden">
        <!-- Decorative Glows -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-teal-400/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-7xl h-full bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-cyan-500/10 via-transparent to-transparent pointer-events-none"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                <div class="lg:col-span-7">
                    <div class="inline-flex items-center space-x-2 px-4 py-1.5 bg-emerald-500/15 border border-emerald-400/30 rounded-full text-emerald-300 text-xs font-bold mb-6">
                        <span class="material-symbols-outlined text-sm">verified</span>
                        <span>Sistem Informasi Desa Terpadu</span>
                    </div>

                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black leading-tight tracking-tight mb-6">
                        Desa Puspamukti<br/>
                        <span class="bg-gradient-to-r from-emerald-300 via-teal-200 to-cyan-300 bg-clip-text text-transparent">Smart Village</span>
                    </h1>

                    <p class="text-slate-300 text-base sm:text-lg mb-8 max-w-xl leading-relaxed">
                        Platform digital terpadu untuk pelayanan administrasi desa, transparansi APBDes, 
                        pengaduan masyarakat, dan informasi desa — Desa Puspamukti, Kecamatan Cigalontang, Kabupaten Tasikmalaya.
                    </p>

                    <div class="flex flex-wrap gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 text-white px-7 py-3.5 rounded-xl font-extrabold text-sm sm:text-base hover:from-emerald-600 hover:to-teal-600 transition-all flex items-center space-x-2 shadow-xl shadow-emerald-500/30 hover:-translate-y-0.5">
                                <span class="material-symbols-outlined text-xl">dashboard</span>
                                <span>Buka Dashboard Admin</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 text-white px-7 py-3.5 rounded-xl font-extrabold text-sm sm:text-base hover:from-emerald-600 hover:to-teal-600 transition-all flex items-center space-x-2 shadow-xl shadow-emerald-500/30 hover:-translate-y-0.5">
                                <span class="material-symbols-outlined text-xl">badge</span>
                                <span>Masuk Warga (NIK & Nama KTP)</span>
                            </a>
                        @endauth
                    </div>
                </div>

                <div class="lg:col-span-5 hidden lg:block">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white/10 backdrop-blur-xl border border-white/15 p-6 rounded-2xl text-center hover:bg-white/15 transition-all">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 flex items-center justify-center text-emerald-300 mx-auto mb-3">
                                <span class="material-symbols-outlined text-3xl">description</span>
                            </div>
                            <p class="text-2xl font-black text-white">5+</p>
                            <p class="text-xs text-emerald-200/80 font-medium">Jenis Surat</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-xl border border-white/15 p-6 rounded-2xl text-center hover:bg-white/15 transition-all translate-y-6">
                            <div class="w-14 h-14 rounded-2xl bg-teal-500/20 flex items-center justify-center text-teal-300 mx-auto mb-3">
                                <span class="material-symbols-outlined text-3xl">account_balance</span>
                            </div>
                            <p class="text-xl font-black text-white">Transparan</p>
                            <p class="text-xs text-teal-200/80 font-medium">APBDes Publik</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-xl border border-white/15 p-6 rounded-2xl text-center hover:bg-white/15 transition-all">
                            <div class="w-14 h-14 rounded-2xl bg-cyan-500/20 flex items-center justify-center text-cyan-300 mx-auto mb-3">
                                <span class="material-symbols-outlined text-3xl">campaign</span>
                            </div>
                            <p class="text-xl font-black text-white">Aspirasi</p>
                            <p class="text-xs text-cyan-200/80 font-medium">Pengaduan Online</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-xl border border-white/15 p-6 rounded-2xl text-center hover:bg-white/15 transition-all translate-y-6">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 flex items-center justify-center text-emerald-300 mx-auto mb-3">
                                <span class="material-symbols-outlined text-3xl">newspaper</span>
                            </div>
                            <p class="text-xl font-black text-white">Informasi</p>
                            <p class="text-xs text-emerald-200/80 font-medium">Berita & Agenda</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Section -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 mb-3 tracking-tight">Fitur Unggulan Layanan</h2>
                <p class="text-slate-500 text-sm sm:text-base max-w-2xl mx-auto">Layanan digital terpadu untuk memudahkan seluruh warga Desa Puspamukti</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60 hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-2xl">groups</span>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">Kependudukan</h3>
                    <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">Data penduduk terintegrasi NIK & KTP dengan update otomatis.</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60 hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-2xl">description</span>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">Pelayanan Surat</h3>
                    <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">Pengajuan surat keterangan cepat, tracking status, dan unduh PDF.</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60 hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-xl bg-cyan-100 text-cyan-700 flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-2xl">account_balance</span>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">APBDes Transparan</h3>
                    <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">Transparansi anggaran belanja dan pendapatan desa untuk warga.</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60 hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-2xl">campaign</span>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">Pengaduan Online</h3>
                    <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">Laporan pengaduan warga ditindaklanjuti cepat 1x24 jam.</p>
                </div>
            </div>

            <div class="flex justify-center gap-4 mt-10 flex-wrap">
                <a href="{{ route('informasi.publik') }}" class="bg-white border border-slate-300 text-slate-700 hover:text-emerald-700 px-6 py-3 rounded-xl font-bold text-xs sm:text-sm shadow-sm hover:shadow transition-all flex items-center space-x-2">
                    <span class="material-symbols-outlined text-base">newspaper</span>
                    <span>Informasi Desa</span>
                </a>
                <a href="{{ route('apbdes.publik') }}" class="bg-white border border-slate-300 text-slate-700 hover:text-emerald-700 px-6 py-3 rounded-xl font-bold text-xs sm:text-sm shadow-sm hover:shadow transition-all flex items-center space-x-2">
                    <span class="material-symbols-outlined text-base">account_balance</span>
                    <span>APBDes Publik</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-12 border-t border-slate-800">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex items-center justify-center space-x-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center font-black text-white text-lg">S</div>
                <span class="font-black text-xl tracking-tight">SILAPU</span>
            </div>
            <p class="text-sm text-slate-400 mb-2">Sistem Informasi Puspamukti Smart Village</p>
            <p class="text-xs text-slate-500">Desa Puspamukti, Kecamatan Cigalontang, Kabupaten Tasikmalaya</p>
            <p class="text-xs text-slate-600 mt-4">&copy; {{ date('Y') }} KKN 05 — All rights reserved</p>
        </div>
    </footer>
</body>
</html>

<x-guest-layout>
    <div class="min-h-screen relative flex flex-col justify-between bg-slate-950 font-sans overflow-x-hidden">
        <!-- Fullscreen Background Village Image with Subtle Dark Overlay -->
        <div class="fixed inset-0 z-0">
            <img src="{{ asset('images/bg-desa-puspamukti.jpg') }}" alt="Desa Puspamukti" class="w-full h-full object-cover object-center filter brightness-75 blur-[0.5px]">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/40 to-emerald-950/40"></div>
        </div>

        <!-- Top Floating Header -->
        <header class="relative z-20 w-full px-4 sm:px-8 py-4 flex items-center justify-between">
            <a href="/" class="inline-flex items-center space-x-3 group bg-slate-900/60 backdrop-blur-xl border border-white/15 px-4 py-2 rounded-2xl hover:bg-slate-900/80 transition-all shadow-lg">
                <span class="material-symbols-outlined text-white text-xl group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
                <img src="{{ asset('images/logo-desa-puspamukti.jpg') }}" alt="Logo Desa Puspamukti" class="w-8 h-8 rounded-xl object-contain bg-white p-0.5 shadow-sm">
                <div>
                    <span class="text-sm font-black tracking-tight text-white block leading-none">SILAPU</span>
                    <span class="text-[10px] font-semibold text-emerald-300 block leading-tight">Desa Puspamukti</span>
                </div>
            </a>

            <div class="inline-flex items-center space-x-2 bg-emerald-900/50 backdrop-blur-xl border border-emerald-500/40 px-3.5 py-1.5 rounded-full text-emerald-200 text-xs font-semibold shadow-lg">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Portal Layanan Warga</span>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="relative z-10 flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8">
            <div class="w-full max-w-4xl mx-auto">
                <!-- Dual Pane Glass Card (Clean White Card on Both Mobile & Desktop) -->
                <div class="bg-white/95 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/40 overflow-hidden grid grid-cols-1 md:grid-cols-12 transition-all duration-300">
                    
                    <!-- Left Hero Banner (Desktop: Full Left Column, Mobile: Top Banner) -->
                    <div class="md:col-span-5 relative bg-gradient-to-br from-emerald-900 via-teal-900 to-slate-900 p-6 sm:p-8 flex flex-col justify-between text-white text-left overflow-hidden">
                        <div class="absolute inset-0 bg-cover bg-center opacity-25 mix-blend-overlay" style="background-image: url('{{ asset('images/bg-desa-puspamukti.jpg') }}');"></div>
                        <div class="absolute -top-24 -left-24 w-64 h-64 bg-emerald-500/30 rounded-full blur-3xl pointer-events-none"></div>
                        
                        <div class="relative z-10 flex flex-row md:flex-col items-center md:items-start space-x-4 md:space-x-0 text-left my-auto md:my-0">
                            <!-- Logo Box at Top Left -->
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-white p-2 shadow-xl border border-emerald-100/80 flex-shrink-0 flex items-center justify-center md:mb-6 group hover:scale-105 transition-transform duration-300">
                                <img src="{{ asset('images/logo-desa-puspamukti.jpg') }}" alt="Emblem Puspamukti" class="w-full h-full object-contain rounded-xl">
                            </div>
                            <div class="text-left">
                                <h2 class="text-xl sm:text-2xl font-black tracking-tight leading-tight text-white text-left">DESA PUSPAMUKTI</h2>
                                <p class="text-[11px] sm:text-xs font-bold text-emerald-300 uppercase tracking-widest text-left mt-0.5 md:mt-1">Sejati, Diri Bertaji</p>
                                <p class="hidden md:block text-xs text-slate-300 leading-relaxed text-left mt-3">Sistem Informasi Pelayanan Desa Terpadu untuk kemudahan administrasi kependudukan warga.</p>
                            </div>
                        </div>

                        <div class="hidden md:block relative z-10 pt-6 border-t border-white/10 space-y-2.5 text-left">
                            <div class="flex items-center space-x-2 text-xs text-emerald-200 font-medium">
                                <span class="material-symbols-outlined text-emerald-400 text-base">verified_user</span>
                                <span>Verifikasi NIK & KTP Otomatis</span>
                            </div>
                            <div class="flex items-center space-x-2 text-xs text-teal-200 font-medium">
                                <span class="material-symbols-outlined text-teal-400 text-base">security</span>
                                <span>Aman, Cepat & Terintegrasi</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Login Form (Clean White Form on Both Mobile & Desktop) -->
                    <div class="col-span-1 md:col-span-7 p-6 sm:p-10 flex flex-col justify-center bg-white">
                        <div class="text-left mb-6">
                            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Masuk Layanan Warga</h2>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1">Gunakan <strong class="text-emerald-700 font-semibold">NIK</strong> dan <strong class="text-emerald-700 font-semibold">Nama Lengkap sesuai KTP</strong>.</p>
                        </div>

                        <x-auth-session-status class="mb-6" :status="session('status')" />

                        @if ($errors->any())
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl flex items-start space-x-3 mb-6">
                                <span class="material-symbols-outlined text-red-500 mt-0.5">error</span>
                                <div>
                                    @foreach ($errors->all() as $error)
                                        <p class="text-xs sm:text-sm font-semibold text-red-800">{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="space-y-4">
                            @csrf

                            <!-- NIK Input -->
                            <div>
                                <label for="nik" class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5 flex items-center justify-between">
                                    <span>NIK (16 Digit KTP) <span class="text-red-500">*</span></span>
                                    <span id="nik-counter" class="text-xs font-mono text-emerald-600 font-bold">0/16</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <span class="material-symbols-outlined text-xl">badge</span>
                                    </div>
                                    <input type="text" id="nik" name="nik" value="{{ old('nik') }}" required autofocus inputmode="numeric" maxlength="16"
                                           pattern="\d{16}"
                                           oninput="document.getElementById('nik-counter').textContent = this.value.length + '/16'"
                                           class="w-full pl-11 pr-4 py-3 sm:py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm font-medium placeholder-slate-400 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                                           placeholder="Masukkan 16 digit NIK">
                                </div>
                            </div>

                            <!-- Nama Lengkap Input -->
                            <div>
                                <label for="nama" class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                                    Nama Lengkap Sesuai KTP <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <span class="material-symbols-outlined text-xl">person</span>
                                    </div>
                                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required maxlength="255"
                                           class="w-full pl-11 pr-4 py-3 sm:py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm font-medium placeholder-slate-400 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                                           placeholder="Nama lengkap sesuai KTP">
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 hover:from-emerald-600 hover:via-teal-600 hover:to-cyan-600 text-white font-extrabold py-3.5 sm:py-4 px-6 rounded-xl sm:rounded-2xl shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center space-x-2 text-base mt-2">
                                <span class="material-symbols-outlined text-xl">login</span>
                                <span>Masuk Layanan Sekarang</span>
                            </button>
                        </form>

                        <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                            <a href="/" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-slate-500 hover:text-emerald-700 transition-colors">
                                <span class="material-symbols-outlined text-base">arrow_back</span>
                                <span>Kembali ke Beranda Utama</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Bottom Footer -->
        <footer class="relative z-10 py-4 text-center">
            <p class="text-xs text-slate-300 font-medium">
                &copy; {{ date('Y') }} DESA PUSPAMUKTI — <span class="text-emerald-400">Sejati, Diri Bertaji</span>
            </p>
        </footer>
    </div>
</x-guest-layout>

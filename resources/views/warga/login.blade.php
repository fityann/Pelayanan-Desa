<x-guest-layout>
    <div class="min-h-screen relative flex flex-col justify-between bg-slate-950 font-sans overflow-x-hidden">
        <!-- Fullscreen Background Village Image with Subtle Dark Overlay -->
        <div class="fixed inset-0 z-0">
            <img src="{{ asset('images/bg-desa-puspamukti.jpg') }}" alt="Desa Puspamukti" class="w-full h-full object-cover object-center filter brightness-75 blur-[0.5px]">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/40 to-emerald-950/40"></div>
        </div>

        <!-- Top Floating Header -->
        <header class="relative z-20 w-full px-4 sm:px-8 py-4 flex items-center justify-between">
            <a href="{{ route('warga.rt.landing', ['rt' => $rt]) }}" class="inline-flex items-center space-x-3 group bg-slate-900/60 backdrop-blur-xl border border-white/15 px-4 py-2 rounded-2xl hover:bg-slate-900/80 transition-all shadow-lg">
                <span class="material-symbols-outlined text-white text-xl group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
                <img src="{{ asset('images/logo-desa-puspamukti.jpg') }}" alt="Logo Desa Puspamukti" class="w-8 h-8 rounded-xl object-contain bg-white p-0.5 shadow-sm">
                <div>
                    <span class="text-sm font-black tracking-tight text-white block leading-none">SILAPU</span>
                    <span class="text-[10px] font-semibold text-emerald-300 block leading-tight">Desa Puspamukti</span>
                </div>
            </a>

            <div class="inline-flex items-center space-x-2 bg-emerald-950/70 backdrop-blur-xl border border-amber-400/50 px-3.5 py-1.5 rounded-full text-amber-300 text-xs font-bold shadow-lg">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                <span>Layanan Warga RT {{ $rt }}</span>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="relative z-10 flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8">
            <div class="w-full max-w-4xl mx-auto">
                <!-- Dual Pane Glass Card (Clean White Card on Both Mobile & Desktop) -->
                <div class="bg-white/95 backdrop-blur-2xl rounded-3xl shadow-2xl border border-amber-300/40 overflow-hidden grid grid-cols-1 md:grid-cols-12 transition-all duration-300">
                    
                    <!-- Left Hero Banner (#6A3297 Green & #D8B84C Gold Theme) -->
                    <div class="md:col-span-5 relative bg-[#6A3297] p-6 sm:p-8 flex flex-col justify-between text-white text-left overflow-hidden">
                        <div class="absolute inset-0 bg-cover bg-center opacity-20 mix-blend-overlay" style="background-image: url('{{ asset('images/bg-desa-puspamukti.jpg') }}');"></div>
                        <div class="absolute -top-24 -left-24 w-64 h-64 bg-[#D8B84C]/20 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="relative z-10">
                            <!-- Logo and Title Side-by-Side (Pinggir Logo) -->
                            <div class="flex items-center space-x-3.5 mb-5">
                                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-white p-1.5 shadow-xl border-2 border-[#D8B84C] flex-shrink-0 flex items-center justify-center group hover:scale-105 transition-transform duration-300">
                                    <img src="{{ asset('images/logo-desa-puspamukti.jpg') }}" alt="Emblem Puspamukti" class="w-full h-full object-contain rounded-xl">
                                </div>
                                <div class="text-left">
                                    <h2 class="text-xl sm:text-2xl font-black tracking-tight leading-tight text-white">DESA PUSPAMUKTI</h2>
                                    <span class="inline-block text-[11px] sm:text-xs font-black text-[#F0D878] bg-[#2A3520]/70 px-2.5 py-0.5 rounded-full border border-[#D8B84C]/40 uppercase tracking-widest mt-1">
                                        RT {{ $rt }}
                                    </span>
                                </div>
                            </div>

                            <!-- Description Underneath -->
                            <p class="text-xs sm:text-sm text-slate-100/90 leading-relaxed text-left font-medium">
                                Sistem Informasi Pelayanan Desa Terpadu Puspamukti untuk kemudahan administrasi kependudukan warga, pengajuan surat online.
                            </p>
                        </div>

                        <!-- Footer Info in Left Panel -->
                        <div class="relative z-10 pt-5 mt-6 border-t border-white/20 text-left">
                            <p class="text-[11px] text-slate-200/90 font-semibold flex items-center space-x-1.5">
                                <span class="material-symbols-outlined text-sm text-[#F0D878]">location_on</span>
                                <span>Kecamatan Cigalontang · Kabupaten Tasikmalaya</span>
                            </p>
                        </div>
                    </div>

                    <!-- Right Form Container -->
                    <div class="md:col-span-7 p-6 sm:p-10 flex flex-col justify-center">
                        @if ($errors->any() || session('error') || session('info') || session('warning'))
                            <div class="mb-5 p-4 rounded-2xl bg-amber-500/15 border border-amber-500/40 flex items-start space-x-3 text-slate-900 shadow-sm animate-shake">
                                <div class="bg-red-100 border border-red-200 p-1.5 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <span class="material-symbols-outlined text-xl font-black text-red-600">warning</span>
                                </div>
                                <div class="text-xs font-bold leading-relaxed pt-0.5">
                                    <p>{{ $errors->first() ?: (session('error') ?? session('info') ?? session('warning')) }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="mb-6">
                            <h3 class="text-xl font-black text-slate-900 tracking-tight">Masuk Layanan Warga</h3>
                            <p class="text-xs text-slate-500 mt-1">Masukkan NIK dan Nama Lengkap sesuai KTP Anda untuk mulai mengurus surat & pengaduan di RT {{ $rt }}.</p>
                        </div>

                        <form method="POST" action="{{ route('warga.rt.login.authenticate', ['rt' => $rt]) }}" class="space-y-4">
                            @csrf

                            <!-- NIK Field -->
                            <div>
                                <label for="nik" class="block text-xs font-bold text-slate-800 mb-1.5">
                                    NIK KTP (16 Digit) <span class="text-[#D8B84C]">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <span class="material-symbols-outlined text-xl">badge</span>
                                    </div>
                                    <input type="text" id="nik" name="nik" value="{{ old('nik') }}" required maxlength="16" pattern="\d{16}"
                                           class="w-full pl-11 pr-4 py-3 sm:py-3.5 bg-slate-50 border {{ $errors->has('nik') ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-slate-200' }} rounded-xl text-slate-900 text-sm font-medium placeholder-slate-400 focus:bg-white focus:border-[#6A3297] focus:ring-4 focus:ring-[#6A3297]/10 transition-all"
                                           placeholder="Contoh: 3206xxxxxxxxxxxx">
                                </div>
                            </div>

                            <!-- Nama Field -->
                            <div>
                                <label for="nama" class="block text-xs font-bold text-slate-800 mb-1.5">
                                    Nama Lengkap Sesuai KTP <span class="text-[#D8B84C]">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <span class="material-symbols-outlined text-xl">person</span>
                                    </div>
                                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required maxlength="255"
                                           class="w-full pl-11 pr-4 py-3 sm:py-3.5 bg-slate-50 border {{ $errors->has('nama') ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-slate-200' }} rounded-xl text-slate-900 text-sm font-medium placeholder-slate-400 focus:bg-white focus:border-[#6A3297] focus:ring-4 focus:ring-[#6A3297]/10 transition-all"
                                           placeholder="Nama lengkap sesuai KTP">
                                </div>
                            </div>

                            <!-- Submit Button (#6A3297 Solid Green) -->
                            <button type="submit" class="w-full bg-[#6A3297] hover:bg-[#4E2472] text-white font-black py-3.5 sm:py-4 px-6 rounded-xl sm:rounded-2xl shadow-xl shadow-[#6A3297]/30 border border-[#D8B84C]/40 hover:scale-[1.01] transition-all duration-200 flex items-center justify-center space-x-2 text-base mt-2">
                                <span class="material-symbols-outlined text-xl text-[#F0D878]">login</span>
                                <span>Masuk Layanan Sekarang</span>
                            </button>
                        </form>


                    </div>
                </div>
            </div>
        </main>

        <!-- Bottom Footer -->
        <footer class="relative z-10 py-4 text-center">
            <p class="text-xs text-slate-300 font-medium">
                &copy; {{ date('Y') }} DESA PUSPAMUKTI — RT {{ $rt }} — <span class="text-[#F0D878]">Sejati, Diri Bertaji</span>
            </p>
        </footer>
    </div>
</x-guest-layout>

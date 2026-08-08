<x-guest-layout>
    <div class="min-h-screen relative flex flex-col justify-between bg-slate-950 font-sans overflow-x-hidden">
        <!-- Background Village Image -->
        <div class="fixed inset-0 z-0">
            <img src="{{ asset('images/bg-desa-puspamukti.jpg') }}" alt="Desa Puspamukti" class="w-full h-full object-cover object-center filter brightness-75 blur-[0.5px]">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/40 to-emerald-950/40"></div>
        </div>

        <!-- Header Navigation -->
        <header class="relative z-20 w-full px-4 sm:px-8 py-4 flex items-center justify-between">
            <a href="/" class="inline-flex items-center space-x-3 group bg-slate-900/60 backdrop-blur-xl border border-white/15 px-4 py-2 rounded-2xl hover:bg-slate-900/80 transition-all shadow-lg">
                <span class="material-symbols-outlined text-white text-xl group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
                <img src="{{ asset('images/logo-desa-puspamukti.jpg') }}" alt="Logo Desa Puspamukti" class="w-8 h-8 rounded-xl object-contain bg-white p-0.5 shadow-sm">
                <div>
                    <span class="text-sm font-black tracking-tight text-white block leading-none">SILAPU</span>
                    <span class="text-[10px] font-semibold text-emerald-300 block leading-tight">Desa Puspamukti</span>
                </div>
            </a>

            <div class="inline-flex items-center space-x-2 bg-amber-900/50 backdrop-blur-xl border border-amber-500/40 px-3.5 py-1.5 rounded-full text-amber-200 text-xs font-semibold shadow-lg">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                <span>Portal Khusus Perangkat Desa (Portal 1)</span>
            </div>
        </header>

        <!-- Main Form Area -->
        <main class="relative z-10 flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8">
            <div class="w-full max-w-md mx-auto">
                <div class="bg-white/95 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/40 p-6 sm:p-10 transition-all">
                    
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center mx-auto mb-4 border border-amber-200 shadow-md">
                            <span class="material-symbols-outlined text-3xl">key</span>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Verifikasi Akses Admin</h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Masukkan **Kode Khusus Akses Admin** untuk membuka portal login Perangkat Desa.</p>
                    </div>

                    @if (session('error'))
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl flex items-center space-x-3 mb-6 shadow-sm">
                            <span class="material-symbols-outlined text-red-500">error</span>
                            <p class="text-xs sm:text-sm font-semibold text-red-800">{{ session('error') }}</p>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl flex items-start space-x-3 mb-6 shadow-sm">
                            <span class="material-symbols-outlined text-red-500 mt-0.5">error</span>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <p class="text-xs sm:text-sm font-semibold text-red-800">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.gate.verify') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="access_code" class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5 flex items-center justify-between">
                                <span>Kode Khusus Akses Admin <span class="text-red-500">*</span></span>
                                <span class="text-[11px] text-amber-700 font-bold bg-amber-100 px-2 py-0.5 rounded-full">Kode Keamanan</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined text-xl">lock_open</span>
                                </div>
                                <input type="password" id="access_code" name="access_code" required autofocus
                                       class="w-full pl-11 pr-4 py-3 sm:py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm font-mono tracking-widest font-bold placeholder-slate-400 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all"
                                       placeholder="Masukkan Kode Khusus">
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1.5">Default kode akses: <code class="bg-slate-100 font-mono px-1.5 py-0.5 rounded text-slate-800 font-bold">PUSPAMUKTI2026</code></p>
                        </div>

                        <button type="submit"
                                class="w-full bg-gradient-to-r from-amber-600 via-amber-700 to-orange-700 hover:from-amber-700 hover:to-orange-800 text-white font-extrabold py-3.5 px-6 rounded-2xl shadow-lg shadow-amber-600/25 hover:shadow-amber-600/40 transition-all flex items-center justify-center space-x-2 text-base mt-2">
                            <span class="material-symbols-outlined text-xl">verified_user</span>
                            <span>Verifikasi Kode Akses</span>
                        </button>
                    </form>

                    <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                        <a href="/" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-slate-500 hover:text-emerald-700 transition-colors">
                            <span class="material-symbols-outlined text-base">arrow_back</span>
                            <span>Kembali ke Beranda Warga</span>
                        </a>
                    </div>
                </div>
            </div>
        </main>

        <footer class="relative z-10 py-4 text-center">
            <p class="text-xs text-slate-300 font-medium">
                &copy; {{ date('Y') }} DESA PUSPAMUKTI — <span class="text-emerald-400">Portal Keamanan Admin</span>
            </p>
        </footer>
    </div>
</x-guest-layout>

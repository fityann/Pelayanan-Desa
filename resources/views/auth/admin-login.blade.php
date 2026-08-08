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

            <div class="inline-flex items-center space-x-2 bg-emerald-950/70 backdrop-blur-xl border border-amber-400/50 px-3.5 py-1.5 rounded-full text-amber-300 text-xs font-bold shadow-lg">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                <span>Portal Login Admin (Perangkat Desa)</span>
            </div>
        </header>

        <!-- Main Form Area -->
        <main class="relative z-10 flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8">
            <div class="w-full max-w-md mx-auto">
                <div class="bg-white/95 backdrop-blur-2xl rounded-3xl shadow-2xl border border-amber-300/40 p-6 sm:p-10 transition-all">
                    
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-amber-500 to-yellow-300 text-emerald-950 flex items-center justify-center mx-auto mb-4 border border-amber-200 shadow-lg shadow-amber-500/20">
                            <span class="material-symbols-outlined text-3xl font-black">admin_panel_settings</span>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Login Perangkat Desa</h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Masukkan kredensial akun admin/staf desa Anda untuk masuk ke Dashboard.</p>
                    </div>

                    @if (session('success'))
                        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl flex items-center space-x-3 mb-6 shadow-sm">
                            <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                            <p class="text-xs sm:text-sm font-semibold text-emerald-900">{{ session('success') }}</p>
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

                    <form method="POST" action="{{ route('admin.login.authenticate') }}" class="space-y-4">
                        @csrf

                        <!-- Login Field -->
                        <div>
                            <label for="login" class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                                Email atau NIK Admin <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined text-xl">person</span>
                                </div>
                                <input type="text" id="login" name="login" value="{{ old('login', session('admin_pending_nik')) }}" required
                                       class="w-full pl-11 pr-4 py-3 sm:py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm font-medium placeholder-slate-400 focus:bg-white focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                                       placeholder="Masukkan Email atau NIK Admin">
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                                Password Admin <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined text-xl">lock</span>
                                </div>
                                <input type="password" id="password" name="password" required autofocus autocomplete="current-password"
                                       class="w-full pl-11 pr-10 py-3 sm:py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm font-medium placeholder-slate-400 focus:bg-white focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                                       placeholder="Masukkan password admin">
                                <button type="button" onclick="toggleAdminPass()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                    <span id="admin-eye" class="material-symbols-outlined text-lg">visibility</span>
                                </button>
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full bg-[#4B5D3A] hover:bg-[#364329] text-white font-black py-3.5 px-6 rounded-2xl shadow-xl shadow-[#4B5D3A]/30 border border-[#D8B84C]/40 hover:scale-[1.01] transition-all flex items-center justify-center space-x-2 text-base mt-2">
                            <span class="material-symbols-outlined text-xl text-[#F0D878]">dashboard</span>
                            <span>Masuk ke Dashboard Admin</span>
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
                &copy; {{ date('Y') }} DESA PUSPAMUKTI — <span class="text-emerald-400">Portal Administrasi Desa</span>
            </p>
        </footer>
    </div>

    <script>
    function toggleAdminPass() {
        const inp = document.getElementById('password');
        const eye = document.getElementById('admin-eye');
        if (inp.type === 'password') {
            inp.type = 'text';
            eye.textContent = 'visibility_off';
        } else {
            inp.type = 'password';
            eye.textContent = 'visibility';
        }
    }
    </script>
</x-guest-layout>

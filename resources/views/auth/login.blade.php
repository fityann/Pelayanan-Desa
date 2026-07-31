<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-surface to-surface-container-low flex items-center justify-center p-lg">
        <div class="w-full max-w-md">
            <div class="text-center mb-xl">
                <div class="w-16 h-16 rounded-2xl bg-primary flex items-center justify-center text-on-primary font-bold text-headline-md mx-auto mb-lg shadow-lg shadow-primary/20">S</div>
                <h1 class="text-headline-md font-bold text-on-surface">Masuk ke SIPANDA</h1>
                <p class="text-body-sm text-on-surface-variant mt-xs">Sistem Informasi Puspamukti Smart Village</p>
            </div>

            <div class="bg-surface-container-lowest rounded-2xl shadow-sm p-xl">
                <x-auth-session-status class="mb-lg" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-lg">
                        <label class="text-label-sm font-bold text-on-surface block mb-xs">NIK / Email</label>
                        <input type="text" name="login" value="{{ old('login') }}" required autofocus class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Masukkan NIK atau Email">
                        <x-input-error :messages="$errors->get('login')" class="mt-2" />
                    </div>

                    <div class="mb-lg">
                        <label class="text-label-sm font-bold text-on-surface block mb-xs">Password</label>
                        <input type="password" name="password" required autocomplete="current-password" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Masukkan password">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between mb-xl">
                        <label class="flex items-center gap-sm">
                            <input type="checkbox" name="remember" class="rounded border-outline-variant text-primary focus:ring-primary">
                            <span class="text-label-sm text-on-surface">Ingat saya</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-label-sm font-bold text-primary hover:underline" href="{{ route('password.request') }}">Lupa password?</a>
                        @endif
                    </div>

                    <button type="submit" class="w-full bg-primary text-on-primary py-3 rounded-xl text-label-md font-bold hover:bg-primary/90 transition-all shadow-lg shadow-primary/20">Masuk</button>
                </form>

                @if (Route::has('register'))
                    <p class="text-center mt-lg text-body-sm text-on-surface-variant">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-primary font-bold hover:underline">Daftar</a>
                    </p>
                @endif

                <div class="mt-lg pt-lg border-t border-surface-variant/30 text-center">
                    <a href="/" class="text-label-sm text-on-surface-variant hover:text-primary flex items-center justify-center gap-xs">
                        <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

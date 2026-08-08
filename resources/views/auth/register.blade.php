<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-emerald-900 via-teal-900 to-slate-900 flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-hidden">
        <!-- Background decorative glows -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-teal-400/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-7xl h-full bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-cyan-500/10 via-transparent to-transparent pointer-events-none"></div>

        <div class="w-full max-w-2xl relative z-10">
            <!-- Header Brand -->
            <div class="text-center mb-8">
                <a href="/" class="inline-flex items-center space-x-3 group">
                    <div class="w-14 h-14 bg-gradient-to-tr from-emerald-500 to-teal-400 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-105 transition-transform duration-300">
                        <span class="material-symbols-outlined text-white text-3xl">holiday_village</span>
                    </div>
                    <div class="text-left">
                        <h1 class="text-2xl font-black tracking-tight text-white">SILAPU</h1>
                        <p class="text-xs font-medium text-emerald-300/80">Puspamukti Smart Village</p>
                    </div>
                </a>
            </div>

            <!-- Card Container -->
            <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-6 sm:p-10 transition-all duration-300">
                <div class="text-center mb-8">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Pendaftaran Akun Warga</h2>
                    <p class="text-sm text-gray-500 mt-2">Daftarkan akun untuk menikmati kemudahan layanan digital Desa Puspamukti.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- NIK (Top priority) -->
                    <div>
                        <label for="nik" class="block text-sm font-semibold text-gray-800 mb-1.5 flex items-center justify-between">
                            <span>NIK (16 Digit KTP) <span class="text-red-500">*</span></span>
                            <span id="nik-counter" class="text-xs text-gray-400 font-normal">0/16</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <span class="material-symbols-outlined text-xl">badge</span>
                            </div>
                            <input type="text" id="nik" name="nik" value="{{ old('nik') }}" required maxlength="16" inputmode="numeric"
                                   pattern="\d{16}"
                                   class="w-full pl-11 pr-4 py-3 bg-gray-50/80 border border-gray-200 rounded-xl text-gray-900 text-sm font-medium placeholder-gray-400 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                                   placeholder="Contoh: 332801XXXXXXXXXX" autofocus>
                        </div>
                        <!-- NIK Status Alert Badge -->
                        <div id="nik-status-box" class="mt-2.5 p-3 rounded-xl bg-slate-100 border border-slate-200/60 flex items-center space-x-2.5 transition-all">
                            <span id="nik-status-icon" class="material-symbols-outlined text-slate-400 text-lg">info</span>
                            <p id="nik-status" class="text-xs text-slate-600 font-medium">Ketik 16 digit NIK Anda untuk verifikasi otomatis dengan data kependudukan desa.</p>
                        </div>
                        <x-input-error :messages="$errors->get('nik')" class="mt-1.5" />
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-800 mb-1.5">
                            Nama Lengkap Sesuai KTP <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <span class="material-symbols-outlined text-xl">person</span>
                            </div>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required autocomplete="name"
                                   class="w-full pl-11 pr-4 py-3 bg-gray-50/80 border border-gray-200 rounded-xl text-gray-900 text-sm font-medium placeholder-gray-400 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                                   placeholder="Nama lengkap sesuai KTP">
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                    </div>

                    <!-- Email & Phone Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-800 mb-1.5">
                                Email <span class="text-xs font-normal text-gray-400">(opsional)</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <span class="material-symbols-outlined text-xl">mail</span>
                                </div>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="username"
                                       class="w-full pl-11 pr-4 py-3 bg-gray-50/80 border border-gray-200 rounded-xl text-gray-900 text-sm font-medium placeholder-gray-400 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                                       placeholder="email@contoh.com">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-800 mb-1.5">
                                No. WhatsApp <span class="text-xs font-normal text-gray-400">(opsional)</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <span class="material-symbols-outlined text-xl">call</span>
                                </div>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                       class="w-full pl-11 pr-4 py-3 bg-gray-50/80 border border-gray-200 rounded-xl text-gray-900 text-sm font-medium placeholder-gray-400 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                                       placeholder="08XXXXXXXXXX">
                            </div>
                            <x-input-error :messages="$errors->get('phone')" class="mt-1.5" />
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-semibold text-gray-800 mb-1.5">
                            Alamat <span class="text-xs font-normal text-gray-400">(opsional)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute top-3 left-3.5 flex items-center pointer-events-none text-gray-400">
                                <span class="material-symbols-outlined text-xl">home_pin</span>
                            </div>
                            <textarea id="address" name="address" rows="2"
                                      class="w-full pl-11 pr-4 py-2.5 bg-gray-50/80 border border-gray-200 rounded-xl text-gray-900 text-sm font-medium placeholder-gray-400 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                                      placeholder="Dusun / Kampung / Jalan">{{ old('address') }}</textarea>
                        </div>
                        <x-input-error :messages="$errors->get('address')" class="mt-1.5" />
                    </div>

                    <!-- RT / RW Grid -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="rt" class="block text-sm font-semibold text-gray-800 mb-1.5">
                                RT <span class="text-xs font-normal text-gray-400">(opsional)</span>
                            </label>
                            <input type="text" id="rt" name="rt" value="{{ old('rt') }}" maxlength="3"
                                   class="w-full px-4 py-3 bg-gray-50/80 border border-gray-200 rounded-xl text-gray-900 text-sm font-medium text-center placeholder-gray-400 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                                   placeholder="001">
                            <x-input-error :messages="$errors->get('rt')" class="mt-1.5" />
                        </div>

                        <div>
                            <label for="rw" class="block text-sm font-semibold text-gray-800 mb-1.5">
                                RW <span class="text-xs font-normal text-gray-400">(opsional)</span>
                            </label>
                            <input type="text" id="rw" name="rw" value="{{ old('rw') }}" maxlength="3"
                                   class="w-full px-4 py-3 bg-gray-50/80 border border-gray-200 rounded-xl text-gray-900 text-sm font-medium text-center placeholder-gray-400 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                                   placeholder="001">
                            <x-input-error :messages="$errors->get('rw')" class="mt-1.5" />
                        </div>
                    </div>

                    <!-- Password & Confirm Password Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-800 mb-1.5">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <span class="material-symbols-outlined text-xl">lock</span>
                                </div>
                                <input type="password" id="password" name="password" required autocomplete="new-password"
                                       class="w-full pl-11 pr-10 py-3 bg-gray-50/80 border border-gray-200 rounded-xl text-gray-900 text-sm font-medium placeholder-gray-400 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                                       placeholder="Minimal 8 karakter">
                                <button type="button" onclick="togglePassword('password', 'eye-icon-1')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <span id="eye-icon-1" class="material-symbols-outlined text-lg">visibility</span>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-800 mb-1.5">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <span class="material-symbols-outlined text-xl">lock_reset</span>
                                </div>
                                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                                       class="w-full pl-11 pr-10 py-3 bg-gray-50/80 border border-gray-200 rounded-xl text-gray-900 text-sm font-medium placeholder-gray-400 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                                       placeholder="Ulangi password">
                                <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-2')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <span id="eye-icon-2" class="material-symbols-outlined text-lg">visibility</span>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="pt-4 space-y-4">
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 hover:from-emerald-700 hover:via-teal-700 hover:to-cyan-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-emerald-600/30 hover:shadow-emerald-600/50 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center space-x-2 text-base">
                            <span class="material-symbols-outlined text-xl">person_add</span>
                            <span>Daftar Akun Warga</span>
                        </button>

                        <div class="text-center pt-2 border-t border-gray-100">
                            <p class="text-sm text-gray-600">
                                Sudah memiliki akun?
                                <a href="{{ route('login') }}" class="font-bold text-emerald-600 hover:text-emerald-700 hover:underline">
                                    Masuk Sekarang
                                </a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer info -->
            <p class="text-center text-xs text-emerald-200/60 mt-6">
                &copy; {{ date('Y') }} SILAPU Desa Puspamukti. Hak Cipta Dilindungi.
            </p>
        </div>
    </div>

    <script>
    let nikTimeout;
    const nikInput = document.getElementById('nik');
    const nikStatus = document.getElementById('nik-status');
    const nikStatusIcon = document.getElementById('nik-status-icon');
    const nikStatusBox = document.getElementById('nik-status-box');
    const nikCounter = document.getElementById('nik-counter');

    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    }

    nikInput.addEventListener('input', function() {
        clearTimeout(nikTimeout);
        const nik = this.value.replace(/\D/g, '').slice(0, 16);
        this.value = nik;
        nikCounter.textContent = `${nik.length}/16`;

        if (nik.length === 16) {
            nikStatus.textContent = 'Memverifikasi NIK di database desa...';
            nikStatusIcon.textContent = 'sync';
            nikStatusIcon.className = 'material-symbols-outlined text-blue-500 animate-spin text-lg';
            nikStatusBox.className = 'mt-2.5 p-3 rounded-xl bg-blue-50 border border-blue-200 flex items-center space-x-2.5 transition-all';

            nikTimeout = setTimeout(() => {
                fetch(`/cek-nik/${nik}`)
                    .then(res => res.json())
                    .then(result => {
                        if (result.found) {
                            if (result.data.nama) document.getElementById('name').value = result.data.nama;
                            if (result.data.alamat) document.getElementById('address').value = result.data.alamat;
                            if (result.data.rt) document.getElementById('rt').value = result.data.rt;
                            if (result.data.rw) document.getElementById('rw').value = result.data.rw;
                            
                            nikStatus.textContent = 'Data kependudukan terverifikasi! Nama, alamat, RT/RW telah diisi otomatis.';
                            nikStatusIcon.textContent = 'check_circle';
                            nikStatusIcon.className = 'material-symbols-outlined text-emerald-600 text-lg';
                            nikStatusBox.className = 'mt-2.5 p-3 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center space-x-2.5 transition-all';
                        } else {
                            nikStatus.textContent = 'NIK belum tercatat di database desa. Anda tetap dapat melanjutkan pendaftaran manual.';
                            nikStatusIcon.textContent = 'help_outline';
                            nikStatusIcon.className = 'material-symbols-outlined text-amber-500 text-lg';
                            nikStatusBox.className = 'mt-2.5 p-3 rounded-xl bg-amber-50 border border-amber-200 flex items-center space-x-2.5 transition-all';
                        }
                    })
                    .catch(() => {
                        nikStatus.textContent = 'Gagal memverifikasi NIK secara otomatis. Silakan isi form secara manual.';
                        nikStatusIcon.textContent = 'error_outline';
                        nikStatusIcon.className = 'material-symbols-outlined text-red-500 text-lg';
                        nikStatusBox.className = 'mt-2.5 p-3 rounded-xl bg-red-50 border border-red-200 flex items-center space-x-2.5 transition-all';
                    });
            }, 400);
        } else {
            nikStatus.textContent = 'Ketik 16 digit NIK Anda untuk verifikasi otomatis dengan data kependudukan desa.';
            nikStatusIcon.textContent = 'info';
            nikStatusIcon.className = 'material-symbols-outlined text-slate-400 text-lg';
            nikStatusBox.className = 'mt-2.5 p-3 rounded-xl bg-slate-100 border border-slate-200/60 flex items-center space-x-2.5 transition-all';
        }
    });
    </script>
</x-guest-layout>

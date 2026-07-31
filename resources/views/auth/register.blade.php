<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- NIK -->
        <div class="mt-4">
            <x-input-label for="nik" :value="__('NIK (16 digit)')" />
            <x-text-input id="nik" class="block mt-1 w-full" type="text" name="nik" :value="old('nik')" required maxlength="16" placeholder="320101XXXXXXXXXX" />
            <p id="nik-status" class="mt-1 text-sm text-gray-500">Ketik NIK untuk auto-fill data dari database desa</p>
            <x-input-error :messages="$errors->get('nik')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email (opsional)')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('No. Telepon (opsional)')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" placeholder="08XXXXXXXXXX" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Address -->
        <div class="mt-4">
            <x-input-label for="address" :value="__('Alamat (opsional)')" />
            <textarea id="address" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="address" rows="2">{{ old('address') }}</textarea>
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <!-- RT / RW -->
        <div class="mt-4 grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="rt" :value="__('RT (opsional)')" />
                <x-text-input id="rt" class="block mt-1 w-full" type="text" name="rt" :value="old('rt')" maxlength="3" placeholder="001" />
                <x-input-error :messages="$errors->get('rt')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="rw" :value="__('RW (opsional)')" />
                <x-text-input id="rw" class="block mt-1 w-full" type="text" name="rw" :value="old('rw')" maxlength="3" placeholder="001" />
                <x-input-error :messages="$errors->get('rw')" class="mt-2" />
            </div>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Sudah punya akun?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Daftar') }}
            </x-primary-button>
        </div>
    </form>

    <script>
    let nikTimeout;
    const nikInput = document.getElementById('nik');
    const nikStatus = document.getElementById('nik-status');

    nikInput.addEventListener('input', function() {
        clearTimeout(nikTimeout);
        const nik = this.value.replace(/\D/g, '').slice(0, 16);
        this.value = nik;

        if (nik.length === 16) {
            nikStatus.textContent = 'Mencari data...';
            nikStatus.className = 'mt-1 text-sm text-blue-500';

            nikTimeout = setTimeout(() => {
                fetch(`/cek-nik/${nik}`)
                    .then(res => res.json())
                    .then(result => {
                        if (result.found) {
                            document.getElementById('name').value = result.data.nama;
                            if (result.data.alamat) document.getElementById('address').value = result.data.alamat;
                            if (result.data.rt) document.getElementById('rt').value = result.data.rt;
                            if (result.data.rw) document.getElementById('rw').value = result.data.rw;
                            nikStatus.textContent = 'Data ditemukan! Form sudah diisi otomatis.';
                            nikStatus.className = 'mt-1 text-sm text-green-600';
                        } else {
                            nikStatus.textContent = 'NIK tidak ditemukan di database desa. Isi manual.';
                            nikStatus.className = 'mt-1 text-sm text-orange-600';
                        }
                    })
                    .catch(() => {
                        nikStatus.textContent = 'Gagal mencari data. Isi manual.';
                        nikStatus.className = 'mt-1 text-sm text-red-500';
                    });
            }, 500);
        } else {
            nikStatus.textContent = 'Ketik NIK 16 digit untuk auto-fill data dari database desa';
            nikStatus.className = 'mt-1 text-sm text-gray-500';
        }
    });
    </script>
</x-guest-layout>

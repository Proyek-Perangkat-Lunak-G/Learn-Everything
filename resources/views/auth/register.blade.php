<x-guest-layout>
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-6">Buat Akun Baru</h2>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" x-data="{ role: '{{ old('role', 'user') }}' }">
        @csrf

        <!-- Role Selection -->
        <div class="mb-6">
            <x-input-label :value="__('Daftar Sebagai')" />
            <div class="flex gap-4 mt-2">
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="role" value="user" x-model="role" class="peer sr-only">
                    <div class="border-2 rounded-lg p-4 text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-gray-400">
                        <svg class="w-8 h-8 mx-auto mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span class="font-semibold text-sm">Siswa</span>
                        <p class="text-xs text-gray-500 mt-1">Belajar & ikuti kursus</p>
                    </div>
                </label>
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="role" value="tutor" x-model="role" class="peer sr-only">
                    <div class="border-2 rounded-lg p-4 text-center transition-all peer-checked:border-green-500 peer-checked:bg-green-50 hover:border-gray-400">
                        <svg class="w-8 h-8 mx-auto mb-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span class="font-semibold text-sm">Tutor</span>
                        <p class="text-xs text-gray-500 mt-1">Mengajar & buat kursus</p>
                    </div>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('No. Telepon')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Tutor Fields -->
        <div x-show="role === 'tutor'" x-transition class="mt-4 space-y-4 border-t pt-4">
            <h3 class="font-semibold text-gray-800">Informasi Tutor</h3>

            <div>
                <x-input-label for="bio" :value="__('Bio / Tentang Anda')" />
                <textarea id="bio" name="bio" rows="3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('bio') }}</textarea>
                <x-input-error :messages="$errors->get('bio')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="skills" :value="__('Keahlian (pisahkan dengan koma)')" />
                <x-text-input id="skills" class="block mt-1 w-full" type="text" name="skills" :value="old('skills')" placeholder="Laravel, Python, React" />
                <x-input-error :messages="$errors->get('skills')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="hourly_rate" :value="__('Tarif Per Jam (Rp)')" />
                <x-text-input id="hourly_rate" class="block mt-1 w-full" type="number" name="hourly_rate" :value="old('hourly_rate')" min="0" step="1000" />
                <x-input-error :messages="$errors->get('hourly_rate')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="document" :value="__('Upload Dokumen (KTP/CV/Ijazah - PDF/JPG/PNG, maks 5MB)')" />
                <input id="document" type="file" name="document" accept=".pdf,.jpg,.jpeg,.png" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                <x-input-error :messages="$errors->get('document')" class="mt-2" />
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                <p class="text-sm text-yellow-800">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Akun tutor akan diverifikasi oleh admin sebelum bisa mengajar.
                </p>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full btn btn-primary btn-lg">Daftar</button>
        </div>

        <div class="text-center mt-4">
            <a class="text-sm text-blue-600 hover:underline" href="{{ route('login') }}">
                Sudah punya akun? Masuk
            </a>
        </div>
    </form>
</x-guest-layout>

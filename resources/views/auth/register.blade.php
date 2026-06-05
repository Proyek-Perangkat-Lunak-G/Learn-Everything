<x-guest-layout>
    <div class="auth-card animate-fade-in-up">
        <!-- Header -->
        <div class="mb-7 text-center">
            <h2 class="text-2xl font-extrabold text-gray-900">Buat Akun Baru</h2>
            <p class="mt-1.5 text-sm text-gray-500">Bergabung dan mulai perjalanan belajarmu</p>
        </div>

        @if($errors->any())
            <div class="mb-5 flex items-start gap-2 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">
                <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="font-semibold mb-0.5">Mohon perbaiki kesalahan berikut:</p>
                    @foreach($errors->all() as $error)
                        <p class="text-xs text-red-700">• {{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data"
              x-data="{ role: '{{ old('role', 'user') }}', showPass: false, showConfirm: false }"
              class="space-y-5">
            @csrf

            {{-- Role Selection --}}
            <div>
                <label class="form-label">Daftar Sebagai</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="user" x-model="role" class="sr-only peer">
                        <div class="border-2 rounded-xl p-3.5 text-center transition-all
                                    peer-checked:border-blue-500 peer-checked:bg-blue-50 border-gray-200 hover:border-gray-300">
                            <svg class="w-7 h-7 mx-auto mb-1.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <p class="font-semibold text-sm text-gray-900">Siswa</p>
                            <p class="text-xs text-gray-500 mt-0.5">Belajar & ikuti kursus</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="tutor" x-model="role" class="sr-only peer">
                        <div class="border-2 rounded-xl p-3.5 text-center transition-all
                                    peer-checked:border-green-500 peer-checked:bg-green-50 border-gray-200 hover:border-gray-300">
                            <svg class="w-7 h-7 mx-auto mb-1.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <p class="font-semibold text-sm text-gray-900">Tutor</p>
                            <p class="text-xs text-gray-500 mt-0.5">Mengajar & buat kursus</p>
                        </div>
                    </label>
                </div>
                @error('role')
                    <p class="form-error mt-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p>
                @enderror
            </div>

            {{-- Name --}}
            <div>
                <label for="name" class="form-label">Nama Lengkap</label>
                <div class="input-group">
                    <div class="input-group-icon">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                           placeholder="Nama lengkap Anda"
                           class="form-input pl-10 {{ $errors->get('name') ? 'border-red-400' : '' }}">
                </div>
                @error('name') <p class="form-error mt-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="form-label">Alamat Email</label>
                <div class="input-group">
                    <div class="input-group-icon">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                           placeholder="nama@email.com"
                           class="form-input pl-10 {{ $errors->get('email') ? 'border-red-400' : '' }}">
                </div>
                @error('email') <p class="form-error mt-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
            </div>

            {{-- Phone --}}
            <div>
                <label for="phone" class="form-label">No. Telepon <span class="text-gray-400 font-normal">(opsional)</span></label>
                <div class="input-group">
                    <div class="input-group-icon">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                           placeholder="08xxxxxxxxxx"
                           class="form-input pl-10 {{ $errors->get('phone') ? 'border-red-400' : '' }}">
                </div>
                @error('phone') <p class="form-error mt-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="form-label">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <input id="password" :type="showPass ? 'text' : 'password'" name="password" required
                           placeholder="Min. 8 karakter"
                           class="form-input pl-10 pr-10 {{ $errors->get('password') ? 'border-red-400' : '' }}">
                    <button type="button" @click="showPass = !showPass"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="showPass" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
                @error('password') <p class="form-error mt-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <input id="password_confirmation" :type="showConfirm ? 'text' : 'password'"
                           name="password_confirmation" required placeholder="Ulangi password"
                           class="form-input pl-10 pr-10 {{ $errors->get('password_confirmation') ? 'border-red-400' : '' }}">
                    <button type="button" @click="showConfirm = !showConfirm"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <svg x-show="!showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="showConfirm" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
                @error('password_confirmation') <p class="form-error mt-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
            </div>

            {{-- Tutor Extra Fields --}}
            <div x-show="role === 'tutor'" x-transition class="space-y-4 rounded-2xl border border-green-200 bg-green-50/50 p-4">
                <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Informasi Tutor
                </h3>
                <div>
                    <label for="bio" class="form-label">Bio / Tentang Anda</label>
                    <textarea id="bio" name="bio" rows="3"
                              placeholder="Ceritakan tentang pengalaman dan keahlian Anda..."
                              class="form-textarea">{{ old('bio') }}</textarea>
                    @error('bio') <p class="form-error mt-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="skills" class="form-label">Keahlian <span class="text-gray-400 font-normal">(pisahkan dengan koma)</span></label>
                    <input id="skills" type="text" name="skills" value="{{ old('skills') }}"
                           placeholder="Contoh: Laravel, Python, React"
                           class="form-input {{ $errors->get('skills') ? 'border-red-400' : '' }}">
                    @error('skills') <p class="form-error mt-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="hourly_rate" class="form-label">Tarif Per Jam (Rp)</label>
                    <input id="hourly_rate" type="number" name="hourly_rate" value="{{ old('hourly_rate') }}"
                           min="0" step="1000" placeholder="50000"
                           class="form-input {{ $errors->get('hourly_rate') ? 'border-red-400' : '' }}">
                    @error('hourly_rate') <p class="form-error mt-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="document" class="form-label">Upload Dokumen <span class="text-gray-400 font-normal">(KTP/CV/Ijazah · PDF/JPG/PNG · maks 5MB)</span></label>
                    <input id="document" type="file" name="document" accept=".pdf,.jpg,.jpeg,.png"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer">
                    @error('document') <p class="form-error mt-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
                </div>
                <div class="flex items-start gap-2 bg-amber-50 border border-amber-200 rounded-xl px-3 py-2.5">
                    <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs text-amber-800">Akun tutor akan diverifikasi oleh admin sebelum bisa mengajar.</p>
                </div>
            </div>

            <button type="submit" class="w-full btn btn-primary btn-lg justify-center mt-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Buat Akun
            </button>

            <div class="relative my-1">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                <div class="relative flex justify-center text-xs text-gray-400 bg-gray-50 px-3">Sudah punya akun?</div>
            </div>

            <a href="{{ route('login') }}"
               class="w-full btn btn-outline btn-lg justify-center text-blue-600 border-blue-200 hover:bg-blue-50 hover:border-blue-300">
                Masuk Sekarang
            </a>
        </form>
    </div>
</x-guest-layout>

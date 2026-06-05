<x-guest-layout>
    <div class="auth-card animate-fade-in-up">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h2 class="text-2xl font-extrabold text-gray-900">Selamat Datang Kembali</h2>
            <p class="mt-1.5 text-sm text-gray-500">Masuk untuk melanjutkan belajar</p>
        </div>

        <!-- Session Status -->
        @if(session('status'))
            <div class="mb-5 flex items-center gap-2 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-xl text-sm">
                <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('status') }}
            </div>
        @endif

        <!-- Global error banner -->
        @if($errors->any())
            <div class="mb-5 flex items-start gap-2 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">
                <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="font-semibold mb-0.5">Login gagal</p>
                    @foreach($errors->all() as $error)
                        <p class="text-xs text-red-700">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" x-data="{ showPass: false }" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="form-label">Alamat Email</label>
                <div class="input-group">
                    <div class="input-group-icon">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                    </div>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="nama@email.com"
                        class="form-input pl-10 {{ $errors->get('email') ? 'border-red-400 focus:ring-red-400/20 focus:border-red-400' : '' }}"
                    >
                </div>
                @error('email')
                    <p class="form-error">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="form-label mb-0">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Lupa password?</a>
                    @endif
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <input
                        id="password"
                        :type="showPass ? 'text' : 'password'"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="form-input pl-10 pr-10 {{ $errors->get('password') ? 'border-red-400 focus:ring-red-400/20 focus:border-red-400' : '' }}"
                    >
                    <button type="button" @click="showPass = !showPass"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                        <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="showPass" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
                @error('password')
                    <p class="form-error">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember_me" type="checkbox" name="remember"
                       class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="remember_me" class="ml-2 text-sm text-gray-600">Ingat saya selama 30 hari</label>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full btn btn-primary btn-lg justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                Masuk ke Akun
            </button>

            <!-- Divider -->
            <div class="relative my-2">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                <div class="relative flex justify-center text-xs text-gray-400 bg-white px-3">Belum punya akun?</div>
            </div>

            <!-- Register link -->
            <a href="{{ route('register') }}"
               class="w-full btn btn-outline btn-lg justify-center text-blue-600 border-blue-200 hover:bg-blue-50 hover:border-blue-300">
                Daftar Sekarang — Gratis!
            </a>
        </form>
    </div>
</x-guest-layout>

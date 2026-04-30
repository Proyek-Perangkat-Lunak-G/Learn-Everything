<x-app-layout>
    <div class="bg-white border-b">
        <div class="max-w-5xl mx-auto px-4 py-6">
            <h1 class="text-2xl font-bold text-gray-900">Profil Tutor</h1>
            <p class="text-gray-600 mt-1">Kelola informasi profil dan rekening bank Anda</p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informasi Akun -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full mx-auto flex items-center justify-center text-white text-3xl font-bold mb-3">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <h3 class="font-bold text-lg">{{ auth()->user()->name }}</h3>
                        <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                        <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-medium
                            {{ $tutor->status === 'approved' ? 'bg-green-100 text-green-800' : ($tutor->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($tutor->status) }}
                        </span>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            <span>Rating: {{ number_format($tutor->rating, 1) }} ({{ $tutor->total_reviews }} ulasan)</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Tarif: Rp {{ number_format($tutor->hourly_rate, 0, ',', '.') }}/jam</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Edit Profile -->
            <div class="lg:col-span-2">
                <form method="POST" action="{{ route('tutor.profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Umum -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-bold mb-4">Informasi Umum</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                                <textarea name="bio" rows="4" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Ceritakan tentang diri Anda dan keahlian Anda...">{{ old('bio', $tutor->bio) }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tarif per Jam (Rp)</label>
                                <input type="number" name="hourly_rate" value="{{ old('hourly_rate', $tutor->hourly_rate) }}" min="0" step="1000" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="50000">
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Rekening Bank -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <h3 class="text-lg font-bold">Informasi Rekening Bank</h3>
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-4">
                            Isi informasi rekening bank untuk menerima pembayaran dari platform.
                        </p>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bank</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name', $tutor->bank_name) }}" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: BCA, Mandiri, BNI">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pemilik Rekening</label>
                                <input type="text" name="bank_account_holder" value="{{ old('bank_account_holder', $tutor->bank_account_holder) }}" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Sesuai dengan nama di buku rekening">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening</label>
                                <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $tutor->bank_account_number) }}" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="1234567890">
                            </div>
                        </div>

                        @if($tutor->bank_account_number)
                            <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="text-sm text-green-700">
                                        <p class="font-medium">Rekening bank terdaftar</p>
                                        <p>{{ $tutor->bank_name }} - {{ $tutor->bank_account_number }}</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <p class="text-sm text-yellow-700">
                                        Anda belum menambahkan rekening bank. Silakan lengkapi untuk menerima pembayaran.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

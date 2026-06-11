<x-app-layout>
    <div class="bg-white border-b">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
            <p class="text-gray-600 mt-1">Kelola informasi akun Anda</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-8">
        {{-- Navigation Menu --}}
        <div class="mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Edit Profile --}}
                <a href="#profile-info" class="p-4 bg-white rounded-lg shadow-sm border-l-4 border-blue-500 hover:shadow-md transition cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-900">Informasi Profil</h3>
                            <p class="text-sm text-gray-600 mt-1">Edit nama, email, dan foto</p>
                        </div>
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </a>

                {{-- Manage Addresses --}}
                <a href="{{ route('addresses.index') }}" class="p-4 bg-white rounded-lg shadow-sm border-l-4 border-green-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-900">Manajemen Alamat</h3>
                            <p class="text-sm text-gray-600 mt-1">Kelola alamat pengiriman & home visit</p>
                        </div>
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    </div>
                </a>

                {{-- Delete Account --}}
                <a href="#delete" class="p-4 bg-white rounded-lg shadow-sm border-l-4 border-red-500 hover:shadow-md transition cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-900">Hapus Akun</h3>
                            <p class="text-sm text-gray-600 mt-1">Menghapus akun dan semua data</p>
                        </div>
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 0v2m0-8v-2m0 8v2m0 0v-2"/></svg>
                    </div>
                </a>
            </div>
        </div>

        {{-- Profile Information Form --}}
        <div id="profile-info" class="p-6 bg-white rounded-lg shadow-sm mb-6">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Delete Account Form --}}
        <div id="delete" class="p-6 bg-white rounded-lg shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>

    <script>
        // Smooth scroll untuk navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href !== '#') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        });
    </script>
</x-app-layout>

<footer class="bg-white border-t mt-16">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    @include('components.app-logo-svg', ['size' => 'small'])
                    <h3 class="text-lg font-semibold text-gradient">Learn Everything</h3>
                </div>
                <p class="text-sm text-gray-600">
                    Platform pembelajaran online terpadu untuk mengembangkan skill akademik dan praktis Anda.
                </p>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Layanan</h4>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><a href="{{ route('courses.index') }}" class="hover:text-blue-600 transition-colors">Kursus Online</a></li>
                    <li><a href="{{ route('tutors.index') }}" class="hover:text-blue-600 transition-colors">Tutor Profesional</a></li>
                    <li><a href="{{ route('marketplace.index') }}" class="hover:text-blue-600 transition-colors">Marketplace</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Komunitas</h4>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><a href="{{ route('forum.index') }}" class="hover:text-blue-600 transition-colors">Forum Diskusi</a></li>
                    @auth
                        <li><a href="{{ route('chat.index') }}" class="hover:text-blue-600 transition-colors">Live Chat</a></li>
                    @endauth
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Akun</h4>
                <ul class="space-y-2 text-sm text-gray-600">
                    @auth
                        <li><a href="{{ route('profile.edit') }}" class="hover:text-blue-600 transition-colors">Profil Saya</a></li>
                        <li><a href="{{ route('history') }}" class="hover:text-blue-600 transition-colors">Riwayat Transaksi</a></li>
                        <li><a href="{{ route('subscriptions.index') }}" class="hover:text-blue-600 transition-colors">Langganan</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="hover:text-blue-600 transition-colors">Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-blue-600 transition-colors">Daftar</a></li>
                    @endauth
                </ul>
            </div>
        </div>
        <div class="mt-8 pt-8 border-t text-center text-sm text-gray-600">
            <p>&copy; {{ date('Y') }} Learn Everything. All rights reserved.</p>
            <p class="mt-2 text-xs">Mendukung SDG 4: Quality Education</p>
        </div>
    </div>
</footer>
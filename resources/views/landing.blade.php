<x-app-layout>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 text-white">
        <div class="max-w-7xl mx-auto px-4 py-20">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">Learn Everything</h1>
                <p class="text-xl md:text-2xl mb-8 text-blue-100">Platform pembelajaran online terpadu untuk mengembangkan skill akademik dan praktis Anda.</p>
                <div class="flex items-center justify-center gap-4">
                    <a href="{{ route('courses.index') }}" class="btn btn-lg bg-white text-blue-600 hover:bg-gray-100 font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Jelajahi Kursus
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-lg border-2 border-white text-white hover:bg-white hover:text-blue-600 font-semibold">Mulai Sekarang</a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 bg-white border-b">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="inline-flex p-3 bg-blue-100 rounded-lg mb-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $coursesCount }}</p>
                    <p class="text-sm text-gray-600">Kursus Tersedia</p>
                </div>
                <div class="text-center">
                    <div class="inline-flex p-3 bg-green-100 rounded-lg mb-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $tutorsCount }}</p>
                    <p class="text-sm text-gray-600">Tutor Profesional</p>
                </div>
                <div class="text-center">
                    <div class="inline-flex p-3 bg-yellow-100 rounded-lg mb-3">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">Sertifikat</p>
                    <p class="text-sm text-gray-600">Digital Resmi</p>
                </div>
                <div class="text-center">
                    <div class="inline-flex p-3 bg-purple-100 rounded-lg mb-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">Komunitas</p>
                    <p class="text-sm text-gray-600">Forum & Chat</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Courses -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Kursus Populer</h2>
                    <p class="text-gray-600 mt-1">Temukan kursus terbaik untuk mengembangkan skill Anda</p>
                </div>
                <a href="{{ route('courses.index') }}" class="btn btn-outline">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($courses as $course)
                    <div class="bg-white rounded-lg overflow-hidden shadow-sm card-hover">
                        <div class="relative h-48">
                            @if($course->image)
                                <img src="{{ Storage::url($course->image) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                            @endif
                            <div class="absolute top-3 right-3">
                                <span class="badge bg-white text-gray-900 shadow-sm">{{ $course->level ?? 'Semua Level' }}</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="badge badge-blue">{{ $course->category }}</span>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $course->title }}</h3>
                            <p class="text-sm text-gray-600 mb-3">Kursus Official Learn Everything</p>
                            <div class="flex items-center gap-1 mb-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                <span class="text-sm font-medium">{{ $course->modules->count() }} modul</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xl font-bold text-blue-600">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                                <a href="{{ route('courses.show', $course) }}" class="btn btn-primary btn-sm">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <p class="text-gray-500">Belum ada kursus tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Featured Tutors -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Tutor Terbaik</h2>
                    <p class="text-gray-600 mt-1">Belajar langsung dari para ahli di bidangnya</p>
                </div>
                <a href="{{ route('tutors.index') }}" class="btn btn-outline">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($tutors as $tutor)
                    <a href="{{ route('tutors.show', $tutor) }}" class="bg-white rounded-lg border shadow-sm p-6 text-center card-hover block">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">{{ $tutor->user->name }}</h3>
                        <div class="flex items-center justify-center mt-2 gap-1">
                            <svg class="w-4 h-4 text-yellow-400 fill-yellow-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span class="text-sm text-gray-600">{{ number_format($tutor->rating, 1) }} ({{ $tutor->total_reviews }} ulasan)</span>
                        </div>
                        @if($tutor->skills)
                            <div class="mt-3 flex flex-wrap justify-center gap-1">
                                @foreach(array_slice($tutor->skills, 0, 3) as $skill)
                                    <span class="badge badge-gray">{{ $skill }}</span>
                                @endforeach
                            </div>
                        @endif
                    </a>
                @empty
                    <p class="col-span-4 text-center text-gray-500">Belum ada tutor tersedia.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 py-16">
        <div class="max-w-4xl mx-auto text-center px-4">
            <h2 class="text-3xl font-bold text-white mb-4">Siap untuk mulai belajar?</h2>
            <p class="text-blue-100 mb-8 text-lg">Bergabung dan kembangkan potensi Anda bersama komunitas pembelajar.</p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-lg bg-white text-blue-600 hover:bg-gray-100 font-semibold">Daftar Gratis</a>
            @else
                <a href="{{ route('dashboard') }}" class="btn btn-lg bg-white text-blue-600 hover:bg-gray-100 font-semibold">Ke Dashboard</a>
            @endguest
        </div>
    </section>
</x-app-layout>


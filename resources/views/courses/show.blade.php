<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 pt-4">
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
            </div>
        @endif

        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
            <div class="max-w-7xl mx-auto px-4 py-12">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="badge bg-white text-blue-600">{{ $course->category }}</span>
                            <span class="badge bg-blue-500 text-white">{{ $course->level ?? 'Semua Level' }}</span>
                        </div>
                        <h1 class="text-4xl font-bold mb-4">{{ $course->title }}</h1>
                        <p class="text-lg text-blue-100 mb-6">{{ $course->description }}</p>
                        <div class="flex items-center gap-6 text-sm">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                <span>{{ $course->modules->count() }} modul</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <span>{{ $course->enrollments->count() }} siswa</span>
                            </div>
                        </div>
                        <div class="mt-6">
                            <p class="text-sm text-blue-100">Kursus Official</p>
                            <p class="text-lg font-semibold">Learn Everything Platform</p>
                        </div>
                    </div>

                    <!-- Price Card -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg overflow-hidden shadow-lg text-gray-900">
                            @if($course->image)
                                <img src="{{ Storage::url($course->image) }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                            @endif
                            <div class="p-6">
                                <div class="mb-4">
                                    <div class="text-3xl font-bold text-blue-600 mb-1">Rp {{ number_format($course->price, 0, ',', '.') }}</div>
                                </div>

                                @auth
                                    @php
                                        $enrollment = auth()->user()->enrollments()->where('course_id', $course->id)->first();
                                    @endphp

                                    @if($enrollment)
                                        <div class="mb-4">
                                            <div class="flex items-center justify-between text-sm mb-1">
                                                <span class="text-gray-600">Progress</span>
                                                <span class="font-medium">{{ $enrollment->progress }}%</span>
                                            </div>
                                            <div class="progress-bar h-2">
                                                <div class="progress-bar-fill" style="width: {{ $enrollment->progress }}%"></div>
                                            </div>
                                        </div>
                                        @if($course->modules->first())
                                            <a href="{{ route('courses.learn', [$course, $course->modules->first()]) }}" class="btn btn-primary w-full btn-lg justify-center">
                                                {{ $enrollment->is_completed ? 'Review Materi' : 'Lanjutkan Belajar' }}
                                            </a>
                                        @endif
                                    @else
                                        @if($course->price > 0)
                                            <form method="POST" action="{{ route('cart.addCourse') }}">
                                                @csrf
                                                <input type="hidden" name="course_id" value="{{ $course->id }}">
                                                <button type="submit" class="btn btn-primary w-full btn-lg justify-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                                                    Tambah ke Keranjang - Rp {{ number_format($course->price, 0, ',', '.') }}
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('courses.enroll', $course) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-primary w-full btn-lg justify-center">Daftar Sekarang (Gratis)</button>
                                            </form>
                                        @endif
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary w-full btn-lg justify-center">Masuk untuk Daftar</a>
                                @endauth

                                <p class="text-xs text-center text-gray-500 mt-3">Akses selamanya &bull; Sertifikat digital</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Tabs -->
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <!-- Syllabus -->
                    <div class="bg-white rounded-lg p-6 shadow-sm mb-6">
                        <h2 class="text-2xl font-bold mb-4">Materi Kursus</h2>
                        <div class="space-y-3">
                            @foreach($course->modules as $index => $module)
                                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">{{ $index + 1 }}</span>
                                        <span class="font-medium text-gray-900">{{ $module->title }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($module->quiz)
                                            <span class="badge badge-yellow">Kuis</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quiz Section -->
                    @if($course->quizzes && $course->quizzes->count() > 0)
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-lg p-6">
                            <div class="flex items-start gap-4">
                                <div class="stat-icon stat-icon-green flex-shrink-0">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold mb-2">Kuis Evaluasi Tersedia</h3>
                                    <p class="text-gray-600">Uji pemahaman Anda dengan kuis interaktif</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar Info -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="font-bold text-lg mb-4">Informasi Kursus</h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                <div>
                                    <p class="text-sm text-gray-500">Modul</p>
                                    <p class="font-medium">{{ $course->modules->count() }} modul</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                <div>
                                    <p class="text-sm text-gray-500">Kategori</p>
                                    <p class="font-medium">{{ $course->category }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <div>
                                    <p class="text-sm text-gray-500">Siswa Terdaftar</p>
                                    <p class="font-medium">{{ $course->enrollments->count() }} siswa</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
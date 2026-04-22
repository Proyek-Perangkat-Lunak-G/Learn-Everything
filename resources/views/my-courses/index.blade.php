<x-app-layout>
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-2">Kursus Saya</h1>
            <p class="text-blue-100">Kelola dan lanjutkan pembelajaran Anda</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Kursus</p>
                        <p class="text-2xl font-bold">{{ $totalCourses }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Selesai</p>
                        <p class="text-2xl font-bold">{{ $completedCourses }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center gap-4">
                    <div class="bg-yellow-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Dalam Progress</p>
                        <p class="text-2xl font-bold">{{ $inProgressCourses }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Courses Grid -->
        @if($enrollments->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <h3 class="text-xl font-semibold mb-2">Belum ada kursus</h3>
                <p class="text-gray-600 mb-6">Mulai perjalanan belajar Anda dengan mendaftar kursus pertama</p>
                <a href="{{ route('courses.index') }}" class="btn btn-primary">Jelajahi Kursus</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($enrollments as $enrollment)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        <!-- Course Image -->
                        <div class="relative h-48 bg-gradient-to-r from-blue-500 to-purple-500">
                            @if($enrollment->course->image)
                                <img src="{{ \Storage::url($enrollment->course->image) }}" alt="{{ $enrollment->course->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Status Badge -->
                            @if($enrollment->is_completed)
                                <span class="absolute top-3 right-3 px-3 py-1 bg-green-500 text-white text-xs font-medium rounded-full">Selesai</span>
                            @else
                                <span class="absolute top-3 right-3 px-3 py-1 bg-yellow-500 text-white text-xs font-medium rounded-full">Berlangsung</span>
                            @endif
                        </div>

                        <!-- Course Info -->
                        <div class="p-6">
                            <h3 class="font-bold text-lg mb-2 line-clamp-2">{{ $enrollment->course->title }}</h3>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $enrollment->course->description }}</p>

                            <!-- Progress Bar -->
                            @php
                                $totalModules = $enrollment->course->modules->count();
                                $completedModules = $enrollment->moduleProgresses->where('is_completed', true)->count();
                                $progress = $totalModules > 0 ? ($completedModules / $totalModules) * 100 : 0;
                            @endphp
                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Progress</span>
                                    <span class="font-semibold">{{ number_format($progress, 0) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $completedModules }}/{{ $totalModules }} modul selesai</p>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <a href="{{ route('courses.show', $enrollment->course) }}" class="flex-1 btn btn-primary btn-sm">
                                    {{ $enrollment->is_completed ? 'Lihat Kursus' : 'Lanjutkan' }}
                                </a>
                                @if($enrollment->is_completed && $enrollment->certificate_url)
                                    <a href="{{ $enrollment->certificate_url }}" class="btn btn-outline btn-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>

                            <!-- Enrollment Date -->
                            <p class="text-xs text-gray-500 mt-3">Terdaftar: {{ $enrollment->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $enrollments->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

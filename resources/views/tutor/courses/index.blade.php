<x-app-layout>
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <h1 class="text-2xl font-bold text-gray-900">Kursus Saya</h1>
            <p class="text-gray-600 mt-1">Daftar kursus yang ditugaskan oleh admin kepada Anda</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
        @endif

        @if($tutor->status !== 'approved')
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                <p class="text-yellow-800 font-medium">Akun tutor Anda belum diverifikasi. Hubungi admin untuk informasi lebih lanjut.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($courses as $course)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="relative h-40">
                            @if($course->image)
                                <img src="{{ Storage::url($course->image) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                    <svg class="w-10 h-10 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                            @endif
                            <div class="absolute top-2 right-2">
                                <span class="badge {{ $course->is_published ? 'badge-green' : 'badge-gray' }}">
                                    {{ $course->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg text-gray-900 mb-1">{{ $course->title }}</h3>
                            <p class="text-sm text-gray-500 mb-2">{{ $course->category }}</p>
                            <div class="flex items-center justify-between text-sm text-gray-600 mb-3">
                                <span>{{ $course->modules_count ?? $course->modules->count() }} modul</span>
                                <span>{{ $course->enrollments_count }} siswa</span>
                            </div>
                            <a href="{{ route('tutor.courses.modules', $course) }}" class="btn btn-outline btn-sm w-full text-center">Lihat Modul</a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white rounded-lg shadow-sm p-12 text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <p class="text-gray-500 text-lg">Belum ada kursus yang ditugaskan kepada Anda.</p>
                        <p class="text-gray-400 text-sm mt-1">Kursus dibuat oleh admin dan ditugaskan ke tutor.</p>
                    </div>
                @endforelse
            </div>
        @endif
    </div>
</x-app-layout>

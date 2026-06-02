<x-app-layout>
    <div class="bg-white border-b">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Kursus</h1>
            <p class="text-gray-600 mt-1">Lihat progress dan sertifikat Anda</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="space-y-4">
            @forelse($enrollments as $enrollment)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $enrollment->course->title }}</h3>
                            <p class="text-sm text-gray-500 mt-1">Terdaftar: {{ $enrollment->created_at->format('d M Y') }}</p>
                            <div class="mt-3 flex items-center gap-3">
                                <div class="flex-1 max-w-xs">
                                    <div class="progress-bar">
                                        <div class="progress-bar-fill" style="width: {{ $enrollment->progress }}%"></div>
                                    </div>
                                </div>
                                <span class="text-sm font-medium text-gray-600">{{ $enrollment->progress }}%</span>
                            </div>
                        </div>
                        <div class="text-right ml-4">
                            @if($enrollment->is_completed)
                                <span class="badge badge-green">Selesai</span>
                                <a href="{{ route('certificates.download', $enrollment) }}" class="block mt-2 text-sm text-blue-600 hover:underline flex items-center gap-1 justify-end">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Download Sertifikat
                                </a>
                            @else
                                <span class="badge badge-yellow">Berlangsung</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <p class="text-gray-500 text-lg">Belum ada riwayat kursus.</p>
                    <a href="{{ route('courses.index') }}" class="btn btn-primary mt-4 inline-block">Jelajahi Kursus</a>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
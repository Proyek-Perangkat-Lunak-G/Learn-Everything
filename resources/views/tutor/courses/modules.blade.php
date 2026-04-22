<x-app-layout>
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Modul: {{ $course->title }}</h1>
            <p class="text-gray-500">{{ $course->modules->count() }} modul &bull; {{ $course->is_published ? 'Published' : 'Draft' }}</p>
        </div>
        <a href="{{ route('tutor.courses.index') }}" class="btn btn-outline btn-sm">← Kursus Saya</a>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <p class="text-sm text-blue-700">Modul dan konten kursus dikelola oleh admin. Hubungi admin jika ada perubahan yang perlu dilakukan.</p>
    </div>

    <!-- Modules List (read-only) -->
    <div class="space-y-4">
        @forelse($course->modules->sortBy('order') as $module)
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800">{{ $module->order }}. {{ $module->title }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($module->content, 300) }}</p>
                    @if($module->video_url)
                        <p class="text-sm text-blue-600 mt-2">🎬 {{ $module->video_url }}</p>
                    @endif
                    @if($module->quiz)
                        <p class="text-sm text-green-600 mt-2">✅ Quiz: {{ $module->quiz->questions->count() }} soal (passing: {{ $module->quiz->passing_score }}%)</p>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow p-12 text-center">
                <p class="text-gray-500">Belum ada modul untuk kursus ini.</p>
            </div>
        @endforelse
    </div>
</div>
</x-app-layout>

<x-app-layout>
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-6 flex items-center justify-between">
            <h1 class="text-xl font-bold text-gray-900">{{ $module->title }}</h1>
            <a href="{{ route('courses.show', $course) }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Kursus
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-4 sticky top-24">
                    <h3 class="font-bold text-gray-900 mb-3">Modul</h3>
                    <div class="space-y-2">
                        @foreach($course->modules as $m)
                            <a href="{{ route('courses.learn', [$course, $m]) }}"
                               class="block p-3 rounded-lg text-sm {{ $m->id === $module->id ? 'bg-blue-50 text-blue-600 font-semibold' : 'hover:bg-gray-50 text-gray-700' }} transition">
                                {{ $loop->iteration }}. {{ $m->title }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-sm p-8">
                    @if($module->video_url)
                        <div class="mb-8 aspect-video bg-black rounded-lg overflow-hidden">
                            <iframe src="{{ $module->video_url }}" class="w-full h-full" allowfullscreen></iframe>
                        </div>
                    @endif

                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($module->content)) !!}
                    </div>

                    <div class="mt-8 flex items-center justify-between border-t pt-6">
                        @if(!$progress->is_completed)
                            <form method="POST" action="{{ route('courses.module.complete', [$course, $module]) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Tandai Selesai
                                </button>
                            </form>
                        @else
                            <span class="flex items-center gap-2 text-green-600 font-semibold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Selesai
                            </span>
                        @endif

                        @if($module->quiz)
                            <a href="{{ route('quizzes.show', $module->quiz) }}" class="bg-yellow-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-yellow-600 transition flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                Kerjakan Quiz
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
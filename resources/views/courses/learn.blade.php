<x-app-layout>
    {{-- Top Bar --}}
    <div class="bg-white border-b sticky top-16 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('courses.show', $course) }}" class="text-blue-600 hover:text-blue-700 transition-colors flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div class="min-w-0">
                    <p class="text-xs text-gray-500 truncate">{{ $course->title }}</p>
                    <h1 class="text-sm font-semibold text-gray-900 truncate">{{ $module->title }}</h1>
                </div>
            </div>
            <div class="hidden sm:flex items-center gap-3">
                @php $idx = $course->modules->search(fn($m) => $m->id === $module->id); @endphp
                <div class="text-xs text-gray-500">Modul {{ $idx + 1 }} / {{ $course->modules->count() }}</div>
                <div class="w-24 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 rounded-full transition-all" style="width: {{ $course->modules->count() > 0 ? round((($idx+1)/$course->modules->count())*100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-6">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                 class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Sidebar --}}
            <aside class="lg:col-span-1 order-2 lg:order-1">
                <div class="bg-white rounded-2xl shadow-sm border p-4 sticky top-32">
                    <h3 class="font-bold text-gray-900 mb-1 text-sm uppercase tracking-wide">Daftar Modul</h3>
                    <p class="text-xs text-gray-400 mb-3">{{ $course->modules->count() }} modul tersedia</p>
                    <div class="space-y-1 max-h-[60vh] overflow-y-auto pr-1">
                        @foreach($course->modules->sortBy('order') as $m)
                            @php
                                $isActive = $m->id === $module->id;
                                $isDone   = $m->progress()->where('user_id', auth()->id())->where('is_completed', true)->exists();
                            @endphp
                            <a href="{{ route('courses.learn', [$course, $m]) }}"
                               class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm transition-all
                                      {{ $isActive ? 'bg-blue-50 text-blue-700 font-semibold ring-1 ring-blue-200' : 'hover:bg-gray-50 text-gray-700' }}">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                                             {{ $isDone ? 'bg-green-100 text-green-600' : ($isActive ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500') }}">
                                    @if($isDone)
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        {{ $loop->iteration }}
                                    @endif
                                </span>
                                <span class="truncate leading-snug">{{ $m->title }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </aside>

            {{-- Main Content --}}
            <main class="lg:col-span-3 order-1 lg:order-2">
                <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

                    {{-- Video Player --}}
                    @if($module->embed_url)
                        <div class="relative bg-black" style="padding-top: 56.25%;">
                            @if($module->is_local_video)
                                <video src="{{ $module->embed_url }}"
                                       class="absolute inset-0 w-full h-full"
                                       controls controlsList="nodownload" preload="metadata">
                                    Browser Anda tidak mendukung pemutar video.
                                </video>
                            @else
                                <iframe src="{{ $module->embed_url }}"
                                        class="absolute inset-0 w-full h-full"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        allowfullscreen loading="lazy"
                                        title="{{ $module->title }}">
                                </iframe>
                            @endif
                        </div>
                    @else
                        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white flex items-center justify-center" style="height: 220px;">
                            <div class="text-center">
                                <svg class="w-14 h-14 mx-auto mb-3 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                <p class="font-semibold text-lg">Materi Teks</p>
                                <p class="text-sm opacity-70">Baca materi di bawah ini</p>
                            </div>
                        </div>
                    @endif

                    {{-- Content --}}
                    <div class="p-6 lg:p-8">
                        <h2 class="text-xl lg:text-2xl font-bold text-gray-900 mb-4">{{ $module->title }}</h2>

                        <div class="prose prose-blue max-w-none text-gray-700 leading-relaxed">
                            {!! nl2br(e($module->content)) !!}
                        </div>

                        <div class="border-t mt-8 pt-6">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                @if(!$progress->is_completed)
                                    <form method="POST" action="{{ route('courses.module.complete', [$course, $module]) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-primary gap-2 shadow-sm hover:shadow-md transition-shadow">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Tandai Selesai & Lanjut
                                        </button>
                                    </form>
                                @else
                                    <div class="flex items-center gap-2 bg-green-50 text-green-700 px-4 py-2.5 rounded-xl font-semibold ring-1 ring-green-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Modul Selesai
                                    </div>
                                @endif

                                @if($module->quiz)
                                    <a href="{{ route('quizzes.show', $module->quiz) }}"
                                       class="btn gap-2 bg-amber-500 hover:bg-amber-600 text-white shadow-sm hover:shadow-md transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                        Kerjakan Kuis
                                    </a>
                                @endif
                            </div>

                            {{-- Prev / Next --}}
                            @php
                                $modules    = $course->modules->sortBy('order')->values();
                                $currentIdx = $modules->search(fn($m) => $m->id === $module->id);
                                $prevModule = $currentIdx > 0 ? $modules[$currentIdx - 1] : null;
                                $nextModule = $currentIdx < $modules->count() - 1 ? $modules[$currentIdx + 1] : null;
                            @endphp
                            @if($prevModule || $nextModule)
                                <div class="flex items-center justify-between mt-6 pt-4 border-t gap-4">
                                    @if($prevModule)
                                        <a href="{{ route('courses.learn', [$course, $prevModule]) }}"
                                           class="flex items-center gap-2 text-sm text-gray-600 hover:text-blue-600 transition-colors">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                            <span class="hidden sm:inline truncate max-w-xs">{{ $prevModule->title }}</span>
                                            <span class="sm:hidden">Sebelumnya</span>
                                        </a>
                                    @else
                                        <div></div>
                                    @endif
                                    @if($nextModule)
                                        <a href="{{ route('courses.learn', [$course, $nextModule]) }}"
                                           class="flex items-center gap-2 text-sm text-gray-600 hover:text-blue-600 transition-colors">
                                            <span class="hidden sm:inline truncate max-w-xs">{{ $nextModule->title }}</span>
                                            <span class="sm:hidden">Berikutnya</span>
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    @else
                                        <a href="{{ route('courses.show', $course) }}"
                                           class="flex items-center gap-2 text-sm text-green-600 hover:text-green-700 font-semibold transition-colors">
                                            Selesai 🎉
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>
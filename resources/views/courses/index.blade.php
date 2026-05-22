<x-app-layout>
<div class="min-h-screen bg-gray-50">

    {{-- Hero Banner --}}
    <div class="bg-gradient-to-r from-blue-600 via-indigo-700 to-purple-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-14">
            <div class="max-w-2xl animate-fade-in-up">
                <h1 class="text-4xl font-extrabold mb-3 leading-tight">Katalog Kursus 📚</h1>
                <p class="text-blue-100 text-lg">Tingkatkan skill Anda dengan {{ $courses->total() }} kursus berkualitas dari instruktur terbaik.</p>

                {{-- Quick Search --}}
                <form method="GET" action="{{ route('courses.index') }}" class="mt-6 flex gap-2">
                    <div class="relative flex-1">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kursus, topik, atau skill..."
                               class="w-full pl-11 pr-4 py-3 rounded-xl text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-white/50 shadow-lg">
                    </div>
                    <button type="submit" class="btn btn-white px-5 py-3 rounded-xl font-semibold shadow-lg flex-shrink-0">Cari</button>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <div class="flex flex-col lg:flex-row gap-7">

            {{-- Sidebar Filter --}}
            <aside class="w-full lg:w-72 flex-shrink-0">
                <div class="bg-white rounded-2xl shadow-sm border p-6 sticky top-20">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            </div>
                            <h2 class="font-semibold text-gray-900">Filter</h2>
                        </div>
                        @if(request()->hasAny(['category', 'min_price', 'max_price', 'search']))
                            <a href="{{ route('courses.index') }}" class="text-xs text-red-500 hover:text-red-700 font-medium flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Reset
                            </a>
                        @endif
                    </div>

                    <form method="GET" action="{{ route('courses.index') }}" id="filterForm">
                        {{-- Kategori --}}
                        <div class="mb-5">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Kategori</h3>
                            <div class="space-y-1">
                                <label class="flex items-center gap-2.5 px-3 py-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors {{ !request('category') ? 'bg-blue-50' : '' }}">
                                    <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} class="text-blue-600" onchange="document.getElementById('filterForm').submit()">
                                    <span class="text-sm {{ !request('category') ? 'text-blue-700 font-semibold' : 'text-gray-700' }}">Semua Kategori</span>
                                </label>
                                @foreach($categories as $cat)
                                    <label class="flex items-center gap-2.5 px-3 py-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors {{ request('category') == $cat ? 'bg-blue-50' : '' }}">
                                        <input type="radio" name="category" value="{{ $cat }}" {{ request('category') == $cat ? 'checked' : '' }} class="text-blue-600" onchange="document.getElementById('filterForm').submit()">
                                        <span class="text-sm {{ request('category') == $cat ? 'text-blue-700 font-semibold' : 'text-gray-700' }}">{{ $cat }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Harga --}}
                        <div class="mb-5 border-t pt-5">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Rentang Harga</h3>
                            <div class="space-y-2">
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium">Rp</span>
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Minimum"
                                           class="w-full pl-8 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium">Rp</span>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Maksimum"
                                           class="w-full pl-8 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        {{-- Hidden search if present --}}
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <button type="submit" class="btn btn-primary w-full">Terapkan Filter</button>
                    </form>
                </div>
            </aside>

            {{-- Main Content --}}
            <main class="flex-1 min-w-0">
                {{-- Toolbar --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span>
                            @if(request()->hasAny(['category','search','min_price','max_price']))
                                Menampilkan <span class="font-semibold text-gray-900">{{ $courses->total() }}</span> hasil filter
                            @else
                                <span class="font-semibold text-gray-900">{{ $courses->total() }}</span> kursus tersedia
                            @endif
                        </span>
                    </div>

                    {{-- Active Filters Pills --}}
                    @if(request()->hasAny(['category','search','min_price','max_price']))
                        <div class="flex flex-wrap gap-2">
                            @if(request('search'))
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    "{{ request('search') }}"
                                    <a href="{{ route('courses.index', request()->except('search')) }}" class="hover:text-blue-900">×</a>
                                </span>
                            @endif
                            @if(request('category'))
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                    {{ request('category') }}
                                    <a href="{{ route('courses.index', request()->except('category')) }}" class="hover:text-indigo-900">×</a>
                                </span>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Course Grid --}}
                @forelse($courses as $i => $course)
                    @if($loop->first)
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                    @endif

                    <div class="course-card animate-fade-in-up stagger group" style="animation-delay: {{ min($i * 60, 400) }}ms">
                        {{-- Thumbnail --}}
                        <a href="{{ route('courses.show', $course) }}" class="block relative h-48 overflow-hidden rounded-t-2xl bg-gray-100">
                            @if($course->image)
                                <img src="{{ Storage::url($course->image) }}" alt="{{ $course->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                @php
                                    $gradients = [
                                        'from-blue-400 to-indigo-600',
                                        'from-purple-400 to-pink-600',
                                        'from-green-400 to-teal-600',
                                        'from-orange-400 to-red-500',
                                        'from-cyan-400 to-blue-500',
                                    ];
                                    $grad = $gradients[$course->id % count($gradients)];
                                @endphp
                                <div class="w-full h-full bg-gradient-to-br {{ $grad }} flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                                    <svg class="w-14 h-14 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                            @endif
                            {{-- Level badge --}}
                            <div class="absolute top-3 left-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-white/90 backdrop-blur-sm text-gray-800 shadow-sm">
                                    {{ $course->level ?? 'Semua Level' }}
                                </span>
                            </div>
                            @if($course->price == 0)
                                <div class="absolute top-3 right-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-green-500 text-white shadow-sm">GRATIS</span>
                                </div>
                            @endif
                        </a>

                        {{-- Body --}}
                        <div class="p-5">
                            <div class="flex items-center gap-2 mb-2.5">
                                <span class="badge badge-blue">{{ $course->category }}</span>
                                <span class="text-xs text-gray-400">•</span>
                                <span class="text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    {{ $course->modules->count() }} modul
                                </span>
                            </div>

                            <a href="{{ route('courses.show', $course) }}" class="block group/title">
                                <h3 class="font-bold text-gray-900 mb-1.5 line-clamp-2 leading-snug group-hover/title:text-blue-600 transition-colors text-[15px]">{{ $course->title }}</h3>
                            </a>
                            <p class="text-xs text-gray-500 mb-4 line-clamp-2">{{ Str::limit(strip_tags($course->description ?? ''), 90) ?: 'Kursus Official Learn Everything' }}</p>

                            {{-- Footer --}}
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div>
                                    @if($course->price == 0)
                                        <span class="text-lg font-bold text-green-600">Gratis</span>
                                    @else
                                        <span class="text-lg font-bold text-blue-600">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('courses.show', $course) }}" class="btn btn-primary btn-sm">
                                    Lihat Detail
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    @if($loop->last)
                        </div>
                    @endif
                @empty
                    {{-- Empty State --}}
                    <div class="text-center py-20 bg-white rounded-2xl border shadow-sm">
                        <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Kursus tidak ditemukan</h3>
                        <p class="text-gray-500 mb-6 text-sm">Coba ubah filter atau kata kunci pencarian Anda</p>
                        <a href="{{ route('courses.index') }}" class="btn btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Reset Filter
                        </a>
                    </div>
                @endforelse

                {{-- Pagination --}}
                @if($courses->hasPages())
                    <div class="mt-8">
                        {{ $courses->withQueryString()->links() }}
                    </div>
                @endif
            </main>
        </div>
    </div>
</div>
</x-app-layout>

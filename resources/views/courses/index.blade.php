<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Katalog Kursus</h1>
                <p class="text-gray-600">Temukan kursus terbaik untuk mengembangkan skill Anda</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar Filter -->
                <aside class="w-full lg:w-64 flex-shrink-0">
                    <div class="bg-white rounded-lg p-6 shadow-sm sticky top-24">
                        <div class="flex items-center gap-2 mb-6">
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            <h2 class="text-lg font-semibold">Filter</h2>
                        </div>

                        <form method="GET" action="{{ route('courses.index') }}">
                            <!-- Search -->
                            <div class="mb-6">
                                <h3 class="font-semibold mb-3">Pencarian</h3>
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kursus..." class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <!-- Kategori -->
                            <div class="mb-6">
                                <h3 class="font-semibold mb-3">Kategori</h3>
                                <div class="space-y-1">
                                    <a href="{{ route('courses.index', array_merge(request()->except('category'), [])) }}" class="block w-full text-left px-3 py-2 rounded-md text-sm transition-colors {{ !request('category') ? 'bg-blue-50 text-blue-600 font-medium' : 'hover:bg-gray-50 text-gray-700' }}">
                                        Semua Kategori
                                    </a>
                                    @foreach($categories as $cat)
                                        <a href="{{ route('courses.index', array_merge(request()->except('category'), ['category' => $cat])) }}" class="block w-full text-left px-3 py-2 rounded-md text-sm transition-colors {{ request('category') == $cat ? 'bg-blue-50 text-blue-600 font-medium' : 'hover:bg-gray-50 text-gray-700' }}">
                                            {{ $cat }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Harga -->
                            <div class="mb-6">
                                <h3 class="font-semibold mb-3">Rentang Harga</h3>
                                <div class="flex gap-2">
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" class="btn btn-primary w-full">Cari</button>
                                <a href="{{ route('courses.index') }}" class="btn btn-outline">Reset</a>
                            </div>
                        </form>
                    </div>
                </aside>

                <!-- Main Content -->
                <main class="flex-1">
                    <!-- Results Count -->
                    <div class="mb-4 text-gray-600 text-sm">
                        Menampilkan {{ $courses->total() }} kursus
                    </div>

                    <!-- Course Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
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
                            <div class="col-span-3 text-center py-12 bg-white rounded-lg">
                                <p class="text-gray-500 mb-4">Tidak ada kursus yang sesuai dengan pencarian Anda</p>
                                <a href="{{ route('courses.index') }}" class="btn btn-outline">Reset Filter</a>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        {{ $courses->withQueryString()->links() }}
                    </div>
                </main>
            </div>
        </div>
    </div>
</x-app-layout>




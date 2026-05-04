<x-app-layout>
    {{-- Header Section --}}
    <div class="bg-white border-b">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Forum Diskusi</h1>
                    <p class="text-gray-600">Berbagi pengalaman dan bertukar ilmu dengan komunitas</p>
                </div>
                <a href="{{ route('forum.create') }}" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Topik Baru
                </a>
            </div>

            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" placeholder="Cari topik diskusi..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>
    </div>

    {{-- Thread List --}}
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="space-y-4">
            @forelse($threads as $thread)
                <a href="{{ route('forum.show', $thread) }}" class="block bg-white rounded-lg shadow-sm p-6 card-hover">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold text-lg">{{ substr($thread->user->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between mb-1">
                                <h3 class="text-lg font-semibold text-gray-900 hover:text-blue-600 transition-colors">{{ $thread->title }}</h3>
                                <span class="badge badge-blue ml-2 flex-shrink-0">{{ $thread->category ?? 'Umum' }}</span>
                            </div>
                            <p class="text-gray-600 line-clamp-2 mb-3">{{ Str::limit($thread->content, 150) }}</p>
                            <div class="flex items-center gap-6 text-sm text-gray-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    {{ $thread->user->name }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    {{ $thread->replies_count }} balasan
                                </span>
                                <span class="ml-auto">{{ $thread->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada topik ditemukan</h3>
                    <p class="text-gray-600 mb-6">Coba kata kunci lain atau buat topik baru</p>
                    <a href="{{ route('forum.create') }}" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Buat Topik Baru
                    </a>
                </div>
            @endforelse
        </div>
        <div class="mt-8">{{ $threads->links() }}</div>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="bg-white border-b">
        <div class="max-w-2xl mx-auto px-4 py-6">
            <a href="{{ route('forum.index') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1 mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Forum
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Buat Topik Baru</h1>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-sm p-8">
            <form method="POST" action="{{ route('forum.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Tulis judul topik...">
                    @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konten</label>
                    <textarea name="content" rows="8" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Tulis konten topik diskusi...">{{ old('content') }}</textarea>
                    @error('content') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="w-full btn btn-primary btn-lg">Posting Topik</button>
            </form>
        </div>
    </div>
</x-app-layout>

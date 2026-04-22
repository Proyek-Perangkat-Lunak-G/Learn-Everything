<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Tutor Profesional</h1>
                <p class="text-gray-600">Temukan tutor terbaik untuk membantu pembelajaran Anda</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($tutors as $tutor)
                    <a href="{{ route('tutors.show', $tutor) }}" class="bg-white rounded-lg shadow-sm p-6 card-hover block">
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-lg">{{ $tutor->user->name }}</h3>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-yellow-400 fill-yellow-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span class="text-sm text-gray-600">{{ number_format($tutor->rating, 1) }} ({{ $tutor->total_reviews }} ulasan)</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm line-clamp-2 mb-3">{{ $tutor->bio }}</p>
                        @if($tutor->skills)
                            <div class="flex flex-wrap gap-1 mb-4">
                                @foreach(array_slice($tutor->skills, 0, 4) as $skill)
                                    <span class="badge badge-blue">{{ $skill }}</span>
                                @endforeach
                            </div>
                        @endif
                        <p class="text-blue-600 font-bold text-lg">Rp {{ number_format($tutor->hourly_rate, 0, ',', '.') }}/jam</p>
                    </a>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <p class="text-gray-500">Belum ada tutor tersedia.</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-8">{{ $tutors->links() }}</div>
        </div>
    </div>
</x-app-layout>

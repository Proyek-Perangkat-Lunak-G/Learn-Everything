<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 pt-4">
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
            </div>
        @endif

        <!-- Tutor Hero -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-700 text-white">
            <div class="max-w-7xl mx-auto px-4 py-12">
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">{{ $tutor->user->name }}</h1>
                        <div class="flex items-center gap-2 mt-2">
                            <svg class="w-5 h-5 text-yellow-400 fill-yellow-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span>{{ number_format($tutor->rating, 1) }} ({{ $tutor->total_reviews }} ulasan)</span>
                        </div>
                        <p class="text-blue-100 mt-2 text-lg font-semibold">Rp {{ number_format($tutor->hourly_rate, 0, ',', '.') }}/jam</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <!-- Bio -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <h2 class="text-xl font-bold mb-4">Tentang Tutor</h2>
                        <p class="text-gray-600 mb-4">{{ $tutor->bio }}</p>
                        @if($tutor->skills)
                            <div class="flex flex-wrap gap-2">
                                @foreach($tutor->skills as $skill)
                                    <span class="badge badge-blue">{{ $skill }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Reviews -->
                    <div class="bg-white rounded-lg shadow-sm p-6">\n                        <h2 class="text-xl font-bold mb-4">Ulasan</h2>
                        @forelse($tutor->reviews as $review)
                            <div class="border-b pb-4 mb-4 last:border-0 last:pb-0 last:mb-0">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-gray-900">{{ $review->user->name }}</span>
                                    <div class="flex items-center gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-gray-600 mt-2">{{ $review->comment }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500">Belum ada ulasan.</p>
                        @endforelse

                        @auth
                            <div class="mt-6 border-t pt-6">
                                <h3 class="font-semibold text-gray-900 mb-3">Tulis Ulasan</h3>
                                <form method="POST" action="{{ route('tutors.reviews.store', $tutor) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <select name="rating" required class="border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Pilih Rating</option>
                                            @for($i = 5; $i >= 1; $i--)
                                                <option value="{{ $i }}">{{ $i }} Bintang</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <textarea name="comment" rows="3" class="w-full border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Tulis ulasan Anda..."></textarea>
                                    @error('rating') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                    <button type="submit" class="btn btn-primary mt-2">Kirim Ulasan</button>
                                </form>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Sidebar -->
                <div>
                    <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24">
                        <div class="text-center mb-6">
                            <p class="text-3xl font-bold text-blue-600">Rp {{ number_format($tutor->hourly_rate, 0, ',', '.') }}</p>
                            <p class="text-gray-500">per jam</p>
                        </div>
                        @auth
                            <a href="{{ route('bookings.create', $tutor) }}" class="btn btn-primary w-full justify-center mb-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Book Home Visit
                            </a>
                            <a href="{{ route('chat.show', $tutor->user) }}" class="btn btn-outline w-full justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                Chat dengan Tutor
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary w-full justify-center">Masuk untuk Booking</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

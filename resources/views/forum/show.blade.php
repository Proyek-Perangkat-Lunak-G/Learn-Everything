<x-app-layout>
    {{-- Header --}}
    <div class="bg-white border-b">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <a href="{{ route('forum.index') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1 mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Forum
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ $thread->title }}</h1>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-8">
        {{-- Thread --}}
        <div class="bg-white rounded-lg shadow-sm p-8 mb-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mr-3">
                    <span class="text-white font-bold">{{ substr($thread->user->name, 0, 1) }}</span>
                </div>
                <div>
                    <span class="font-semibold text-gray-900">{{ $thread->user->name }}</span>
                    <p class="text-sm text-gray-500">{{ $thread->created_at->diffForHumans() }}</p>
                </div>
            </div>
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($thread->content)) !!}
            </div>
        </div>

        {{-- Replies --}}
        <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            {{ $thread->replies->count() }} Balasan
        </h3>
        <div class="space-y-4 mb-8">
            @foreach($thread->replies as $reply)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center mr-2">
                                <span class="text-xs font-bold text-white">{{ substr($reply->user->name, 0, 1) }}</span>
                            </div>
                            <span class="font-semibold text-gray-900 text-sm">{{ $reply->user->name }}</span>
                            <span class="text-xs text-gray-400 ml-2">{{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                        @auth
                            <form method="POST" action="{{ route('forum.replies.like', $reply) }}">
                                @csrf
                                <button type="submit" class="flex items-center text-sm {{ $reply->isLikedBy(auth()->user()) ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }} transition">
                                    <svg class="w-4 h-4 mr-1" fill="{{ $reply->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                                    {{ $reply->likes_count ?? $reply->likes->count() }}
                                </button>
                            </form>
                        @endauth
                    </div>
                    <p class="text-gray-700">{!! nl2br(e($reply->content)) !!}</p>
                </div>
            @endforeach
        </div>

        {{-- Reply Form --}}
        @auth
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold text-gray-900 mb-3">Tulis Balasan</h3>
                <form method="POST" action="{{ route('forum.replies.store', $thread) }}">
                    @csrf
                    <textarea 
                        name="content" 
                        rows="4" 
                        required 
                        minlength="1"
                        maxlength="500"
                        class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-500 @enderror" 
                        placeholder="Tulis balasan Anda (minimal 1 karakter, maksimal 500 karakter)..."
                    >{{ old('content') }}</textarea>
                    @error('content') 
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                    <div class="flex items-center justify-between mt-3">
                        <span class="text-xs text-gray-500" id="charCount">0 / 500 karakter</span>
                        <button type="submit" class="btn btn-primary">Kirim Balasan</button>
                    </div>
                </form>
            </div>
        @else
            <p class="text-center text-gray-500"><a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a> untuk membalas.</p>
        @endauth
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.querySelector('textarea[name="content"]');
            const charCount = document.getElementById('charCount');
            
            if (textarea && charCount) {
                textarea.addEventListener('input', function() {
                    const length = this.value.length;
                    charCount.textContent = length + ' / 500 karakter';
                    
                    if (length > 500) {
                        charCount.classList.add('text-red-500');
                        charCount.classList.remove('text-gray-500');
                    } else {
                        charCount.classList.add('text-gray-500');
                        charCount.classList.remove('text-red-500');
                    }
                });
                
                // Initialize counter on page load
                const initialLength = textarea.value.length;
                charCount.textContent = initialLength + ' / 500 karakter';
            }
        });
    </script>
    @endpush
</x-app-layout>
<x-app-layout>
    <div class="bg-white border-b">
        <div class="max-w-3xl mx-auto px-4 py-6">
            <h1 class="text-2xl font-bold text-gray-900">Quiz: {{ $quiz->title }}</h1>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-8">
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow-sm p-8">
            <div class="flex items-center gap-2 mb-6 p-4 bg-blue-50 rounded-lg">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm text-blue-800">Skor minimal kelulusan: <strong>{{ $quiz->passing_score }}%</strong></span>
            </div>

            <form method="POST" action="{{ route('quizzes.submit', $quiz) }}">
                @csrf
                @foreach($quiz->questions as $index => $question)
                    <div class="mb-8 p-6 border border-gray-200 rounded-lg">
                        <p class="font-semibold text-gray-900 mb-4">{{ $index + 1 }}. {{ $question->question }}</p>
                        <div class="space-y-3">
                            @foreach($question->options as $key => $option)
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $key }}" class="mr-3 text-blue-600 focus:ring-blue-500">
                                    <span>{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <button type="submit" class="w-full btn btn-primary btn-lg">Kirim Jawaban</button>
            </form>
        </div>
    </div>
</x-app-layout>
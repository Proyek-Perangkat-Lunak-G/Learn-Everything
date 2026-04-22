@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Modul: {{ $course->title }}</h1>
            <p class="text-gray-500">{{ $course->modules->count() }} modul</p>
        </div>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline">← Kembali</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">{{ session('success') }}</div>
    @endif

    <!-- Add Module Form -->
    <div class="bg-white rounded-xl shadow p-6 mb-8">
        <h2 class="text-lg font-semibold mb-4">Tambah Modul Baru</h2>
        <form action="{{ route('admin.courses.modules.store', $course) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Modul</label>
                <input type="text" name="title" class="w-full rounded-lg border-gray-300 focus:ring-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konten</label>
                <textarea name="content" rows="6" class="w-full rounded-lg border-gray-300 focus:ring-blue-500" required></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Video URL (opsional)</label>
                <input type="url" name="video_url" class="w-full rounded-lg border-gray-300 focus:ring-blue-500">
            </div>
            <button type="submit" class="btn btn-primary">Tambah Modul + Auto Quiz</button>
        </form>
    </div>

    <!-- Existing Modules -->
    <div class="space-y-4">
        @foreach($course->modules->sortBy('order') as $module)
            <div class="bg-white rounded-xl shadow p-6" x-data="{ editing: false }">
                <div x-show="!editing" class="flex items-start justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $module->order }}. {{ $module->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ Str::limit($module->content, 200) }}</p>
                        @if($module->video_url)
                            <p class="text-sm text-blue-600 mt-1">🎬 {{ $module->video_url }}</p>
                        @endif
                        @if($module->quiz)
                            <p class="text-sm text-green-600 mt-2">✅ Quiz: {{ $module->quiz->questions->count() }} soal</p>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <button @click="editing = true" class="text-blue-600 hover:underline text-sm">Edit</button>
                        @if($module->quiz)
                            <form action="{{ route('admin.modules.regenerateQuiz', $module) }}" method="POST" onsubmit="return confirm('Regenerate quiz?')">
                                @csrf
                                <button class="text-purple-600 hover:underline text-sm">Regen Quiz</button>
                            </form>
                        @endif
                        <form action="{{ route('admin.modules.destroy', $module) }}" method="POST" onsubmit="return confirm('Hapus modul ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline text-sm">Hapus</button>
                        </form>
                    </div>
                </div>
                <form x-show="editing" x-cloak action="{{ route('admin.modules.update', $module) }}" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <input type="text" name="title" value="{{ $module->title }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500" required>
                    <textarea name="content" rows="4" class="w-full rounded-lg border-gray-300 focus:ring-blue-500" required>{{ $module->content }}</textarea>
                    <input type="url" name="video_url" value="{{ $module->video_url }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500" placeholder="Video URL">
                    <div class="flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <button type="button" @click="editing = false" class="btn btn-outline btn-sm">Batal</button>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection

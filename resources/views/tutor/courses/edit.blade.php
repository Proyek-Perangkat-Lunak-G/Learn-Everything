@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Kursus</h1>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tutor.courses.update', $course) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow p-6 space-y-6">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Kursus</label>
            <input type="text" name="title" value="{{ old('title', $course->title) }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full rounded-lg border-gray-300 focus:ring-blue-500" required>{{ old('description', $course->description) }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <input type="text" name="category" value="{{ old('category', $course->category) }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $course->price) }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500" min="0" required>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Gambar</label>
            <input type="file" name="image" accept="image/*" class="w-full rounded-lg border-gray-300">
            @if($course->image)
                <img src="{{ asset('storage/' . $course->image) }}" class="mt-2 h-32 rounded-lg object-cover">
            @endif
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_published" value="1" id="is_published" {{ $course->is_published ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
            <label for="is_published" class="text-sm text-gray-700">Published</label>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('dashboard') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection

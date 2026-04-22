@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Kelola Tutor</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">#</th>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Skills</th>
                    <th class="px-4 py-3 text-left">Kursus</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Dokumen</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($tutors as $tutor)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $loop->iteration + ($tutors->currentPage() - 1) * $tutors->perPage() }}</td>
                        <td class="px-4 py-3 font-medium">{{ $tutor->user->name }}</td>
                        <td class="px-4 py-3">{{ $tutor->user->email }}</td>
                        <td class="px-4 py-3">
                            @if($tutor->skills)
                                @foreach(is_array($tutor->skills) ? $tutor->skills : explode(',', $tutor->skills) as $skill)
                                    <span class="inline-block bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full mr-1">{{ trim($skill) }}</span>
                                @endforeach
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $tutor->courses_count }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs
                                {{ $tutor->status === 'approved' ? 'bg-green-100 text-green-700' : ($tutor->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ ucfirst($tutor->status ?? 'pending') }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($tutor->document)
                                <a href="{{ asset('storage/' . $tutor->document) }}" target="_blank" class="text-blue-600 hover:underline">Lihat</a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if(($tutor->status ?? 'pending') === 'pending')
                                <div class="flex gap-2">
                                    <form action="{{ route('admin.tutors.verify', $tutor) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button class="text-green-600 hover:underline text-sm font-medium">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.tutors.verify', $tutor) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button class="text-red-600 hover:underline text-sm font-medium">Reject</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-8 text-center text-gray-500">Belum ada tutor.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $tutors->links() }}</div>
</div>
@endsection

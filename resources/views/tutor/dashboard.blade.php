<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard Tutor</h1>
                <p class="text-gray-600">Selamat datang, {{ auth()->user()->name }}!</p>
                @if($tutor->status === 'pending')
                    <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-yellow-800 font-medium">Akun tutor Anda sedang dalam proses verifikasi oleh admin.</p>
                    </div>
                @elseif($tutor->status === 'rejected')
                    <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-red-800 font-medium">Akun tutor Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.</p>
                    </div>
                @endif
            </div>

            @if($tutor->status === 'approved')
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Chat</p>
                            <p class="text-2xl font-bold">{{ $totalStudents }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Rating</p>
                            <p class="text-2xl font-bold">{{ number_format($tutor->rating, 1) }} <span class="text-sm text-gray-500">({{ $tutor->total_reviews }})</span></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Pendapatan dari Admin</p>
                            <p class="text-2xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <h3 class="font-semibold text-blue-900 mb-2">Layanan Tutor</h3>
                        <p class="text-blue-800 text-sm">Sebagai tutor, Anda menyediakan layanan <strong>Live Chat</strong> untuk membantu siswa belajar secara online dan <strong>Home Visit Booking</strong> untuk sesi belajar offline. Kursus dikelola sepenuhnya oleh admin dan diberikan kepada siswa.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Bookings -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold">Booking Home Visit</h2>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm divide-y">
                        @forelse($bookings as $booking)
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <p class="font-semibold">{{ $booking->user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }} - {{ $booking->time }}</p>
                                        <p class="text-sm text-gray-500">📍 {{ $booking->location }}</p>
                                    </div>
                                    <span class="badge {{ $booking->status === 'confirmed' ? 'badge-green' : ($booking->status === 'pending' ? 'badge-yellow' : ($booking->status === 'completed' ? 'badge-blue' : 'badge-gray')) }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                                @if($booking->status === 'pending')
                                    <div class="flex gap-2 mt-3">
                                        <form method="POST" action="{{ route('bookings.updateStatus', $booking) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="confirmed">
                                            <button class="btn btn-sm bg-green-600 text-white hover:bg-green-700">Terima</button>
                                        </form>
                                        <form method="POST" action="{{ route('bookings.updateStatus', $booking) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button class="btn btn-sm bg-red-600 text-white hover:bg-red-700">Tolak</button>
                                        </form>
                                    </div>
                                @elseif($booking->status === 'confirmed')
                                    <form method="POST" action="{{ route('bookings.updateStatus', $booking) }}" class="mt-3">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button class="btn btn-sm bg-blue-600 text-white hover:bg-blue-700">Tandai Selesai</button>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <div class="p-12 text-center">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-gray-500">Belum ada booking home visit.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Reviews -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold">Review Terbaru</h2>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm divide-y">
                        @forelse($recentReviews as $review)
                            <div class="p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                        <span class="text-xs font-bold text-white">{{ substr($review->user->name, 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <span class="font-semibold text-sm">{{ $review->user->name }}</span>
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-3 h-3 {{ $i <= $review->rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600">{{ $review->comment }}</p>
                            </div>
                        @empty
                            <div class="p-12 text-center">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                <p class="text-gray-500">Belum ada review.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

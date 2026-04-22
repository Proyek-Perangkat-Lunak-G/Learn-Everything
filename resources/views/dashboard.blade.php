<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard Pembelajaran</h1>
                <p class="text-gray-600">Selamat datang kembali, {{ auth()->user()->name }}! Mari lanjutkan pembelajaran Anda.</p>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <a href="{{ route('my-courses.index') }}" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow cursor-pointer">
                    <div class="flex items-center gap-4">
                        <div class="stat-icon stat-icon-blue">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Kursus</p>
                            <p class="text-2xl font-bold">{{ $enrollments->count() }}</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('my-courses.index') }}" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow cursor-pointer">
                    <div class="flex items-center gap-4">
                        <div class="stat-icon stat-icon-green">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Selesai</p>
                            <p class="text-2xl font-bold">{{ $enrollments->where('is_completed', true)->count() }}</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('my-expenses.index') }}" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow cursor-pointer">
                    <div class="flex items-center gap-4">
                        <div class="stat-icon stat-icon-yellow">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Pengeluaran</p>
                            <p class="text-2xl font-bold">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('seller.wallet') }}" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow cursor-pointer">
                    <div class="flex items-center gap-4">
                        <div class="stat-icon stat-icon-purple">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Pendapatan Marketplace</p>
                            <p class="text-2xl font-bold">Rp {{ number_format($salesRevenue, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Subscription Status -->
            @if($subscription && $subscription->isActive())
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-4 mb-8 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="font-semibold text-lg">Paket {{ ucfirst($subscription->package) }}</span>
                            <p class="text-blue-100 text-sm">Berlaku hingga {{ $subscription->expires_at->format('d M Y') }}</p>
                        </div>
                        <span class="badge bg-white text-blue-600">Aktif</span>
                    </div>
                </div>
            @endif

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold">Kursus Saya</h2>
                        <a href="{{ route('courses.index') }}" class="btn btn-outline">Jelajahi Kursus</a>
                    </div>

                    @if($enrollments->isEmpty())
                        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            <h3 class="text-xl font-semibold mb-2">Belum ada kursus</h3>
                            <p class="text-gray-600 mb-6">Mulai perjalanan belajar Anda dengan mendaftar kursus pertama</p>
                            <a href="{{ route('courses.index') }}" class="btn btn-primary">Jelajahi Kursus</a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($enrollments as $enrollment)
                                <div class="bg-white rounded-lg overflow-hidden shadow-sm card-hover">
                                    <div class="flex gap-4 p-4">
                                        <div class="relative w-48 h-32 flex-shrink-0">
                                            @if($enrollment->course->image)
                                                <img src="{{ Storage::url($enrollment->course->image) }}" alt="{{ $enrollment->course->title }}" class="w-full h-full object-cover rounded-lg">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center">
                                                    <svg class="w-10 h-10 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between mb-2">
                                                <div>
                                                    <h3 class="text-lg font-semibold mb-1">{{ $enrollment->course->title }}</h3>
                                                    <p class="text-sm text-gray-600">{{ $enrollment->course->category }} &bull; {{ $enrollment->course->modules->count() }} modul</p>
                                                </div>
                                                @if($enrollment->is_completed)
                                                    <span class="badge badge-green">Selesai</span>
                                                @else
                                                    <span class="badge badge-blue">Berlangsung</span>
                                                @endif
                                            </div>
                                            <div class="mb-3">
                                                <div class="flex items-center justify-between text-sm mb-1">
                                                    <span class="text-gray-600">Progress</span>
                                                    <span class="font-medium">{{ $enrollment->progress }}%</span>
                                                </div>
                                                <div class="progress-bar h-2">
                                                    <div class="progress-bar-fill" style="width: {{ $enrollment->progress }}%"></div>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-600">
                                                    {{ $enrollment->course->modules->count() }} modul
                                                </span>
                                                <div class="flex gap-2">
                                                    @if($enrollment->is_completed)
                                                        <a href="{{ route('certificates.download', $enrollment) }}" class="btn btn-sm bg-green-600 text-white hover:bg-green-700">Sertifikat</a>
                                                    @endif
                                                    @if($enrollment->course->modules->first())
                                                        <a href="{{ route('courses.learn', [$enrollment->course, $enrollment->course->modules->first()]) }}" class="btn btn-primary btn-sm">
                                                            {{ $enrollment->progress == 0 ? 'Mulai Belajar' : ($enrollment->is_completed ? 'Review' : 'Lanjutkan') }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Recent Bookings -->
                    @if($bookings->isNotEmpty())
                    <div class="mt-8">
                        <h2 class="text-2xl font-bold mb-4">Booking Home Visit</h2>
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left py-3 px-4">Tutor</th>
                                        <th class="text-left py-3 px-4">Tanggal</th>
                                        <th class="text-left py-3 px-4">Lokasi</th>
                                        <th class="text-center py-3 px-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                    <tr class="border-t">
                                        <td class="py-3 px-4 font-medium">{{ $booking->tutor->user->name }}</td>
                                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }} {{ $booking->time }}</td>
                                        <td class="py-3 px-4">{{ $booking->location }}</td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="badge {{ $booking->status === 'confirmed' ? 'badge-green' : ($booking->status === 'pending' ? 'badge-yellow' : ($booking->status === 'completed' ? 'badge-blue' : 'badge-gray')) }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Recent Transactions -->
                    <div class="mb-6">
                        <h3 class="text-lg font-bold mb-4">Transaksi Terakhir</h3>
                        @forelse($recentTransactions as $t)
                            <div class="bg-white rounded-lg shadow-sm p-3 mb-2">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-mono text-xs text-gray-500">{{ $t->transaction_code }}</p>
                                        <p class="font-semibold text-sm">Rp {{ number_format($t->total_amount, 0, ',', '.') }}</p>
                                    </div>
                                    <span class="badge {{ $t->status === 'success' ? 'badge-green' : 'badge-yellow' }}">{{ $t->status }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Belum ada transaksi.</p>
                        @endforelse
                    </div>

                    <h3 class="text-lg font-bold mb-4">Aksi Cepat</h3>
                    <div class="space-y-4">
                        <a href="{{ route('courses.index') }}" class="bg-white rounded-lg shadow-sm p-4 flex items-center gap-4 card-hover block">
                            <div class="stat-icon stat-icon-blue">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">Cari Kursus Baru</h4>
                                <p class="text-sm text-gray-600">Jelajahi katalog kursus</p>
                            </div>
                        </a>
                        <a href="{{ route('tutors.index') }}" class="bg-white rounded-lg shadow-sm p-4 flex items-center gap-4 card-hover block">
                            <div class="stat-icon stat-icon-green">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">Temukan Tutor</h4>
                                <p class="text-sm text-gray-600">Belajar dengan tutor profesional</p>
                            </div>
                        </a>
                        <a href="{{ route('chat.index') }}" class="bg-white rounded-lg shadow-sm p-4 flex items-center gap-4 card-hover block">
                            <div class="stat-icon stat-icon-purple">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">Chat</h4>
                                <p class="text-sm text-gray-600">Hubungi tutor atau teman</p>
                            </div>
                        </a>
                        <a href="{{ route('marketplace.index') }}" class="bg-white rounded-lg shadow-sm p-4 flex items-center gap-4 card-hover block">
                            <div class="stat-icon stat-icon-yellow">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">Marketplace</h4>
                                <p class="text-sm text-gray-600">Jual beli produk & jasa</p>
                            </div>
                        </a>
                        <a href="{{ route('my-products.index') }}" class="bg-white rounded-lg shadow-sm p-4 flex items-center gap-4 card-hover block">
                            <div class="stat-icon stat-icon-teal">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">Etalase Saya</h4>
                                <p class="text-sm text-gray-600">Kelola produk yang dijual</p>
                            </div>
                        </a>
                        <a href="{{ route('history') }}" class="bg-white rounded-lg shadow-sm p-4 flex items-center gap-4 card-hover block">
                            <div class="stat-icon stat-icon-green">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">Riwayat & Sertifikat</h4>
                                <p class="text-sm text-gray-600">Lihat riwayat belajar</p>
                            </div>
                        </a>
                        <a href="{{ route('orders.index') }}" class="bg-white rounded-lg shadow-sm p-4 flex items-center gap-4 card-hover block">
                            <div class="stat-icon stat-icon-orange">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">Pesanan Saya</h4>
                                <p class="text-sm text-gray-600">Lihat status pesanan</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

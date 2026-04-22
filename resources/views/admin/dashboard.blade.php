<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Admin Dashboard</h1>
                <p class="text-gray-600">Kelola platform Learn Everything</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Siswa</p>
                            <p class="text-2xl font-bold">{{ $stats['total_users'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Tutor</p>
                            <p class="text-2xl font-bold">{{ $stats['total_tutors'] }}</p>
                            @if($stats['pending_tutors'] > 0)
                                <span class="text-xs text-orange-600">{{ $stats['pending_tutors'] }} pending</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Kursus</p>
                            <p class="text-2xl font-bold">{{ $stats['total_courses'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Pendapatan</p>
                            <p class="text-2xl font-bold">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <p class="text-sm text-gray-600">Total Enrollment</p>
                    <p class="text-2xl font-bold">{{ $stats['total_enrollments'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <p class="text-sm text-gray-600">Produk Marketplace</p>
                    <p class="text-2xl font-bold">{{ $stats['total_products'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <p class="text-sm text-gray-600">Langganan Aktif</p>
                    <p class="text-2xl font-bold">{{ $stats['total_subscriptions'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-primary w-full text-center">Kelola Kursus</a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Pending Tutor Verifications -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold">Verifikasi Tutor</h2>
                        <a href="{{ route('admin.tutors.index') }}" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
                    </div>
                    @forelse($pendingTutors as $tutor)
                        <div class="flex items-center justify-between py-3 border-b last:border-0">
                            <div>
                                <p class="font-medium">{{ $tutor->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $tutor->user->email }}</p>
                                <div class="flex gap-1 mt-1">
                                    @foreach(($tutor->skills ?? []) as $skill)
                                        <span class="badge badge-gray text-xs">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <form method="POST" action="{{ route('admin.tutors.verify', $tutor) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="approved">
                                    <button class="btn btn-sm bg-green-600 text-white hover:bg-green-700">Setujui</button>
                                </form>
                                <form method="POST" action="{{ route('admin.tutors.verify', $tutor) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button class="btn btn-sm bg-red-600 text-white hover:bg-red-700">Tolak</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Tidak ada tutor menunggu verifikasi.</p>
                    @endforelse
                </div>

                <!-- Monthly Revenue Chart -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold mb-4">Pendapatan Bulanan {{ date('Y') }}</h2>
                    <div class="space-y-3">
                        @php
                            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                            $maxRevenue = max(array_values($monthlyRevenue) ?: [1]);
                        @endphp
                        @for($i = 1; $i <= 12; $i++)
                            @php $val = $monthlyRevenue[$i] ?? 0; @endphp
                            <div class="flex items-center gap-3">
                                <span class="w-8 text-xs text-gray-500">{{ $months[$i-1] }}</span>
                                <div class="flex-1 bg-gray-100 rounded-full h-5">
                                    <div class="bg-blue-500 h-5 rounded-full flex items-center justify-end pr-2" style="width: {{ $maxRevenue > 0 ? max(($val / $maxRevenue) * 100, 2) : 0 }}%">
                                        @if($val > 0)
                                            <span class="text-xs text-white font-medium">{{ number_format($val/1000) }}K</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Transactions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold mb-4">Transaksi Terbaru</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-2">Kode</th>
                                    <th class="text-left py-2">User</th>
                                    <th class="text-right py-2">Jumlah</th>
                                    <th class="text-center py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $t)
                                    <tr class="border-b last:border-0">
                                        <td class="py-2 font-mono text-xs">{{ $t->transaction_code }}</td>
                                        <td class="py-2">{{ $t->user->name ?? '-' }}</td>
                                        <td class="py-2 text-right">Rp {{ number_format($t->total_amount, 0, ',', '.') }}</td>
                                        <td class="py-2 text-center">
                                            <span class="badge {{ $t->status === 'success' ? 'badge-green' : 'badge-yellow' }}">{{ $t->status }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-4 text-gray-500">Belum ada transaksi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Enrollments -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold mb-4">Enrollment Terbaru</h2>
                    @forelse($recentEnrollments as $e)
                        <div class="flex items-center justify-between py-3 border-b last:border-0">
                            <div>
                                <p class="font-medium">{{ $e->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $e->course->title }}</p>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium">{{ $e->progress }}%</div>
                                <div class="w-20 bg-gray-200 rounded-full h-2 mt-1">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $e->progress }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Belum ada enrollment.</p>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 grid grid-cols-2 md:grid-cols-5 gap-4">
                <a href="{{ route('admin.courses.index') }}" class="bg-white rounded-lg shadow-sm p-4 text-center card-hover block">
                    <svg class="w-8 h-8 mx-auto mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span class="text-sm font-medium">Kelola Kursus</span>
                </a>
                <a href="{{ route('admin.tutors.index') }}" class="bg-white rounded-lg shadow-sm p-4 text-center card-hover block">
                    <svg class="w-8 h-8 mx-auto mb-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span class="text-sm font-medium">Kelola Tutor</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="bg-white rounded-lg shadow-sm p-4 text-center card-hover block">
                    <svg class="w-8 h-8 mx-auto mb-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span class="text-sm font-medium">Kelola Users</span>
                </a>
                <a href="{{ route('admin.transactions.index') }}" class="bg-white rounded-lg shadow-sm p-4 text-center card-hover block">
                    <svg class="w-8 h-8 mx-auto mb-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-medium">Transaksi</span>
                </a>
                <a href="{{ route('admin.courses.create') }}" class="bg-white rounded-lg shadow-sm p-4 text-center card-hover block">
                    <svg class="w-8 h-8 mx-auto mb-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span class="text-sm font-medium">Tambah Kursus</span>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

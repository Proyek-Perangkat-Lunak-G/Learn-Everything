<x-app-layout>
<div class="space-y-6">
    <!-- Wallet Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Saldo Pending</h3>
                <svg class="w-6 h-6 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-bold">Rp {{ number_format($tutor->wallet_pending, 0, ',', '.') }}</p>
            <p class="text-xs opacity-75 mt-1">Menunggu approval admin</p>
        </div>

        <div class="bg-gradient-to-br from-green-400 to-blue-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Saldo Tersedia</h3>
                <svg class="w-6 h-6 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-bold">Rp {{ number_format($tutor->wallet_available, 0, ',', '.') }}</p>
            <p class="text-xs opacity-75 mt-1">Siap untuk ditarik</p>
        </div>
    </div>

    <!-- Add Availability Form -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-bold mb-4">Tambah Jadwal Baru</h2>
        
        <form method="POST" action="{{ route('tutor.availability.store') }}" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="date" required min="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                    <input type="time" name="start_time" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('start_time') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                    <input type="time" name="end_time" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('end_time') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tambah Jadwal
            </button>
        </form>
    </div>

    <!-- Current Availability -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-bold mb-4">Jadwal Anda Saat Ini</h2>
        
        @if($availabilities->isEmpty())
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-gray-500">Belum ada jadwal ketersediaan</p>
                <p class="text-sm text-gray-400 mt-1">Tambahkan jadwal agar user dapat booking</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($availabilities as $availability)
                    <div class="flex items-center justify-between p-4 border rounded-lg {{ $availability->is_booked ? 'bg-blue-50 border-blue-200' : ($availability->is_available ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200') }}">
                        <div class="flex items-center gap-4">
                            <div class="{{ $availability->is_booked ? 'bg-blue-500' : ($availability->is_available ? 'bg-green-500' : 'bg-gray-400') }} w-2 h-12 rounded-full"></div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $availability->date->translatedFormat('l, d F Y') }}</p>
                                <p class="text-sm text-gray-600">{{ substr($availability->start_time, 0, 5) }} - {{ substr($availability->end_time, 0, 5) }}</p>
                            </div>
                            @if($availability->is_booked)
                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">Sudah Dibooking</span>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-2">
                            @if($availability->is_booked && $availability->booking)
                                <button onclick="showBookingDetail{{ $availability->id }}()" class="px-3 py-1 text-sm bg-blue-500 text-white hover:bg-blue-600 rounded">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </button>
                            @elseif(!$availability->is_booked)
                                <form method="POST" action="{{ route('tutor.availability.toggle', $availability) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 text-sm rounded {{ $availability->is_available ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-blue-100 text-blue-700 hover:bg-blue-200' }}">
                                        {{ $availability->is_available ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                
                                <form method="POST" action="{{ route('tutor.availability.destroy', $availability) }}" onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 text-sm bg-red-100 text-red-700 hover:bg-red-200 rounded">
                                        Hapus
                                    </button>
                                </form>
                            @else
                                <span class="text-sm text-gray-400">Tidak dapat diubah</span>
                            @endif
                        </div>
                    </div>

                    <!-- Booking Detail Modal (Hidden by default) -->
                    @if($availability->is_booked && $availability->booking)
                        <div id="bookingModal{{ $availability->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                                <!-- Modal Header -->
                                <div class="border-b px-6 py-4 flex items-center justify-between bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-t-lg">
                                    <h3 class="text-xl font-bold">Detail Booking</h3>
                                    <button onclick="hideBookingDetail{{ $availability->id }}()" class="text-white hover:text-gray-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Modal Body -->
                                <div class="p-6 space-y-6">
                                    <!-- Status Badge -->
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-500">Status Booking</span>
                                        <span class="px-3 py-1 rounded-full text-sm font-medium
                                            {{ $availability->booking->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $availability->booking->status === 'confirmed' || $availability->booking->status === 'pending' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $availability->booking->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                            {{ ucfirst($availability->booking->status) }}
                                        </span>
                                    </div>

                                    <!-- User Info -->
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            Informasi Pemesan
                                        </h4>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Nama:</span>
                                                <span class="font-medium">{{ $availability->booking->user->name }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Email:</span>
                                                <span class="font-medium">{{ $availability->booking->user->email }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Telepon:</span>
                                                <span class="font-medium">{{ $availability->booking->user->phone ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Schedule Info -->
                                    <div class="bg-blue-50 rounded-lg p-4">
                                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Jadwal & Lokasi
                                        </h4>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Tanggal:</span>
                                                <span class="font-medium">{{ $availability->booking->date->translatedFormat('l, d F Y') }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Waktu:</span>
                                                <span class="font-medium">{{ substr($availability->booking->time, 0, 5) }} - {{ substr($availability->booking->end_time, 0, 5) }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Durasi:</span>
                                                <span class="font-medium">{{ $availability->booking->duration_hours }} jam</span>
                                            </div>
                                            <div class="pt-2 border-t">
                                                <span class="text-gray-600">Lokasi:</span>
                                                <p class="font-medium mt-1">{{ $availability->booking->location }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payment Info -->
                                    <div class="bg-green-50 rounded-lg p-4">
                                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Pembayaran
                                        </h4>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Total:</span>
                                                <span class="font-bold text-green-600 text-lg">Rp {{ number_format($availability->booking->price, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Status Pembayaran:</span>
                                                <span class="px-2 py-1 rounded text-xs {{ $availability->booking->is_paid ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                    {{ $availability->booking->is_paid ? 'Sudah Dibayar' : 'Belum Dibayar' }}
                                                </span>
                                            </div>
                                            @if($availability->booking->is_paid && $availability->booking->paid_at)
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Dibayar pada:</span>
                                                    <span class="font-medium">{{ $availability->booking->paid_at->format('d M Y, H:i') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Notes -->
                                    @if($availability->booking->notes)
                                        <div class="bg-yellow-50 rounded-lg p-4">
                                            <h4 class="font-semibold text-gray-700 mb-2">Catatan dari Pemesan</h4>
                                            <p class="text-sm text-gray-600">{{ $availability->booking->notes }}</p>
                                        </div>
                                    @endif

                                    <!-- Completion Info -->
                                    @if($availability->booking->status === 'completed' && $availability->booking->completed_at)
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                            <div class="flex items-center text-green-700">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="font-medium">Selesai pada {{ $availability->booking->completed_at->format('d M Y, H:i') }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Modal Footer -->
                                <div class="border-t px-6 py-4 bg-gray-50 flex items-center justify-end gap-3 rounded-b-lg">
                                    <button onclick="hideBookingDetail{{ $availability->id }}()" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                        Tutup
                                    </button>
                                    @if($availability->booking->status !== 'completed' && $availability->booking->status !== 'cancelled' && $availability->booking->is_paid)
                                        <form method="POST" action="{{ route('tutor.bookings.complete', $availability->booking) }}" onsubmit="return confirm('Tandai booking ini sebagai selesai?')">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 flex items-center">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Tandai Sudah Selesai
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <script>
                            function showBookingDetail{{ $availability->id }}() {
                                document.getElementById('bookingModal{{ $availability->id }}').classList.remove('hidden');
                            }
                            function hideBookingDetail{{ $availability->id }}() {
                                document.getElementById('bookingModal{{ $availability->id }}').classList.add('hidden');
                            }
                        </script>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>
</x-app-layout>
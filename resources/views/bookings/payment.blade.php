<x-app-layout>
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-3xl font-bold mb-2">Konfirmasi Pembayaran</h1>
            <p class="text-blue-100">Selesaikan pembayaran untuk menyelesaikan booking Anda</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Booking Details -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold mb-4">Detail Booking</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-4 pb-4 border-b">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-2xl font-bold">
                                {{ strtoupper(substr($booking->tutor->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-lg">{{ $booking->tutor->user->name }}</h3>
                                <p class="text-sm text-gray-600">Tutor Professional</p>
                                <div class="flex items-center gap-1 mt-1">
                                    <svg class="w-4 h-4 text-yellow-400 fill-yellow-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span class="text-sm font-medium">{{ number_format($booking->tutor->rating, 1) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Tanggal</p>
                                <p class="font-medium">{{ $booking->date->format('d F Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Waktu</p>
                                <p class="font-medium">{{ $booking->time }} - {{ $booking->end_time }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Durasi</p>
                                <p class="font-medium">{{ $booking->duration_hours }} jam</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Tarif</p>
                                <p class="font-medium">Rp {{ number_format($booking->tutor->hourly_rate, 0, ',', '.') }}/jam</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Lokasi</p>
                            <p class="font-medium">{{ $booking->location }}</p>
                        </div>

                        @if($booking->notes)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Catatan</p>
                                <p class="text-gray-700">{{ $booking->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold mb-4">Metode Pembayaran</h2>

                    <!-- Bank Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <h3 class="font-semibold text-blue-800 mb-3">Informasi Transfer Bank</h3>
                        <div class="space-y-2 text-sm text-blue-700">
                            <div class="flex justify-between">
                                <span class="font-medium">Bank BCA</span>
                                <span class="font-mono">1234567890 a.n. Learn Everything</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Bank BNI</span>
                                <span class="font-mono">0987654321 a.n. Learn Everything</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Bank Mandiri</span>
                                <span class="font-mono">1122334455 a.n. Learn Everything</span>
                            </div>
                        </div>
                    </div>

                    @if($booking->transaction->payment_proof)
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="font-medium text-green-800">Bukti pembayaran sudah diupload</p>
                            </div>
                            <img src="{{ asset('storage/' . $booking->transaction->payment_proof) }}" alt="Bukti Pembayaran" class="max-w-xs rounded-lg border">
                            <p class="text-sm text-green-700 mt-2">Menunggu konfirmasi admin.</p>
                        </div>
                    @else
                        <form method="POST" action="{{ route('bookings.pay', $booking) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload Bukti Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <input type="file" name="payment_proof" accept="image/*" required
                                    class="w-full border border-gray-300 rounded-lg p-2 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB.</p>
                                @error('payment_proof')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="w-full btn btn-primary btn-lg">
                                Upload Bukti &amp; Konfirmasi Pembayaran
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
                    <h3 class="font-bold text-lg mb-4">Ringkasan Pembayaran</h3>
                    
                    <div class="space-y-3 mb-4 pb-4 border-b">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tarif per jam</span>
                            <span class="font-medium">Rp {{ number_format($booking->tutor->hourly_rate, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Durasi</span>
                            <span class="font-medium">{{ $booking->duration_hours }} jam</span>
                        </div>
                    </div>

                    <div class="flex justify-between mb-6">
                        <span class="text-xl font-bold">Total</span>
                        <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($booking->price, 0, ',', '.') }}</span>
                    </div>

                    <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-yellow-700">
                                <p class="font-medium">Menunggu Konfirmasi</p>
                                <p>Setelah upload bukti, admin akan memverifikasi pembayaran Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
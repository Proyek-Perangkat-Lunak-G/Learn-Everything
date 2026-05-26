<x-app-layout>
    <div class="bg-white border-b">
        <div class="max-w-2xl mx-auto px-4 py-6">
            <a href="{{ route('tutors.show', $tutor) }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1 mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Profil Tutor
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Booking Home Visit - {{ $tutor->user->name }}</h1>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-8">
        @if($availabilities->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <h3 class="font-bold text-yellow-800 mb-1">Tidak Ada Jadwal Tersedia</h3>
                        <p class="text-sm text-yellow-700">Tutor ini belum memiliki jadwal yang tersedia. Silakan hubungi tutor melalui chat untuk menanyakan ketersediaan.</p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm p-8">
                <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg mb-6">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm text-blue-800">Tarif: <strong class="text-blue-600">Rp {{ number_format($tutor->hourly_rate, 0, ',', '.') }}/jam</strong></span>
                </div>

                <form method="POST" action="{{ route('bookings.store') }}" x-data="{ selectedSlot: null, selectedPrice: 0 }">
                    @csrf
                    <input type="hidden" name="tutor_id" value="{{ $tutor->id }}">

                    <!-- Slot Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Pilih Jadwal</label>
                        <div class="space-y-3">
                            @foreach($availabilities as $slot)
                                @php
                                    $start = \Carbon\Carbon::parse($slot->start_time);
                                    $end = \Carbon\Carbon::parse($slot->end_time);
                                    $duration = max(1, $end->diffInHours($start));
                                    $slotPrice = $tutor->hourly_rate * $duration;
                                @endphp
                                <label class="flex items-center gap-4 p-4 border-2 rounded-lg cursor-pointer transition-all"
                                       :class="selectedSlot == {{ $slot->id }} ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300'">
                                    <input type="radio" name="availability_id" value="{{ $slot->id }}" required
                                           x-on:change="selectedSlot = {{ $slot->id }}; selectedPrice = {{ $slotPrice }}"
                                           class="text-blue-600 focus:ring-blue-500">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">{{ $slot->date->translatedFormat('l, d F Y') }}</p>
                                        <p class="text-sm text-gray-600">{{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }} ({{ $duration }} jam)</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-blue-600">Rp {{ number_format($slotPrice, 0, ',', '.') }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('availability_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi/Alamat</label>
                        <textarea name="location" rows="3" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Alamat lengkap untuk home visit...">{{ old('location') }}</textarea>
                        @error('location') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                        <textarea name="notes" rows="2" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Topik yang ingin dipelajari...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 mb-6" x-show="selectedSlot">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Biaya</span>
                            <span class="text-lg font-bold text-blue-600" x-text="'Rp ' + selectedPrice.toLocaleString('id-ID')"></span>
                        </div>
                    </div>

                    <button type="submit" class="w-full btn btn-primary btn-lg">Lanjut ke Pembayaran</button>
                </form>
            </div>
        @endif
    </div>
</x-app-layout>
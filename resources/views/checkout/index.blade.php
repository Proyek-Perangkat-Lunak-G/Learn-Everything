<x-app-layout>
    <div class="bg-white border-b">
        <div class="max-w-3xl mx-auto px-4 py-6">
            <h1 class="text-2xl font-bold text-gray-900">Checkout</h1>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-8">
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow-sm p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Pesanan</h3>
            <div class="divide-y mb-6">
                @foreach($cart->items as $item)
                    <div class="py-3">
                        <div class="flex justify-between items-start mb-1">
                            <div class="flex-1">
                                <span class="text-gray-700 font-medium">{{ $item->itemable->title ?? $item->itemable->name }}</span>
                                @if($item->itemable_type === 'App\\Models\\Product')
                                    <span class="ml-2 text-xs px-2 py-0.5 rounded-full
                                        {{ $item->itemable->type === 'physical' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $item->itemable->type === 'digital' ? 'bg-purple-100 text-purple-700' : '' }}
                                        {{ $item->itemable->type === 'service' ? 'bg-green-100 text-green-700' : '' }}">
                                        {{ ucfirst($item->itemable->type) }}
                                    </span>
                                @endif
                            </div>
                            <span class="font-semibold ml-4">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                        </div>
                        <span class="text-sm text-gray-500">Jumlah: {{ $item->quantity }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Digital Products Notice -->
            @php
                $hasDigitalProducts = $cart->items->contains(function($item) {
                    return $item->itemable_type === 'App\\Models\\Product' && $item->itemable->type === 'digital';
                });
            @endphp
            
            @if($hasDigitalProducts)
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-purple-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h4 class="font-semibold text-purple-800 mb-1">Produk Digital</h4>
                            <p class="text-sm text-purple-700">Produk digital akan dikirim melalui email atau dapat diunduh dari halaman "Pesanan Saya" setelah pembayaran dikonfirmasi.</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex justify-between text-xl font-bold border-t pt-4 mb-8">
                <span>Total</span>
                <span class="text-blue-600">Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
            </div>

            <form method="POST" action="{{ route('checkout.process') }}">
                @csrf

                <!-- Shipping Address (only for physical products) -->
                @if($hasPhysicalProducts)
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h4 class="font-semibold text-blue-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Alamat Pengiriman
                        </h4>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Alamat *</label>
                        
                        @if($addresses->isEmpty())
                            <div class="bg-yellow-50 border border-yellow-200 rounded p-3 mb-3">
                                <p class="text-sm text-yellow-700 mb-2">Anda belum memiliki alamat tersimpan.</p>
                                <a href="{{ route('profile.edit') }}" class="text-sm text-blue-600 hover:underline">+ Tambah Alamat Baru</a>
                            </div>
                        @else
                            <select name="address_id" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Alamat --</option>
                                @foreach($addresses as $addr)
                                    <option value="{{ $addr->id }}" {{ $addr->is_default ? 'selected' : '' }}>
                                        {{ $addr->label }} - {{ $addr->address }}, {{ $addr->city }}, {{ $addr->province }} {{ $addr->postal_code }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-2">
                                <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:underline">+ Kelola Alamat</a>
                            </p>
                        @endif
                        @error('address_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                @endif

                <!-- Payment Method -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran *</label>
                    <select name="payment_method" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="transfer">Transfer Bank</option>
                        <option value="ewallet">E-Wallet (GoPay, OVO, DANA)</option>
                    </select>
                </div>

                <button type="submit" class="w-full btn btn-primary btn-lg">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Lanjutkan ke Pembayaran
                </button>
            </form>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    {{-- Header --}}
    <div class="bg-white border-b">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Keranjang Belanja</h1>
            <p class="text-gray-600">Review pesanan Anda sebelum checkout</p>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">{{ session('error') }}</div>
        @endif

        @if($cart && $cart->items->count())
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cart->items as $item)
                        <div class="bg-white rounded-lg shadow-sm p-4">
                            <div class="flex gap-4">
                                <div class="w-24 h-24 rounded-lg bg-gradient-to-br {{ $item->itemable_type === 'App\\Models\\Course' ? 'from-blue-400 to-purple-500' : 'from-green-400 to-teal-500' }} flex items-center justify-center flex-shrink-0">
                                    @if($item->itemable_type === 'App\\Models\\Course')
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    @else
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ $item->itemable->title ?? $item->itemable->name }}</h3>
                                    <p class="text-sm text-gray-500 mb-2">{{ $item->itemable_type === 'App\\Models\\Course' ? 'Kursus' : 'Produk' }} × {{ $item->quantity }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-lg font-bold text-blue-600">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                        <form method="POST" action="{{ route('cart.remove', $item) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 transition p-1">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Summary Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Belanja</h3>
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>{{ $cart->items->count() }} item</span>
                                <span>Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                            </div>
                            <div class="pt-3 border-t">
                                <div class="flex justify-between text-lg font-bold">
                                    <span>Total</span>
                                    <span class="text-blue-600">Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="block w-full btn btn-primary btn-lg text-center mb-3">Lanjut ke Pembayaran</a>
                        <a href="{{ route('courses.index') }}" class="block w-full btn btn-outline text-center">Lanjut Belanja</a>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Keranjang Kosong</h3>
                <p class="text-gray-600 mb-6">Belum ada produk di keranjang Anda</p>
                <div class="flex gap-4 justify-center">
                    <a href="{{ route('courses.index') }}" class="btn btn-primary">Jelajahi Kursus</a>
                    <a href="{{ route('marketplace.index') }}" class="btn btn-outline">Jelajahi Marketplace</a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>

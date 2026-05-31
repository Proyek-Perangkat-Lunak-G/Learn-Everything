<x-app-layout>
    {{-- Header --}}
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Marketplace</h1>
                    <p class="text-gray-600">Jual beli produk, jasa, dan karya kerajinan</p>
                </div>
                @auth
                    <a href="{{ route('marketplace.create') }}" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Unggah Produk
                    </a>
                @endauth
            </div>

            <div class="flex flex-col md:flex-row gap-4">
                <form action="{{ route('marketplace.index') }}" method="GET" class="flex-1 flex gap-3">
                    <div class="flex-1 relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk atau jasa..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <select name="type"
                        class="appearance-none bg-white border border-gray-300 rounded-lg px-3 py-2 pr-10 w-35 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Tipe</option>
                        <option value="physical" {{ request('type') === 'physical' ? 'selected' : '' }}>Fisik</option>
                        <option value="digital" {{ request('type') === 'digital' ? 'selected' : '' }}>Digital</option>
                        <option value="service" {{ request('type') === 'service' ? 'selected' : '' }}>Jasa</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($products as $product)
                <a href="{{ route('marketplace.show', $product) }}" class="bg-white rounded-lg shadow-sm overflow-hidden card-hover block">
                    @if($product->image)
                        <div class="relative h-48">
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            <div class="absolute top-3 right-3">
                                <span class="badge badge-blue">{{ ucfirst($product->type ?? 'physical') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="relative h-48 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                            <svg class="w-12 h-12 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <div class="absolute top-3 right-3">
                                <span class="badge badge-blue">{{ ucfirst($product->type ?? 'physical') }}</span>
                            </div>
                        </div>
                    @endif
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-500 mb-3">Oleh: {{ $product->seller->name }}</p>

                        @if($product->stock !== null)
                            <p class="text-xs text-gray-500 mb-3">Stok: {{ $product->stock }}</p>
                        @endif

                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-blue-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @auth
                                @if($product->seller_id !== auth()->id())
                                    <form method="POST" action="{{ route('cart.addProduct') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-500 px-2 py-1 bg-gray-100 rounded">Produk Anda</span>
                                @endif
                            @endauth
                        </div>
                        @auth
                            @if($product->seller_id === auth()->id())
                                <span onclick="event.preventDefault(); window.location='{{ route('marketplace.edit', $product) }}'" class="mt-2 block text-center text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg px-4 py-2 transition duration-200">Edit Produk</span>
                            @endif
                        @endauth
                    </div>
                </a>
            @empty
                <div class="col-span-4 text-center py-12 bg-white rounded-lg shadow-sm">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <p class="text-gray-500 text-lg mb-4">Tidak ada produk yang sesuai</p>
                    <a href="{{ route('marketplace.create') }}" class="btn btn-primary">Unggah Produk Pertama</a>
                </div>
            @endforelse
        </div>
        <div class="mt-8">{{ $products->links() }}</div>
    </div>
</x-app-layout>

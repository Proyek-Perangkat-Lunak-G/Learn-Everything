<x-app-layout>
    <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Etalase Saya</h1>
                    <p class="text-green-100">Kelola produk marketplace Anda</p>
                </div>
                <a href="{{ route('marketplace.create') }}" class="bg-white text-green-600 px-6 py-3 rounded-lg font-medium hover:bg-green-50 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Produk
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <p class="text-sm text-gray-600 mb-1">Total Produk</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalProducts }}</p>
            </div>
            <div class="bg-green-50 rounded-lg shadow-sm p-6 text-center">
                <p class="text-sm text-green-600 mb-1">Aktif</p>
                <p class="text-2xl font-bold text-green-700">{{ $activeProducts }}</p>
            </div>
            <div class="bg-red-50 rounded-lg shadow-sm p-6 text-center">
                <p class="text-sm text-red-600 mb-1">Nonaktif</p>
                <p class="text-2xl font-bold text-red-700">{{ $inactiveProducts }}</p>
            </div>
            <div class="bg-blue-50 rounded-lg shadow-sm p-6 text-center">
                <p class="text-sm text-blue-600 mb-1">Digital</p>
                <p class="text-2xl font-bold text-blue-700">{{ $digitalProducts }}</p>
            </div>
            <div class="bg-purple-50 rounded-lg shadow-sm p-6 text-center">
                <p class="text-sm text-purple-600 mb-1">Fisik</p>
                <p class="text-2xl font-bold text-purple-700">{{ $physicalProducts }}</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Produk</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau deskripsi..." class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                    <select name="type" class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                        <option value="">Semua Tipe</option>
                        <option value="digital" {{ request('type') === 'digital' ? 'selected' : '' }}>Digital</option>
                        <option value="physical" {{ request('type') === 'physical' ? 'selected' : '' }}>Fisik</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 btn btn-primary">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'type', 'status']))
                        <a href="{{ route('my-products.index') }}" class="btn btn-outline">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Products Grid -->
        @if($products->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <h3 class="text-xl font-semibold mb-2">Belum ada produk</h3>
                <p class="text-gray-600 mb-6">Mulai jual produk Anda di marketplace</p>
                <a href="{{ route('marketplace.create') }}" class="btn btn-primary">Tambah Produk Pertama</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow {{ !$product->is_active ? 'opacity-60' : '' }}">
                        <!-- Product Image -->
                        <div class="relative h-48 bg-gradient-to-r from-green-500 to-teal-500">
                            @if($product->image)
                                <img src="{{ \Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Badges -->
                            <div class="absolute top-3 left-3 flex flex-col gap-2">
                                @if($product->type === 'digital')
                                    <span class="px-2 py-1 bg-blue-500 text-white text-xs font-medium rounded">Digital</span>
                                @else
                                    <span class="px-2 py-1 bg-purple-500 text-white text-xs font-medium rounded">Fisik</span>
                                @endif
                                
                                @if(!$product->is_active)
                                    <span class="px-2 py-1 bg-red-500 text-white text-xs font-medium rounded">Nonaktif</span>
                                @endif
                            </div>

                            @if($product->stock <= 5 && $product->type === 'physical')
                                <span class="absolute top-3 right-3 px-2 py-1 bg-yellow-500 text-white text-xs font-medium rounded">Stok: {{ $product->stock }}</span>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-1 line-clamp-1">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $product->description }}</p>
                            
                            <div class="flex items-center justify-between mb-4">
                                <p class="font-bold text-green-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                @if($product->type === 'physical')
                                    <p class="text-sm text-gray-500">Stok: {{ $product->stock }}</p>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <a href="{{ route('marketplace.show', $product) }}" class="flex-1 btn btn-outline btn-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('marketplace.edit', $product) }}" class="flex-1 btn btn-primary btn-sm">Edit</a>
                            </div>
                            
                            <!-- Toggle & Delete -->
                            <div class="flex gap-2 mt-2">
                                <form method="POST" action="{{ route('marketplace.toggle', $product) }}" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full btn {{ $product->is_active ? 'btn-warning' : 'btn-success' }} btn-sm">
                                        {{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('marketplace.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
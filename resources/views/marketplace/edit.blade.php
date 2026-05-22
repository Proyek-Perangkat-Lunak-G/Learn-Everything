<x-app-layout>
    <div class="bg-white border-b">
        <div class="max-w-2xl mx-auto px-4 py-6">
            <a href="{{ route('marketplace.index') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1 mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Marketplace
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Edit Produk</h1>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-sm p-8">
            <form method="POST" action="{{ route('marketplace.update', $product) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="4" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('description', $product->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required min="0" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        @error('stock') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Produk</label>
                    <select name="type" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="physical" {{ old('type', $product->type) === 'physical' ? 'selected' : '' }}>Fisik</option>
                        <option value="digital" {{ old('type', $product->type) === 'digital' ? 'selected' : '' }}>Digital</option>
                        <option value="service" {{ old('type', $product->type) === 'service' ? 'selected' : '' }}>Jasa</option>
                    </select>
                    @error('type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                @if($product->image)
                    <div class="mb-4">
                        <img src="{{ Storage::url($product->image) }}" class="w-32 h-32 object-cover rounded-lg">
                    </div>
                @endif
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ganti Foto</label>
                    <input type="file" name="image" accept="image/*" class="w-full border border-gray-300 rounded-lg p-2">
                    @error('image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="w-full btn btn-primary btn-lg">Perbarui Produk</button>
            </form>
            <form method="POST" action="{{ route('marketplace.destroy', $product) }}" class="mt-3" onsubmit="return confirm('Hapus produk ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition">Hapus Produk</button>
            </form>
        </div>
    </div>
</x-app-layout>
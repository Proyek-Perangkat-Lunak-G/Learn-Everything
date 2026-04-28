<x-app-layout>
    <div class="bg-white border-b">
        <div class="max-w-2xl mx-auto px-4 py-6">
            <a href="{{ route('marketplace.index') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1 mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Marketplace
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Unggah Produk</h1>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-sm p-8">
            <form method="POST" action="{{ route('marketplace.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="4" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                        <input type="number" name="price" value="{{ old('price') }}" required min="0" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                        <input type="number" name="stock" value="{{ old('stock', 1) }}" required min="0" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        @error('stock') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Produk</label>
                    <select name="type" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="physical" {{ old('type') === 'physical' ? 'selected' : '' }}>Fisik</option>
                        <option value="digital" {{ old('type') === 'digital' ? 'selected' : '' }}>Digital</option>
                        <option value="service" {{ old('type') === 'service' ? 'selected' : '' }}>Jasa</option>
                    </select>
                    @error('type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Produk</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition">
                        <input type="file" name="image" accept="image/*" class="hidden" id="product-image" onchange="document.getElementById('file-label').textContent = this.files[0]?.name || 'Klik untuk upload foto'">
                        <label for="product-image" class="cursor-pointer">
                            <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p id="file-label" class="text-sm text-gray-600">Klik untuk upload foto</p>
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG max 5MB</p>
                        </label>
                    </div>
                    @error('image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="w-full btn btn-primary btn-lg">Unggah Produk</button>
            </form>
        </div>
    </div>
</x-app-layout>
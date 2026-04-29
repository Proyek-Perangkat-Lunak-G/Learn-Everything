<x-app-layout>
    <div class="bg-white border-b">
        <div class="max-w-3xl mx-auto px-4 py-6">
            <h1 class="text-2xl font-bold text-gray-900">Alamat Saya</h1>
            <p class="text-gray-600 mt-1">Kelola alamat pengiriman dan home visit</p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
        @endif

        {{-- Add Address --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h3 class="font-semibold text-gray-900 mb-3">Tambah Alamat Baru</h3>
            <form method="POST" action="{{ route('addresses.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                    <input type="text" name="label" placeholder="Label (Rumah, Kantor...)" required class="border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <input type="text" name="city" placeholder="Kota" required class="border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <input type="text" name="postal_code" placeholder="Kode Pos" required class="border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <textarea name="address" rows="2" required placeholder="Alamat lengkap..." class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 mb-3"></textarea>
                @error('address') <p class="text-red-500 text-sm mb-2">{{ $message }}</p> @enderror
                <button type="submit" class="btn btn-primary">Tambah Alamat</button>
            </form>
        </div>

        {{-- Existing Addresses --}}
        <div class="space-y-4">
            @forelse($addresses as $address)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="badge badge-blue">{{ $address->label }}</span>
                            <p class="text-gray-700 mt-2">{{ $address->address }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ $address->city }} {{ $address->postal_code }}</p>
                        </div>
                        <form method="POST" action="{{ route('addresses.destroy', $address) }}" onsubmit="return confirm('Hapus alamat ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <p class="text-gray-500">Belum ada alamat tersimpan.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <a href="{{ route('marketplace.index') }}" class="text-blue-600 hover:underline text-sm mb-4 inline-block">← Kembali ke Marketplace</a>

    <div class="bg-white rounded-xl shadow overflow-hidden md:flex">
        @if($product->image)
            <div class="md:w-1/2">
                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-80 object-cover" alt="{{ $product->name }}">
            </div>
        @endif
        <div class="p-6 {{ $product->image ? 'md:w-1/2' : 'w-full' }}">
            @if($product->type ?? false)
                <span class="inline-block bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full mb-2">{{ ucfirst($product->type) }}</span>
            @endif
            <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>
            <p class="text-3xl font-bold text-blue-600 mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            <p class="text-gray-600 mb-4">{{ $product->description }}</p>

            <div class="text-sm text-gray-500 space-y-1 mb-6">
                <p>Penjual: <strong>{{ $product->seller->name }}</strong></p>
                <p>Stok: <strong>{{ $product->stock }}</strong></p>
            </div>

            @auth
                @if($product->seller_id === auth()->id())
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                        <p class="text-gray-600 font-medium">Ini adalah produk Anda</p>
                        <a href="{{ route('marketplace.edit', $product) }}" class="mt-3 inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition duration-200 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">Edit Produk</a>
                    </div>
                @elseif($product->stock > 0)
                    <form action="{{ route('cart.addProduct') }}" method="POST" class="mb-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="btn btn-primary w-full">Tambah ke Keranjang</button>
                    </form>
                    <a href="{{ route('chat.show', $product->seller) }}" class="btn btn-outline w-full flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Chat Penjual
                    </a>
                @else
                    <p class="text-red-500 font-medium mb-3">Stok habis</p>
                    <a href="{{ route('chat.show', $product->seller) }}" class="btn btn-outline w-full flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Chat Penjual
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-primary w-full block text-center">Login untuk Membeli</a>
            @endauth
        </div>
    </div>
</div>
@endsection
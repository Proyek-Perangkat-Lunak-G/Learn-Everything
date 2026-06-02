@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Riwayat Transaksi</h1>
        <p class="text-lg font-semibold text-green-600">Total Revenue: Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>

    <form class="flex flex-wrap gap-3 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode transaksi..." class="rounded-lg border-gray-300 focus:ring-blue-500">
        <select name="status" class="rounded-lg border-gray-300 focus:ring-blue-500">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
        </select>
        <select name="type" class="rounded-lg border-gray-300 focus:ring-blue-500">
            <option value="">Semua Tipe</option>
            <option value="purchase" {{ request('type') === 'purchase' ? 'selected' : '' }}>E-Commerce</option>
            <option value="subscription" {{ request('type') === 'subscription' ? 'selected' : '' }}>Langganan</option>
            <option value="booking" {{ request('type') === 'booking' ? 'selected' : '' }}>Booking Tutor</option>
        </select>
        <button class="btn btn-primary btn-sm">Filter</button>
    </form>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">#</th>
                    <th class="px-4 py-3 text-left">Kode</th>
                    <th class="px-4 py-3 text-left">Tipe</th>
                    <th class="px-4 py-3 text-left">User</th>
                    <th class="px-4 py-3 text-left">Detail Pembelian</th>
                    <th class="px-4 py-3 text-left">Total</th>
                    <th class="px-4 py-3 text-left">Bukti</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($transactions as $tx)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</td>
                        <td class="px-4 py-3 font-mono text-xs">{{ $tx->transaction_code }}</td>
                        <td class="px-4 py-3">
                            @if($tx->type === 'booking')
                                <span class="px-2 py-1 rounded-full text-xs bg-purple-100 text-purple-700">Booking Tutor</span>
                            @elseif($tx->type === 'subscription')
                                <span class="px-2 py-1 rounded-full text-xs bg-indigo-100 text-indigo-700">Langganan</span>
                            @elseif($tx->type === 'purchase')
                                @php
                                    $items = $tx->items ?? [];
                                    $hasCourse = collect($items)->contains('type', 'Course');
                                    $hasProduct = collect($items)->contains('type', 'Product');
                                @endphp
                                @if($hasCourse && $hasProduct)
                                    <span class="px-2 py-1 rounded-full text-xs bg-orange-100 text-orange-700">Kursus + Produk</span>
                                @elseif($hasCourse)
                                    <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700">Kursus</span>
                                @elseif($hasProduct)
                                    <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">E-Commerce</span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-700">Pembelian</span>
                                @endif
                            @else
                                <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-700">{{ ucfirst($tx->type ?? '-') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $tx->user->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <button onclick="showTransactionDetail{{ $tx->id }}()" class="px-3 py-1.5 text-xs bg-blue-500 text-white hover:bg-blue-600 rounded flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat Detail
                            </button>
                        </td>
                        <td class="px-4 py-3 font-medium">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            @if($tx->payment_proof)
                                <a href="{{ asset('storage/' . $tx->payment_proof) }}" target="_blank" class="text-blue-600 hover:underline text-xs">Lihat Bukti</a>
                            @else
                                <span class="text-gray-400 text-xs">Belum ada</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs
                                {{ $tx->status === 'success' ? 'bg-green-100 text-green-700' : ($tx->status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ ucfirst($tx->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $tx->created_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3">
                            @if($tx->status === 'pending' && $tx->payment_proof)
                                <div class="flex gap-1">
                                    <form method="POST" action="{{ route('admin.transactions.approve', $tx) }}">
                                        @csrf
                                        <button type="submit" class="px-2 py-1 text-xs bg-green-100 text-green-700 hover:bg-green-200 rounded" onclick="return confirm('Konfirmasi pembayaran ini?')">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.transactions.reject', $tx) }}">
                                        @csrf
                                        <button type="submit" class="px-2 py-1 text-xs bg-red-100 text-red-700 hover:bg-red-200 rounded" onclick="return confirm('Tolak pembayaran ini?')">Reject</button>
                                    </form>
                                </div>
                            @elseif($tx->status === 'pending')
                                <span class="text-xs text-gray-400">Menunggu bukti</span>
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="10" class="px-4 py-8 text-center text-gray-500">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $transactions->links() }}</div>

    <!-- Transaction Detail Modals -->
    @foreach($transactions as $tx)
        <div id="transactionModal{{ $tx->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 rounded-t-lg flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold">Detail Transaksi</h3>
                        <p class="text-sm opacity-90 mt-1">{{ $tx->transaction_code }}</p>
                    </div>
                    <button onclick="hideTransactionDetail{{ $tx->id }}()" class="text-white hover:bg-white/20 rounded-full p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-6">
                    <!-- Status & Type -->
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Tipe Transaksi</p>
                            @if($tx->type === 'booking')
                                <span class="px-3 py-1.5 rounded-lg text-sm font-medium bg-purple-100 text-purple-700">Booking Tutor</span>
                            @elseif($tx->type === 'subscription')
                                <span class="px-3 py-1.5 rounded-lg text-sm font-medium bg-indigo-100 text-indigo-700">Langganan</span>
                            @elseif($tx->type === 'purchase')
                                @php
                                    $items = $tx->items ?? [];
                                    $hasCourse = collect($items)->contains('type', 'Course');
                                    $hasProduct = collect($items)->contains('type', 'Product');
                                @endphp
                                @if($hasCourse && $hasProduct)
                                    <span class="px-3 py-1.5 rounded-lg text-sm font-medium bg-orange-100 text-orange-700">Kursus + Produk</span>
                                @elseif($hasCourse)
                                    <span class="px-3 py-1.5 rounded-lg text-sm font-medium bg-blue-100 text-blue-700">Pembelian Kursus</span>
                                @elseif($hasProduct)
                                    <span class="px-3 py-1.5 rounded-lg text-sm font-medium bg-green-100 text-green-700">E-Commerce</span>
                                @else
                                    <span class="px-3 py-1.5 rounded-lg text-sm font-medium bg-gray-100 text-gray-700">Pembelian</span>
                                @endif
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 mb-1">Status</p>
                            <span class="px-3 py-1.5 rounded-lg text-sm font-medium inline-block
                                {{ $tx->status === 'success' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $tx->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $tx->status === 'failed' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ $tx->status === 'success' ? 'Berhasil' : '' }}
                                {{ $tx->status === 'pending' ? 'Pending' : '' }}
                                {{ $tx->status === 'failed' ? 'Gagal' : '' }}
                            </span>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Informasi Pembeli
                        </h4>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="text-gray-600">Nama:</span>
                                <p class="font-medium">{{ $tx->user->name ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Email:</span>
                                <p class="font-medium">{{ $tx->user->email ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Items Purchased -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Detail Pembelian
                        </h4>
                        @if($tx->items && count($tx->items) > 0)
                            <div class="space-y-2">
                                @foreach($tx->items as $item)
                                    <div class="flex justify-between items-center bg-white rounded p-3">
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-800">{{ $item['name'] ?? '-' }}</p>
                                            <p class="text-xs text-gray-500">{{ $item['type'] ?? '-' }} @if(($item['quantity'] ?? 1) > 1) • Qty: {{ $item['quantity'] }} @endif</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-gray-700">Rp {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}</p>
                                            <p class="text-xs text-gray-500">@ Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Tidak ada detail item</p>
                        @endif
                    </div>

                    <!-- Shipping Address (if physical products) -->
                    @if($tx->shipping_address)
                        <div class="bg-green-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Alamat Pengiriman
                            </h4>
                            <p class="text-sm">{{ $tx->shipping_address }}</p>
                            @if($tx->shipping_phone)
                                <p class="text-sm text-gray-600 mt-1">Telp: {{ $tx->shipping_phone }}</p>
                            @endif
                        </div>
                    @endif

                    <!-- Payment Info -->
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Informasi Pembayaran
                        </h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Metode Pembayaran:</span>
                                <span class="font-medium">{{ ucfirst($tx->payment_method ?? '-') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Bukti Pembayaran:</span>
                                @if($tx->payment_proof)
                                    <a href="{{ asset('storage/' . $tx->payment_proof) }}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-gray-400">Belum upload</span>
                                @endif
                            </div>
                            <div class="flex justify-between text-lg font-bold border-t pt-2 mt-2">
                                <span>Total Pembayaran:</span>
                                <span class="text-blue-600">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Timestamp -->
                    <div class="text-sm text-gray-500 text-center">
                        <p>Transaksi dibuat: {{ $tx->created_at->format('d F Y, H:i') }}</p>
                        @if($tx->updated_at != $tx->created_at)
                            <p>Terakhir diupdate: {{ $tx->updated_at->format('d F Y, H:i') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="border-t bg-gray-50 px-6 py-4 rounded-b-lg flex items-center justify-end gap-3">
                    <button onclick="hideTransactionDetail{{ $tx->id }}()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Tutup
                    </button>
                    @if($tx->status === 'pending' && $tx->payment_proof)
                        <form method="POST" action="{{ route('admin.transactions.approve', $tx) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600" onclick="return confirm('Konfirmasi pembayaran ini?')">
                                Approve Pembayaran
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.transactions.reject', $tx) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600" onclick="return confirm('Tolak pembayaran ini?')">
                                Tolak
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <script>
            function showTransactionDetail{{ $tx->id }}() {
                document.getElementById('transactionModal{{ $tx->id }}').classList.remove('hidden');
            }
            function hideTransactionDetail{{ $tx->id }}() {
                document.getElementById('transactionModal{{ $tx->id }}').classList.add('hidden');
            }
        </script>
    @endforeach
</div>
@endsection

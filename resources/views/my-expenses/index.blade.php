<x-app-layout>
    <div class="bg-gradient-to-r from-yellow-600 to-orange-600 text-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-2">Riwayat Pengeluaran</h1>
            <p class="text-yellow-100">Detail semua transaksi dan pembelian Anda</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <p class="text-sm text-gray-600 mb-1">Total Pengeluaran</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
            </div>
            <div class="bg-blue-50 rounded-lg shadow-sm p-6 text-center">
                <p class="text-sm text-blue-600 mb-1">Kursus</p>
                <p class="text-xl font-bold text-blue-700">Rp {{ number_format($courseSpent, 0, ',', '.') }}</p>
            </div>
            <div class="bg-green-50 rounded-lg shadow-sm p-6 text-center">
                <p class="text-sm text-green-600 mb-1">Produk</p>
                <p class="text-xl font-bold text-green-700">Rp {{ number_format($productSpent, 0, ',', '.') }}</p>
            </div>
            <div class="bg-purple-50 rounded-lg shadow-sm p-6 text-center">
                <p class="text-sm text-purple-600 mb-1">Booking Tutor</p>
                <p class="text-xl font-bold text-purple-700">Rp {{ number_format($bookingSpent, 0, ',', '.') }}</p>
            </div>
            <div class="bg-indigo-50 rounded-lg shadow-sm p-6 text-center">
                <p class="text-sm text-indigo-600 mb-1">Langganan</p>
                <p class="text-xl font-bold text-indigo-700">Rp {{ number_format($subscriptionSpent, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Transactions List -->
        @if($transactions->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-xl font-semibold mb-2">Belum ada transaksi</h3>
                <p class="text-gray-600">Semua transaksi yang berhasil akan muncul di sini</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Kode Transaksi</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Tipe</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Detail</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Total</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($transactions as $tx)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <p class="font-mono text-xs text-gray-900">{{ $tx->transaction_code }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if($tx->type === 'booking')
                                        <span class="px-2 py-1 rounded-full text-xs bg-purple-100 text-purple-700 font-medium">Booking Tutor</span>
                                    @elseif($tx->type === 'subscription')
                                        <span class="px-2 py-1 rounded-full text-xs bg-indigo-100 text-indigo-700 font-medium">Langganan</span>
                                    @elseif($tx->type === 'purchase')
                                        @php
                                            $items = $tx->items ?? [];
                                            $hasCourse = collect($items)->contains('type', 'Course');
                                            $hasProduct = collect($items)->contains('type', 'Product');
                                        @endphp
                                        @if($hasCourse && $hasProduct)
                                            <span class="px-2 py-1 rounded-full text-xs bg-orange-100 text-orange-700 font-medium">Kursus + Produk</span>
                                        @elseif($hasCourse)
                                            <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700 font-medium">Kursus</span>
                                        @elseif($hasProduct)
                                            <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700 font-medium">Produk</span>
                                        @else
                                            <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-700 font-medium">Pembelian</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($tx->items && count($tx->items) > 0)
                                        <div class="space-y-1">
                                            @foreach($tx->items as $item)
                                                <p class="text-sm text-gray-700">
                                                    {{ $item['name'] ?? '-' }}
                                                    @if(($item['quantity'] ?? 1) > 1)
                                                        <span class="text-gray-500">({{ $item['quantity'] }}x)</span>
                                                    @endif
                                                </p>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                    @if($tx->shipping_address)
                                        <p class="text-xs text-gray-500 mt-1">
                                            <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            Dikirim ke: {{ \Str::limit($tx->shipping_address, 40) }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-900">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-500">{{ ucfirst($tx->payment_method ?? 'transfer') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-700">{{ $tx->created_at->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $tx->created_at->format('H:i') }}</p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

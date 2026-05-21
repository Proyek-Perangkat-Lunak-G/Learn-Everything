<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Pesanan Saya</h1>

        <form class="flex flex-wrap gap-3 mb-6">
            <select name="type" class="rounded-lg border-gray-300 focus:ring-blue-500">
                <option value="">Semua Tipe</option>
                <option value="purchase" {{ request('type') === 'purchase' ? 'selected' : '' }}>Pembelian</option>
                <option value="subscription" {{ request('type') === 'subscription' ? 'selected' : '' }}>Langganan</option>
                <option value="booking" {{ request('type') === 'booking' ? 'selected' : '' }}>Booking Tutor</option>
            </select>
            <select name="status" class="rounded-lg border-gray-300 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Berhasil</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
            </select>
            <button class="btn btn-primary btn-sm">Filter</button>
        </form>

        @if($transactions->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <h3 class="text-xl font-semibold mb-2">Belum ada pesanan</h3>
                <p class="text-gray-600">Pesanan Anda akan muncul di sini setelah melakukan pembelian.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($transactions as $tx)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <!-- Header -->
                        <div class="flex items-center justify-between px-6 py-3 bg-gray-50 border-b">
                            <div class="flex items-center gap-4">
                                <span class="font-mono text-xs text-gray-500">{{ $tx->transaction_code }}</span>
                                @if($tx->type === 'booking')
                                    <span class="px-2 py-1 rounded-full text-xs bg-purple-100 text-purple-700">Booking Tutor</span>
                                @elseif($tx->type === 'subscription')
                                    <span class="px-2 py-1 rounded-full text-xs bg-indigo-100 text-indigo-700">Langganan</span>
                                @elseif($tx->type === 'purchase')
                                    <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Pembelian</span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-700">{{ ucfirst($tx->type ?? '-') }}</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-gray-500">{{ $tx->created_at->format('d M Y H:i') }}</span>
                                <span class="px-2 py-1 rounded-full text-xs
                                    {{ $tx->status === 'success' ? 'bg-green-100 text-green-700' : ($tx->status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ $tx->status === 'success' ? 'Berhasil' : ($tx->status === 'failed' ? 'Gagal' : 'Menunggu') }}
                                </span>
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="px-6 py-4">
                            @if($tx->items && count($tx->items) > 0)
                                @foreach($tx->items as $item)
                                    <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b' : '' }}">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $item['name'] ?? '-' }}</p>
                                            <p class="text-xs text-gray-500">{{ $item['type'] ?? '' }} @if(($item['quantity'] ?? 1) > 1) &bull; Qty: {{ $item['quantity'] }} @endif</p>
                                        </div>
                                        <p class="font-medium text-gray-700">Rp {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}</p>
                                    </div>
                                @endforeach
                            @elseif($tx->notes)
                                <p class="text-sm text-gray-600">{{ $tx->notes }}</p>
                            @endif
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-between px-6 py-3 bg-gray-50 border-t">
                            <div class="flex items-center gap-3 flex-wrap">
                                @if($tx->status === 'pending' && !$tx->payment_proof)
                                    <a href="{{ route('transactions.upload-proof', $tx) }}" class="text-sm text-blue-600 hover:underline">Upload Bukti Pembayaran</a>
                                @elseif($tx->status === 'pending' && $tx->payment_proof)
                                    <span class="text-sm text-yellow-600">Menunggu verifikasi admin</span>
                                @elseif($tx->status === 'success' && $tx->type === 'purchase')
                                    <span class="text-sm text-green-600 font-medium">Pembayaran terverifikasi</span>
                                    @php
                                        $hasDigital = collect($tx->items ?? [])->contains(fn($i) => ($i['type'] ?? '') === 'Product');
                                        $sellerUserId = null;
                                        if ($hasDigital) {
                                            $firstProductId = collect($tx->items)->firstWhere('type', 'Product')['id'] ?? null;
                                            $sellerUserId = $firstProductId ? \App\Models\Product::find($firstProductId)?->seller_id : null;
                                        }
                                    @endphp
                                    @if($hasDigital && $sellerUserId)
                                        <a href="{{ route('chat.show', $sellerUserId) }}" class="inline-flex items-center gap-1 text-sm text-blue-600 hover:underline">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                            Chat Penjual untuk terima file
                                        </a>
                                    @endif
                                @endif
                            </div>
                            <p class="text-lg font-bold text-gray-900">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</p>
                        </div>

                        {{-- Status Steps for purchase --}}
                        @if($tx->type === 'purchase')
                        <div class="px-6 py-3 border-t bg-white">
                            <div class="flex items-center gap-0 text-xs">
                                @php
                                    $step = match(true) {
                                        $tx->status === 'failed' => -1,
                                        $tx->status === 'pending' && !$tx->payment_proof => 0,
                                        $tx->status === 'pending' && $tx->payment_proof => 1,
                                        $tx->status === 'success' => 2,
                                        default => 0,
                                    };
                                @endphp
                                @foreach([0 => 'Menunggu Bayar', 1 => 'Verifikasi Admin', 2 => 'Selesai'] as $s => $label)
                                    <div class="flex items-center {{ $loop->last ? '' : 'flex-1' }}">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 text-white text-xs
                                            {{ $step >= $s ? 'bg-blue-600' : 'bg-gray-200 text-gray-500' }}">
                                            @if($step > $s)
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            @else
                                                {{ $s + 1 }}
                                            @endif
                                        </div>
                                        <span class="ml-1 {{ $step >= $s ? 'text-blue-600 font-medium' : 'text-gray-400' }}">{{ $label }}</span>
                                        @if(!$loop->last)
                                            <div class="flex-1 h-px mx-2 {{ $step > $s ? 'bg-blue-600' : 'bg-gray-200' }}"></div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $transactions->links() }}</div>
        @endif
    </div>
</x-app-layout>

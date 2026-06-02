<x-app-layout>
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-2">Pendapatan Marketplace</h1>
            <p class="text-purple-100">Kelola pendapatan dari penjualan produk Anda</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">{{ session('error') }}</div>
        @endif

        <!-- Wallet Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium opacity-90">Saldo Pending</h3>
                    <svg class="w-6 h-6 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-3xl font-bold">Rp {{ number_format($user->wallet_pending, 0, ',', '.') }}</p>
                <p class="text-xs opacity-75 mt-1">Menunggu konfirmasi admin</p>
            </div>

            <div class="bg-gradient-to-br from-purple-400 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium opacity-90">Total Pendapatan</h3>
                    <svg class="w-6 h-6 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <p class="text-3xl font-bold">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</p>
                <p class="text-xs opacity-75 mt-1">Dari {{ count($salesDetails) }} transaksi</p>
            </div>
        </div>

        <!-- Withdrawal Form -->
        @if($user->wallet_available > 0)
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <h2 class="text-xl font-bold mb-4">Tarik Saldo</h2>
                <form method="POST" action="{{ route('seller.wallet.withdraw') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Penarikan (Rp) *</label>
                        <input type="number" name="amount" required min="10000" max="{{ $user->wallet_available }}" step="1000" class="w-full border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" placeholder="Minimal Rp 10.000">
                        <p class="text-xs text-gray-500 mt-1">Maksimal: Rp {{ number_format($user->wallet_available, 0, ',', '.') }}</p>
                        @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bank *</label>
                        <input type="text" name="bank_name" required class="w-full border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" placeholder="BCA, Mandiri, BNI, dll">
                        @error('bank_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening *</label>
                        <input type="text" name="bank_account_number" required class="w-full border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" placeholder="1234567890">
                        @error('bank_account_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pemilik Rekening *</label>
                        <input type="text" name="bank_account_holder" required value="{{ $user->name }}" class="w-full border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                        @error('bank_account_holder') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Ajukan Penarikan
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Sales Details -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50">
                    <h3 class="font-semibold text-lg">Detail Penjualan</h3>
                </div>
                <div class="p-6">
                    @if(empty($salesDetails))
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <p class="text-gray-500">Belum ada penjualan</p>
                        </div>
                    @else
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            @foreach($salesDetails as $sale)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $sale['product_name'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $sale['transaction_code'] }}</p>
                                        </div>
                                        <span class="font-bold text-green-600">Rp {{ number_format($sale['total'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Qty: {{ $sale['quantity'] }}</span>
                                        <span>{{ $sale['date']->format('d M Y') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Withdrawal History -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50">
                    <h3 class="font-semibold text-lg">Riwayat Penarikan</h3>
                </div>
                <div class="p-6">
                    @if($withdrawals->isEmpty())
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-500">Belum ada penarikan</p>
                        </div>
                    @else
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            @foreach($withdrawals as $withdrawal)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="font-medium text-gray-900">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</p>
                                            <p class="text-xs text-gray-500">{{ $withdrawal->bank_name }} - {{ $withdrawal->bank_account_number }}</p>
                                        </div>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                            {{ $withdrawal->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $withdrawal->status === 'approved' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $withdrawal->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                            {{ $withdrawal->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                                            {{ ucfirst($withdrawal->status) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">{{ $withdrawal->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">{{ $withdrawals->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

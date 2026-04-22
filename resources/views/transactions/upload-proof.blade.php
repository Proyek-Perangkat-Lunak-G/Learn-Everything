<x-app-layout>
    <div class="bg-white border-b">
        <div class="max-w-3xl mx-auto px-4 py-6">
            <h1 class="text-2xl font-bold text-gray-900">Upload Bukti Pembayaran</h1>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-8">
        @if(session('info'))
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-6">{{ session('info') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow-sm p-8">
            <!-- Transaction Info -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Kode Transaksi</p>
                        <p class="font-mono font-semibold">{{ $transaction->transaction_code }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Total</p>
                        <p class="font-bold text-blue-600">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Metode Pembayaran</p>
                        <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Status</p>
                        <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">Menunggu Pembayaran</span>
                    </div>
                </div>
            </div>

            <!-- Bank Info -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="font-semibold text-blue-800 mb-2">Informasi Transfer</h3>
                <div class="text-sm text-blue-700 space-y-1">
                    <p><strong>Bank BCA:</strong> 1234567890 a.n. Learn Everything</p>
                    <p><strong>Bank BNI:</strong> 0987654321 a.n. Learn Everything</p>
                    <p><strong>Bank Mandiri:</strong> 1122334455 a.n. Learn Everything</p>
                </div>
            </div>

            @if($transaction->payment_proof)
                <div class="mb-6">
                    <p class="text-sm text-gray-500 mb-2">Bukti yang sudah diupload:</p>
                    <img src="{{ asset('storage/' . $transaction->payment_proof) }}" alt="Bukti Pembayaran" class="max-w-xs rounded-lg border">
                    <p class="text-sm text-green-600 mt-2">Bukti sudah diupload. Menunggu konfirmasi admin.</p>
                </div>
            @else
                <form method="POST" action="{{ route('transactions.upload-proof.store', $transaction) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Pembayaran</label>
                        <input type="file" name="payment_proof" accept="image/*" required
                            class="w-full border border-gray-300 rounded-lg p-2 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, JPEG. Maksimal 2MB.</p>
                        @error('payment_proof')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full btn btn-primary">Upload Bukti Pembayaran</button>
                </form>
            @endif

            <div class="mt-6 pt-4 border-t">
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</x-app-layout>

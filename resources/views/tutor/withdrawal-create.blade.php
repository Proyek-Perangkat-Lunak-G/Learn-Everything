<x-app-layout>
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-8">
        <!-- Wallet Balance -->
        <div class="bg-gradient-to-br from-green-400 to-blue-500 rounded-lg shadow-lg p-6 text-white mb-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Saldo Tersedia</h3>
                <svg class="w-6 h-6 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-bold">Rp {{ number_format($tutor->wallet_available, 0, ',', '.') }}</p>
            <p class="text-xs opacity-75 mt-1">Siap untuk ditarik</p>
        </div>

        <!-- Bank Account Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h4 class="font-medium text-blue-900 mb-2">Rekening Tujuan</h4>
            <div class="text-sm text-blue-800">
                <p><strong>Bank:</strong> {{ $tutor->bank_name }}</p>
                <p><strong>No. Rekening:</strong> {{ $tutor->bank_account_number }}</p>
                <p><strong>Atas Nama:</strong> {{ $tutor->bank_account_holder }}</p>
            </div>
            <a href="{{ route('tutor.profile.edit') }}" class="text-xs text-blue-600 hover:underline mt-2 inline-block">
                Ubah rekening →
            </a>
        </div>

        <!-- Withdrawal Form -->
        <form method="POST" action="{{ route('tutor.withdrawals.store') }}">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Penarikan</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                    <input 
                        type="number" 
                        name="amount" 
                        value="{{ old('amount') }}"
                        min="50000" 
                        max="{{ $tutor->wallet_available }}" 
                        step="1000"
                        required 
                        class="w-full pl-12 pr-4 py-3 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-lg font-medium"
                        placeholder="50000"
                    >
                </div>
                @error('amount') 
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                @else
                    <p class="text-xs text-gray-600 mt-1">Minimal Rp 50.000, maksimal Rp {{ number_format($tutor->wallet_available, 0, ',', '.') }}</p>
                @enderror
            </div>

            <!-- Important Notes -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                <h4 class="font-medium text-gray-900 mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Informasi Penting
                </h4>
                <ul class="text-sm text-gray-700 space-y-1 ml-7">
                    <li>• Dana akan ditransfer ke rekening yang terdaftar</li>
                    <li>• Proses penarikan membutuhkan persetujuan admin</li>
                    <li>• Setelah disetujui, dana akan ditransfer dalam 1-3 hari kerja</li>
                    <li>• Saldo akan dipotong saat permintaan diajukan</li>
                </ul>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('tutor.withdrawals.index') }}" class="btn btn-secondary flex-1">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary flex-1">
                    Ajukan Penarikan
                </button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>
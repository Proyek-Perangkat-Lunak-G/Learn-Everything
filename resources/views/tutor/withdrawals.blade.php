<x-app-layout>
<div class="space-y-6">
    <!-- Wallet Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Saldo Pending</h3>
                <svg class="w-6 h-6 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-bold">Rp {{ number_format($tutor->wallet_pending, 0, ',', '.') }}</p>
            <p class="text-xs opacity-75 mt-1">Menunggu approval admin</p>
        </div>

        <div class="bg-gradient-to-br from-green-400 to-blue-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Saldo Tersedia</h3>
                <svg class="w-6 h-6 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-bold">Rp {{ number_format($tutor->wallet_available, 0, ',', '.') }}</p>
            <p class="text-xs opacity-75 mt-1">Siap untuk ditarik</p>
        </div>
    </div>

    <!-- Request Withdrawal Button -->
    @if($tutor->wallet_available >= 50000)
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-lg mb-1">Ajukan Penarikan</h3>
                    <p class="text-sm text-gray-600">Minimal penarikan Rp 50.000</p>
                </div>
                <a href="{{ route('tutor.withdrawals.create') }}" class="btn btn-primary">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tarik Saldo
                </a>
            </div>
        </div>
    @else
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="font-medium text-gray-700 mb-1">Saldo Belum Mencukupi</h3>
                    <p class="text-sm text-gray-600">Minimal saldo untuk penarikan adalah Rp 50.000. Saldo tersedia Anda saat ini: Rp {{ number_format($tutor->wallet_available, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Withdrawal History -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-bold mb-4">Riwayat Penarikan</h2>
        
        @if($withdrawals->isEmpty())
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-gray-500">Belum ada riwayat penarikan</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rekening</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($withdrawals as $withdrawal)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    {{ $withdrawal->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $withdrawal->bank_name }}<br>
                                    {{ $withdrawal->bank_account_number }}<br>
                                    <span class="text-xs">{{ $withdrawal->bank_account_holder }}</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($withdrawal->status === 'pending')
                                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Pending</span>
                                    @elseif($withdrawal->status === 'approved')
                                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Disetujui</span>
                                    @elseif($withdrawal->status === 'completed')
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Selesai</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $withdrawal->admin_notes ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>
</div>
</x-app-layout>
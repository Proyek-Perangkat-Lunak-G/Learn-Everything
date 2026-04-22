<x-app-layout>
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Pembayaran Tutor</h1>
                    <p class="text-gray-600 mt-1">Kelola tagihan dan pembayaran ke tutor</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-2">
                        <p class="text-sm text-yellow-600">Total Tagihan</p>
                        <p class="text-xl font-bold text-yellow-800">Rp {{ number_format($totalPending, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg px-4 py-2">
                        <p class="text-sm text-green-600">Total Dibayarkan</p>
                        <p class="text-xl font-bold text-green-800">Rp {{ number_format($totalPaid, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">{{ session('error') }}</div>
        @endif

        <div class="space-y-8">
            <!-- Tagihan Pending -->
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-4">Tagihan yang Harus Dibayar</h2>
                
                @if($pendingInvoices->isEmpty())
                    <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-xl font-semibold mb-2">Tidak ada tagihan pending</h3>
                        <p class="text-gray-600">Semua pembayaran ke tutor sudah selesai.</p>
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="text-left px-6 py-3 text-sm font-medium text-gray-700">Tutor</th>
                                    <th class="text-left px-6 py-3 text-sm font-medium text-gray-700">Rekening Bank</th>
                                    <th class="text-left px-6 py-3 text-sm font-medium text-gray-700">Tagihan</th>
                                    <th class="text-left px-6 py-3 text-sm font-medium text-gray-700">Saldo Tersedia</th>
                                    <th class="text-center px-6 py-3 text-sm font-medium text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($pendingInvoices as $tutor)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $tutor->user->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $tutor->user->email }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($tutor->bank_name)
                                                <div class="text-sm">
                                                    <p class="font-medium text-gray-900">{{ $tutor->bank_name }}</p>
                                                    <p class="text-gray-600">{{ $tutor->bank_account_number }}</p>
                                                    <p class="text-gray-500">{{ $tutor->bank_account_holder }}</p>
                                                </div>
                                            @else
                                                <span class="text-xs text-yellow-600 bg-yellow-50 px-2 py-1 rounded">Belum ada rekening</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-lg font-bold text-yellow-600">Rp {{ number_format($tutor->wallet_pending, 0, ',', '.') }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-600">Rp {{ number_format($tutor->wallet_available, 0, ',', '.') }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <form method="POST" action="{{ route('admin.tutor-payments.pay', $tutor) }}" onsubmit="return confirm('Bayar tagihan Rp {{ number_format($tutor->wallet_pending, 0, ',', '.') }} ke {{ $tutor->user->name }}?')">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Bayar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Riwayat Pembayaran -->
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-4">Riwayat Pembayaran</h2>
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-3">Tutor</th>
                                <th class="text-left px-4 py-3">Jumlah</th>
                                <th class="text-left px-4 py-3">Periode</th>
                                <th class="text-left px-4 py-3">Status</th>
                                <th class="text-left px-4 py-3">Catatan</th>
                                <th class="text-left px-4 py-3">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($payments as $payment)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div>
                                            <p class="font-medium">{{ $payment->tutor->user->name }}</p>
                                            @if($payment->tutor->bank_account_number)
                                                <p class="text-xs text-gray-500">{{ $payment->tutor->bank_name }} - {{ $payment->tutor->bank_account_number }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 font-semibold {{ $payment->status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">{{ $payment->period }}</td>
                                    <td class="px-4 py-3">
                                        <span class="badge {{ $payment->status === 'paid' ? 'badge-green' : 'badge-yellow' }}">
                                            {{ $payment->status === 'paid' ? 'Telah Dibayar' : 'Belum Dibayar' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">{{ $payment->notes ?? '-' }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $payment->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada pembayaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="p-4">{{ $payments->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

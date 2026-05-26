<x-app-layout>
    <div class="bg-white border-b">
        <div class="max-w-5xl mx-auto px-4 py-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Riwayat Pembayaran</h1>
                <p class="text-gray-600 mt-1">Pembayaran yang Anda terima dari platform</p>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg px-4 py-2 text-right">
                <p class="text-sm text-green-600">Total Diterima</p>
                <p class="text-2xl font-bold text-green-800">Rp {{ number_format($totalReceived, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-3">Tanggal</th>
                        <th class="text-left px-4 py-3">Jumlah</th>
                        <th class="text-left px-4 py-3">Periode</th>
                        <th class="text-left px-4 py-3">Catatan</th>
                        <th class="text-left px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($payments as $payment)
                        <tr>
                            <td class="px-4 py-3">{{ $payment->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-green-600 font-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ $payment->period }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $payment->notes ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="badge {{ $payment->status === 'paid' ? 'badge-green' : 'badge-yellow' }}">
                                    {{ $payment->status === 'paid' ? 'Dibayar' : 'Pending' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-12 text-center text-gray-500">Belum ada riwayat pembayaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">{{ $payments->links() }}</div>
        </div>
    </div>
</x-app-layout>
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Pembayaran Langganan</h1>
    <p class="text-gray-500 mb-8">Paket {{ ucfirst($package) }} - 30 Hari</p>

    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center justify-between mb-6 pb-6 border-b">
            <div>
                <p class="font-semibold text-lg">Paket {{ ucfirst($package) }}</p>
                <p class="text-sm text-gray-500">Berlaku 30 hari sejak pembayaran</p>
            </div>
            <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($price, 0, ',', '.') }}</p>
        </div>

        <form action="{{ route('subscriptions.process') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="package" value="{{ $package }}">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Metode Pembayaran</label>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" name="payment_method" value="bank_transfer" required class="text-blue-600">
                        <div>
                            <p class="font-medium">Transfer Bank</p>
                            <p class="text-sm text-gray-500">BCA, BNI, Mandiri, BRI</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" name="payment_method" value="e_wallet" class="text-blue-600">
                        <div>
                            <p class="font-medium">E-Wallet</p>
                            <p class="text-sm text-gray-500">GoPay, OVO, DANA, ShopeePay</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" name="payment_method" value="credit_card" class="text-blue-600">
                        <div>
                            <p class="font-medium">Kartu Kredit</p>
                            <p class="text-sm text-gray-500">Visa, Mastercard</p>
                        </div>
                    </label>
                </div>
            </div>

            @if($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded-lg text-sm">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('subscriptions.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Bayar Rp {{ number_format($price, 0, ',', '.') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

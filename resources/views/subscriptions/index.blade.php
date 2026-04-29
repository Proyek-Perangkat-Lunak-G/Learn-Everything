<x-app-layout>
    {{-- Gradient Header --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
        <div class="max-w-7xl mx-auto px-4 py-12 text-center">
            <h1 class="text-4xl font-bold mb-4">Pilih Paket Langganan</h1>
            <p class="text-xl text-blue-100 mb-6">Investasi terbaik untuk pengembangan diri Anda</p>
            <div class="flex items-center justify-center gap-8 text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Akses Unlimited</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Sertifikat Resmi</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Cancel Kapan Saja</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-12">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
        @endif

        @if($currentSubscription && $currentSubscription->isActive())
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8 flex items-center gap-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="text-blue-800 font-semibold">Paket Aktif: <span class="text-blue-600">{{ ucfirst($currentSubscription->package) }}</span></p>
                    <p class="text-sm text-blue-600 mt-1">Berakhir: {{ $currentSubscription->expires_at->format('d M Y') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($packages as $key => $package)
                <div class="bg-white rounded-lg shadow-sm p-8 {{ $key === 'premium' ? 'ring-2 ring-blue-600 relative scale-105' : 'card-hover' }}">
                    @if($key === 'premium')
                        <span class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white text-xs px-4 py-1 rounded-full font-semibold">Paling Populer</span>
                    @endif
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $package['name'] }}</h3>
                        <p class="text-4xl font-bold text-blue-600 mt-4">Rp {{ number_format($package['price'], 0, ',', '.') }}</p>
                        <p class="text-gray-500 text-sm">/ {{ $package['duration'] }} hari</p>
                    </div>
                    <ul class="space-y-3 mb-6">
                        @foreach($package['features'] as $feature)
                            <li class="flex items-start gap-2 text-sm text-gray-600">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                    <form method="POST" action="{{ route('subscriptions.subscribe') }}">
                        @csrf
                        <input type="hidden" name="package" value="{{ $key }}">
                        <button type="submit" class="w-full {{ $key === 'premium' ? 'btn btn-primary btn-lg' : 'btn btn-outline btn-lg' }}">Pilih Paket</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>

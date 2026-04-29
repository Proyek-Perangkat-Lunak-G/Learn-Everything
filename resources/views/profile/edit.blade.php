<x-app-layout>
    <div class="bg-white border-b">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
            <p class="text-gray-600 mt-1">Kelola informasi akun Anda</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-8 space-y-6">
        <div class="p-6 bg-white rounded-lg shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>

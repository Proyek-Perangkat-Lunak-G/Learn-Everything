<section class="space-y-6">
    {{-- Header Section --}}
    <div class="flex items-start gap-4">
        <div class="flex-shrink-0">
            <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 0v2m0-8v-2m0 8v2m0 0v-2"/>
                </svg>
            </div>
        </div>
        <div class="flex-1">
            <h2 class="text-xl font-bold text-gray-900">
                {{ __('Hapus Akun Anda') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
            </p>
        </div>
    </div>

    {{-- Warning Box --}}
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">
                    <span class="font-semibold">Perhatian:</span> Tindakan ini tidak dapat dibatalkan. Semua data Anda akan dihapus secara permanen.
                </p>
            </div>
        </div>
    </div>

    {{-- Button Section --}}
    <div class="flex gap-3 pt-4">
        <button
            type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition duration-150 ease-in-out"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            {{ __('Hapus Akun') }}
        </button>
    </div>

    {{-- Confirmation Modal --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <div class="p-6 space-y-6">
            {{-- Modal Header --}}
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __('Konfirmasi Penghapusan Akun') }}
                    </h3>
                </div>
            </div>

            {{-- Modal Content --}}
            <div class="space-y-4">
                <p class="text-sm text-gray-600 leading-relaxed">
                    {{ __('Are you sure you want to delete your account? This action cannot be undone.') }}
                </p>
                <p class="text-sm text-red-600 font-medium">
                    {{ __('All your personal data, addresses, courses, and history will be permanently deleted.') }}
                </p>
            </div>

            {{-- Form --}}
            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                @csrf
                @method('delete')

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Masukkan Password Anda') }}
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('password') border-red-500 @enderror"
                        placeholder="{{ __('Password') }}"
                        required
                        autofocus
                    />
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Modal Actions --}}
                <div class="flex gap-3 justify-end pt-4 border-t">
                    <button
                        type="button"
                        @click="$dispatch('close')"
                        class="px-4 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition duration-150 ease-in-out"
                    >
                        {{ __('Batal') }}
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition duration-150 ease-in-out"
                    >
                        {{ __('Hapus Akun Saya') }}
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</section>

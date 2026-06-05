<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Learn Everything') }}</title>

        <!-- Fonts: Inter -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50">

        {{-- ===== GLOBAL TOAST SYSTEM ===== --}}
        <div
            x-data="{
                toasts: [],
                add(msg, type = 'success') {
                    const id = Date.now();
                    this.toasts.push({ id, msg, type });
                    setTimeout(() => this.remove(id), 4500);
                },
                remove(id) { this.toasts = this.toasts.filter(t => t.id !== id); }
            }"
            x-on:toast.window="add($event.detail.message, $event.detail.type ?? 'success')"
            x-ref="toastContainer"
            class="toast-container"
            id="toast-container"
        >
            <template x-for="toast in toasts" :key="toast.id">
                <div
                    x-show="true"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-8"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-8"
                    :class="{
                        'toast toast-success': toast.type === 'success',
                        'toast toast-error':   toast.type === 'error',
                        'toast toast-info':    toast.type === 'info',
                        'toast toast-warning': toast.type === 'warning',
                        'toast': !['success','error','info','warning'].includes(toast.type)
                    }"
                >
                    <!-- Icon -->
                    <div class="flex-shrink-0 mt-0.5">
                        <template x-if="toast.type === 'success'">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </template>
                        <template x-if="toast.type === 'error'">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </template>
                        <template x-if="toast.type === 'info'">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </template>
                        <template x-if="toast.type === 'warning'">
                            <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </template>
                    </div>
                    <!-- Message -->
                    <span class="flex-1 text-sm" x-text="toast.msg"></span>
                    <!-- Close -->
                    <button @click="remove(toast.id)" class="flex-shrink-0 ml-1 opacity-60 hover:opacity-100 transition-opacity">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </template>
        </div>

        {{-- ===== AUTO-FIRE SESSION FLASH ===== --}}
        @if(session('success'))
            <script>
                document.addEventListener('alpine:init', () => {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: { message: @js(session('success')), type: 'success' }
                    }));
                });
            </script>
        @endif
        @if(session('error'))
            <script>
                document.addEventListener('alpine:init', () => {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: { message: @js(session('error')), type: 'error' }
                    }));
                });
            </script>
        @endif
        @if(session('warning'))
            <script>
                document.addEventListener('alpine:init', () => {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: { message: @js(session('warning')), type: 'warning' }
                    }));
                });
            </script>
        @endif
        @if(session('info'))
            <script>
                document.addEventListener('alpine:init', () => {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: { message: @js(session('info')), type: 'info' }
                    }));
                });
            </script>
        @endif

        <div class="min-h-screen bg-gray-50">
            @auth
                @if(Auth::user()->isAdmin())
                    @include('layouts.admin-sidebar')
                    <div class="ml-64">
                        <main>
                            {{ $slot ?? '' }}
                            @yield('content')
                        </main>
                    </div>
                @elseif(Auth::user()->isTutor())
                    @include('layouts.tutor-sidebar')
                    <div class="ml-64">
                        <main>
                            {{ $slot ?? '' }}
                            @yield('content')
                        </main>
                    </div>
                @else
                    @include('layouts.navigation')
                    <main>
                        {{ $slot ?? '' }}
                        @yield('content')
                    </main>
                    @include('layouts.footer')
                @endif
            @else
                @include('layouts.navigation')
                <main>
                    {{ $slot ?? '' }}
                    @yield('content')
                </main>
                @include('layouts.footer')
            @endauth
        </div>
        @stack('scripts')
    </body>
</html>

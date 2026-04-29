<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Learn Everything') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
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
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

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">

        <div class="min-h-screen flex">
            {{-- ===== LEFT PANEL (decorative) ===== --}}
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-blue-600 via-indigo-700 to-purple-800">
                <!-- Background pattern -->
                <div class="absolute inset-0 opacity-10">
                    <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#grid)" />
                    </svg>
                </div>

                <!-- Decorative circles -->
                <div class="absolute -top-20 -left-20 w-80 h-80 bg-white/5 rounded-full"></div>
                <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-white/5 rounded-full"></div>
                <div class="absolute top-1/3 left-1/2 w-48 h-48 bg-purple-400/20 rounded-full blur-2xl"></div>

                <!-- Content -->
                <div class="relative z-10 flex flex-col justify-center px-12 text-white">
                    <!-- Logo -->
                    <div class="flex items-center gap-3 mb-12">
                        <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold">Learn Everything</span>
                    </div>

                    <!-- Headline -->
                    <h1 class="text-4xl font-extrabold leading-tight mb-4">
                        Belajar Tanpa<br>Batas, Berkembang<br>Tanpa Henti
                    </h1>
                    <p class="text-blue-100 text-lg mb-12 leading-relaxed">
                        Platform pembelajaran terpadu dengan kursus berkualitas, tutor profesional, dan komunitas aktif.
                    </p>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-white/10 backdrop-blur rounded-2xl p-4 text-center">
                            <div class="text-2xl font-bold mb-0.5">500+</div>
                            <div class="text-blue-100 text-xs">Kursus</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur rounded-2xl p-4 text-center">
                            <div class="text-2xl font-bold mb-0.5">10K+</div>
                            <div class="text-blue-100 text-xs">Pelajar</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur rounded-2xl p-4 text-center">
                            <div class="text-2xl font-bold mb-0.5">200+</div>
                            <div class="text-blue-100 text-xs">Tutor</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== RIGHT PANEL (form) ===== --}}
            <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 bg-gray-50">
                <!-- Mobile logo -->
                <div class="lg:hidden mb-8 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center shadow">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gradient">Learn Everything</span>
                </div>

                <!-- Form Card -->
                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>

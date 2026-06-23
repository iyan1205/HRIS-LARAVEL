<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{
        isDark: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
        sidebarOpen: true,
        mobileSidebarOpen: false
    }"
    x-bind:class="{ 'dark': isDark }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Dark mode init: cegah flash saat load -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-950 transition-colors duration-300">

    <div class="flex h-screen overflow-hidden">

        {{-- ===== SIDEBAR ===== --}}
        @include('layouts.partials.sidebar')

        {{-- ===== MOBILE OVERLAY ===== --}}
        <div x-show="mobileSidebarOpen"
            x-transition:enter="transition-opacity duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="mobileSidebarOpen = false"
            class="fixed inset-0 bg-black/50 z-20 lg:hidden">
        </div>

        {{-- ===== MAIN AREA ===== --}}
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

            {{-- Topbar --}}
            @include('layouts.partials.topbar')

            {{-- Page Header --}}
            @if (isset($header))
                <div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 px-5 py-2.5 shrink-0">
                    {{ $header }}
                </div>
            @endif

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto">
                {{ $slot }}
            </main>

            {{-- Footer --}}
            @include('layouts.partials.footer')

        </div>
    </div>

</body>
</html>
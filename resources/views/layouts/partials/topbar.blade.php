{{-- resources/views/layouts/partials/topbar.blade.php --}}

<header class="h-12 flex items-center justify-between px-4 sm:px-6 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 shrink-0 z-20 transition-colors duration-300">

    {{-- Kiri: Hamburger toggle --}}
    <div class="flex items-center gap-2">

        {{-- Desktop toggle (collapse sidebar) --}}
        <button @click="sidebarOpen = !sidebarOpen"
            class="hidden lg:flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800 transition-colors duration-150">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        {{-- Mobile toggle --}}
        <button @click="mobileSidebarOpen = !mobileSidebarOpen"
            class="lg:hidden flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800 transition-colors duration-150">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    {{-- Kanan: Actions --}}
    <div class="flex items-center gap-2">

        {{-- ===== DARK MODE TOGGLE ===== --}}
        <button @click="
                isDark = !isDark;
                localStorage.theme = isDark ? 'dark' : 'light';
                document.documentElement.classList.toggle('dark', isDark)
            "
            :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
            class="relative flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800 transition-colors duration-150">

            {{-- Sun icon (tampil saat dark mode aktif) --}}
            <svg x-show="isDark"
                x-transition:enter="transition-all duration-300"
                x-transition:enter-start="opacity-0 rotate-90 scale-50"
                x-transition:enter-end="opacity-100 rotate-0 scale-100"
                x-transition:leave="transition-all duration-200"
                x-transition:leave-start="opacity-100 rotate-0 scale-100"
                x-transition:leave-end="opacity-0 -rotate-90 scale-50"
                class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>

            {{-- Moon icon (tampil saat light mode aktif) --}}
            <svg x-show="!isDark"
                x-transition:enter="transition-all duration-300"
                x-transition:enter-start="opacity-0 -rotate-90 scale-50"
                x-transition:enter-end="opacity-100 rotate-0 scale-100"
                x-transition:leave="transition-all duration-200"
                x-transition:leave-start="opacity-100 rotate-0 scale-100"
                x-transition:leave-end="opacity-0 rotate-90 scale-50"
                class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>

        {{-- Notification bell --}}
        <button class="relative flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800 transition-colors duration-150">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
        </button>

        {{-- User Dropdown --}}
        <x-dropdown align="right" width="56">
            <x-slot name="trigger">
                <button class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-150">
                    <div class="w-7 h-7 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-bold shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="hidden sm:block max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                    <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">{{ Auth::user()->email }}</p>
                </div>

                <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    {{ __('Profile') }}
                </x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        class="flex items-center gap-2 text-red-500 hover:text-red-600 dark:text-red-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>

    </div>
</header>
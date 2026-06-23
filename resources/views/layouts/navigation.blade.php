<aside x-data="{ open: false, collapsed: false }" 
    :class="{ 'w-64': !collapsed, 'w-16': collapsed }"
    class="relative flex flex-col h-screen bg-gray-900 text-white transition-all duration-300 ease-in-out shrink-0 z-50">

    {{-- Toggle collapse button (desktop) --}}
    <button @click="collapsed = !collapsed"
        class="hidden sm:flex absolute -right-3 top-6 z-10 items-center justify-center w-6 h-6 rounded-full bg-indigo-500 hover:bg-indigo-400 text-white shadow-lg transition-colors duration-200">
        <svg :class="{ 'rotate-180': collapsed }" class="w-3 h-3 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    {{-- Logo --}}
    <div class="flex items-center h-16 px-4 border-b border-gray-700/60">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 min-w-0">
            <div class="shrink-0 flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-500">
                <x-application-logo class="w-5 h-5 fill-current text-white" />
            </div>
            <span x-show="!collapsed" x-transition:enter="transition-opacity duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-150"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="font-semibold text-sm tracking-wide text-white truncate">
                {{ config('app.name', 'Laravel') }}
            </span>
        </a>
    </div>

    {{-- Navigation Links --}}
    <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
            class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                {{ request()->routeIs('dashboard')
                    ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30'
                    : 'text-gray-400 hover:bg-gray-800 hover:text-white border border-transparent' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span x-show="!collapsed" x-transition:enter="transition-opacity duration-200 delay-75"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-100"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="truncate">
                {{ __('Dashboard') }}
            </span>
            {{-- Tooltip when collapsed --}}
            <div x-show="collapsed"
                class="absolute left-full ml-3 px-2 py-1 bg-gray-700 text-white text-xs rounded-md whitespace-nowrap
                    opacity-0 group-hover:opacity-100 transition-opacity duration-150 pointer-events-none shadow-lg">
                {{ __('Dashboard') }}
            </div>
        </a>

        {{-- Tambahkan nav item lain di sini dengan pola yang sama --}}
        {{-- Contoh: --}}
        {{-- 
        <a href="{{ route('users.index') }}"
            class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                {{ request()->routeIs('users.*')
                    ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30'
                    : 'text-gray-400 hover:bg-gray-800 hover:text-white border border-transparent' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span x-show="!collapsed" class="truncate">{{ __('Users') }}</span>
        </a>
        --}}

    </nav>

    {{-- Divider --}}
    <div class="border-t border-gray-700/60 mx-2"></div>

    {{-- User Profile & Logout --}}
    <div class="p-2 py-3" x-data="{ dropup: false }">

        {{-- Profile Link --}}
        <a href="{{ route('profile.edit') }}"
            class="group flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-400 hover:bg-gray-800 hover:text-white transition-all duration-150">
            <div class="w-7 h-7 rounded-full bg-indigo-500/30 border border-indigo-400/30 flex items-center justify-center shrink-0 text-indigo-300 text-xs font-semibold">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div x-show="!collapsed" x-transition:enter="transition-opacity duration-200 delay-75"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-100"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="min-w-0 flex-1">
                <p class="text-xs font-medium text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
            </div>
        </a>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="group w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-500 hover:bg-red-500/10 hover:text-red-400 transition-all duration-150 mt-1">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span x-show="!collapsed" x-transition:enter="transition-opacity duration-200 delay-75"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-100"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="truncate text-left">
                    {{ __('Log Out') }}
                </span>
                {{-- Tooltip when collapsed --}}
                <div x-show="collapsed"
                    class="absolute left-full ml-3 px-2 py-1 bg-gray-700 text-white text-xs rounded-md whitespace-nowrap
                        opacity-0 group-hover:opacity-100 transition-opacity duration-150 pointer-events-none shadow-lg">
                    {{ __('Log Out') }}
                </div>
            </button>
        </form>

    </div>

    {{-- Mobile overlay toggle button --}}
    <div class="sm:hidden fixed bottom-4 right-4 z-50">
        <button @click="open = !open"
            class="flex items-center justify-center w-12 h-12 rounded-full bg-indigo-500 text-white shadow-xl hover:bg-indigo-400 transition-colors duration-200">
            <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

</aside>
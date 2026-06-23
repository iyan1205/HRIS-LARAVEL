@php
    $isOrgActive = request()->routeIs('departemen', 'departemen.*')
                || request()->routeIs('unit', 'unit.*')
                || request()->routeIs('jabatan', 'jabatan.*')
                || request()->routeIs('mobilitas', 'mobilitas.*');
    
    $isKaryawanActive = request()->routeIs('karyawan', 'karyawan.*')
                || request()->routeIs('resign', 'resign.*');

    $isUserActive = request()->routeIs('users', 'users.*')
                || request()->routeIs('permissions', 'permissions.*')
                || request()->routeIs('roles', 'roles.*');

    $isCutiActive = request()->routeIs('cuti', 'cuti.*');
@endphp

{{-- =================================================================
     DESKTOP SIDEBAR
     ================================================================= --}}
<aside
    x-data="{
        hovering: false,
        get expanded() { return sidebarOpen || this.hovering }
    }"
    :class="expanded ? 'w-64' : 'w-16'"
    @mouseenter="hovering = true"
    @mouseleave="hovering = false"
    class="hidden lg:flex flex-col h-screen bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 transition-all duration-300 ease-in-out shrink-0 relative z-30">

    {{-- Logo --}}
    <div class="flex items-center h-12 px-4 border-b border-gray-200 dark:border-gray-800 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 min-w-0">
            <div class="shrink-0 flex items-center justify-center w-7 h-7 ">
               <img src="{{ asset('lte/dist/img/logo.png') }}" alt="Logo" class="w-7 h-7 object-contain">
            </div>
            <span
                x-show="expanded"
                x-transition:enter="transition-opacity duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-100"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="text-xs font-semibold text-gray-800 dark:text-white truncate whitespace-nowrap">
                {{ config('app.name', 'Laravel') }}
            </span>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 py-4 px-2 space-y-0.5 overflow-y-auto
        [&::-webkit-scrollbar]:w-1
        [&::-webkit-scrollbar-track]:bg-transparent
        [&::-webkit-scrollbar-thumb]:bg-gray-300
        dark:[&::-webkit-scrollbar-thumb]:bg-gray-700
        [&::-webkit-scrollbar-thumb]:rounded-full">

        {{-- Section: Menu Utama --}}
        <p x-show="expanded"
            class="px-3 pb-2 pt-1 text-[10px] font-semibold uppercase tracking-widest text-gray-800 dark:text-gray-600">
            Menu Utama
        </p>

        {{-- Dashboard --}}
        <x-sidebar-link
            :href="route('dashboard')"
            :active="request()->routeIs('dashboard')">
            <x-slot name="icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </x-slot>
            {{ __('Dashboard') }}
        </x-sidebar-link>

        {{-- Section: Menu Organisasi --}}
        <p x-show="expanded"
            class="px-3 pb-2 pt-4 text-[10px] font-semibold uppercase tracking-widest text-gray-800 dark:text-gray-600">
            Menu Organisasi
        </p>

        @can('sidebar organisasi')
        <x-sidebar-dropdown
            label="{{ __('Organisasi') }}"
            :active="$isOrgActive">
            <x-slot name="icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </x-slot>
            <x-slot name="items">
                <x-sidebar-sub-link :href="route('departemen')" :active="request()->routeIs('departemen', 'departemen.*')">
                    {{ __('Departemen') }}
                </x-sidebar-sub-link>
                <x-sidebar-sub-link :href="route('unit')" :active="request()->routeIs('unit', 'unit.*')">
                    {{ __('Instalasi / Divisi') }}
                </x-sidebar-sub-link>
                <x-sidebar-sub-link :href="route('jabatan')" :active="request()->routeIs('jabatan', 'jabatan.*')">
                    {{ __('Jabatan') }}
                </x-sidebar-sub-link>
                <x-sidebar-sub-link :href="route('mobilitas.index')" :active="request()->routeIs('mobilitas', 'mobilitas.*')">
                    {{ __('Mobilitas Jabatan') }}
                </x-sidebar-sub-link>
            </x-slot>
        </x-sidebar-dropdown>
        @endcan
        
        {{-- Section: Menu Master Karyawan --}}
    <p x-show="expanded"
            class="px-3 pb-2 pt-4 text-[10px] font-semibold uppercase tracking-widest text-gray-800 dark:text-gray-600">
            Menu Master Karyawan
        </p>

    @can('sidebar masterkaryawan')
     <x-sidebar-dropdown
        label="{{ __('Master Karyawan') }}"
        :active="$isKaryawanActive">
        <x-slot name="icon">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="2" y="5" width="20" height="14" rx="2" stroke-width="1.75"/>
                <circle cx="8.5" cy="11" r="2" stroke-width="1.75"/>
                <path stroke-linecap="round" stroke-width="1.75" d="M5 17c0-1.657 1.567-3 3.5-3s3.5 1.343 3.5 3"/>
            </svg>
        </x-slot>
        <x-slot name="items">
            <x-sidebar-sub-link :href="route('karyawan')" :active="request()->routeIs('karyawan', 'karyawan.*')">
                {{ __('Karyawan') }}
            </x-sidebar-sub-link>
            <x-sidebar-sub-link :href="route('resign')" :active="request()->routeIs('resign', 'resign.*')">
                {{ __('Resign') }}
            </x-sidebar-sub-link>
        </x-slot>
    </x-sidebar-dropdown>
    @endcan
    @can('pelatihan')
    <x-sidebar-link
        :href="route('pelatihan')"
        :active="request()->routeIs('pelatihan')">
        <x-slot name="icon">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
            </svg>
        </x-slot>

        {{ __('Pelatihan') }}
    </x-sidebar-link>
    @endcan
    {{-- Section: Menu Cuti --}}
    <p class="px-3 pb-2 pt-4 text-[10px] font-semibold uppercase tracking-widest text-gray-800 dark:text-gray-600">
            Menu Cuti
    </p>
    <x-sidebar-dropdown
        label="{{ __('Cuti') }}"
        :active="$isCutiActive">
        <x-slot name="icon">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path d="M6.36 17.4 4 17l-2-4 1.1-.55a2 2 0 0 1 1.8 0l.17.1a2 2 0 0 0 1.8 0L8 12 5 6l.9-.45a2 2 0 0 1 2.09.2l4.02 3a2 2 0 0 0 2.1.2l4.19-2.06a2.41 2.41 0 0 1 1.73-.17L21 7a1.4 1.4 0 0 1 .87 1.99l-.38.76c-.23.46-.6.84-1.07 1.08L7.58 17.2a2 2 0 0 1-1.22.18Z"/></svg>
            </svg>
        </x-slot>
        <x-slot name="items">
            @can('sidebar laporan cuti')
            <x-sidebar-sub-link :href="route('laporan-cuti')" :active="request()->routeIs('cuti', 'cuti.*')">
                {{ __('Laporan Cuti') }}
            </x-sidebar-sub-link>
            @endcan
            <x-sidebar-sub-link :href="route('resign')" :active="request()->routeIs('resign', 'resign.*')">
                {{ __('Resign') }}
            </x-sidebar-sub-link>
        </x-slot>
    </x-sidebar-dropdown>
    </nav>
</aside>


{{-- =================================================================
     MOBILE SIDEBAR
     ================================================================= --}}
<aside
    :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    @mouseleave="mobileSidebarOpen = false"
    class="lg:hidden fixed inset-y-0 left-0 z-30 w-64 flex flex-col bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 transition-transform duration-300 ease-in-out">

    {{-- Logo --}}
    <div class="flex items-center justify-between h-12 px-4 border-b border-gray-200 dark:border-gray-800 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="flex items-center justify-center w-7 h-7 rounded-lg bg-teal-600">
                <x-application-logo class="w-4 h-4 fill-current text-white" />
            </div>
            <span class="text-xs font-semibold text-gray-800 dark:text-white">{{ config('app.name', 'Laravel') }}</span>
        </a>
        <button @click="mobileSidebarOpen = false"
            class="text-gray-800 hover:text-gray-600 dark:hover:text-white transition-colors p-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 py-4 px-2 space-y-0.5 overflow-y-auto
        [&::-webkit-scrollbar]:w-1
        [&::-webkit-scrollbar-track]:bg-transparent
        [&::-webkit-scrollbar-thumb]:bg-gray-300
        dark:[&::-webkit-scrollbar-thumb]:bg-gray-700
        [&::-webkit-scrollbar-thumb]:rounded-full">

        {{-- Section: Menu Utama --}}
        <p class="px-3 pb-2 pt-1 text-[10px] font-semibold uppercase tracking-widest text-gray-800 dark:text-gray-600">
            Menu Utama
        </p>

        {{-- Dashboard --}}
        @php $active = request()->routeIs('dashboard') @endphp
        <a href="{{ route('dashboard') }}"
            class="group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                {{ $active
                    ? 'bg-teal-50 dark:bg-teal-500/15 text-teal-600 dark:text-teal-300'
                    : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
            @if($active)
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-5 bg-teal-500 dark:bg-teal-400 rounded-r-full"></span>
            @endif
            <span class="shrink-0 {{ $active ? 'text-teal-500 dark:text-teal-400' : 'text-gray-800 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </span>
            {{ __('Dashboard') }}
        </a>

        {{-- Section: Menu Organisasi --}}
        <p class="px-3 pb-2 pt-4 text-[10px] font-semibold uppercase tracking-widest text-gray-800 dark:text-gray-600">
            Menu Organisasi
        </p>

        @can('sidebar organisasi')
        <div x-data="{ open: {{ $isOrgActive ? 'true' : 'false' }} }" class="relative">
            <button @click="open = !open"
                class="group w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                    {{ $isOrgActive
                        ? 'bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-300'
                        : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                @if($isOrgActive)
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-5 bg-teal-500 dark:bg-teal-400 rounded-r-full"></span>
                @endif
                <span class="shrink-0 {{ $isOrgActive ? 'text-teal-500 dark:text-teal-400' : 'text-gray-800 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </span>
                <span class="flex-1 flex items-center justify-between">
                    <span>{{ __('Organisasi') }}</span>
                    <svg :class="open ? 'rotate-180' : ''"
                        class="w-3.5 h-3.5 text-gray-800 dark:text-gray-500 transition-transform duration-200 mr-0.5"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </span>
            </button>

            <div x-show="open"
                x-transition:enter="transition-all duration-200 ease-out"
                x-transition:enter-start="opacity-0 -translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition-all duration-150 ease-in"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-1"
                class="mt-0.5 ml-4 pl-3 border-l border-gray-200 dark:border-gray-700/60 space-y-0.5">

                <x-sidebar-sub-link :href="route('departemen')" :active="request()->routeIs('departemen', 'departemen.*')">
                    {{ __('Departemen') }}
                </x-sidebar-sub-link>
                <x-sidebar-sub-link :href="route('unit')" :active="request()->routeIs('unit', 'unit.*')">
                    {{ __('Instalasi / Divisi') }}
                </x-sidebar-sub-link>
                <x-sidebar-sub-link :href="route('jabatan')" :active="request()->routeIs('jabatan', 'jabatan.*')">
                    {{ __('Jabatan') }}
                </x-sidebar-sub-link>
                <x-sidebar-sub-link :href="route('mobilitas.index')" :active="request()->routeIs('mobilitas', 'mobilitas.*')">
                    {{ __('Mobilitas Jabatan') }}
                </x-sidebar-sub-link>

            </div>
        </div>
        @endcan
        {{-- Section: Menu Master Karyawan --}}
        <p class="px-3 pb-2 pt-4 text-[10px] font-semibold uppercase tracking-widest text-gray-800 dark:text-gray-600">
            Menu Master Karyawan
        </p>

        @can('sidebar masterkaryawan')
        <div x-data="{ open: {{ $isKaryawanActive ? 'true' : 'false' }} }" class="relative">
            <button @click="open = !open"
                class="group w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                    {{ $isKaryawanActive
                        ? 'bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-300'
                        : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                @if($isKaryawanActive)
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-5 bg-teal-500 dark:bg-teal-400 rounded-r-full"></span>
                @endif
                <span class="shrink-0 {{ $isKaryawanActive ? 'text-steal-500 dark:text-steal-400' : 'text-gray-800 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="2" y="5" width="20" height="14" rx="2" stroke-width="1.75"/>
                        <circle cx="8.5" cy="11" r="2" stroke-width="1.75"/>
                        <path stroke-linecap="round" stroke-width="1.75" d="M5 17c0-1.657 1.567-3 3.5-3s3.5 1.343 3.5 3"/>
                        <path stroke-linecap="round" stroke-width="1.75" d="M14 9h4M14 12h3"/>
                    </svg>
                </span>
                <span class="flex-1 flex items-center justify-between">
                    <span>{{ __('Master Karyawan') }}</span>
                    <svg :class="open ? 'rotate-180' : ''"
                        class="w-3.5 h-3.5 text-gray-800 dark:text-gray-500 transition-transform duration-200 mr-0.5"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </span>
            </button>

            <div x-show="open"
                x-transition:enter="transition-all duration-200 ease-out"
                x-transition:enter-start="opacity-0 -translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition-all duration-150 ease-in"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-1"
                class="mt-0.5 ml-4 pl-3 border-l border-gray-200 dark:border-gray-700/60 space-y-0.5">

                <x-sidebar-sub-link :href="route('karyawan')" :active="request()->routeIs('karyawan', 'karyawan.*')">
                    {{ __('Karyawan') }}
                </x-sidebar-sub-link>
                <x-sidebar-sub-link :href="route('resign')" :active="request()->routeIs('resign', 'resign.*')">
                    {{ __('Resign') }}
                </x-sidebar-sub-link>

            </div>
        </div>
        @endcan
         @can('pelatihan')
        <x-sidebar-link
            :href="route('pelatihan')"
            :active="request()->routeIs('pelatihan')">
            <x-slot name="icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                </svg>
            </x-slot>
            {{ __('Pelatihan') }}
        </x-sidebar-link>
        @endcan
        {{-- Section: Menu Cuti --}}
        <p class="px-3 pb-2 pt-4 text-[10px] font-semibold uppercase tracking-widest text-gray-800 dark:text-gray-600">
            Menu Cuti
        </p>
    </nav>

</aside>
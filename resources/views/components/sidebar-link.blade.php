{{-- resources/views/components/sidebar-link.blade.php --}}
@props(['active' => false, 'href' => '#'])

@php
$activeClass   = 'bg-teal-50 dark:bg-teal-500/15 text-teal-600 dark:text-teal-300';
$inactiveClass = 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white';
@endphp

<a href="{{ $href }}"
    x-data="{ get _expanded() { return typeof expanded !== 'undefined' ? expanded : true } }"
    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ $active ? $activeClass : $inactiveClass }}">

    {{-- Icon --}}
    @isset($icon)
        <span class="shrink-0 {{ $active ? 'text-teal-500' : 'text-gray-500 group-hover:text-gray-300' }}">
            {{ $icon }}
        </span>
    @endisset

    {{-- Label --}}
    <span x-show="_expanded"
        x-transition:enter="transition-opacity duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-100"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="truncate whitespace-nowrap">
        {{ $slot }}
    </span>

    {{-- Tooltip (saat collapsed) --}}
    <span x-show="!_expanded"
        class="absolute left-full ml-3 px-2.5 py-1.5 bg-gray-800 text-white text-xs rounded-lg
               whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150
               pointer-events-none shadow-xl border border-gray-700 z-50">
        {{ $slot }}
    </span>

    {{-- Active indicator --}}
    @if($active)
        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-5 bg-teal-500 rounded-r-full"></span>
    @endif

</a>
{{-- resources/views/components/sidebar-sub-link.blade.php --}}
{{-- Sub-item di dalam sidebar-dropdown --}}
@props(['active' => false, 'href' => '#'])

@php
$activeClass   = 'text-teal-600 dark:text-teal-300 bg-teal-50 dark:bg-teal-500/10';
$inactiveClass = 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white';
@endphp

<a href="{{ $href }}"
    class="group relative flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors duration-150
           {{ $active ? $activeClass : $inactiveClass }}">

    {{-- Dot indicator --}}
    <span class="w-1.5 h-1.5 rounded-full shrink-0 {{ $active ? 'bg-teal-500 dark:bg-teal-400' : 'bg-gray-300 dark:bg-gray-600 group-hover:bg-gray-400 dark:group-hover:bg-gray-400' }} transition-colors duration-150"></span>

    <span class="truncate whitespace-nowrap">{{ $slot }}</span>

    @if($active)
        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-4 bg-teal-500/50 rounded-r-full -ml-3"></span>
    @endif
</a>

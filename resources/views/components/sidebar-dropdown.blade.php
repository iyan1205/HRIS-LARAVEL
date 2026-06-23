{{-- resources/views/components/sidebar-dropdown.blade.php --}}
{{--
    Props:
    - label  : string — judul grup menu
    - active : bool   — true jika salah satu child aktif (auto-expand)

    Slots:
    - $icon  — SVG icon untuk trigger button
    - $items — isi sub-menu (gunakan <x-sidebar-sub-link>)

    Requires Alpine x-data parent: sidebarOpen (bool)
--}}
@props(['label' => '', 'active' => false])

<div x-data="{ open: {{ $active ? 'true' : 'false' }} }" class="relative">

    {{-- ── Trigger ──────────────────────────────────────────────── --}}
    <button
        @click="expanded ? open = !open : null"
        class="group w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
               {{ $active
                   ? 'bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-300'
                   : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">

        {{-- Active bar --}}
        @if ($active)
            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-5 bg-teal-500 rounded-r-full"></span>
        @endif

        {{-- Icon --}}
        @isset($icon)
            <span class="shrink-0 {{ $active ? 'text-teal-400' : 'text-gray-500 group-hover:text-gray-300' }}">
                {{ $icon }}
            </span>
        @endisset

        {{-- Label + Chevron (visible when expanded) --}}
        <span
            x-show="expanded"
            x-transition:enter="transition-opacity duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-100"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="flex-1 flex items-center justify-between min-w-0">
            <span class="truncate whitespace-nowrap">{{ $label }}</span>
            <svg
                :class="open ? 'rotate-180' : 'rotate-0'"
                class="w-3.5 h-3.5 shrink-0 text-gray-500 transition-transform duration-200 mr-0.5"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
        </span>

        {{-- Tooltip (visible when collapsed) --}}
        <span
            x-show="!expanded"
            class="absolute left-full ml-3 px-2.5 py-1.5 bg-gray-800 text-white text-xs rounded-lg
                   whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150
                   pointer-events-none shadow-xl border border-gray-700 z-50">
            {{ $label }}
        </span>
    </button>

    {{-- ── Sub-items ─────────────────────────────────────────────── --}}
    <div
        x-show="open && expanded"
        x-transition:enter="transition-all duration-200 ease-out"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition-all duration-150 ease-in"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="mt-0.5 ml-4 pl-3 border-l border-gray-700/60 space-y-0.5">
        {{ $items }}
    </div>

</div>

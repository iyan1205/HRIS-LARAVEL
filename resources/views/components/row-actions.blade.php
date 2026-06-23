{{-- resources/views/components/row-actions.blade.php --}}
{{--
    Tombol ··· dengan dropdown aksi per baris.
    Slots: $slot — isi menu item (gunakan <x-row-action-item>)
--}}

<div class="relative inline-block" x-data="{ open: false }">
    <button
        @click="open = !open"
        @click.outside="open = false"
        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-teal-600 hover:bg-teal-500 text-white transition-colors duration-150 shadow-sm text-base font-bold leading-none">
        ···
    </button>

    <div
        x-show="open"
        x-transition:enter="transition duration-150 ease-out"
        x-transition:enter-start="opacity-0 scale-95 translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition duration-100 ease-in"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-1"
        class="absolute right-0 mt-1.5 w-40 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 z-20 overflow-hidden py-1">
        {{ $slot }}
    </div>
</div>

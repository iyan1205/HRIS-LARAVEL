{{-- resources/views/components/status-badge.blade.php --}}
{{-- Props: active (bool) --}}
@props(['active' => true])

@if($active)
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400">
        Aktif
    </span>
@else
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400">
        Nonaktif
    </span>
@endif

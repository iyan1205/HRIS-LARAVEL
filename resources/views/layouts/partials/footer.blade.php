{{-- resources/views/layouts/partials/footer.blade.php --}}

<footer class="shrink-0 h-12 flex items-center justify-between px-6 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
    <p class="text-xs text-gray-400 dark:text-gray-500">
        &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
    </p>
    <p class="text-xs text-gray-400 dark:text-gray-500">
        v{{ config('app.version', '1.0.0') }}
    </p>
</footer>

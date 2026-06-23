{{-- resources/views/components/data-table.blade.php --}}
{{--
    Props:
    - paginator  : LengthAwarePaginator — hasil paginate() dari controller
    - filterName : string — id input filter (untuk JS)

    Slots:
    - $filters   — isi filter bar (input, select, toggle, dll)
    - $actions   — tombol aksi di sebelah kanan filter (Cari, Reset, Deleted, dll)
    - $thead     — <th> kolom header tabel
    - $tbody     — <tr> isi baris tabel
    - $empty     — tampilan saat data kosong (opsional, ada default)
--}}
@props([
    'paginator'  => null,
    'filterName' => 'filterNama',
])

<div class="bg-white dark:bg-gray-900 sm:rounded-xl sm:border border-gray-200 dark:border-gray-800 sm:shadow-sm overflow-hidden p-1">

    {{-- ── Filter Bar ──────────────────────────────────────────── --}}
    @if(isset($filters) || isset($actions))
    <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800 space-y-2.5">

        {{-- Baris 1: Filter fields --}}
        @isset($filters)
        <div class="flex flex-wrap items-end gap-3">
            {{ $filters }}
        </div>
        @endisset

        {{-- Baris 2: Action buttons --}}
        @isset($actions)
        <div class="flex items-center gap-2">
            {{ $actions }}
        </div>
        @endisset

    </div>
    @endif

    {{-- ── Table ───────────────────────────────────────────────── --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="allTable">
            <thead>
                <tr class="bg-teal-600 dark:bg-teal-700">
                    {{ $thead }}
                </tr>
            </thead>
            <tbody id="tableBody">
                {{ $tbody }}

                {{-- Empty state fallback --}}
                @if(isset($empty) && $paginator && $paginator->isEmpty())
                    {{ $empty }}
                @endif
            </tbody>
        </table>
    </div>

    {{-- ── Pagination ──────────────────────────────────────────── --}}
    @if($paginator && $paginator->hasPages())
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-4 py-2.5 border-t border-gray-100 dark:border-gray-800 bg-gray-50/60 dark:bg-gray-800/30">

        {{-- Per-page select + info --}}
        <div class="flex items-center gap-3">
            <select onchange="window.location.href=this.value"
                class="text-xs border border-gray-200 dark:border-gray-700 rounded-lg px-2 py-1.5 w-16 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500/30">
                @foreach([10, 25, 50, 100] as $n)
                    <option value="{{ request()->fullUrlWithQuery(['per_page' => $n]) }}"
                        {{ $paginator->perPage() == $n ? 'selected' : '' }}>{{ $n }} </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Menampilkan data ke
                <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $paginator->firstItem() }}</span>
                hingga
                <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $paginator->lastItem() }}</span>
                dari
                <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $paginator->total() }}</span>
                data
            </p>
        </div>

        {{-- Page numbers --}}
        <div class="flex items-center gap-1">

            {{-- Prev --}}
            @if($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-teal-600 hover:bg-teal-50 dark:text-gray-400 dark:hover:text-teal-400 dark:hover:bg-teal-900/20 transition-colors duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
            @endif

            {{-- Numbered pages --}}
            @foreach($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                <a href="{{ $url }}"
                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs font-semibold transition-colors duration-150
                           {{ $page == $paginator->currentPage()
                               ? 'bg-teal-600 text-white shadow-sm shadow-teal-600/20'
                               : 'text-gray-500 hover:text-teal-600 hover:bg-teal-50 dark:text-gray-400 dark:hover:text-teal-400 dark:hover:bg-teal-900/20' }}">
                    {{ $page }}
                </a>
            @endforeach

            {{-- Ellipsis + last --}}
            @if($paginator->lastPage() > $paginator->currentPage() + 2)
                <span class="inline-flex items-center justify-center w-8 h-8 text-xs text-gray-400">…</span>
                <a href="{{ $paginator->url($paginator->lastPage()) }}"
                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs font-semibold text-gray-500 hover:text-teal-600 hover:bg-teal-50 dark:text-gray-400 dark:hover:text-teal-400 dark:hover:bg-teal-900/20 transition-colors duration-150">
                    {{ $paginator->lastPage() }}
                </a>
            @endif

            {{-- Next --}}
            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-teal-600 hover:bg-teal-50 dark:text-gray-400 dark:hover:text-teal-400 dark:hover:bg-teal-900/20 transition-colors duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif

        </div>
    </div>
    @endif

</div>

<x-app-layout>

    <div class="py-0 sm:py-6 sm:px-6 lg:px-8">

        {{-- Flash message --}}
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="mb-4">
            <div class="flex items-center justify-between gap-3 px-4 py-3 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        {{-- ── Data Table ──────────────────────────────────────────── --}}
        <x-data-table :paginator="$departemens">

            {{-- Action buttons --}}
            <x-slot name="actions">
                <a href="{{ route('departemen.create') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold bg-indigo-900 hover:bg-indigo-800 text-white transition-colors duration-150 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Departemen
                </a>

                <a href="{{ route('departemen.create') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700 transition-colors duration-150 ml-auto">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Data Terhapus
                </a>
            </x-slot>


            {{-- Filter fields --}}
            <x-slot name="filters">
                <input type="hidden" id="perPage" name="per_page" value="{{ request('per_page', 10) }}">
            </x-slot>

            {{-- Table head --}}
            <x-slot name="thead">
                <x-table-th>No</x-table-th>
                <x-table-th>
                    <div class="flex flex-col gap-1.5 py-0.5">
                        <span>Nama Departemen</span>
                        <div class="relative">
                            <input type="text" id="filterNama" value="{{ request('nama') }}"
                                placeholder="Cari..."
                                class="w-full pl-7 pr-2 py-1 text-xs font-normal rounded-md border border-teal-500/40 bg-teal-700/30 dark:bg-teal-900/40 text-white placeholder-teal-200/60 focus:outline-none focus:ring-1 focus:ring-white/40 focus:border-white/40 transition">
                            <svg class="absolute left-2 top-1/2 -translate-y-1/2 w-3 h-3 text-teal-200/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                            </svg>
                        </div>
                    </div>
                </x-table-th>
                <x-table-th align="right">Action</x-table-th>
            </x-slot>

            {{-- Table body --}}
            <x-slot name="tbody">
                @forelse ($departemens as $departemen)
                <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900/50 dark:even:bg-gray-950 border-t border-gray-100 dark:border-gray-800 hover:bg-teal-50/40 dark:hover:bg-teal-900/10 transition-colors duration-100 table-row">
                    <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 tabular-nums text-sm">
                        {{ ($departemens->currentPage() - 1) * $departemens->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-5 py-3.5 font-medium text-gray-800 dark:text-gray-200">
                        {{ $departemen->name }}
                    </td>
                    {{-- <td class="px-5 py-3.5 text-center">
                        <span class="inline-block px-3 py-0.5 rounded-full text-xs font-medium
                            {{ $departemen->status
                                ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400'
                                : 'bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400' }}">
                            {{ $departemen->status ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td> --}}
                    <td class="px-5 py-3.5 text-right space-x-1">
                        <a href="{{ route('departemen.edit', $departemen->id) }}"
                            class="inline-flex items-center justify-center w-7 h-7 rounded-md bg-teal-600 hover:bg-teal-500 text-white transition"
                            title="Edit">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <button x-data
                            @click="$dispatch('open-modal-hapus', { id: {{ $departemen->id }}, name: '{{ addslashes($departemen->name) }}' })"
                            class="inline-flex items-center justify-center w-7 h-7 rounded-md bg-red-500 hover:bg-red-400 text-white transition"
                            title="Hapus">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-14 text-center text-gray-400 dark:text-gray-500">
                        <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.25" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-medium">Belum ada data departemen</p>
                        <p class="text-xs mt-1">Klik "Tambah" untuk menambahkan.</p>
                    </td>
                </tr>
                @endforelse
            </x-slot>

        </x-data-table>
    </div>

    {{-- Modal Hapus --}}
    <x-modal-hapus entity="departemen" :deleteUrl="url('departemen')" />

    {{-- Filter JS --}}
    <script>
        function debounce(fn, delay) {
            let timer
            return function (...args) {
                clearTimeout(timer)
                timer = setTimeout(() => fn.apply(this, args), delay)
            }
        }

        function filterTable() {
            const url = new URL(window.location.href)
            const nama = document.getElementById('filterNama').value

            // Hanya filter jika minimal 3 karakter atau kosong (untuk reset)
            if (nama.length === 0) {
                url.searchParams.delete('nama')
            } else if (nama.length >= 3) {
                url.searchParams.set('nama', nama)
            } else {
                return // kurang dari 3 karakter, tidak bereaksi
            }

            url.searchParams.set('per_page', document.getElementById('perPage').value)
            url.searchParams.set('page', 1)
            window.location.href = url.toString()
        }

        function resetFilter() {
            window.location.href = "{{ route('departemen') }}"
        }

        document.getElementById('filterNama').addEventListener('input', debounce(filterTable, 400))
    </script>

</x-app-layout>
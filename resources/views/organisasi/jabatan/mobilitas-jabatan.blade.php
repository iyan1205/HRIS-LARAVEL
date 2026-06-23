<x-app-layout>
        {{-- ===== PAGE HEADER (Breadcrumb + Judul + Tombol Tambah) ===== --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-0.5">
                    <a href="{{ route('dashboard') }}" class="hover:text-teal-600 dark:hover:text-teal-400 transition">Master Data</a>
                    <span class="mx-1">/</span>
                    <span class="text-gray-700 dark:text-gray-300">Mobilitas Jabatan</span>
                </p>
                <h1 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Mobilitas Jabatan
                </h1>
            </div>

        </div>
    </x-slot>
    <div class="py-0 sm:pt-3 sm:pb-4 px-0 sm:px-2">

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
        <x-data-table :paginator="$mobilitasJabatans">

            {{-- Filter fields --}}
            <x-slot name="filters">
                <input type="hidden" id="perPage" name="per_page" value="{{ request('per_page', 10) }}">
            </x-slot>

            {{-- Table head --}}
            <x-slot name="thead">
                <x-table-th class="w-10 lg:hidden"></x-table-th> {{-- kolom expand, hilang di desktop --}}
                <x-table-th>No</x-table-th>
                <x-table-th>
                    <div class="flex flex-col gap-1.5 py-0.5">
                        <span>Nama Karyawan</span>
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

                {{-- Tampil mulai sm --}}
                <x-table-th class="hidden sm:table-cell">
                    <div class="flex flex-col gap-1.5 py-0.5">
                        <span>Aspek</span>
                        <select id="filterAspek"
                            class="w-full px-2 py-1 text-xs font-normal rounded-md border border-teal-500/40 bg-teal-700/30 dark:bg-teal-900/40 text-white focus:outline-none focus:ring-1 focus:ring-white/40 focus:border-white/40 transition">
                            <option value="" class="bg-teal-800 text-white">Semua</option>
                            <option value="Promosi" class="bg-teal-800 text-white" {{ request('aspek') == 'Promosi' ? 'selected' : '' }}>Promosi</option>
                            <option value="Mutasi"  class="bg-teal-800 text-white" {{ request('aspek') == 'Mutasi'  ? 'selected' : '' }}>Mutasi</option>
                            <option value="Demosi"  class="bg-teal-800 text-white" {{ request('aspek') == 'Demosi'  ? 'selected' : '' }}>Demosi</option>
                            <option value="Rotasi"  class="bg-teal-800 text-white" {{ request('aspek') == 'Rotasi'  ? 'selected' : '' }}>Rotasi</option>
                        </select>
                    </div>
                </x-table-th>
                <x-table-th class="hidden sm:table-cell">
                    <div class="flex flex-col gap-1.5 py-0.5">
                        <span>Jabatan Sebelumnya</span>
                        <div class="relative">
                            <input type="text" id="filterJabatanSebelumnya" value="{{ request('jabatan_sebelumnya') }}"
                                placeholder="Cari..."
                                class="w-full pl-7 pr-2 py-1 text-xs font-normal rounded-md border border-teal-500/40 bg-teal-700/30 dark:bg-teal-900/40 text-white placeholder-teal-200/60 focus:outline-none focus:ring-1 focus:ring-white/40 focus:border-white/40 transition">
                            <svg class="absolute left-2 top-1/2 -translate-y-1/2 w-3 h-3 text-teal-200/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                            </svg>
                        </div>
                    </div>
                </x-table-th>
                <x-table-th class="hidden sm:table-cell">
                    <div class="flex flex-col gap-1.5 py-0.5">
                        <span>Jabatan Baru</span>
                        <div class="relative">
                            <input type="text" id="filterJabatanBaru" value="{{ request('jabatan_baru') }}"
                                placeholder="Cari..."
                                class="w-full pl-7 pr-2 py-1 text-xs font-normal rounded-md border border-teal-500/40 bg-teal-700/30 dark:bg-teal-900/40 text-white placeholder-teal-200/60 focus:outline-none focus:ring-1 focus:ring-white/40 focus:border-white/40 transition">
                            <svg class="absolute left-2 top-1/2 -translate-y-1/2 w-3 h-3 text-teal-200/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                            </svg>
                        </div>
                    </div>
                </x-table-th>

                {{-- Tampil hanya desktop --}}
                <x-table-th class="hidden lg:table-cell">
                    <div class="flex flex-col gap-1.5 py-0.5">
                        <span>Departemen Sebelumnya</span>
                        <div class="relative">
                            <input type="text" id="filterDepartemenSebelumnya" value="{{ request('departemen_sebelumnya') }}"
                                placeholder="Cari..."
                                class="w-full pl-7 pr-2 py-1 text-xs font-normal rounded-md border border-teal-500/40 bg-teal-700/30 dark:bg-teal-900/40 text-white placeholder-teal-200/60 focus:outline-none focus:ring-1 focus:ring-white/40 focus:border-white/40 transition">
                            <svg class="absolute left-2 top-1/2 -translate-y-1/2 w-3 h-3 text-teal-200/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                            </svg>
                        </div>
                    </div>
                </x-table-th>
                <x-table-th class="hidden lg:table-cell">
                    <div class="flex flex-col gap-1.5 py-0.5">
                        <span>Departemen Baru</span>
                        <div class="relative">
                            <input type="text" id="filterDepartemenBaru" value="{{ request('departemen_baru') }}"
                                placeholder="Cari..."
                                class="w-full pl-7 pr-2 py-1 text-xs font-normal rounded-md border border-teal-500/40 bg-teal-700/30 dark:bg-teal-900/40 text-white placeholder-teal-200/60 focus:outline-none focus:ring-1 focus:ring-white/40 focus:border-white/40 transition">
                            <svg class="absolute left-2 top-1/2 -translate-y-1/2 w-3 h-3 text-teal-200/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                            </svg>
                        </div>
                    </div>
                </x-table-th>
                <x-table-th class="hidden lg:table-cell">
                    <div class="flex flex-col gap-1.5 py-0.5">
                        <span>Instalasi/Divisi Sebelumnya</span>
                        <div class="relative">
                            <input type="text" id="filterInstalasiDivisiSebelumnya" value="{{ request('instalasi_divisi_sebelumnya') }}"
                                placeholder="Cari..."
                                class="w-full pl-7 pr-2 py-1 text-xs font-normal rounded-md border border-teal-500/40 bg-teal-700/30 dark:bg-teal-900/40 text-white placeholder-teal-200/60 focus:outline-none focus:ring-1 focus:ring-white/40 focus:border-white/40 transition">
                            <svg class="absolute left-2 top-1/2 -translate-y-1/2 w-3 h-3 text-teal-200/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                            </svg>
                        </div>
                    </div>
                </x-table-th>
                <x-table-th class="hidden lg:table-cell">
                    <div class="flex flex-col gap-1.5 py-0.5">
                        <span>Instalasi/Divisi Baru</span>
                        <div class="relative">
                            <input type="text" id="filterInstalasiDivisiBaru" value="{{ request('instalasi_divisi_baru') }}"
                                placeholder="Cari..."
                                class="w-full pl-7 pr-2 py-1 text-xs font-normal rounded-md border border-teal-500/40 bg-teal-700/30 dark:bg-teal-900/40 text-white placeholder-teal-200/60 focus:outline-none focus:ring-1 focus:ring-white/40 focus:border-white/40 transition">
                            <svg class="absolute left-2 top-1/2 -translate-y-1/2 w-3 h-3 text-teal-200/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                            </svg>
                        </div>
                    </div>
                </x-table-th>
                <x-table-th class="hidden lg:table-cell text-center">Tanggal Efektif</x-table-th>

                <x-table-th align="right">Action</x-table-th>
            </x-slot>

            {{-- Table body --}}
            <x-slot name="tbody">
                @forelse ($mobilitasJabatans as $mobilitas)
                <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900/50 dark:even:bg-gray-950 border-t border-gray-100 dark:border-gray-800 hover:bg-teal-50/40 dark:hover:bg-teal-900/10 transition-colors duration-100">

                    {{-- Toggle expand: hilang di desktop --}}
                    <td class="px-3 py-3.5 text-center lg:hidden">
                        <button @click="$store.expandedRows['{{ $mobilitas->id }}'] = !$store.expandedRows['{{ $mobilitas->id }}']"
                            class="inline-flex items-center justify-center w-6 h-6 rounded-full transition"
                            :class="$store.expandedRows['{{ $mobilitas->id }}'] ? 'bg-red-500 hover:bg-red-400 text-white' : 'bg-blue-500 hover:bg-blue-400 text-white'">
                            <svg x-show="!$store.expandedRows['{{ $mobilitas->id }}']" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            <svg x-show="$store.expandedRows['{{ $mobilitas->id }}']" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/>
                            </svg>
                        </button>
                    </td>

                    <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 tabular-nums text-sm">
                        {{ ($mobilitasJabatans->currentPage() - 1) * $mobilitasJabatans->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-5 py-3.5 font-medium text-gray-800 dark:text-gray-200">
                        {{ $mobilitas->karyawan->name }}
                    </td>

                    {{-- Tampil mulai sm --}}
                    <td class="hidden sm:table-cell px-5 py-3.5 text-gray-800 dark:text-gray-200">
                        {{ $mobilitas->aspek }}
                    </td>
                    <td class="hidden sm:table-cell px-5 py-3.5 text-gray-800 dark:text-gray-200">
                        {{ $mobilitas->jabatan_sekarang }}
                    </td>
                    <td class="hidden sm:table-cell px-5 py-3.5 text-gray-800 dark:text-gray-200">
                        {{ $mobilitas->jabatan_baru }}
                    </td>

                    {{-- Tampil hanya desktop --}}
                    <td class="hidden lg:table-cell px-5 py-3.5 text-gray-800 dark:text-gray-200">
                        {{ $mobilitas->departemen_sekarang ?? '-' }}
                    </td>
                    <td class="hidden lg:table-cell px-5 py-3.5 text-gray-800 dark:text-gray-200">
                        {{ $mobilitas->departemen_baru ?? '-' }}
                    </td>
                    <td class="hidden lg:table-cell px-5 py-3.5 text-gray-800 dark:text-gray-200">
                        {{ $mobilitas->unit_sekarang ?? '-' }}
                    </td>
                    <td class="hidden lg:table-cell px-5 py-3.5 text-gray-800 dark:text-gray-200">
                        {{ $mobilitas->unit_baru ?? '-' }}
                    </td>
                    <td class="hidden lg:table-cell px-5 py-3.5 text-center text-gray-800 dark:text-gray-200">
                        {{ \Carbon\Carbon::parse($mobilitas->tanggal_efektif)->translatedFormat('d/m/Y') }}
                    </td>

                    {{-- Action: selalu tampil --}}
                    <td class="px-5 py-3.5 text-right">
                        <a href="{{ route('mobilitas.edit', $mobilitas->id) }}"
                            class="inline-flex items-center justify-center w-7 h-7 rounded-md bg-green-600 hover:bg-green-500 text-white transition"
                            title="Edit">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                    </td>
                </tr>

                {{-- Baris expand: satu saja, otomatis menyesuaikan via class responsive --}}
                <tr x-show="$store.expandedRows['{{ $mobilitas->id }}']" class="lg:hidden" style="display: none;">
                    <td colspan="11" class="px-5 py-3.5 bg-gray-50 dark:bg-gray-900/50 text-sm text-gray-600 dark:text-gray-400">
                        <div class="space-y-2">
                            {{-- Hanya tampil di mobile (<sm), karena di tablet sudah tampil sebagai kolom --}}
                            <p class="sm:hidden"><span class="font-semibold text-gray-700 dark:text-gray-300">Aspek</span> {{ $mobilitas->aspek }}</p>
                            <p class="sm:hidden"><span class="font-semibold text-gray-700 dark:text-gray-300">Jabatan Sebelumnya</span> {{ $mobilitas->jabatan_sekarang }}</p>
                            <p class="sm:hidden"><span class="font-semibold text-gray-700 dark:text-gray-300">Jabatan Baru</span> {{ $mobilitas->jabatan_baru }}</p>

                            {{-- Selalu tampil di expand (mobile & tablet), karena baru muncul sebagai kolom di lg --}}
                            <p><span class="font-semibold text-gray-700 dark:text-gray-300">Departemen Sebelumnya</span> {{ $mobilitas->departemen_sekarang ?? '-' }}</p>
                            <p><span class="font-semibold text-gray-700 dark:text-gray-300">Departemen Baru</span> {{ $mobilitas->departemen_baru ?? '-' }}</p>
                            <p><span class="font-semibold text-gray-700 dark:text-gray-300">Instalasi/Divisi Sebelumnya</span> {{ $mobilitas->unit_sekarang ?? '-' }}</p>
                            <p><span class="font-semibold text-gray-700 dark:text-gray-300">Instalasi/Divisi Baru</span> {{ $mobilitas->unit_baru ?? '-' }}</p>
                            <p><span class="font-semibold text-gray-700 dark:text-gray-300">Tanggal</span> {{ \Carbon\Carbon::parse($mobilitas->tanggal_efektif)->translatedFormat('d/m/Y') }}</p>
                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="11" class="px-5 py-14 text-center text-gray-400 dark:text-gray-500">
                        <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.25" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-medium">Belum ada data jabatan</p>
                        <p class="text-xs mt-1">Klik "Tambah" untuk menambahkan.</p>
                    </td>
                </tr>
                @endforelse
            </x-slot>

        </x-data-table>
    </div>

    {{-- Modal Hapus --}}
    <x-modal-hapus entity="mobilitas" :deleteUrl="url('mobilitas')" />

    {{-- Filter JS --}}
    <script>
        function debounce(fn, delay) {
            let timer
            return function (...args) {
                clearTimeout(timer)
                timer = setTimeout(() => fn.apply(this, args), delay)
            }
        }

        const filters = [
            { id: 'filterNama',                      param: 'nama',                        type: 'text' },
            { id: 'filterAspek',                     param: 'aspek',                       type: 'select' },
            { id: 'filterJabatanSebelumnya',         param: 'jabatan_sebelumnya',          type: 'text' },
            { id: 'filterJabatanBaru',                param: 'jabatan_baru',                type: 'text' },
            { id: 'filterDepartemenSebelumnya',       param: 'departemen_sebelumnya',       type: 'text' },
            { id: 'filterDepartemenBaru',             param: 'departemen_baru',             type: 'text' },
            { id: 'filterInstalasiDivisiSebelumnya',  param: 'instalasi_divisi_sebelumnya', type: 'text' },
            { id: 'filterInstalasiDivisiBaru',        param: 'instalasi_divisi_baru',       type: 'text' },
            { id: 'filterTanggal',                    param: 'tanggal_efektif',             type: 'select' },
        ]

        function filterTable() {
            const url = new URL(window.location.href)
            let shouldFetch = false

            filters.forEach(({ id, param, type }) => {
                const el = document.getElementById(id)
                if (!el) return

                const val = el.value.trim()
                const current = url.searchParams.get(param) ?? ''

                if (type === 'select') {
                    if (val !== current) {
                        val ? url.searchParams.set(param, val) : url.searchParams.delete(param)
                        shouldFetch = true
                    }
                } else {
                    if (val.length === 0) {
                        if (current !== '') {
                            url.searchParams.delete(param)
                            shouldFetch = true
                        }
                    } else if (val.length >= 3 && val !== current) {
                        url.searchParams.set(param, val)
                        shouldFetch = true
                    }
                }
            })

            if (!shouldFetch) return

            url.searchParams.set('per_page', document.getElementById('perPage').value)
            url.searchParams.set('page', 1)
            window.location.href = url.toString()
        }

        function resetFilter() {
            window.location.href = "{{ route('mobilitas.index') }}"
        }

        const debouncedFilter = debounce(filterTable, 400)

        filters.forEach(({ id, type }) => {
            const el = document.getElementById(id)
            if (!el) return
            el.addEventListener(type === 'select' ? 'change' : 'input',
                type === 'select' ? filterTable : debouncedFilter)
        })
    </script>

</x-app-layout>
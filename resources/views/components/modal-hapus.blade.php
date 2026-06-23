{{-- resources/views/components/modal-hapus.blade.php --}}
{{--
    Props:
    - entity     : string — nama entitas untuk teks konfirmasi, misal 'departemen', 'jabatan'
    - deleteUrl  : string — base URL delete, misal url('jabatan') → endpoint: `{url}/{id}`
--}}
@props([
    'entity'    => 'data',
    'deleteUrl' => '',
])

<div
    x-data="{
        show: false,
        id: null,
        name: '',
        open(data) { this.id = data.id; this.name = data.name; this.show = true },
    }"
    @open-modal-hapus.window="open($event.detail)"
    x-show="show"
    x-transition:enter="transition duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display:none">

    {{-- Backdrop --}}
    <div @click="show = false" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    {{-- Dialog --}}
    <div
        x-show="show"
        x-transition:enter="transition duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        class="relative w-full max-w-md bg-white dark:bg-gray-900 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/30">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Konfirmasi Hapus</h3>
            </div>
            <button @click="show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="px-5 py-4">
            <p class="text-sm text-gray-600 dark:text-gray-300">
                Apakah kamu yakin ingin menghapus {{ $entity }}
                <span class="font-semibold text-gray-900 dark:text-white" x-text="'«' + name + '»'"></span>?
            </p>
            {{-- <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">
                Data yang dihapus dapat dipulihkan melalui menu <em>Data Terhapus</em>.
            </p> --}}
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-end gap-2 px-5 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800">
            <button @click="show = false"
                class="px-4 py-2 text-xs font-semibold rounded-lg text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
                Batal
            </button>
            <form :action="`{{ $deleteUrl }}/${id}`" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-4 py-2 text-xs font-semibold rounded-lg text-white bg-red-600 hover:bg-red-500 transition-colors duration-150 shadow-sm">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

{{-- resources/views/components/page-header.blade.php --}}
{{--
    Props:
    - title       : string — judul halaman
    - breadcrumbs : array  — [['label' => 'Organisasi'], ['label' => 'Jabatan', 'active' => true]]
    - createRoute : string — nama route untuk tombol Tambah (opsional)
    - createLabel : string — label tombol (default: 'Tambah')
    - icon        : slot   — SVG icon (opsional)
--}}
@props([
    'title'       => '',
    'breadcrumbs' => [],
    'createRoute' => null,
    'createLabel' => 'Tambah',
])

<div class="flex items-center justify-between">
    <div class="flex items-center gap-2.5">

        {{-- Icon --}}
        @isset($icon)
        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-900/30 shrink-0">
            <span class="text-teal-600 dark:text-teal-400">{{ $icon }}</span>
        </div>
        @endisset

        <div>
            <h2 class="text-sm font-bold text-gray-800 dark:text-gray-100">{{ $title }}</h2>

            {{-- Breadcrumb --}}
            @if(count($breadcrumbs))
            <nav class="flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                @foreach($breadcrumbs as $i => $crumb)
                    @if($i > 0)
                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    @endif
                    @if(!empty($crumb['active']))
                        <span class="text-gray-600 dark:text-gray-300 font-medium">{{ $crumb['label'] }}</span>
                    @else
                        <span>{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>
            @endif
        </div>
    </div>

</div>

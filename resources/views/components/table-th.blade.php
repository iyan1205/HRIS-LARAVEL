{{-- resources/views/components/table-th.blade.php --}}
@props(['align' => 'left', 'width' => null])

<th {{ $attributes->merge([
    'class' => 'px-4 py-2.5 text-xs font-bold uppercase tracking-wider text-white ' .
                'border-r border-teal-500/40 last:border-r-0 ' .
               ($align === 'center' ? 'text-center' : ($align === 'right' ? 'text-right' : 'text-left')) .
               ($width ? " w-{$width}" : '')
]) }}>
    {{ $slot }}
</th>

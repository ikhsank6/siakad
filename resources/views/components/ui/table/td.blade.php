@props(['shrink' => false])

<td {{ $attributes->class([
    'px-4 py-4 text-sm text-[#4b5675] dark:text-[#a1a5b7] border-t border-[#e8e8e8] dark:border-[#2d2d3a]',
    'w-1' => $shrink
]) }}>
    {{ $slot }}
</td>
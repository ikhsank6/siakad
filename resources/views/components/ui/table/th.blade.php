@props(['shrink' => false])

<th {{ $attributes->class([
    'px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-[#99a1b7] dark:text-[#6d6d80] uppercase whitespace-nowrap bg-transparent',
    'w-1' => $shrink
]) }}>
    {{ $slot }}
</th>
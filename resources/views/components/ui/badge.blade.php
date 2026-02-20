@props(['variant' => 'neutral'])

@php
    $classes = match ($variant) {
        'success' => 'bg-[#17c653]/15 text-[#17c653]',
        'danger' => 'bg-[#f8285a]/15 text-[#f8285a]',
        'warning' => 'bg-[#f6b100]/15 text-[#f6b100]',
        'info' => 'bg-[#1b84ff]/15 text-[#1b84ff]',
        'admin' => 'bg-[#17c653]/15 text-[#17c653]',
        'user' => 'bg-[#f1f1f4] dark:bg-[#252532] text-[#99a1b7] dark:text-[#6d6d80]',
        default => 'bg-[#f1f1f4] dark:bg-[#252532] text-[#99a1b7] dark:text-[#6d6d80]',
    };
@endphp

<span {{ $attributes->class([
    'inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-tight',
    $classes
]) }}>
    {{ $slot }}
</span>
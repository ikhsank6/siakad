@props(['name' => '', 'src' => null, 'size' => 'md'])

@php
    $initials = collect(explode(' ', $name))->map(fn($n) => mb_substr($n, 0, 1))->take(2)->join('');

    $sizes = [
        'sm' => 'h-6 w-6 text-[10px]',
        'md' => 'h-9 w-9 text-xs',
        'lg' => 'h-12 w-12 text-sm',
        'xl' => 'h-16 w-16 text-base',
    ];

    $colors = [
        'bg-[#5260ff]',
        'bg-[#ff5d5d]',
        'bg-[#2ecc71]',
        'bg-[#f1c40f]',
        'bg-[#9b59b6]',
        'bg-[#34495e]',
        'bg-[#1abc9c]',
        'bg-[#e67e22]',
        'bg-[#95a5a6]',
        'bg-[#3498db]'
    ];
    // Simple hash to pick a consistent color for a name
    $colorIndex = abs(crc32($name)) % count($colors);
    $bgColor = $colors[$colorIndex];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

@if($src)
    <img 
        src="{{ $src }}" 
        alt="{{ $name }}"
        {{ $attributes->class([
            'rounded-full object-cover shrink-0',
            $sizeClass
        ]) }}
    />
@else
    <div {{ $attributes->class([
        'flex items-center justify-center rounded-full font-bold text-white shrink-0',
        $sizeClass,
        $bgColor
    ]) }}>
        {{ $initials }}
    </div>
@endif
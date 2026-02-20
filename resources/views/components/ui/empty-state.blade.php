@props([
    'title' => 'No data found',
    'description' => 'Try adjusting your search or filters.',
])

<div {{ $attributes->class(['flex flex-col items-center justify-center py-12 text-center']) }}>
    {{-- Title --}}
    <h3 class="text-base font-semibold text-zinc-500 dark:text-zinc-400 mb-1">{{ $title }}</h3>

    {{-- Description --}}
    <p class="text-sm text-zinc-400 dark:text-zinc-500">{{ $description }}</p>
</div>

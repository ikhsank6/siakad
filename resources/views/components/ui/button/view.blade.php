@props([
    'tooltip' => 'Lihat Detail',
])

<flux:tooltip :content="$tooltip" position="top">
    <button {{ $attributes->merge([
        'type' => 'button',
        'class' => 'p-2 text-zinc-400 hover:text-metronic-primary hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-all active:scale-95'
    ]) }}>
        <flux:icon name="arrow-top-right-on-square" variant="mini" class="w-4 h-4" />
    </button>
</flux:tooltip>

@props([
    'tooltip' => 'Tandai Selesai',
])

<flux:tooltip :content="$tooltip" position="top">
    <button {{ $attributes->merge([
        'type' => 'button',
        'class' => 'p-2 text-zinc-400 hover:text-metronic-success hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-all active:scale-95'
    ]) }}>
        <flux:icon name="check" variant="mini" class="w-4 h-4" />
    </button>
</flux:tooltip>

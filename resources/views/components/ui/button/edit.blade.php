@props([
    'tooltip' => 'Edit Data',
    'uuid' => null,
])

<flux:tooltip :content="$tooltip" position="top">
    <button {{ $attributes->merge([
        'type' => 'button',
        'wire:click' => $uuid ? "edit('{$uuid}')" : null,
        'wire:loading.attr' => 'disabled',
        'class' => 'p-2 text-zinc-400 hover:text-metronic-primary hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-all active:scale-95 disabled:opacity-50 disabled:cursor-wait flex items-center justify-center'
    ]) }}>
        <span wire:loading.remove wire:target="edit">
            <flux:icon name="pencil-square" variant="mini" class="w-4 h-4" />
        </span>
        <span wire:loading wire:target="edit">
            <flux:icon.loading class="w-4 h-4 text-metronic-dark-border dark:text-metronic-light-text-muted" />
        </span>
    </button>
</flux:tooltip>
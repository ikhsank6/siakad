@props([
    'label' => '',
    'icon' => null,
])

<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white transition-all active:scale-95'
]) }}>
    @if($icon)
        <flux:icon :name="$icon" variant="mini" class="w-4 h-4" />
    @endif
    <span>{{ $label }}</span>
</button>

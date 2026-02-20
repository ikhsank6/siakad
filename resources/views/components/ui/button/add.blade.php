@props([
    'label' => 'Tambah Data',
    'tooltip' => null,
    'icon' => 'plus',
])

@php
    $finalTooltip = $tooltip ?? "klik untuk {$label}";
@endphp

<flux:tooltip :content="$finalTooltip" position="top">
    <button {{ $attributes->merge([
        'type' => 'button',
        'wire:click' => 'create',
        'class' => 'inline-flex items-center gap-2 rounded-lg bg-metronic-primary px-4 py-2.5 text-sm font-semibold text-white shadow-soft-primary hover:bg-metronic-primary/90 transition-all active:scale-95 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-metronic-primary'
    ]) }}>
        <flux:icon :name="$icon" variant="mini" class="w-4 h-4" />
        <span>{{ $label }}</span>
    </button>
</flux:tooltip>

@props([
    'tooltip' => 'Hapus Data',
    'uuid' => null,
    'componentId' => null,
    'name' => '',
    'message' => null,
])

@php
    $componentId = $componentId ?? $this->getId();
    $finalMessage = $message ?? "Apakah Anda yakin ingin menghapus data \"{$name}\"?";
@endphp

<flux:tooltip :content="$tooltip" position="top">
    <button {{ $attributes->merge([
        'type' => 'button',
        'x-on:click' => "\$dispatch('open-delete-confirm', { 
                            id: '{$uuid}', 
                            componentId: '{$componentId}',
                            message: '{$finalMessage}'
                        })",
        'class' => 'p-2 text-zinc-400 hover:text-metronic-danger hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all active:scale-95'
    ]) }}>
        <flux:icon name="trash" variant="mini" class="w-4 h-4" />
    </button>
</flux:tooltip>

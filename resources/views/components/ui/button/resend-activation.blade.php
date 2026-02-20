@props([
    'tooltip' => 'Resend Activation Email',
    'uuid' => null,
])

@php
    $target = "resendActivation('{$uuid}')";
@endphp

@if($uuid)
    <flux:tooltip :content="$tooltip" position="top">
        <button {{ $attributes->merge([
            'type' => 'button',
            'wire:click' => $target,
            'wire:loading.attr' => 'disabled',
            'wire:target' => $target,
            'class' => 'p-2 text-zinc-400 hover:text-amber-500 hover:bg-amber-100 dark:hover:bg-amber-900/20 rounded-lg transition-all active:scale-95 disabled:opacity-50'
        ]) }}>
            <div class="relative w-4 h-4 flex items-center justify-center">
                <div wire:loading.remove wire:target="{{ $target }}">
                    <flux:icon name="envelope" variant="mini" class="w-4 h-4" />
                </div>
                <div wire:loading wire:target="{{ $target }}">
                    <svg class="w-4 h-4 animate-spin text-amber-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </button>
    </flux:tooltip>
@endif

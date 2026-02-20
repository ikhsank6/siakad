@props([
    'name' => null,
    'title' => '',
    'maxWidth' => '2xl',
    'formId' => null,
    'cancelClick' => '$set("showModal", false)'
])

@php
$maxWidthClass = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    '3xl' => 'sm:max-w-3xl',
    '4xl' => 'sm:max-w-4xl',
    '5xl' => 'sm:max-w-5xl',
    '6xl' => 'sm:max-w-6xl',
    '7xl' => 'sm:max-w-7xl',
][$maxWidth] ?? 'sm:max-w-2xl';
@endphp

<div
    x-data="{ 
        show: @if($attributes->wire('model')->value()) @entangle($attributes->wire('model')) @else false @endif 
    }"
    x-on:open-modal.window="if ($event.detail.name === '{{ $name }}') show = true"
    x-on:close-modal.window="if ($event.detail.name === '{{ $name }}') show = false"
    x-show="show"
    class="fixed inset-0 z-50"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm"
        aria-hidden="true"
    ></div>

    {{-- Modal Container --}}
    <div class="fixed inset-0 flex items-start justify-center p-4 sm:p-6 py-8 overflow-y-auto">
        {{-- Modal Panel --}}
        <div
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="relative w-full {{ $maxWidthClass }} bg-white dark:bg-[#1e1e2d] rounded-xl shadow-2xl border border-zinc-200 dark:border-[#2d2d3a] flex flex-col my-auto"
        >
            {{-- Header --}}
            @if($title)
                <div class="h-16 min-h-[64px] max-h-[64px] px-6 flex items-center justify-between border-b border-zinc-200 dark:border-[#2d2d3a] bg-white dark:bg-[#1e1e2d] shrink-0">
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white truncate">
                        {{ $title }}
                    </h3>
                    <button @click="show = false" class="p-2 -mr-2 text-zinc-400 hover:text-zinc-600 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-all shrink-0" type="button">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            {{-- Body --}}
            <div class="flex-1 p-6 bg-white dark:bg-[#1e1e2d] custom-modal-body">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            <div class="h-16 min-h-[64px] max-h-[64px] px-6 flex items-center justify-end gap-3 border-t border-zinc-200 dark:border-[#2d2d3a] shrink-0 bg-white dark:bg-[#1e1e2d]">
                @if(isset($footer))
                    {{ $footer }}
                @else
                    <button type="button" @click.prevent="show = false" wire:click="{{ $cancelClick }}"
                        class="h-10 px-5 inline-flex items-center justify-center rounded-lg text-sm font-bold text-zinc-600 bg-zinc-100 hover:bg-zinc-200 hover:text-zinc-900 border border-zinc-200 transition-all duration-200 cursor-pointer dark:text-zinc-400 dark:bg-zinc-800/50 dark:hover:bg-zinc-800 dark:hover:text-white dark:border-zinc-700 shadow-none">
                        Cancel
                    </button>
                    <button type="submit" @if($formId) form="{{ $formId }}" @endif
                        class="h-10 px-6 inline-flex items-center justify-center gap-2 rounded-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 active:scale-95 transition-all duration-200 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed shadow-none"
                        wire:loading.attr="disabled">
                        <svg wire:loading class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove>Save Changes</span>
                        <span wire:loading>Saving...</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Scrollbar for Premium Feel */
.style-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.style-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.style-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
.dark .style-scrollbar::-webkit-scrollbar-thumb {
    background: #334155;
}

/* FILAMENT FORM OVERRIDES - Needed to force layout inside modal */
.custom-modal-body .fi-fo-component-ctn,
.custom-modal-body .fi-fo-grid {
    display: flex !important;
    flex-direction: column !important;
    width: 100% !important;
    gap: 1.25rem !important;
    grid-template-columns: none !important;
}

.custom-modal-body .fi-fo-field-wrp {
    width: 100% !important;
}

/* PREMIUM TOGGLE BUTTONS - Consistent with news/carousels */
.premium-toggle-group .fi-fo-toggle-buttons {
    display: flex !important;
    gap: 0.5rem !important;
    margin-top: 0.5rem;
}

.premium-toggle-group button {
    height: 44px !important;
    padding: 0 1.25rem !important;
    border-radius: 10px !important;
    font-weight: 600 !important;
    font-size: 0.875rem !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 0.5rem !important;
    border: 2px solid transparent !important;
    transition: all 0.15s !important;
    cursor: pointer !important;
}

/* Default unselected */
.premium-toggle-group button {
    background: #f3f4f6 !important;
    color: #6b7280 !important;
    border-color: #e5e7eb !important;
}
.dark .premium-toggle-group button {
    background: #27272a !important;
    color: #9ca3af !important;
    border-color: #3f3f46 !important;
}

/* Selected Success (Active/Yes) */
.premium-toggle-group button[data-checked="true"][value="1"],
.premium-toggle-group button.fi-active[value="1"],
.premium-toggle-group button[aria-pressed="true"]:first-child {
    background: #10b981 !important;
    color: white !important;
    border-color: #059669 !important;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3) !important;
}

/* Selected Danger (Inactive/No) */
.premium-toggle-group button[data-checked="true"][value="0"],
.premium-toggle-group button.fi-active[value="0"],
.premium-toggle-group button[aria-pressed="true"]:nth-child(2) {
    background: #ef4444 !important;
    color: white !important;
    border-color: #dc2626 !important;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3) !important;
}

.premium-toggle-group button[aria-pressed="true"] svg,
.premium-toggle-group button.fi-active svg,
.premium-toggle-group button[data-checked="true"] svg {
    color: white !important;
}
</style>

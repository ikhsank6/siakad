@props([
    'search' => 'search',
    'perPage' => 'perPage',
    'view' => 'view',
    'showFilters' => true,
    'showBulk' => true,
    'showColumns' => true,
    'showPageSize' => true,
    'showViewToggle' => false,
])

<div {{ $attributes->class(['flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 pt-1']) }}>
    {{-- Left: Elegant Search --}}
    <div class="flex flex-1 items-center gap-2 max-w-sm">
        <flux:input 
            wire:model.live.debounce.300ms="{{ $search }}"
            placeholder="Search..." 
            icon="magnifying-glass"
            clearable
            class="flex-1"
        />
    </div>

    {{-- Right: Integrated Actions --}}
    <div class="flex items-center gap-2">
        @if ($showFilters)
            <flux:button icon="funnel" variant="ghost" class="h-10! w-10! flex items-center justify-center border-zinc-200! dark:border-zinc-700! rounded-xl!" />
        @endif

        @if ($showBulk)
            <flux:dropdown>
                <flux:button variant="ghost" class="h-10! px-4! rounded-xl! border border-zinc-200! dark:border-zinc-700! text-sm! font-semibold! text-zinc-600! dark:text-zinc-400! shadow-sm!" icon-trailing="chevron-down">Action</flux:button>
                <flux:menu>
                    <flux:menu.item icon="arrow-down-tray">Export Selected</flux:menu.item>
                    <flux:menu.item icon="trash" variant="danger">Delete Selected</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        @endif

        @if ($showViewToggle)
            <div class="flex items-center">
                <flux:radio.group wire:model.live="{{ $view }}" variant="segmented" size="sm">
                    <flux:radio value="table" icon="table-cells" />
                    <flux:radio value="board" icon="squares-2x2" />
                </flux:radio.group>
            </div>

        @endif

        @if ($showPageSize)
            <flux:dropdown>
                <flux:button variant="ghost" class="h-10! px-3! min-w-[80px] rounded-xl! border border-zinc-200! dark:border-zinc-700! text-zinc-800! dark:text-white! font-bold! bg-zinc-50/50! dark:bg-zinc-800/40! shadow-sm!" icon="list-bullet" icon-trailing="chevron-down">
                    {{ $this->{$perPage} ?? 10 }}
                </flux:button>

                <flux:menu class="min-w-32">
                    <flux:menu.radio.group wire:model.live="{{ $perPage }}">
                        <flux:menu.radio value="10">10 Rows</flux:menu.radio>
                        <flux:menu.radio value="25">25 Rows</flux:menu.radio>
                        <flux:menu.radio value="50">50 Rows</flux:menu.radio>
                        <flux:menu.radio value="100">100 Rows</flux:menu.radio>
                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>
        @endif
    </div>
</div>
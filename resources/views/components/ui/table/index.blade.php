@props(['header' => null, 'footer' => null, 'view' => 'table', 'board' => null, 'customTable' => null, 'paginator' => null])

<div {{ $attributes->class(['flex flex-col']) }}>
    @if ($header)
        <div class="mb-4">
            {{ $header }}
        </div>
    @endif

    @if ($view === 'table')
        @if ($customTable)
            {{ $customTable }}
        @else
            <div
                class="premium-table-container overflow-hidden bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-0">
                        {{ $slot }}
                    </table>
                </div>
            </div>
        @endif
    @elseif ($board)
        <div class="premium-board-container">
            {{ $board }}
        </div>
    @else
        {{-- Fallback to table if board data not provided --}}
        <div
            class="premium-table-container overflow-hidden bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-0">
                    {{ $slot }}
                </table>
            </div>
        </div>
    @endif

    @if ($footer || $paginator)
        <div class="mt-4">
            @if ($footer)
                {{ $footer }}
            @elseif ($paginator)
                <x-ui.pagination :paginator="$paginator" />
            @endif
        </div>
    @endif
</div>
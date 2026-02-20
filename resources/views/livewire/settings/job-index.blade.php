<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Settings</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Jobs</flux:breadcrumbs.item>
</x-slot>

<div>
    <x-ui.card title="Failed Jobs" description="Manage and retry background jobs that have failed.">
        <x-slot name="headerAction">
            <div class="flex items-center gap-3">
                <flux:button wire:click="$refresh" variant="ghost" icon="arrow-path" size="sm"
                    class="h-10! w-10! flex items-center justify-center rounded-xl! border border-zinc-200! dark:border-zinc-700! shadow-sm!"
                    tooltip="Refresh List" />
                @if($failedJobs->total() > 0)
                    <flux:button wire:click="retryAll" wire:loading.attr="disabled" wire:target="retryAll" variant="primary"
                        icon="arrow-path" size="sm" class="h-10! rounded-xl!">
                        Retry All
                    </flux:button>
                    <flux:button wire:confirm="Are you sure you want to delete all failed jobs?" wire:click="deleteAll"
                        wire:loading.attr="disabled" wire:target="deleteAll" variant="danger" icon="trash" size="sm"
                        class="h-10! rounded-xl!">
                        Clear All
                    </flux:button>
                @endif
            </div>
        </x-slot>

        <x-ui.table :paginator="$failedJobs">
            <x-slot name="header">
                <x-ui.table.header search="search" :showFilters="false" :showBulk="false" :showColumns="false"
                    :showViewToggle="false" />
            </x-slot>

            <x-ui.table.thead>
                <x-ui.table.th>ID</x-ui.table.th>
                <x-ui.table.th>Queue</x-ui.table.th>
                <x-ui.table.th>Failed At</x-ui.table.th>
                <x-ui.table.th>Details</x-ui.table.th>
                <x-ui.table.th shrink></x-ui.table.th>
            </x-ui.table.thead>

            <x-ui.table.tbody>
                @forelse($failedJobs as $job)
                    <x-ui.table.tr x-data="{ expanded: false }" class="align-top">
                        <x-ui.table.td class="text-xs font-mono text-zinc-500">
                            #{{ $job->id }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge variant="info" class="capitalize">{{ $job->queue }}</x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td class="whitespace-nowrap text-xs text-zinc-500">
                            {{ \Carbon\Carbon::parse($job->failed_at)->format('Y-m-d H:i:s') }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <div class="flex flex-col gap-1 max-w-2xl">
                                @php
                                    $payload = json_decode($job->payload, true);
                                    $displayName = $payload['displayName'] ?? ($payload['job'] ?? 'Unknown Job');
                                @endphp
                                <div class="text-sm font-bold text-zinc-900 dark:text-white truncate">
                                    {{ $displayName }}
                                </div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400 line-clamp-1 italic">
                                    {{ Str::limit($job->exception, 150) }}
                                </div>

                                <button @click="expanded = !expanded"
                                    class="mt-2 text-metronic-primary hover:opacity-80 text-[10px] font-bold uppercase text-left flex items-center gap-1">
                                    <flux:icon name="chevron-up" x-show="expanded" size="xs" x-cloak />
                                    <flux:icon name="chevron-down" x-show="!expanded" size="xs" x-cloak />
                                    <span x-text="expanded ? 'Hide Exception' : 'Show Full Exception'"></span>
                                </button>

                                <div x-show="expanded" x-cloak
                                    class="mt-4 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden bg-zinc-50 dark:bg-zinc-900/50 p-4">
                                    <div
                                        class="font-mono text-[11px] leading-relaxed text-zinc-700 dark:text-zinc-400 whitespace-pre-wrap break-all">
                                        {{ $job->exception }}
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                                        <div class="text-[10px] uppercase font-bold text-zinc-400 mb-2">Payload</div>
                                        <pre
                                            class="text-[10px] font-mono text-zinc-600 dark:text-zinc-500 overflow-x-auto">{{ json_encode($payload, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                </div>
                            </div>
                        </x-ui.table.td>
                        <x-ui.table.td shrink>
                            <div class="flex items-center justify-end gap-2">
                                <flux:button wire:click="retry({{ $job->id }})" wire:loading.attr="disabled"
                                    wire:target="retry({{ $job->id }})" variant="ghost" icon="arrow-path" size="sm"
                                    tooltip="Retry Job"
                                    class="text-emerald-600 hover:bg-emerald-50! dark:hover:bg-emerald-900/20!" />
                                <flux:button wire:confirm="Are you sure you want to delete this failed job?"
                                    wire:click="delete({{ $job->id }})" wire:loading.attr="disabled"
                                    wire:target="delete({{ $job->id }})" variant="ghost" icon="trash" size="sm"
                                    tooltip="Delete Job"
                                    class="text-rose-600 hover:bg-rose-50! dark:hover:bg-rose-900/20!" />
                            </div>
                        </x-ui.table.td>
                    </x-ui.table.tr>
                @empty
                    <x-ui.table.tr>
                        <x-ui.table.td colspan="5">
                            <x-ui.empty-state message="No failed jobs found." />
                        </x-ui.table.td>
                    </x-ui.table.tr>
                @endforelse
            </x-ui.table.tbody>

            @if($failedJobs->hasPages())
                <x-slot name="footer">
                    <x-ui.pagination :paginator="$failedJobs" />
                </x-slot>
            @endif
        </x-ui.table>
    </x-ui.card>
</div>
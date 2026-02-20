<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Settings</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Logs</flux:breadcrumbs.item>
</x-slot>

<div>
    <x-ui.card title="Laravel Logs" description="View and analyze application log files.">
        <x-slot name="headerAction">
            <div class="flex items-center gap-3">
                <flux:dropdown>
                    <flux:button variant="ghost"
                        class="h-10! px-3! min-w-[200px] rounded-xl! border border-zinc-200! dark:border-zinc-700! text-zinc-800! dark:text-white! font-bold! bg-zinc-50/50! dark:bg-zinc-800/40! shadow-sm!"
                        icon="document-text" icon-trailing="chevron-down">
                        {{ $selectedFile?->name ?? 'Select File' }} ({{ $selectedFile?->sizeFormatted() ?? '0 B' }})
                    </flux:button>

                    <flux:menu class="min-w-64">
                        <flux:menu.radio.group wire:model.live="selectedFileIdentifier">
                            @foreach($files as $file)
                                <flux:menu.radio value="{{ $file->identifier }}">
                                    {{ $file->name }} ({{ $file->sizeFormatted() }})
                                </flux:menu.radio>
                            @endforeach
                        </flux:menu.radio.group>
                    </flux:menu>
                </flux:dropdown>

                <flux:button wire:click="downloadLog" icon="arrow-down-tray" size="sm" tooltip="Download Log File"
                    class="h-10! w-10! flex items-center justify-center rounded-xl! border border-zinc-200! dark:border-zinc-700! shadow-sm!" />
            </div>
        </x-slot>

        <div class="space-y-4">
            <x-ui.table :paginator="$logs">
                <x-slot name="header">
                    <x-ui.table.header search="search" :showFilters="false" :showBulk="false" :showColumns="false"
                        :showViewToggle="false" />
                </x-slot>

                <x-ui.table.thead>
                    <x-ui.table.th>Datetime</x-ui.table.th>
                    <x-ui.table.th>Severity</x-ui.table.th>
                    <x-ui.table.th>Env</x-ui.table.th>
                    <x-ui.table.th>Message</x-ui.table.th>
                </x-ui.table.thead>

                <x-ui.table.tbody>
                    @if($logs)
                        @forelse($logs as $log)
                            <x-ui.table.tr x-data="{ expanded: false }" class="align-top">
                                <x-ui.table.td class="whitespace-nowrap text-xs text-zinc-500">
                                    {{ $log->datetime?->format('Y-m-d H:i:s') }}
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    @php
                                        $severity = strtolower($log->level);
                                        $variant = match ($severity) {
                                            'error', 'critical', 'alert', 'emergency' => 'danger',
                                            'warning' => 'warning',
                                            'notice', 'info' => 'info',
                                            'debug' => 'gray',
                                            default => 'gray',
                                        };
                                    @endphp
                                    <x-ui.badge :variant="$variant" class="uppercase text-[10px]">{{ $log->level }}</x-ui.badge>
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    <span class="text-xs font-medium text-zinc-500 italic uppercase">
                                        {{ $log->extra['environment'] ?? '-' }}
                                    </span>
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    <div class="flex flex-col gap-1">
                                        <div class="text-sm text-zinc-700 dark:text-zinc-300 break-all font-mono line-clamp-2"
                                            :class="expanded ? 'line-clamp-none' : 'line-clamp-2'">
                                            {{ $log->message }}
                                        </div>

                                        @if(strlen($log->getOriginalText()) > 200)
                                            <button @click="expanded = !expanded"
                                                class="text-indigo-500 hover:text-indigo-400 text-[10px] font-bold uppercase text-left">
                                                <span x-show="!expanded">Show Full Trace</span>
                                                <span x-show="expanded">Show Less</span>
                                            </button>
                                        @endif

                                        <div x-show="expanded" x-cloak x-data="{ tab: 'stack' }"
                                            class="mt-4 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden bg-white dark:bg-zinc-950 shadow-sm transition-all">
                                            {{-- Tabs Header --}}
                                            <div
                                                class="flex items-center px-4 bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
                                                <button @click="tab = 'stack'"
                                                    :class="tab === 'stack' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'"
                                                    class="py-2.5 px-4 text-xs font-bold uppercase tracking-wider border-b-2 transition-all">
                                                    Stack Trace
                                                </button>
                                                <button @click="tab = 'raw'"
                                                    :class="tab === 'raw' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'"
                                                    class="py-2.5 px-4 text-xs font-bold uppercase tracking-wider border-b-2 transition-all">
                                                    Raw
                                                </button>
                                            </div>

                                            {{-- Tabs Content --}}
                                            <div class="p-0 max-h-[500px] overflow-y-auto">
                                                {{-- Stack Trace View (Parsed/Better Wrapping) --}}
                                                <div x-show="tab === 'stack'"
                                                    class="p-4 font-mono text-[11px] leading-relaxed text-zinc-700 dark:text-zinc-400 whitespace-pre-wrap wrap-break-word italic">
                                                    {{ $log->message }}
                                                    @if(count($log->context) > 0)
                                                        <hr class="my-3 border-zinc-200 dark:border-zinc-800">
                                                        <div
                                                            class="font-bold mb-1 opacity-50 uppercase tracking-tighter text-[9px]">
                                                            Context:</div>
                                                        {{ json_encode($log->context, JSON_PRETTY_PRINT) }}
                                                    @endif
                                                </div>

                                                {{-- Raw View --}}
                                                <div x-show="tab === 'raw'"
                                                    class="p-4 font-mono text-[11px] leading-relaxed text-zinc-600 dark:text-zinc-500 whitespace-pre-wrap break-all bg-zinc-50/50 dark:bg-zinc-900/50">
                                                    {{ $log->getOriginalText() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </x-ui.table.td>
                            </x-ui.table.tr>
                        @empty
                            <x-ui.table.tr>
                                <x-ui.table.td colspan="4">
                                    <x-ui.empty-state message="No logs found matching your criteria." />
                                </x-ui.table.td>
                            </x-ui.table.tr>
                        @endforelse
                    @else
                        <x-ui.table.tr>
                            <x-ui.table.td colspan="4">
                                <x-ui.empty-state message="Please select a log file to view entries." />
                            </x-ui.table.td>
                        </x-ui.table.tr>
                    @endif
                </x-ui.table.tbody>

            </x-ui.table>
        </div>
    </x-ui.card>
</div>
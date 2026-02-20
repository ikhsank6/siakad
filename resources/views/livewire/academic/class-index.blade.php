<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Academic</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Classes</flux:breadcrumbs.item>
</x-slot>

<div>
    <x-ui.card title="Classes" description="Manage academic classes.">
        <x-slot name="headerAction">
            <x-ui.button.add label="Tambah Kelas" wire:click="create" />
        </x-slot>

        <x-ui.table :paginator="$classes" :view="$view">
            <x-slot name="header">
                <x-ui.table.header search="search" :showFilters="false" :showBulk="false" :showColumns="false"
                    :showViewToggle="true" />
            </x-slot>

            <x-ui.table.thead>
                <x-ui.table.th>Name</x-ui.table.th>
                <x-ui.table.th>Grade Level</x-ui.table.th>
                <x-ui.table.th>Major</x-ui.table.th>
                <x-ui.table.th>Room</x-ui.table.th>
                <x-ui.table.th shrink></x-ui.table.th>
            </x-ui.table.thead>

            <x-ui.table.tbody>
                @forelse($classes as $class)
                    <x-ui.table.tr>
                        <x-ui.table.td class="font-bold">{{ $class->name }}</x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge variant="primary">Grade {{ $class->grade_level }}</x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td>{{ $class->major ?? '-' }}</x-ui.table.td>
                        <x-ui.table.td>
                            @if($class->room)
                                <x-ui.badge variant="neutral">{{ $class->room->name }}</x-ui.badge>
                            @else
                                <span class="text-zinc-400">-</span>
                            @endif
                        </x-ui.table.td>
                        <x-ui.table.td shrink>
                            <div class="flex items-center justify-end gap-2">
                                <x-ui.button.edit :uuid="$class->uuid" />
                                <x-ui.button.delete :uuid="$class->uuid" :name="$class->name" />
                            </div>
                        </x-ui.table.td>
                    </x-ui.table.tr>
                @empty
                    <x-ui.table.tr>
                        <x-ui.table.td colspan="5"><x-ui.empty-state /></x-ui.table.td>
                    </x-ui.table.tr>
                @endforelse
            </x-ui.table.tbody>

            <x-slot name="board">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($classes as $class)
                        <div
                            class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 hover:shadow-md transition-all group">
                            <div class="flex items-start justify-between mb-4 gap-2">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="p-3 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 shrink-0">
                                        <flux:icon.rectangle-group class="w-6 h-6" />
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="font-bold text-zinc-900 dark:text-white truncate"
                                            title="{{ $class->name }}">{{ $class->name }}</h3>
                                        <p class="text-[10px] text-zinc-500 truncate">
                                            {{ $class->major ?? 'Umum' }}
                                        </p>
                                    </div>
                                </div>
                                <x-ui.badge variant="primary" class="shrink-0">
                                    Grade {{ $class->grade_level }}
                                </x-ui.badge>
                            </div>
                            <div class="space-y-3 mb-5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-zinc-500">Ruangan</span>
                                    @if($class->room)
                                        <x-ui.badge variant="neutral" class="px-1.5 py-0">{{ $class->room->name }}</x-ui.badge>
                                    @else
                                        <span class="text-zinc-400">-</span>
                                    @endif
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-zinc-500">Siswa</span>
                                    <span class="text-zinc-700 dark:text-zinc-300 font-medium">0 Siswa</span>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-end gap-2 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                                <x-ui.button.edit :uuid="$class->uuid" />
                                <x-ui.button.delete :uuid="$class->uuid" :name="$class->name" />
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full">
                            <x-ui.empty-state />
                        </div>
                    @endforelse
                </div>
            </x-slot>
        </x-ui.table>
    </x-ui.card>

    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit Class' : 'Create Class'" formId="class-form">
        <form wire:submit="save" id="class-form" novalidate>
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>
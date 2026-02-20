<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Academic</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Rooms</flux:breadcrumbs.item>
</x-slot>

<div>
    <x-ui.card title="Rooms" description="Manage physical spaces for academic activities.">
        <x-slot name="headerAction">
            <x-ui.button.add label="Tambah Ruangan" wire:click="create" />
        </x-slot>

        <x-ui.table :paginator="$rooms" :view="$view">
            <x-slot name="header">
                <x-ui.table.header search="search" :showFilters="false" :showBulk="false" :showColumns="false"
                    :showViewToggle="true" />
            </x-slot>

            <x-ui.table.thead>
                <x-ui.table.th>Name</x-ui.table.th>
                <x-ui.table.th>Capacity</x-ui.table.th>
                <x-ui.table.th>Type</x-ui.table.th>
                <x-ui.table.th shrink></x-ui.table.th>
            </x-ui.table.thead>

            <x-ui.table.tbody>
                @forelse($rooms as $room)
                    <x-ui.table.tr>
                        <x-ui.table.td class="font-bold">{{ $room->name }}</x-ui.table.td>
                        <x-ui.table.td>{{ $room->capacity }}</x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge variant="primary">{{ $room->type }}</x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td shrink>
                            <div class="flex items-center justify-end gap-2">
                                <x-ui.button.edit :uuid="$room->uuid" />
                                <x-ui.button.delete :uuid="$room->uuid" :name="$room->name" />
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
                    @forelse($rooms as $room)
                        <div
                            class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 hover:shadow-md transition-all group">
                            <div class="flex items-start justify-between mb-4 gap-2">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="p-3 rounded-xl bg-teal-50 dark:bg-teal-900/20 text-teal-600 shrink-0">
                                        <flux:icon.building-library class="w-6 h-6" />
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="font-bold text-zinc-900 dark:text-white truncate"
                                            title="{{ $room->name }}">{{ $room->name }}</h3>
                                        <x-ui.badge variant="primary" class="mt-1">
                                            {{ $room->type }}
                                        </x-ui.badge>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-3 mb-5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-zinc-500">Kapasitas</span>
                                    <span class="text-zinc-700 dark:text-zinc-300 font-bold text-base">{{ $room->capacity }}
                                        <span class="text-[10px] font-medium text-zinc-400">Orang</span></span>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-end gap-2 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                                <x-ui.button.edit :uuid="$room->uuid" />
                                <x-ui.button.delete :uuid="$room->uuid" :name="$room->name" />
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

    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit Room' : 'Create Room'" formId="room-form">
        <form wire:submit="save" id="room-form" novalidate>
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>
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
                        <x-ui.table.td colspan="4"><x-ui.empty-state /></x-ui.table.td>
                    </x-ui.table.tr>
                @endforelse
            </x-ui.table.tbody>

        </x-ui.table>
    </x-ui.card>

    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit Room' : 'Create Room'" formId="room-form">
        <form wire:submit="save" id="room-form" novalidate>
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>
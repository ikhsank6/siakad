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
                        <x-ui.table.td colspan="4"><x-ui.empty-state /></x-ui.table.td>
                    </x-ui.table.tr>
                @endforelse
            </x-ui.table.tbody>

        </x-ui.table>
    </x-ui.card>

    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit Class' : 'Create Class'" formId="class-form">
        <form wire:submit="save" id="class-form" novalidate>
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>
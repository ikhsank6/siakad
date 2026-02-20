<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Academic</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Teachers</flux:breadcrumbs.item>
</x-slot>

<div>
    <x-ui.card title="Teachers" description="Browse and manage teacher information and assignments.">
        <x-slot name="headerAction">
            <x-ui.button.add label="Tambah Guru" wire:click="create" />
        </x-slot>

        <x-ui.table :paginator="$teachers" :view="$view">
            <x-slot name="header">
                <x-ui.table.header search="search" :showFilters="false" :showBulk="false" :showColumns="false"
                    :showViewToggle="true" />
            </x-slot>

            <x-ui.table.thead>
                <x-ui.table.th>NIP</x-ui.table.th>
                <x-ui.table.th>Full Name</x-ui.table.th>
                <x-ui.table.th>Phone</x-ui.table.th>
                <x-ui.table.th shrink></x-ui.table.th>
            </x-ui.table.thead>

            <x-ui.table.tbody>
                @forelse($teachers as $teacher)
                    <x-ui.table.tr>
                        <x-ui.table.td>{{ $teacher->nip }}</x-ui.table.td>
                        <x-ui.table.td>
                            <div class="flex items-center gap-3">
                                <x-ui.avatar :name="$teacher->name" size="sm" />
                                <span class="font-bold">{{ $teacher->name }}</span>
                            </div>
                        </x-ui.table.td>
                        <x-ui.table.td>{{ $teacher->phone ?? '-' }}</x-ui.table.td>
                        <x-ui.table.td shrink>
                            <div class="flex items-center justify-end gap-2">
                                <x-ui.button.edit :uuid="$teacher->uuid" />
                                <x-ui.button.delete :uuid="$teacher->uuid" :name="$teacher->name" />
                            </div>
                        </x-ui.table.td>
                    </x-ui.table.tr>
                @empty
                    <x-ui.table.tr>
                        <x-ui.table.td colspan="4"><x-ui.empty-state /></x-ui.table.td>
                    </x-ui.table.tr>
                @endforelse
            </x-ui.table.tbody>

            <x-slot name="footer">
                <x-ui.pagination :paginator="$teachers" />
            </x-slot>
        </x-ui.table>
    </x-ui.card>

    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit Teacher' : 'Create Teacher'" formId="teacher-form">
        <form wire:submit="save" id="teacher-form">
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>
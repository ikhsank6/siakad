<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Academic</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Students</flux:breadcrumbs.item>
</x-slot>

<div>
    <x-ui.card title="Students" description="Manage student registration and class assignments.">
        <x-slot name="headerAction">
            <x-ui.button.add label="Tambah Murid" wire:click="create" />
        </x-slot>

        <x-ui.table :paginator="$students" :view="$view">
            <x-slot name="header">
                <x-ui.table.header search="search" :showFilters="false" :showBulk="false" :showColumns="false"
                    :showViewToggle="true" />
            </x-slot>

            <x-ui.table.thead>
                <x-ui.table.th>NISN</x-ui.table.th>
                <x-ui.table.th>Name</x-ui.table.th>
                <x-ui.table.th>Class</x-ui.table.th>
                <x-ui.table.th shrink></x-ui.table.th>
            </x-ui.table.thead>

            <x-ui.table.tbody>
                @forelse($students as $student)
                    <x-ui.table.tr>
                        <x-ui.table.td>{{ $student->nisn }}</x-ui.table.td>
                        <x-ui.table.td>
                            <div class="flex items-center gap-3">
                                <x-ui.avatar :name="$student->name" size="sm" />
                                <span class="font-bold">{{ $student->name }}</span>
                            </div>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge variant="primary">{{ $student->academicClass->name ?? '-' }}</x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td shrink>
                            <div class="flex items-center justify-end gap-2">
                                <x-ui.button.edit :uuid="$student->uuid" />
                                <x-ui.button.delete :uuid="$student->uuid" :name="$student->name" />
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
                <x-ui.pagination :paginator="$students" />
            </x-slot>
        </x-ui.table>
    </x-ui.card>

    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit Student' : 'Create Student'" formId="student-form">
        <form wire:submit="save" id="student-form" novalidate>
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>
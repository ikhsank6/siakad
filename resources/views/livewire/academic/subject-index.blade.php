<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Academic</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Subjects</flux:breadcrumbs.item>
</x-slot>

<div>
    <x-ui.card title="Subjects" description="Manage academic subjects and their default teaching requirements.">
        <x-slot name="headerAction">
            <x-ui.button.add label="Tambah Mata Pelajaran" wire:click="create" />
        </x-slot>

        <x-ui.table :paginator="$subjects" :view="$view">
            <x-slot name="header">
                <x-ui.table.header search="search" :showFilters="false" :showBulk="false" :showColumns="false"
                    :showViewToggle="true" />
            </x-slot>

            <x-ui.table.thead>
                <x-ui.table.th>Code</x-ui.table.th>
                <x-ui.table.th>Name</x-ui.table.th>
                <x-ui.table.th>Default Hours</x-ui.table.th>
                <x-ui.table.th shrink></x-ui.table.th>
            </x-ui.table.thead>

            <x-ui.table.tbody>
                @forelse($subjects as $subject)
                    <x-ui.table.tr>
                        <x-ui.table.td>{{ $subject->code }}</x-ui.table.td>
                        <x-ui.table.td class="font-bold">{{ $subject->name }}</x-ui.table.td>
                        <x-ui.table.td>{{ $subject->default_hours_per_week }} hrs/week</x-ui.table.td>
                        <x-ui.table.td shrink>
                            <div class="flex items-center justify-end gap-2">
                                <x-ui.button.edit :uuid="$subject->uuid" />
                                <x-ui.button.delete :uuid="$subject->uuid" :name="$subject->name" />
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

    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit Subject' : 'Create Subject'" formId="subject-form">
        <form wire:submit="save" id="subject-form" novalidate>
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>
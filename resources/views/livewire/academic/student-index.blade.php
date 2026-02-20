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
                        <x-ui.table.td colspan="5"><x-ui.empty-state /></x-ui.table.td>
                    </x-ui.table.tr>
                @endforelse
            </x-ui.table.tbody>

            <x-slot name="board">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($students as $student)
                        <div
                            class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 hover:shadow-md transition-all group">
                            <div class="flex items-start justify-between mb-4 gap-2">
                                <div class="flex items-center gap-3 min-w-0">
                                    <x-ui.avatar :name="$student->name" size="lg" class="shrink-0" />
                                    <div class="min-w-0">
                                        <h3 class="font-bold text-zinc-900 dark:text-white truncate"
                                            title="{{ $student->name }}">{{ $student->name }}</h3>
                                        <p class="text-xs text-zinc-500 truncate">
                                            NISN: {{ $student->nisn }}
                                        </p>
                                    </div>
                                </div>
                                <x-ui.badge variant="primary" class="shrink-0">
                                    {{ $student->academicClass->name ?? 'No Class' }}
                                </x-ui.badge>
                            </div>
                            <div class="space-y-3 mb-5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-zinc-500">Gender</span>
                                    <span class="text-zinc-700 dark:text-zinc-300">{{ $student->gender ?? '-' }}</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-zinc-500">Agama</span>
                                    <span class="text-zinc-700 dark:text-zinc-300">{{ $student->religion ?? '-' }}</span>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-end gap-2 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                                <x-ui.button.edit :uuid="$student->uuid" />
                                <x-ui.button.delete :uuid="$student->uuid" :name="$student->name" />
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

    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit Student' : 'Create Student'" formId="student-form">
        <form wire:submit="save" id="student-form" novalidate>
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>
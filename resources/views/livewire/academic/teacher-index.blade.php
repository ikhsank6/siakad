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
                <x-ui.table.th>Subjects</x-ui.table.th>
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
                        <x-ui.table.td>
                            <div class="flex flex-wrap gap-1">
                                @forelse($teacher->subjects->unique('name') as $subject)
                                    <x-ui.badge variant="primary"
                                        class="text-[10px]! px-1.5! py-0.5!">{{ $subject->name }}</x-ui.badge>
                                @empty
                                    <span class="text-xs text-zinc-400">No subjects</span>
                                @endforelse
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
                        <x-ui.table.td colspan="5"><x-ui.empty-state /></x-ui.table.td>
                    </x-ui.table.tr>
                @endforelse
            </x-ui.table.tbody>

            <x-slot name="board">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($teachers as $teacher)
                        <div
                            class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 hover:shadow-md transition-all group">
                            <div class="flex items-start justify-between mb-4 gap-2">
                                <div class="flex items-center gap-3 min-w-0">
                                    <x-ui.avatar :name="$teacher->name" size="lg" class="shrink-0" />
                                    <div class="min-w-0">
                                        <h3 class="font-bold text-zinc-900 dark:text-white truncate"
                                            title="{{ $teacher->name }}">{{ $teacher->name }}</h3>
                                        <p class="text-xs text-zinc-500 truncate">
                                            NIP: {{ $teacher->nip }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-3 mb-5">
                                <div class="flex flex-col gap-1.5">
                                    <span class="text-[10px] uppercase font-bold text-zinc-400 tracking-wider">Mata
                                        Pelajaran</span>
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($teacher->subjects->unique('name') as $subject)
                                            <x-ui.badge variant="neutral"
                                                class="px-1.5! py-0! text-[10px]!">{{ $subject->name }}</x-ui.badge>
                                        @empty
                                            <span class="text-xs text-zinc-400 italic">Belum ada mapel</span>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-zinc-500">Telepon</span>
                                    <span class="text-zinc-700 dark:text-zinc-300">{{ $teacher->phone ?? '-' }}</span>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-end gap-2 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                                <x-ui.button.edit :uuid="$teacher->uuid" />
                                <x-ui.button.delete :uuid="$teacher->uuid" :name="$teacher->name" />
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

    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit Teacher' : 'Create Teacher'" formId="teacher-form">
        <form wire:submit="save" id="teacher-form" novalidate>
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>
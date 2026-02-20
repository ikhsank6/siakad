<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Academic</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Academic Years</flux:breadcrumbs.item>
</x-slot>

<div>
    <x-ui.card title="Academic Years" description="Manage school academic years and semesters.">
        <x-slot name="headerAction">
            <x-ui.button.add label="Tambah Tahun" wire:click="create" />
        </x-slot>

        <x-ui.table :paginator="$academicYears" :view="$view">
            <x-slot name="header">
                <x-ui.table.header search="search" :showFilters="false" :showBulk="false" :showColumns="false"
                    :showViewToggle="true" />
            </x-slot>

            <x-ui.table.thead>
                <x-ui.table.th>Name</x-ui.table.th>
                <x-ui.table.th>Semester</x-ui.table.th>
                <x-ui.table.th>Duration</x-ui.table.th>
                <x-ui.table.th>Status</x-ui.table.th>
                <x-ui.table.th shrink></x-ui.table.th>
            </x-ui.table.thead>

            <x-ui.table.tbody>
                @forelse($academicYears as $year)
                    <x-ui.table.tr>
                        <x-ui.table.td class="font-bold text-zinc-900 dark:text-white">{{ $year->name }}</x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge :variant="$year->semester === 'Ganjil' ? 'primary' : 'info'">
                                {{ $year->semester }}
                            </x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td class="text-xs text-zinc-500">
                            {{ $year->start_date->format('d M Y') }} - {{ $year->end_date->format('d M Y') }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge :variant="$year->is_active ? 'success' : 'neutral'">
                                {{ $year->is_active ? 'Active' : 'Inactive' }}
                            </x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td shrink>
                            <div class="flex items-center justify-end gap-2 text-right">
                                <x-ui.button.edit :uuid="$year->uuid" tooltip="Edit Tahun Akademik" />
                                <x-ui.button.delete :uuid="$year->uuid" :name="$year->name" tooltip="Hapus Tahun Akademik"
                                    :message="'Apakah Anda yakin ingin menghapus tahun akademik ' . $year->name . '?'" />
                            </div>
                        </x-ui.table.td>
                    </x-ui.table.tr>
                @empty
                    <x-ui.table.tr>
                        <x-ui.table.td colspan="5">
                            <x-ui.empty-state />
                        </x-ui.table.td>
                    </x-ui.table.tr>
                @endforelse
            </x-ui.table.tbody>

            <x-slot name="board">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($academicYears as $year)
                        <div
                            class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 hover:shadow-md transition-all group">
                            <div class="flex items-start justify-between mb-4 gap-2">
                                <div class="flex items-center gap-3">
                                    <div class="p-3 rounded-xl bg-zinc-50 dark:bg-zinc-800 text-zinc-600">
                                        <flux:icon.calendar class="w-6 h-6" />
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-zinc-900 dark:text-white">{{ $year->name }}</h3>
                                        <x-ui.badge :variant="$year->semester === 'Ganjil' ? 'primary' : 'info'" class="mt-1">
                                            Semester {{ $year->semester }}
                                        </x-ui.badge>
                                    </div>
                                </div>
                                <x-ui.badge :variant="$year->is_active ? 'success' : 'neutral'">
                                    {{ $year->is_active ? 'Active' : 'Inactive' }}
                                </x-ui.badge>
                            </div>
                            <div class="space-y-3 mb-5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-zinc-500">Mulai</span>
                                    <span
                                        class="font-medium text-zinc-900 dark:text-zinc-300">{{ $year->start_date->format('d M Y') }}</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-zinc-500">Selesai</span>
                                    <span
                                        class="font-medium text-zinc-900 dark:text-zinc-300">{{ $year->end_date->format('d M Y') }}</span>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-end gap-2 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                                <x-ui.button.edit :uuid="$year->uuid" tooltip="Edit Tahun Akademik" />
                                <x-ui.button.delete :uuid="$year->uuid" :name="$year->name" tooltip="Hapus Tahun Akademik"
                                    :message="'Apakah Anda yakin ingin menghapus tahun akademik ' . $year->name . '?'" />
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

    <!-- Edit/Create Modal -->
    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit Tahun Akademik' : 'Tambah Tahun Akademik'"
        formId="academic-year-form">
        <form wire:submit="save" id="academic-year-form" novalidate>
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>
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

            <x-slot name="board">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($subjects as $subject)
                        <div
                            class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 hover:shadow-md transition-all group">
                            <div class="flex items-start justify-between mb-4 gap-2">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="p-3 rounded-xl bg-orange-50 dark:bg-orange-900/20 text-orange-600 shrink-0">
                                        <flux:icon.book-open class="w-6 h-6" />
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="font-bold text-zinc-900 dark:text-white truncate"
                                            title="{{ $subject->name }}">{{ $subject->name }}</h3>
                                        <p class="text-[10px] text-zinc-500 truncate">
                                            Code: {{ $subject->code }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-3 mb-5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-zinc-500">Beban Mengajar</span>
                                    <span
                                        class="text-zinc-700 dark:text-zinc-300 font-medium">{{ $subject->default_hours_per_week }}
                                        Jam/Minggu</span>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-end gap-2 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                                <x-ui.button.edit :uuid="$subject->uuid" />
                                <x-ui.button.delete :uuid="$subject->uuid" :name="$subject->name" />
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

    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit Subject' : 'Create Subject'" formId="subject-form">
        <form wire:submit="save" id="subject-form" novalidate>
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>
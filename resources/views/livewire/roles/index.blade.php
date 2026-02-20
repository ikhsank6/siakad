<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Master Data</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Roles</flux:breadcrumbs.item>
</x-slot>
<div>
    <x-ui.card title="Roles" description="Define and manage user roles and their associated permissions.">

        <x-slot name="headerAction">
            <x-ui.button.add label="Tambah Role" tooltip="Tambah Role Baru" />
        </x-slot>

        <x-ui.table :paginator="$roles" :view="$view">
            <x-slot name="header">
                <x-ui.table.header search="search" :showFilters="false" :showBulk="false" :showColumns="false"
                    :showViewToggle="true" />
            </x-slot>

            <x-ui.table.thead>
                <x-ui.table.th>Name</x-ui.table.th>
                <x-ui.table.th>Slug</x-ui.table.th>
                <x-ui.table.th>Description</x-ui.table.th>
                <x-ui.table.th>Users Count</x-ui.table.th>
                <x-ui.table.th shrink></x-ui.table.th>
            </x-ui.table.thead>

            <x-ui.table.tbody>
                @forelse($roles as $role)
                    <x-ui.table.tr>
                        <x-ui.table.td>
                            <span class="font-bold text-zinc-900 dark:text-white">{{ $role->name }}</span>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <code
                                class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded text-metronic-primary border border-zinc-200 dark:border-zinc-700">{{ $role->slug }}</code>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <span class="text-zinc-500 dark:text-zinc-400">{{ Str::limit($role->description, 50) }}</span>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge variant="info">{{ $role->users_count }} users</x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td shrink>
                            <div class="flex items-center justify-end gap-2 text-right">
                                <x-ui.button.edit :uuid="$role->uuid" tooltip="Edit Role" />
                                <x-ui.button.delete :uuid="$role->uuid" :name="$role->name" tooltip="Hapus Role"
                                    :message="'Apakah Anda yakin ingin menghapus role ' . $role->name . '?'" />
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
                    @forelse($roles as $role)
                        <div
                            class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 hover:shadow-md transition-all group">
                            <div class="flex items-start justify-between mb-4 gap-2">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-metronic-primary/10 flex items-center justify-center text-metronic-primary shrink-0">
                                        <flux:icon name="shield-check" variant="outline" class="w-6 h-6" />
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="font-bold text-zinc-900 dark:text-white truncate"
                                            title="{{ $root->name ?? $role->name }}">{{ $role->name }}</h3>
                                        <code
                                            class="text-[10px] text-metronic-primary font-mono uppercase tracking-wider block truncate">{{ $role->slug }}</code>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <x-ui.badge variant="info" class="whitespace-nowrap">{{ $role->users_count }}
                                        users</x-ui.badge>
                                </div>
                            </div>
                            <p class="text-sm text-zinc-500 line-clamp-2 min-h-[40px] mb-5">
                                {{ $role->description ?: 'No description provided for this role.' }}
                            </p>
                            <div
                                class="flex items-center justify-end gap-2 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                                <x-ui.button.edit :uuid="$role->uuid" tooltip="Edit Role" />
                                <x-ui.button.delete :uuid="$role->uuid" :name="$role->name" tooltip="Hapus Role"
                                    :message="'Apakah Anda yakin ingin menghapus role ' . $role->name . '?'" />
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
    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit Role' : 'Create Role'" formId="role-form">
        <form wire:submit="save" id="role-form" novalidate>
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>
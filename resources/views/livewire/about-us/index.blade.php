<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>CMS</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>About Us</flux:breadcrumbs.item>
</x-slot>

<div>
    <x-ui.card title="About Us" description="Manage company information, contacts, and location.">

        <x-slot name="headerAction">
            @if(!$hasRecord)
                <x-ui.button.add label="Tambah Info" tooltip="Tambah Informasi Perusahaan Baru" />
            @else
                <x-ui.button.edit :uuid="$firstRecord->uuid" label="Edit Info" tooltip="Edit Informasi Perusahaan" />
            @endif
        </x-slot>

        <x-ui.table :paginator="$items" :view="$view">
            <x-slot name="header">
                <x-ui.table.header search="search" :showFilters="false" :showBulk="false" :showColumns="false"
                    :showViewToggle="true" />
            </x-slot>

            <x-ui.table.thead>
                <x-ui.table.th>Logo</x-ui.table.th>
                <x-ui.table.th>Company Name</x-ui.table.th>
                <x-ui.table.th>Contact</x-ui.table.th>
                <x-ui.table.th>Active</x-ui.table.th>
                <x-ui.table.th shrink></x-ui.table.th>
            </x-ui.table.thead>

            <x-ui.table.tbody>
                @forelse($items as $item)
                    <x-ui.table.tr>
                        <x-ui.table.td>
                            @if($item->logo)
                                <img src="{{ Storage::url($item->logo) }}" alt="{{ $item->company_name }}"
                                    class="w-12 h-12 object-contain rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white">
                            @else
                                <div class="w-12 h-12 bg-zinc-200 dark:bg-zinc-800 rounded-lg flex items-center justify-center">
                                    <flux:icon name="building-office-2" class="w-6 h-6 text-zinc-400" />
                                </div>
                            @endif
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <div class="flex flex-col">
                                <span class="font-bold text-zinc-900 dark:text-white">{{ $item->company_name }}</span>
                                <span
                                    class="text-xs text-zinc-500 dark:text-zinc-400">{{ Str::limit($item->address, 50) }}</span>
                            </div>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <div class="flex flex-col gap-1 text-sm">
                                @if($item->phone)
                                    <span class="text-zinc-500 dark:text-zinc-400">📞 {{ $item->phone }}</span>
                                @endif
                                @if($item->email)
                                    <span class="text-zinc-500 dark:text-zinc-400">✉️ {{ $item->email }}</span>
                                @endif
                            </div>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge :variant="$item->is_active ? 'success' : 'danger'">
                                {{ $item->is_active ? 'Yes' : 'No' }}
                            </x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td shrink>
                            <div class="flex items-center justify-end gap-2 text-right">
                                <x-ui.button.edit :uuid="$item->uuid" tooltip="Edit Info" />
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
                    @forelse($items as $item)
                        <div
                            class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 hover:shadow-md transition-all group">
                            <div class="flex items-start justify-between mb-4 gap-2">
                                <div class="flex items-center gap-3 min-w-0">
                                    @if($item->logo)
                                        <img src="{{ Storage::url($item->logo) }}" alt="{{ $item->company_name }}"
                                            class="w-12 h-12 rounded-xl object-contain shrink-0 border border-zinc-200 dark:border-zinc-700 bg-white">
                                    @else
                                        <div
                                            class="w-12 h-12 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center shrink-0">
                                            <flux:icon name="building-office-2" variant="mini" class="w-6 h-6 text-zinc-400" />
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <h3 class="font-bold text-zinc-900 dark:text-white truncate"
                                            title="{{ $item->company_name }}">{{ $item->company_name }}</h3>
                                        <p class="text-xs text-zinc-500 truncate" title="{{ $item->email }}">
                                            {{ $item->email ?: 'No email' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <x-ui.badge :variant="$item->is_active ? 'success' : 'danger'"
                                        class="whitespace-nowrap px-1.5 py-0">
                                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                                    </x-ui.badge>
                                </div>
                            </div>
                            <div class="space-y-3 mb-5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-zinc-500">Phone</span>
                                    <span class="text-zinc-700 dark:text-zinc-300">{{ $item->phone ?: '-' }}</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-zinc-500">Address</span>
                                    <span class="text-zinc-700 dark:text-zinc-300 truncate ml-4"
                                        title="{{ $item->address }}">{{ Str::limit($item->address ?: '-', 30) }}</span>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-end gap-2 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                                <x-ui.button.edit :uuid="$item->uuid" tooltip="Edit Info" />
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
    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit About Us' : 'Create About Us'" formId="about-form"
        maxWidth="4xl">
        <form wire:submit="save" id="about-form" novalidate>
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>
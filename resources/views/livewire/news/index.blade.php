<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>CMS</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>News</flux:breadcrumbs.item>
</x-slot>
<div>
    <x-ui.card title="News" description="Manage news articles and publications.">

        <x-slot name="headerAction">
            <x-ui.button.add label="Tambah Berita" tooltip="Buat Artikel Baru" />
        </x-slot>

        <x-ui.table :paginator="$news" :view="$view">
            <x-slot name="header">
                <x-ui.table.header search="search" :showFilters="false" :showBulk="false" :showColumns="false"
                    :showViewToggle="true" />
            </x-slot>

            <x-ui.table.thead>
                <x-ui.table.th>Image</x-ui.table.th>
                <x-ui.table.th>Title</x-ui.table.th>
                <x-ui.table.th>Category</x-ui.table.th>
                <x-ui.table.th>Published</x-ui.table.th>
                <x-ui.table.th>Active</x-ui.table.th>
                <x-ui.table.th shrink></x-ui.table.th>
            </x-ui.table.thead>

            <x-ui.table.tbody>
                @forelse($news as $item)
                    <x-ui.table.tr>
                        <x-ui.table.td>
                            @if($item->image)
                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}"
                                    class="w-16 h-10 object-cover rounded-lg">
                            @else
                                <div class="w-16 h-10 bg-zinc-100 dark:bg-zinc-800 rounded-lg flex items-center justify-center">
                                    <flux:icon name="photo" class="w-4 h-4 text-zinc-400" />
                                </div>
                            @endif
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <div class="flex flex-col">
                                <span class="font-bold text-zinc-900 dark:text-white line-clamp-1">{{ $item->title }}</span>
                                <span class="text-xs text-zinc-500">{{ Str::limit($item->summary, 40) }}</span>
                            </div>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge variant="neutral">{{ $item->category->name ?? 'Uncategorized' }}</x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <span class="text-zinc-500">{{ $item->published_at?->format('d M Y') ?? '-' }}</span>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge :variant="$item->is_active ? 'success' : 'danger'">
                                {{ $item->is_active ? 'Yes' : 'No' }}
                            </x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td shrink>
                            <div class="flex items-center justify-end gap-2 text-right">
                                <x-ui.button.edit :uuid="$item->uuid" tooltip="Edit News" />
                                <x-ui.button.delete :uuid="$item->uuid" :name="$item->title" tooltip="Delete News"
                                    :message="'Delete news article ' . $item->title . '?'" />
                            </div>
                        </x-ui.table.td>
                    </x-ui.table.tr>
                @empty
                    <x-ui.table.tr>
                        <x-ui.table.td colspan="6">
                            <x-ui.empty-state />
                        </x-ui.table.td>
                    </x-ui.table.tr>
                @endforelse
            </x-ui.table.tbody>

            <x-slot name="board">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($news as $item)
                        <div
                            class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 hover:shadow-md transition-all group">
                            <div class="flex items-start justify-between mb-4 gap-2">
                                <div class="flex items-center gap-3 min-w-0">
                                    @if($item->image)
                                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}"
                                            class="w-12 h-12 rounded-xl object-cover shrink-0 border border-zinc-200 dark:border-zinc-700">
                                    @else
                                        <div
                                            class="w-12 h-12 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center shrink-0">
                                            <flux:icon name="photo" variant="mini" class="w-6 h-6 text-zinc-400" />
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <h3 class="font-bold text-zinc-900 dark:text-white truncate"
                                            title="{{ $item->title }}">{{ $item->title }}</h3>
                                        <p class="text-xs text-zinc-500 truncate"
                                            title="{{ $item->category->name ?? 'General' }}">
                                            {{ $item->category->name ?? 'General' }}
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
                                    <span class="text-zinc-500">Summary</span>
                                    <span class="text-zinc-700 dark:text-zinc-300 truncate ml-4"
                                        title="{{ $item->summary }}">{{ Str::limit($item->summary, 40) }}</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-zinc-500">Published</span>
                                    <span
                                        class="text-zinc-700 dark:text-zinc-300">{{ $item->published_at?->format('M d, Y') ?? 'Draft' }}</span>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-end gap-2 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                                <x-ui.button.edit :uuid="$item->uuid" tooltip="Edit News" />
                                <x-ui.button.delete :uuid="$item->uuid" :name="$item->title" tooltip="Hapus News"
                                    :message="'Delete ' . $item->title . '?'" />
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

    <!-- Modal Form -->
    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit News' : 'Add News'" formId="news-form">
        <form wire:submit="save" id="news-form" novalidate>
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>
<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>CMS</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Carousels</flux:breadcrumbs.item>
</x-slot>
<div>
    <x-ui.card title="Carousels" description="Manage homepage carousel/slider images.">

        <x-slot name="headerAction">
            <x-ui.button.add label="Tambah Carousel" tooltip="Tambah Gambar Baru" />
        </x-slot>

        <x-ui.table :paginator="$carousels" :view="$view">
            <x-slot name="header">
                <x-ui.table.header search="search" :showFilters="false" :showBulk="false" :showColumns="false"
                    :showViewToggle="true" />
            </x-slot>

            <x-slot name="customTable">
                <div class="premium-table-container overflow-x-auto bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-xl"
                    x-data="{
                    dragging: null,
                    dragOver: null,
                    items: @js($carousels->pluck('id')->toArray()),
                    
                    handleDragStart(e, id) {
                        this.dragging = id;
                        e.dataTransfer.effectAllowed = 'move';
                        e.dataTransfer.setData('text/plain', id);
                        e.target.classList.add('opacity-50');
                    },
                    
                    handleDragEnd(e) {
                        e.target.classList.remove('opacity-50');
                        this.dragging = null;
                        this.dragOver = null;
                    },
                    
                    handleDragOver(e, targetId) {
                        e.preventDefault();
                        if (this.dragging == targetId) return;
                        this.dragOver = targetId;
                    },
                    
                    handleDrop(e, targetId) {
                        e.preventDefault();
                        if (this.dragging == targetId) return;
                        
                        const dragIndex = this.items.indexOf(this.dragging);
                        const targetIndex = this.items.indexOf(targetId);
                        
                        if (dragIndex !== -1 && targetIndex !== -1) {
                            this.items.splice(dragIndex, 1);
                            this.items.splice(targetIndex, 0, this.dragging);
                            $wire.updateOrder(this.items);
                        }
                        
                        this.dragging = null;
                        this.dragOver = null;
                    }
                }">

                    {{-- Drag & Drop Instructions --}}
                    <div
                        class="px-4 py-3 bg-zinc-100 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700 text-[10px] font-bold tracking-wider text-zinc-500 dark:text-zinc-400 uppercase flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-metronic-primary" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>
                            <strong>Drag & Drop:</strong>
                            Gunakan icon
                            <flux:icon name="bars-2" variant="mini" class="w-3 h-3 inline-block" /> untuk mengubah
                            urutan gambar
                        </span>
                    </div>

                    <table class="w-full text-left border-separate border-spacing-0">
                        <thead class="bg-zinc-100 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                            <tr>
                                <th
                                    class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-zinc-500 dark:text-zinc-400 uppercase whitespace-nowrap bg-transparent w-10">
                                </th>
                                <th
                                    class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-zinc-500 dark:text-zinc-400 uppercase whitespace-nowrap bg-transparent">
                                    IMAGE</th>
                                <th
                                    class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-zinc-500 dark:text-zinc-400 uppercase whitespace-nowrap bg-transparent">
                                    TITLE</th>
                                <th
                                    class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-zinc-500 dark:text-zinc-400 uppercase whitespace-nowrap bg-transparent">
                                    ORDER</th>
                                <th
                                    class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-zinc-500 dark:text-zinc-400 uppercase whitespace-nowrap bg-transparent">
                                    ACTIVE</th>
                                <th
                                    class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-zinc-500 dark:text-zinc-400 uppercase whitespace-nowrap bg-transparent text-right">
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @forelse($carousels->sortBy('order') as $index => $carousel)
                                <tr draggable="true" x-on:dragstart="handleDragStart($event, {{ $carousel->id }})"
                                    x-on:dragend="handleDragEnd($event)"
                                    x-on:dragover="handleDragOver($event, {{ $carousel->id }})"
                                    x-on:drop="handleDrop($event, {{ $carousel->id }})"
                                    class="bg-white dark:bg-zinc-900 transition-all duration-200 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 font-medium"
                                    :class="{ 
                                                    'opacity-25 scale-[0.98]': dragging == {{ $carousel->id }}, 
                                                    'bg-metronic-primary/5 dark:bg-metronic-primary/10 ring-2 ring-inset ring-metronic-primary/30': dragOver == {{ $carousel->id }} 
                                                }">
                                    <td class="px-4 py-4 text-center">
                                        <div
                                            class="cursor-grab active:cursor-grabbing text-zinc-300 hover:text-metronic-primary transition-colors">
                                            <flux:icon name="bars-2" variant="mini" class="w-5 h-5 mx-auto" />
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($carousel->image)
                                            <img src="{{ Storage::url($carousel->image) }}" alt="{{ $carousel->title }}"
                                                class="w-20 h-12 object-cover rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm">
                                        @else
                                            <div
                                                class="w-20 h-12 bg-zinc-100 dark:bg-zinc-800 rounded-xl flex items-center justify-center border border-dashed border-zinc-300 dark:border-zinc-700">
                                                <flux:icon name="photo" variant="mini" class="w-5 h-5 text-zinc-400" />
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-bold text-zinc-900 dark:text-white">{{ $carousel->title }}</span>
                                            <span
                                                class="text-[10px] text-zinc-500 dark:text-zinc-400">{{ Str::limit($carousel->description, 40) ?: 'No description' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <x-ui.badge variant="neutral" class="font-mono">#{{ $carousel->order }}</x-ui.badge>
                                    </td>
                                    <td class="px-4 py-4">
                                        <x-ui.badge :variant="$carousel->is_active ? 'success' : 'danger'"
                                            class="uppercase text-[9px] tracking-tighter">
                                            {{ $carousel->is_active ? 'YES' : 'NO' }}
                                        </x-ui.badge>
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1">
                                            <x-ui.button.edit :uuid="$carousel->uuid" tooltip="Edit Carousel" />
                                            <x-ui.button.delete :uuid="$carousel->uuid" :name="$carousel->title"
                                                tooltip="Hapus Carousel" :message="'Hapus carousel ' . $carousel->title . '?'" />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <x-ui.empty-state />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-slot>

            <x-slot name="board">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-data="{
                    dragging: null,
                    dragOver: null,
                    items: @js($carousels->pluck('id')->toArray()),
                    
                    handleDragStart(e, id) {
                        this.dragging = id;
                        e.dataTransfer.effectAllowed = 'move';
                        e.target.classList.add('opacity-50', 'scale-95');
                    },
                    
                    handleDragEnd(e) {
                        e.target.classList.remove('opacity-50', 'scale-95');
                        this.dragging = null;
                        this.dragOver = null;
                    },
                    
                    handleDragOver(e, targetId) {
                        e.preventDefault();
                        if (this.dragging == targetId) return;
                        this.dragOver = targetId;
                    },
                    
                    handleDrop(e, targetId) {
                        e.preventDefault();
                        if (this.dragging == targetId) return;
                        
                        const dragIndex = this.items.indexOf(this.dragging);
                        const targetIndex = this.items.indexOf(targetId);
                        
                        if (dragIndex !== -1 && targetIndex !== -1) {
                            this.items.splice(dragIndex, 1);
                            this.items.splice(targetIndex, 0, this.dragging);
                            $wire.updateOrder(this.items);
                        }
                        
                        this.dragging = null;
                        this.dragOver = null;
                    }
                }">
                    @forelse($carousels->sortBy('order') as $carousel)
                        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 hover:shadow-md transition-all group cursor-grab active:cursor-grabbing hover:border-metronic-primary/50"
                            draggable="true" x-on:dragstart="handleDragStart($event, {{ $carousel->id }})"
                            x-on:dragend="handleDragEnd($event)" x-on:dragover="handleDragOver($event, {{ $carousel->id }})"
                            x-on:drop="handleDrop($event, {{ $carousel->id }})"
                            :class="{ 'ring-2 ring-metronic-primary ring-offset-2 dark:ring-offset-zinc-900 border-metronic-primary': dragOver == {{ $carousel->id }} }">
                            <div class="flex items-start justify-between mb-4 gap-2">
                                <div class="flex items-center gap-3 min-w-0 pointer-events-none">
                                    @if($carousel->image)
                                        <img src="{{ Storage::url($carousel->image) }}" alt="{{ $carousel->title }}"
                                            class="w-12 h-12 rounded-xl object-cover shrink-0 border border-zinc-200 dark:border-zinc-700">
                                    @else
                                        <div
                                            class="w-12 h-12 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center shrink-0">
                                            <flux:icon name="photo" variant="mini" class="w-6 h-6 text-zinc-400" />
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <h3 class="font-bold text-zinc-900 dark:text-white truncate"
                                            title="{{ $carousel->title }}">{{ $carousel->title }}</h3>
                                        <p class="text-xs text-zinc-500 truncate" title="{{ $carousel->description }}">
                                            {{ Str::limit($carousel->description, 30) ?: 'No description' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <x-ui.badge variant="info" class="whitespace-nowrap">
                                        #{{ $carousel->order }}
                                    </x-ui.badge>
                                </div>
                            </div>
                            <div class="space-y-3 mb-5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-zinc-500">Status</span>
                                    <x-ui.badge :variant="$carousel->is_active ? 'success' : 'danger'" class="px-1.5 py-0">
                                        {{ $carousel->is_active ? 'Active' : 'Inactive' }}
                                    </x-ui.badge>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-zinc-500">Created</span>
                                    <span
                                        class="text-zinc-700 dark:text-zinc-300">{{ $carousel->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-end gap-2 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                                <x-ui.button.edit :uuid="$carousel->uuid" tooltip="Edit Carousel" />
                                <x-ui.button.delete :uuid="$carousel->uuid" :name="$carousel->title"
                                    tooltip="Hapus Carousel" :message="'Hapus carousel ' . $carousel->title . '?'" />
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full">
                            <x-ui.empty-state />
                        </div>
                    @endforelse
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-ui.pagination :paginator="$carousels" />
            </x-slot>
        </x-ui.table>
    </x-ui.card>

    <!-- Edit/Create Modal -->
    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit Carousel' : 'Create Carousel'" formId="carousel-form"
        maxWidth="3xl">
        <form wire:submit="save" id="carousel-form" novalidate>
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>
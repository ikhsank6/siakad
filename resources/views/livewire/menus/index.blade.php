<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Master Data</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Menus</flux:breadcrumbs.item>
</x-slot>
<div>
    <x-ui.card title="Menus" description="Organize your application navigation and hierarchy. Drag items to reorder.">

        <x-slot name="headerAction">
            <x-ui.button.add label="Tambah Menu" tooltip="Tambah Menu Baru" />
        </x-slot>

        <x-ui.table :view="$view">
            <x-slot name="header">
                <x-ui.table.header search="search" :showFilters="false" :showBulk="false" :showColumns="false"
                    :showPageSize="false" :showViewToggle="true" />
            </x-slot>

            <x-slot name="customTable">
                <div class="premium-table-container overflow-x-auto bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-xl"
                    x-data="{
                        dragging: null,
                        draggingParentId: null,
                        dragOver: null,
                        dropAsChild: null,
                        items: @js($menus->pluck('id')->toArray()),
                        menuParents: @js($menus->pluck('parent_id', 'id')->toArray()),
                        
                        handleDragStart(e, id, parentId) {
                            this.dragging = id;
                            this.draggingParentId = parentId;
                            e.dataTransfer.effectAllowed = 'move';
                            e.dataTransfer.setData('text/plain', id);
                            e.target.classList.add('opacity-50');
                        },
                        
                        handleDragEnd(e) {
                            e.target.classList.remove('opacity-50');
                            this.resetState();
                        },
                        
                        handleDragOver(e, targetId) {
                            e.preventDefault();
                            if (this.dragging == targetId) return;
                            
                            this.dragOver = targetId;
                            
                            const targetParentId = this.menuParents[targetId];
                            const isSibling = targetParentId == this.draggingParentId;
                            
                            // If Alt key is pressed, we want to drop AS CHILD of target
                            // Otherwise, if it's not a sibling and target is a root menu, we assume drop as child
                            if (e.altKey || (targetParentId === null && !isSibling)) {
                                this.dropAsChild = targetId;
                            } else {
                                this.dropAsChild = null;
                            }
                        },
                        
                        handleDragLeave(e) {
                            // Keep state for drop
                        },
                        
                        handleDrop(e, targetId) {
                            e.preventDefault();
                            if (this.dragging == targetId) return;
                            
                            const targetParentId = this.menuParents[targetId];
                            
                            // FORCE Move to parent if Alt key was pressed or we detected dropAsChild
                            if (this.dropAsChild == targetId || e.altKey) {
                                $wire.updateParent(this.dragging, targetId);
                            } else if (targetParentId != this.draggingParentId) {
                                // Move to the same parent as target
                                $wire.updateParent(this.dragging, targetParentId);
                            } else {
                                // Normal reorder among siblings
                                const dragIndex = this.items.indexOf(this.dragging);
                                const targetIndex = this.items.indexOf(targetId);
                                
                                if (dragIndex !== -1 && targetIndex !== -1) {
                                    this.items.splice(dragIndex, 1);
                                    this.items.splice(targetIndex, 0, this.dragging);
                                    $wire.updateOrder(this.items);
                                }
                            }
                            
                            this.resetState();
                        },
                        
                        resetState() {
                            this.dragging = null;
                            this.draggingParentId = null;
                            this.dragOver = null;
                            this.dropAsChild = null;
                        },
                        
                        handleDropToRoot(e) {
                            e.preventDefault();
                            if (!this.dragging) return;
                            $wire.updateParent(this.dragging, null);
                            this.resetState();
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
                            Drag ke grup lain untuk pindah parent •
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
                                    Name</th>
                                <th
                                    class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-zinc-500 dark:text-zinc-400 uppercase whitespace-nowrap bg-transparent">
                                    Slug</th>
                                <th
                                    class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-zinc-500 dark:text-zinc-400 uppercase whitespace-nowrap bg-transparent">
                                    Route</th>
                                <th
                                    class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-zinc-500 dark:text-zinc-400 uppercase whitespace-nowrap bg-transparent">
                                    Parent</th>
                                <th
                                    class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-zinc-500 dark:text-zinc-400 uppercase whitespace-nowrap bg-transparent">
                                    Order</th>
                                <th
                                    class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-zinc-500 dark:text-zinc-400 uppercase whitespace-nowrap bg-transparent">
                                    Status</th>
                                <th
                                    class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-zinc-500 dark:text-zinc-400 uppercase whitespace-nowrap bg-transparent text-right">
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @forelse($menus as $menu)
                                <tr draggable="true"
                                    x-on:dragstart="handleDragStart($event, {{ $menu->id }}, {{ $menu->parent_id ?? 'null' }})"
                                    x-on:dragend="handleDragEnd($event)"
                                    x-on:dragover="handleDragOver($event, {{ $menu->id }})"
                                    x-on:dragleave="handleDragLeave($event)" x-on:drop="handleDrop($event, {{ $menu->id }})"
                                    class="bg-white dark:bg-zinc-900 transition-all duration-200 hover:bg-zinc-50 dark:hover:bg-zinc-800/50"
                                    :class="{ 
                                            'opacity-25 scale-95': dragging === {{ $menu->id }}, 
                                            'bg-metronic-primary/10 dark:bg-metronic-primary/20 ring-2 ring-inset ring-metronic-primary': dragOver === {{ $menu->id }} && dropAsChild !== {{ $menu->id }},
                                            'bg-green-50 dark:bg-green-900/20 ring-2 ring-inset ring-green-500': dropAsChild === {{ $menu->id }}
                                        }">
                                    <td class="px-4 py-4 text-center">
                                        <div
                                            class="cursor-grab active:cursor-grabbing text-zinc-300 hover:text-metronic-primary transition-colors">
                                            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 8h16M4 16h16"></path>
                                            </svg>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 flex items-center justify-center text-zinc-500 dark:text-zinc-400">
                                                @if($menu->icon)
                                                    <flux:icon :name="$menu->icon" variant="mini" class="w-5 h-5" />
                                                @else
                                                    <flux:icon name="folder" variant="mini" class="w-5 h-5" />
                                                @endif
                                            </div>
                                            <div class="flex flex-col">
                                                <span
                                                    class="font-bold text-zinc-900 dark:text-white">{{ $menu->name }}</span>
                                                <span
                                                    class="text-[10px] text-zinc-400 font-medium">{{ $menu->route_name ?: 'No Route Name' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        <code
                                            class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded text-metronic-primary border border-zinc-200 dark:border-zinc-700">{{ $menu->slug }}</code>
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        <span
                                            class="text-zinc-500 dark:text-zinc-400 text-xs">{{ $menu->route ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        <span
                                            class="text-zinc-500 dark:text-zinc-400">{{ $menu->parent->name ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        <x-ui.badge variant="neutral">{{ $menu->order }}</x-ui.badge>
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        <x-ui.badge :variant="$menu->is_active ? 'success' : 'danger'">
                                            {{ $menu->is_active ? 'Active' : 'Inactive' }}
                                        </x-ui.badge>
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2 text-right">
                                            <x-ui.button.edit :uuid="$menu->uuid" tooltip="Edit Data Menu" />
                                            <x-ui.button.delete :uuid="$menu->uuid" :name="$menu->name"
                                                tooltip="Hapus Data Menu" :message="'Apakah Anda yakin ingin menghapus menu ' . $menu->name . '? Tindakan ini juga akan menghapus akses menu ini dari semua role yang memilikinya.'" />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <x-ui.empty-state />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Drop zone to move submenu to root level --}}
                    <div x-show="dragging && draggingParentId !== null" x-on:dragover.prevent="dragOver = 'root'"
                        x-on:dragleave="dragOver = null" x-on:drop="handleDropToRoot($event)"
                        class="mx-4 mb-4 p-4 border-2 border-dashed rounded-xl text-center text-sm font-semibold transition-all"
                        :class="dragOver === 'root' ? 'border-amber-500 bg-amber-50 dark:bg-amber-900/20 text-amber-600' : 'border-zinc-300 dark:border-zinc-600 text-zinc-400'">
                        <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                        Drop here to make it a root menu
                    </div>
                </div>
            </x-slot>

            <x-slot name="board">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-data="{
                        draggingCard: null,
                        dragOverCard: null,
                        rootItems: @js($menus->whereNull('parent_id')->sortBy('order')->pluck('id')->values()->toArray()),
                        
                        // Global state for cross-parent child dragging
                        globalDraggingChild: null,
                        globalDraggingChildParent: null,
                        dropTargetParent: null,
                        
                        handleCardDragStart(e, id) {
                            // Only allow root card drag if not dragging a child
                            if (this.globalDraggingChild) return;
                            this.draggingCard = id;
                            e.dataTransfer.effectAllowed = 'move';
                            e.target.classList.add('opacity-50', 'scale-95');
                        },
                        
                        handleCardDragEnd(e) {
                            e.target.classList.remove('opacity-50', 'scale-95');
                            this.draggingCard = null;
                            this.dragOverCard = null;
                        },
                        
                        handleCardDragOver(e, id) {
                            e.preventDefault();
                            // If dragging a child, show as drop target for parent change
                            if (this.globalDraggingChild && this.globalDraggingChildParent !== id) {
                                this.dropTargetParent = id;
                                return;
                            }
                            // Otherwise, normal root card reorder
                            if (this.draggingCard && this.draggingCard !== id) {
                                this.dragOverCard = id;
                            }
                        },
                        
                        handleCardDragLeave(e) {
                            this.dragOverCard = null;
                            this.dropTargetParent = null;
                        },
                        
                        handleCardDrop(e, targetId) {
                            e.preventDefault();
                            
                            // Check if this is a child being dropped to a new parent
                            if (this.globalDraggingChild && this.globalDraggingChildParent !== targetId) {
                                $wire.updateParent(this.globalDraggingChild, targetId);
                                this.globalDraggingChild = null;
                                this.globalDraggingChildParent = null;
                                this.dropTargetParent = null;
                                return;
                            }
                            
                            // Normal root card reorder
                            if (this.draggingCard === targetId) return;
                            
                            const dragIndex = this.rootItems.indexOf(this.draggingCard);
                            const targetIndex = this.rootItems.indexOf(targetId);
                            
                            if (dragIndex === -1 || targetIndex === -1) return;
                            
                            this.rootItems.splice(dragIndex, 1);
                            this.rootItems.splice(targetIndex, 0, this.draggingCard);
                            
                            $wire.updateOrder(this.rootItems);
                            
                            this.draggingCard = null;
                            this.dragOverCard = null;
                        },
                        
                        // Methods to be called from child components
                        setGlobalDraggingChild(childId, parentId) {
                            this.globalDraggingChild = childId;
                            this.globalDraggingChildParent = parentId;
                        },
                        
                        clearGlobalDraggingChild() {
                            this.globalDraggingChild = null;
                            this.globalDraggingChildParent = null;
                            this.dropTargetParent = null;
                        }
                    }">
                    @php
                        $rootMenus = $menus->whereNull('parent_id')->sortBy('order');
                    @endphp

                    @forelse($rootMenus as $root)
                        <div class="flex flex-col bg-zinc-50 dark:bg-zinc-800/50 rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 cursor-grab active:cursor-grabbing"
                            draggable="true" x-on:dragstart="handleCardDragStart($event, {{ $root->id }})"
                            x-on:dragend="handleCardDragEnd($event)"
                            x-on:dragover="handleCardDragOver($event, {{ $root->id }})"
                            x-on:dragleave="handleCardDragLeave($event)" x-on:drop="handleCardDrop($event, {{ $root->id }})"
                            :class="{ 
                                    'ring-2 ring-metronic-primary ring-offset-2 dark:ring-offset-zinc-900': dragOverCard === {{ $root->id }},
                                    'ring-2 ring-green-500 ring-offset-2 dark:ring-offset-zinc-900 bg-green-50 dark:bg-green-900/20': dropTargetParent === {{ $root->id }}
                                }">
                            <!-- Root Header -->
                            <div
                                class="px-5 py-4 bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-metronic-primary/10 flex items-center justify-center text-metronic-primary">
                                        @if($root->icon)
                                            <flux:icon :name="$root->icon" variant="mini" class="w-5 h-5" />
                                        @else
                                            <flux:icon name="folder" variant="mini" class="w-5 h-5" />
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-zinc-900 dark:text-white">{{ $root->name }}</h3>
                                        <p class="text-[10px] text-zinc-500 font-mono">{{ $root->slug }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <x-ui.button.edit :uuid="$root->uuid" tooltip="Edit Menu" />
                                </div>
                            </div>

                            <!-- Children List with Drag & Drop -->
                            <div class="p-3 flex-1 space-y-2" x-data="{
                                        parentId: {{ $root->id }},
                                        draggingChild: null,
                                        dragOverChild: null,
                                        childItems: @js($menus->where('parent_id', $root->id)->sortBy('order')->pluck('id')->values()->toArray()),

                                        handleChildDragStart(e, id) {
                                            e.stopPropagation();
                                            this.draggingChild = id;
                                            e.dataTransfer.effectAllowed = 'move';
                                            e.dataTransfer.setData('text/plain', id);
                                            e.target.classList.add('opacity-50');
                                            // Notify parent scope about the drag
                                            $dispatch('child-drag-start', { childId: id, parentId: this.parentId });
                                            // Also set in parent scope directly
                                            setGlobalDraggingChild(id, this.parentId);
                                        },

                                        handleChildDragEnd(e) {
                                            e.target.classList.remove('opacity-50');
                                            this.draggingChild = null;
                                            this.dragOverChild = null;
                                            // Clear parent scope
                                            clearGlobalDraggingChild();
                                        },

                                        handleChildDragOver(e, id) {
                                            e.preventDefault();
                                            e.stopPropagation();
                                            if (this.draggingChild && this.draggingChild !== id) {
                                                this.dragOverChild = id;
                                            }
                                        },

                                        handleChildDragLeave(e) {
                                            this.dragOverChild = null;
                                        },

                                        handleChildDrop(e, targetId) {
                                            e.preventDefault();
                                            e.stopPropagation();

                                            // Only handle if dragging within same parent
                                            if (!this.draggingChild || this.draggingChild === targetId) return;

                                            const dragIndex = this.childItems.indexOf(this.draggingChild);
                                            const targetIndex = this.childItems.indexOf(targetId);

                                            if (dragIndex === -1 || targetIndex === -1) return;

                                            this.childItems.splice(dragIndex, 1);
                                            this.childItems.splice(targetIndex, 0, this.draggingChild);

                                            $wire.updateOrder(this.childItems);

                                            this.draggingChild = null;
                                            this.dragOverChild = null;
                                        }
                                    }">
                                @php
                                    $children = $menus->where('parent_id', $root->id)->sortBy('order');
                                @endphp

                                @forelse($children as $child)
                                    <div class="flex items-center justify-between p-3 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 group hover:border-metronic-primary/50 transition-colors shadow-sm cursor-grab active:cursor-grabbing"
                                        draggable="true" x-on:dragstart="handleChildDragStart($event, {{ $child->id }})"
                                        x-on:dragend="handleChildDragEnd($event)"
                                        x-on:dragover="handleChildDragOver($event, {{ $child->id }})"
                                        x-on:dragleave="handleChildDragLeave($event)"
                                        x-on:drop="handleChildDrop($event, {{ $child->id }})"
                                        :class="{ 'ring-2 ring-metronic-primary': dragOverChild === {{ $child->id }} }">
                                        <div class="flex items-center gap-3">
                                            <div class="text-zinc-300 hover:text-zinc-500 cursor-grab">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 8h16M4 16h16"></path>
                                                </svg>
                                            </div>
                                            @if($child->icon)
                                                <flux:icon :name="$child->icon" variant="mini"
                                                    class="w-4 h-4 text-zinc-400 group-hover:text-metronic-primary" />
                                            @else
                                                <div class="w-4 h-4 rounded-full border border-zinc-300 dark:border-zinc-700"></div>
                                            @endif
                                            <div>
                                                <p class="text-xs font-bold text-zinc-700 dark:text-zinc-300">{{ $child->name }}
                                                </p>
                                                <p class="text-[10px] text-zinc-400">{{ $child->route ?: '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <x-ui.badge :variant="$child->is_active ? 'success' : 'danger'"
                                                class="text-[9px] px-1.5 py-0">
                                                {{ $child->is_active ? 'Active' : 'Inactive' }}
                                            </x-ui.badge>
                                            <x-ui.button.edit :uuid="$child->uuid" tooltip="Edit Menu"
                                                class="p-1 text-zinc-300 hover:text-metronic-primary transition-colors opacity-0 group-hover:opacity-100" />
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-zinc-400 text-xs italic"
                                        x-on:dragover.prevent="if (globalDraggingChild && globalDraggingChildParent !== {{ $root->id }}) { dropTargetParent = {{ $root->id }}; }"
                                        x-on:dragleave="dropTargetParent = null"
                                        x-on:drop.prevent="if (globalDraggingChild && globalDraggingChildParent !== {{ $root->id }}) { $wire.updateParent(globalDraggingChild, {{ $root->id }}); clearGlobalDraggingChild(); }"
                                        :class="{ 'bg-green-50 dark:bg-green-900/20 border-2 border-dashed border-green-400 rounded-lg': dropTargetParent === {{ $root->id }} }">
                                        <span x-show="dropTargetParent !== {{ $root->id }}">No children menus.</span>
                                        <span x-show="dropTargetParent === {{ $root->id }}"
                                            class="text-green-600 font-semibold">Drop here to move</span>
                                    </div>
                                @endforelse

                                {{-- Drop zone at the bottom for moving children from other parents --}}
                                <div x-show="globalDraggingChild && globalDraggingChildParent !== {{ $root->id }}"
                                    x-on:dragover.prevent="dropTargetParent = {{ $root->id }}"
                                    x-on:dragleave="dropTargetParent = null"
                                    x-on:drop.prevent="$wire.updateParent(globalDraggingChild, {{ $root->id }}); clearGlobalDraggingChild();"
                                    class="mt-2 p-3 border-2 border-dashed border-green-400 rounded-xl text-center text-xs font-semibold text-green-600 bg-green-50 dark:bg-green-900/20 transition-all"
                                    :class="{ 'bg-green-100 dark:bg-green-900/40': dropTargetParent === {{ $root->id }} }">
                                    <svg class="w-4 h-4 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Drop here to move
                                </div>
                            </div>

                            <!-- Footer Stats -->
                            <div
                                class="px-5 py-3 bg-zinc-100/50 dark:bg-zinc-800/20 border-t border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Status</span>
                                    <x-ui.badge :variant="$root->is_active ? 'success' : 'danger'" class="text-[10px]">
                                        {{ $root->is_active ? 'Active' : 'Inactive' }}
                                    </x-ui.badge>
                                </div>
                                <div class="text-[10px] text-zinc-400">
                                    Order: <span
                                        class="font-bold text-zinc-600 dark:text-zinc-300">{{ $root->order }}</span>
                                </div>
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
    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit Menu' : 'Create Menu'" formId="menu-form">
        <form wire:submit="save" id="menu-form" novalidate>
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>
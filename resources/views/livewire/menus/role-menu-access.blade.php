<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Master Data</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Akses Menu</flux:breadcrumbs.item>
</x-slot>
<div>
    @if($selectedRole)
        <x-ui.card title="Menu Access: {{ $selectedRole->name }}"
            description="Configure which menus this role can access.">

            <x-slot name="headerLeading">
                <button wire:click="backToRoles"
                    class="p-2 text-zinc-400 hover:text-metronic-primary hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </button>
            </x-slot>

            <x-slot name="headerAction">
                <button type="button" wire:click="saveMenuAccess"
                    class="flex items-center gap-2 rounded-lg bg-metronic-success px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:opacity-90 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    Save Changes
                </button>
            </x-slot>

            <div class="space-y-1">
                @forelse($menus as $parentMenu)
                    <div wire:key="menu-{{ $parentMenu->id }}" class="mb-4">
                        <!-- Parent Menu -->
                        <div
                            class="flex items-center gap-3 py-2.5 px-3 rounded-xl bg-zinc-50 dark:bg-zinc-900/50 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors border border-zinc-200 dark:border-zinc-800">
                            <input type="checkbox" wire:model.live="selectedMenus" value="{{ $parentMenu->id }}"
                                id="menu-{{ $parentMenu->id }}"
                                class="w-4 h-4 rounded border-zinc-300 dark:border-zinc-600 text-indigo-600 focus:ring-indigo-500 dark:bg-zinc-950">
                            <label for="menu-{{ $parentMenu->id }}"
                                class="flex items-center gap-3 cursor-pointer flex-1 group/label">
                                @if($parentMenu->icon)
                                    <flux:icon name="{{ $parentMenu->icon }}"
                                        class="w-5 h-5 text-indigo-600 dark:text-indigo-400 group-hover/label:scale-110 transition-transform" />
                                @else
                                    <flux:icon name="folder" class="w-5 h-5 text-zinc-400" />
                                @endif
                                <span
                                    class="font-bold text-zinc-900 dark:text-white transition-colors">{{ $parentMenu->name }}</span>
                                <span
                                    class="text-[10px] text-zinc-500 dark:text-zinc-400 font-mono bg-zinc-200/50 dark:bg-zinc-800 px-2 py-0.5 rounded border border-zinc-200 dark:border-zinc-700 ml-2 uppercase tracking-tight">{{ $parentMenu->slug }}</span>
                            </label>
                        </div>

                        <!-- Child Menus -->
                        @if($parentMenu->children->count() > 0)
                            <div
                                class="mt-1 ml-12 pl-6 border-l-2 border-indigo-100 dark:border-zinc-700 space-y-1 relative">
                                @foreach($parentMenu->children as $childMenu)
                                    <div class="flex items-center gap-3 py-1.5 px-3 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors relative"
                                        wire:key="menu-{{ $childMenu->id }}">
                                        {{-- Horizontal Line Connector --}}
                                        <div class="absolute -left-6 top-1/2 w-6 h-0.5 bg-zinc-200 dark:bg-zinc-800"></div>

                                        <input type="checkbox" wire:model.live="selectedMenus"
                                            value="{{ $childMenu->id }}" id="menu-{{ $childMenu->id }}"
                                            class="w-4 h-4 rounded border-zinc-400 dark:border-zinc-600 text-indigo-600 focus:ring-indigo-500 dark:bg-zinc-950">
                                        <label for="menu-{{ $childMenu->id }}"
                                            class="flex items-center gap-3 cursor-pointer flex-1 group/child">
                                            @if($childMenu->icon)
                                                <flux:icon name="{{ $childMenu->icon }}"
                                                    class="w-4 h-4 text-zinc-500 dark:text-zinc-400 group-hover/child:scale-110 transition-transform" />
                                            @else
                                                <flux:icon name="document" class="w-4 h-4 text-zinc-400" />
                                            @endif
                                            <span
                                                class="text-sm font-medium text-zinc-700 dark:text-zinc-200 group-hover/child:text-indigo-600 dark:group-hover/child:text-indigo-400 transition-colors">{{ $childMenu->name }}</span>
                                            <span
                                                class="text-[10px] text-zinc-500 dark:text-zinc-500 font-mono tracking-widest">{{ $childMenu->slug }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 text-zinc-400">
                        <flux:icon.squares-plus class="w-12 h-12 mb-3 opacity-20" />
                        <p class="text-base font-medium">No menus available</p>
                        <p class="text-sm">Please create menus first in the Menus section.</p>
                    </div>
                @endforelse
            </div>
        </x-ui.card>
    @else
        <x-ui.card title="Menu Access" description="Select a role to configure its menu access permissions.">

            <!-- Search -->
            <div class="mb-6">
                <div class="relative w-full max-w-md">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-zinc-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search roles..."
                        class="block w-full pl-10 pr-3 py-2.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white text-sm rounded-lg focus:ring-2 focus:ring-metronic-primary focus:border-metronic-primary placeholder-zinc-400 dark:placeholder-zinc-500 transition-all">
                </div>
            </div>

            <!-- Role Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse($roles as $role)
                    <button wire:click="selectRole({{ $role->id }})"
                        class="relative flex items-center gap-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 px-5 py-4 text-left transition-all hover:border-metronic-primary hover:shadow-lg hover:shadow-metronic-primary/10 group active:scale-95">

                        <div class="shrink-0 transition-transform group-hover:scale-110">
                            <div
                                class="h-12 w-12 rounded-xl bg-linear-to-br from-metronic-primary to-[#0070f0] flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-metronic-primary/25">
                                {{ strtoupper(substr($role->name, 0, 1)) }}
                            </div>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p
                                class="text-base font-bold text-zinc-900 dark:text-white group-hover:text-metronic-primary transition-colors">
                                {{ $role->name }}
                            </p>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $role->slug }}</p>
                            <div class="mt-1">
                                <x-ui.badge variant="info">{{ $role->users_count }} users</x-ui.badge>
                            </div>
                        </div>

                        <div
                            class="text-zinc-400 group-hover:text-metronic-primary transition-all group-hover:translate-x-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </div>
                    </button>
                @empty
                    <div class="col-span-full">
                        <div class="flex flex-col items-center justify-center py-12 text-zinc-400">
                            <svg class="w-12 h-12 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <p class="text-base font-medium">No roles found</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </x-ui.card>
    @endif
</div>
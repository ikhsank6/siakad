<div x-cloak>
    <!-- Mobile Backdrop -->
    <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-zinc-900/50 backdrop-blur-sm lg:hidden"
        x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="sidebarOpen = false"></div>

    <!-- Sidebar Content -->
    <aside
        class="fixed top-0 left-0 z-50 h-screen transition-all duration-300 bg-white border-r border-zinc-200 dark:bg-zinc-900 dark:border-zinc-700 flex flex-col"
        :class="{
               'translate-x-0': sidebarOpen,
               '-translate-x-full': !sidebarOpen,
               'w-64': !sidebarCollapsed,
               'w-20': sidebarCollapsed,
               'lg:translate-x-0': true
           }">

        <!-- Header/Logo -->
        <div class="flex items-center h-16 px-4 border-b border-zinc-200 dark:border-zinc-700"
            :class="{ 'justify-center': sidebarCollapsed, 'justify-between': !sidebarCollapsed }">

            <div class="flex items-center gap-2 overflow-hidden whitespace-nowrap" x-show="!sidebarCollapsed">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-600 text-white">
                    <span class="font-bold">L</span>
                </div>
                <span class="font-bold text-lg tracking-tight text-zinc-900 dark:text-white">Livewire</span>
            </div>

            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-600 text-white"
                x-show="sidebarCollapsed">
                <span class="font-bold">L</span>
            </div>

            <button @click="sidebarOpen = false"
                class="lg:hidden text-zinc-500 hover:text-zinc-700 dark:text-white dark:hover:text-indigo-400 trasition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto overflow-x-hidden py-4 px-3 space-y-1">
            @foreach($menuTree as $menu)
                @if(empty($menu['children']))
                    <!-- Single Menu Item -->
                    <div x-data="{ 
                        active: '{{ $currentRoute === $menu['route'] }}',
                        tooltip: '{{ $menu['name'] }}'
                    }" class="relative group">

                        <a href="{{ \App\Services\MenuService::safeRoute($menu['route']) }}" wire:navigate
                            class="flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200 group relative"
                            :class="{ 
                                'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-white': active,
                                'text-zinc-700 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800 dark:hover:text-indigo-400': !active,
                                'justify-center': sidebarCollapsed
                            }">

                            <div class="shrink-0">
                                @if($menu['icon'])
                                    @svg('heroicon-o-' . $menu['icon'], 'w-6 h-6')
                                @endif
                            </div>

                            <span class="ml-3 font-medium whitespace-nowrap transition-opacity duration-200"
                                :class="{ 'opacity-0 w-0 hidden': sidebarCollapsed, 'opacity-100': !sidebarCollapsed }">
                                {{ $menu['name'] }}
                            </span>
                        </a>

                        <!-- Tooltip -->
                        <div x-show="sidebarCollapsed"
                            class="absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2 py-1 bg-zinc-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-50">
                            {{ $menu['name'] }}
                        </div>
                    </div>

                @else
                    <!-- Dropdown/Nested Menu -->
                    <?php $hasActiveChild = collect($menu['children'])->contains('route', $currentRoute); ?>
                    <div x-data="{ expanded: {{ $hasActiveChild ? 'true' : 'false' }} }" class="relative group">

                        <button
                            @click="if(sidebarCollapsed) { sidebarCollapsed = false; setTimeout(() => expanded = true, 300); } else { expanded = !expanded }"
                            class="w-full flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200 text-zinc-700 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800 dark:hover:text-indigo-400 group relative"
                            :class="{ 'justify-center': sidebarCollapsed }">

                            <div class="shrink-0">
                                @if($menu['icon'])
                                    @svg('heroicon-o-' . $menu['icon'], 'w-6 h-6')
                                @endif
                            </div>

                            <span class="ml-3 font-medium whitespace-nowrap flex-1 text-left transition-opacity duration-200"
                                :class="{ 'opacity-0 w-0 hidden': sidebarCollapsed, 'opacity-100': !sidebarCollapsed }">
                                {{ $menu['name'] }}
                            </span>

                            <svg class="w-4 h-4 transition-transform duration-200 ml-auto"
                                :class="{ 'rotate-180': expanded, 'opacity-0 w-0 hidden': sidebarCollapsed }" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Tooltip for Parent -->
                        <div x-show="sidebarCollapsed"
                            class="absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2 py-1 bg-zinc-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-50">
                            {{ $menu['name'] }}
                        </div>

                        <!-- Children -->
                        <div x-show="expanded && !sidebarCollapsed" x-collapse class="mt-1 space-y-1 pl-10 pr-2">
                            @foreach($menu['children'] as $child)
                                <?php $isChildActive = $currentRoute === $child['route']; ?>
                                <a href="{{ \App\Services\MenuService::safeRoute($child['route']) }}" wire:navigate
                                    class="flex items-center px-3 py-2 rounded-md text-sm transition-colors duration-200" :class="{ 
                                        'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/40 dark:text-white font-semibold': {{ $isChildActive ? 'true' : 'false' }},
                                        'text-zinc-600 hover:text-zinc-900 dark:text-zinc-100 dark:hover:text-indigo-400': !{{ $isChildActive ? 'true' : 'false' }}
                                    }">

                                    @if($child['icon'])
                                        <div class="mr-2">
                                            @svg('heroicon-o-' . $child['icon'], 'w-4 h-4')
                                        </div>
                                    @endif

                                    <span>{{ $child['name'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </nav>

        <!-- Footer / Profile (Optional bottom section) -->
        <div class="border-t border-zinc-200 dark:border-zinc-700 p-4" x-show="!sidebarCollapsed">
            <!-- Simplified footer if needed -->
        </div>

    </aside>
</div>
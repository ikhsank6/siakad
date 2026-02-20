<div>
    {{-- Custom animation styles --}}
    <style>
        @keyframes bell-ring {
            0% { transform: rotate(0); }
            5% { transform: rotate(15deg); }
            10% { transform: rotate(-14deg); }
            15% { transform: rotate(17deg); }
            20% { transform: rotate(-16deg); }
            25% { transform: rotate(13deg); }
            30% { transform: rotate(-12deg); }
            35% { transform: rotate(8deg); }
            40% { transform: rotate(-6deg); }
            45% { transform: rotate(3deg); }
            50%, 100% { transform: rotate(0); }
        }
        
        @keyframes dot-glow {
            0%, 100% { 
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            }
            50% { 
                box-shadow: 0 0 0 4px rgba(239, 68, 68, 0);
            }
        }
        
        .bell-ring {
            animation: bell-ring 2s ease-in-out infinite;
            transform-origin: 50% 4px;
        }
        
        .dot-glow {
            animation: dot-glow 1.5s ease-in-out infinite;
        }
    </style>

    <flux:dropdown position="bottom" align="end">
        {{-- Notification Bell Trigger --}}
        <button type="button" class="relative inline-flex items-center justify-center p-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 focus:outline-none transition-colors group">
            {{-- Bell Icon Container --}}
            <div class="relative">
                {{-- Bell Icon --}}
                <svg 
                    class="size-5 text-zinc-500 dark:text-zinc-400 group-hover:text-zinc-700 dark:group-hover:text-zinc-200 transition-colors {{ $this->unreadCount > 0 ? 'bell-ring' : '' }}" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke-width="1.5" 
                    stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
                
                {{-- Red Notification Dot - positioned at top right of bell --}}
                @if($this->unreadCount > 0)
                    <span class="absolute -top-1 -right-1 flex items-center justify-center">
                        <span class="absolute inline-flex h-3 w-3 rounded-full bg-red-400 opacity-75 animate-ping"></span>
                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-red-500 dot-glow border border-white dark:border-zinc-900"></span>
                    </span>
                @endif
            </div>
        </button>

        <flux:menu class="w-80!">
            <div class="px-4 py-3 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                <span class="text-sm font-bold text-zinc-800 dark:text-zinc-100">Notifications</span>
                @if($this->unreadCount > 0)
                    <flux:badge size="sm" color="red">{{ $this->unreadCount }} New</flux:badge>
                @endif
            </div>

            <div class="max-h-96 overflow-y-auto">
                @forelse($this->notifications as $notification)
                    <div wire:click="markAsRead('{{ $notification->uuid }}')"
                        class="px-4 py-3 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer border-b border-zinc-100 dark:border-zinc-800 last:border-0">
                        <div class="flex items-start gap-3">
                            <div class="shrink-0 mt-1">
                                @if($notification->read)
                                    <div class="size-2 rounded-full bg-zinc-300 dark:bg-zinc-600"></div>
                                @else
                                    <div class="size-2 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.5)]"></div>
                                @endif
                            </div>

                            <div class="flex flex-col gap-1">
                                <p
                                    class="text-xs text-zinc-600 dark:text-zinc-500 line-clamp-2 {{ $notification->read ? '' : 'font-semibold text-zinc-900! dark:text-white!' }}">
                                    {{ $notification->message }}
                                </p>

                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] text-zinc-400 dark:text-zinc-500">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                    @if($notification->fromRole)
                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-blue-50 dark:bg-blue-400/10 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-400/20">
                                            {{ $notification->fromRole->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-8 text-center bg-zinc-50 dark:bg-zinc-800/50">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-zinc-100 dark:bg-zinc-800 mb-3">
                            <flux:icon.bell-slash class="size-6 text-zinc-400 dark:text-zinc-500" />
                        </div>
                        <p class="text-sm font-medium text-zinc-900 dark:text-white mb-1">No unread notifications</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">You're all caught up! Check back later.</p>
                    </div>
                @endforelse
            </div>

            @if($this->notifications->count() > 0)
                <flux:menu.item href="{{ route('notifications.index') }}"
                    class="justify-center text-xs text-indigo-600 dark:text-indigo-400 font-medium border-t border-zinc-100 dark:border-zinc-800 rounded-none!">
                    View all notifications
                </flux:menu.item>
            @endif
        </flux:menu>
    </flux:dropdown>
</div>
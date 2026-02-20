<div>
    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        <flux:breadcrumbs.item href="{{ route('dashboard') }}" icon="home">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Notifications</flux:breadcrumbs.item>
    </x-slot>

    <div class="max-w-5xl mx-auto">
        {{-- Simple Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-white">Notifications</h1>
                @if($this->unreadCount > 0)
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">{{ $this->unreadCount }} unread</p>
                @endif
            </div>

            <div class="flex items-center gap-2">
                <flux:dropdown>
                    <flux:button variant="ghost"
                        class="h-10! px-3! min-w-[80px] rounded-xl! border border-zinc-200! dark:border-zinc-700! text-zinc-800! dark:text-white! font-bold! bg-white! dark:bg-zinc-900! shadow-sm!"
                        icon="list-bullet" icon-trailing="chevron-down">
                        {{ $perPage }}
                    </flux:button>
                    <flux:menu class="min-w-32">
                        <flux:menu.radio.group wire:model.live="perPage">
                            <flux:menu.radio value="10">10 Rows</flux:menu.radio>
                            <flux:menu.radio value="25">25 Rows</flux:menu.radio>
                            <flux:menu.radio value="50">50 Rows</flux:menu.radio>
                            <flux:menu.radio value="100">100 Rows</flux:menu.radio>
                        </flux:menu.radio.group>
                    </flux:menu>
                </flux:dropdown>

                @if($this->unreadCount > 0)
                    <x-ui.button.ghost wire:click="markAllAsRead" label="Mark all as read" icon="check-badge" />
                @endif
                <x-ui.button.ghost wire:click="deleteAllRead" label="Clear read" icon="trash"
                    wire:confirm="Are you sure you want to delete all read notifications?" />
            </div>
        </div>

        {{-- Filter Tabs --}}
        <div class="mb-6 flex items-center gap-2">
            <button wire:click="$set('filter', 'all')"
                class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-300 {{ $filter === 'all' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30 active:scale-95' : 'bg-white dark:bg-zinc-900 text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white border border-zinc-200 dark:border-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-700' }}">
                All
            </button>

            <button wire:click="$set('filter', 'unread')"
                class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-300 flex items-center gap-2.5 {{ $filter === 'unread' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30 active:scale-95' : 'bg-white dark:bg-zinc-900 text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white border border-zinc-200 dark:border-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-700' }}">
                Unread
                @if($this->unreadCount > 0)
                    <span
                        class="inline-flex items-center justify-center px-1.5 py-0.5 min-w-[20px] rounded-md text-[10px] font-bold bg-red-500 text-white">
                        {{ $this->unreadCount }}
                    </span>
                @endif
            </button>

            <button wire:click="$set('filter', 'read')"
                class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-300 {{ $filter === 'read' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30 active:scale-95' : 'bg-white dark:bg-zinc-900 text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white border border-zinc-200 dark:border-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-700' }}">
                Read
            </button>
        </div>

        {{-- Notification List (Email-like) --}}
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg overflow-hidden">
            @forelse($notifications as $notification)
                <div
                    class="group relative flex items-start gap-4 px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 last:border-0 {{ !$notification->read ? 'bg-indigo-50/30 dark:bg-indigo-950/20' : 'hover:bg-zinc-50 dark:hover:bg-zinc-800/50' }} transition-colors cursor-pointer">
                    {{-- Left Side: Status Indicator --}}
                    <div class="flex items-start pt-1 gap-3">
                        {{-- Unread dot --}}
                        <div
                            class="w-2 h-2 mt-1.5 rounded-full {{ !$notification->read ? 'bg-indigo-600 dark:bg-indigo-500' : 'bg-transparent' }}">
                        </div>

                        {{-- Icon --}}
                        <div class="shrink-0">
                            <div
                                class="w-10 h-10 rounded-full {{ !$notification->read ? 'bg-indigo-100 dark:bg-indigo-900/30' : 'bg-zinc-100 dark:bg-zinc-800' }} flex items-center justify-center">
                                <svg class="w-5 h-5 {{ !$notification->read ? 'text-indigo-600 dark:text-indigo-400' : 'text-zinc-400 dark:text-zinc-500' }}"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Middle: Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-sm {{ !$notification->read ? 'font-semibold text-zinc-900 dark:text-white' : 'text-zinc-700 dark:text-zinc-300' }}">
                                    {{ $notification->message }}
                                </p>

                                <div class="flex items-center gap-3 mt-2">
                                    <span class="text-xs text-zinc-500 dark:text-zinc-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                    @if($notification->fromRole)
                                        <span class="inline-flex items-center gap-1 text-xs text-zinc-500 dark:text-zinc-400">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                                            </svg>
                                            {{ $notification->fromRole->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="shrink-0 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        @if(!$notification->read)
                            <x-ui.button.check wire:click.stop="markAsRead({{ $notification->id }})" tooltip="Mark as read" />
                        @endif

                        @if($notification->url)
                            <x-ui.button.view x-on:click.stop="window.location.href='{{ $notification->url }}'"
                                tooltip="Buka Tautan" />
                        @endif

                        <x-ui.button.delete :uuid="$notification->id" tooltip="Hapus Notifikasi"
                            message="Apakah Anda yakin ingin menghapus notifikasi ini?" />
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="px-6 py-16 text-center">
                    <svg class="w-16 h-16 mx-auto text-zinc-300 dark:text-zinc-600 mb-4" fill="none" viewBox="0 0 24 24"
                        stroke-width="1" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-1">
                        @if($filter === 'unread')
                            No unread notifications
                        @elseif($filter === 'read')
                            No read notifications
                        @else
                            No notifications
                        @endif
                    </h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        @if($filter === 'unread')
                            You're all caught up!
                        @elseif($filter === 'read')
                            You haven't read any notifications yet.
                        @else
                            When you receive notifications, they'll appear here.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            <x-ui.pagination :paginator="$notifications" />
        </div>
    </div>
</div>
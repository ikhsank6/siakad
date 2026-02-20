<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Dashboard</flux:breadcrumbs.item>
</x-slot>
<div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Stats Card 1 -->
        <div
            class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm border border-zinc-200 dark:border-zinc-800 rounded-2xl transition-all hover:shadow-md">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="flex items-center justify-center w-12 h-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400 truncate">
                                Total Users
                            </dt>
                            <dd>
                                <div class="text-lg font-medium text-zinc-900 dark:text-white">
                                    {{ \App\Models\User::count() }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Card 2 -->
        <div
            class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm border border-zinc-200 dark:border-zinc-800 rounded-2xl transition-all hover:shadow-md">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="flex items-center justify-center w-12 h-12 rounded-md bg-emerald-500 text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400 truncate">
                                Active Roles
                            </dt>
                            <dd>
                                <div class="text-lg font-medium text-zinc-900 dark:text-white">
                                    {{ \App\Models\Role::count() }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Welcome Card -->
        <div class="col-span-1 md:col-span-2 lg:col-span-3">
            <div
                class="bg-white dark:bg-zinc-900 shadow-sm border border-zinc-200 dark:border-zinc-800 rounded-2xl p-8">
                <h3 class="text-lg leading-6 font-medium text-zinc-900 dark:text-white">
                    Welcome back, {{ auth()->user()->name }}!
                </h3>
                <div class="mt-2 max-w-xl text-sm text-zinc-500 dark:text-zinc-400">
                    <p>
                        You are logged in as <strong>{{ auth()->user()->role->name }}</strong>.
                        Use the sidebar to navigate to different sections of the application.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
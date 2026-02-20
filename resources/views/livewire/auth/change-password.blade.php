<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Change Password</flux:breadcrumbs.item>
</x-slot>

<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-light text-zinc-900 dark:text-white">Change Password</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400">Update your account password.</p>
    </div>

    <!-- Password Settings Card -->
    <div
        class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Password Settings</h2>
        </div>

        <form wire:submit="changePassword">
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Current Password -->
                <flux:field class="md:col-span-2 max-w-md">
                    <flux:label>Current Password</flux:label>
                    <flux:input wire:model="current_password" type="password" viewable placeholder="••••••••" />
                    <flux:error name="current_password" />
                </flux:field>

                <!-- New Password -->
                <flux:field>
                    <flux:label>New Password</flux:label>
                    <flux:input wire:model.live="password" type="password" viewable placeholder="••••••••" />

                    <flux:error name="password" />

                    <x-password-strength :strength="$this->passwordStrength"
                        :requirements="$this->passwordRequirements" />
                </flux:field>

                <!-- Confirm New Password -->
                <flux:field>
                    <flux:label>Confirm Password</flux:label>
                    <flux:input wire:model="password_confirmation" type="password" viewable placeholder="••••••••" />
                    <flux:error name="password_confirmation" />
                </flux:field>
            </div>

            <!-- Buttons -->
            <div
                class="px-6 py-4 bg-zinc-50 dark:bg-zinc-800/50 flex items-center justify-end gap-x-4 border-t border-zinc-200 dark:border-zinc-800">
                <a href="{{ route('dashboard') }}"
                    class="text-sm font-semibold leading-6 text-zinc-900 dark:text-zinc-100 hover:text-zinc-700 dark:hover:text-zinc-300">
                    Cancel
                </a>
                <flux:button type="submit" variant="primary">
                    <flux:icon.loading wire:loading class="mr-2" />
                    <span wire:loading.remove>Update Password</span>
                    <span wire:loading>Updating...</span>
                </flux:button>
            </div>
        </form>
    </div>
</div>
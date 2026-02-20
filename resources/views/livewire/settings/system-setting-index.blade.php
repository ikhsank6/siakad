<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Settings</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>System</flux:breadcrumbs.item>
</x-slot>

<x-ui.card title="System Settings" description="Configure SEO metadata and analytics for your application.">

    <x-slot name="headerExtras">
        <p class="mt-2 text-xs text-zinc-400 dark:text-zinc-500">
            <strong>Note:</strong> Branding settings (App Name, Logo, Contact Info) are managed in
            <a href="{{ route('cms.about-us.index') }}" class="text-indigo-500 hover:underline">CMS > About Us</a>.
        </p>
    </x-slot>

    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex items-center justify-end gap-3 pt-6 border-t border-zinc-200 dark:border-zinc-800">
            <flux:button type="submit" variant="primary" icon="check-circle" class="px-6">
                Save Settings
            </flux:button>
        </div>
    </form>
</x-ui.card>
<div x-data="{ 
        show: false,
        id: null,
        componentId: null,
        title: 'Konfirmasi Hapus',
        message: 'Apakah Anda yakin ingin menghapus data ini?',
        action: 'delete'
    }" x-on:open-delete-confirm.window="
        id = $event.detail.id;
        componentId = $event.detail.componentId;
        title = $event.detail.title || 'Konfirmasi Hapus';
        message = $event.detail.message || 'Apakah Anda yakin ingin menghapus data ini?';
        action = $event.detail.action || 'delete';
        show = true;
    " x-show="show" class="fixed inset-0 z-60 flex items-center justify-center p-4 sm:p-6" style="display: none;">
    {{-- Backdrop --}}
    <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-zinc-950/60 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

    {{-- Modal Content --}}
    <div x-show="show" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative w-full sm:max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-zinc-900 shadow-2xl transition-all border border-zinc-200 dark:border-zinc-800">
        <div class="p-6">
            <div class="text-center py-4">
                <div
                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/20 mb-6">
                    <flux:icon name="trash" class="h-8 w-8 text-red-600 dark:text-red-400" />
                </div>
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-2" x-text="title"></h3>
                <p class="text-zinc-600 dark:text-zinc-400 text-base" x-text="message"></p>
            </div>
        </div>

        <div
            class="bg-zinc-50 dark:bg-zinc-900/50 px-6 py-4 border-t border-zinc-100 dark:border-zinc-800 flex flex-row gap-3">
            <button type="button" x-on:click="show = false"
                class="flex-1 inline-flex justify-center rounded-lg px-4 py-2.5 bg-zinc-100 dark:bg-zinc-800 text-sm font-bold text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors">
                Batal
            </button>
            <button type="button" x-on:click="Livewire.find(componentId).call(action, id); show = false"
                class="flex-1 inline-flex justify-center items-center rounded-lg px-4 py-2.5 bg-red-600 text-sm font-bold text-white hover:bg-red-700 transition-colors shadow-lg shadow-red-500/30">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>
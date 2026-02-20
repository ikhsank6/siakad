{{-- Toast Notification System - Modern Monochromatic Light Design --}}
<div wire:ignore class="fixed top-5 right-5 pointer-events-none" style="z-index: 999999;">
    <div x-data="{ 
        toasts: [],
        add(data) {
            const payload = data.detail || data;
            const text = typeof payload === 'string' ? payload : (payload.text || '');
            const variant = payload.variant || 'success';
            const title = payload.title || null;
            
            if (!text) return;
            
            const id = Date.now() + Math.random();
            this.toasts.push({ id, text, variant, title });
            
            setTimeout(() => {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }, 5000);
        }
    }" x-init="
        @if(session('success')) add({ text: '{{ session('success') }}', variant: 'success' }); @endif
        @if(session('error')) add({ text: '{{ session('error') }}', variant: 'danger' }); @endif
        @if(session('warning')) add({ text: '{{ session('warning') }}', variant: 'warning' }); @endif
        @if(session('info')) add({ text: '{{ session('info') }}', variant: 'info' }); @endif
    " @notify.window="add($event.detail)" class="flex flex-col gap-3 items-end">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="translate-x-8 opacity-0 scale-95"
                x-transition:enter-end="translate-x-0 opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                x-transition:leave-end="opacity-0 translate-x-8 scale-95"
                class="pointer-events-auto w-[400px] rounded-2xl overflow-hidden toast-container" :class="{
                    'toast-success': toast.variant === 'success',
                    'toast-danger': toast.variant === 'danger',
                    'toast-warning': toast.variant === 'warning',
                    'toast-info': toast.variant === 'info'
                }">

                {{-- Main Content --}}
                <div class="p-5">
                    {{-- Header with Icon and Close Button --}}
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex items-center gap-3">
                            {{-- Success Icon --}}
                            <template x-if="toast.variant === 'success'">
                                <div class="shrink-0 toast-icon-wrapper">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </template>

                            {{-- Danger Icon --}}
                            <template x-if="toast.variant === 'danger'">
                                <div class="shrink-0 toast-icon-wrapper">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </template>

                            {{-- Warning Icon --}}
                            <template x-if="toast.variant === 'warning'">
                                <div class="shrink-0 toast-icon-wrapper">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                </div>
                            </template>

                            {{-- Info Icon --}}
                            <template x-if="toast.variant === 'info'">
                                <div class="shrink-0 toast-icon-wrapper">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </template>

                            {{-- Title --}}
                            <h4 class="font-bold text-base toast-title"
                                x-text="toast.title || (toast.variant === 'success' ? 'Success!' : (toast.variant === 'danger' ? 'Whoops! Something went wrong' : (toast.variant === 'warning' ? 'Warning' : 'Information')))">
                            </h4>
                        </div>

                        {{-- Close Button --}}
                        <button @click="toasts = toasts.filter(t => t.id !== toast.id)"
                            class="shrink-0 w-6 h-6 rounded-lg flex items-center justify-center toast-close-btn transition-all duration-200 focus:outline-none hover:opacity-70">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Message Text --}}
                    <p class="text-sm toast-message leading-relaxed pl-8" x-text="toast.text"></p>
                </div>

                {{-- Progress Bar --}}
                <div class="h-1 w-full toast-progress-bg">
                    <div class="h-full animate-shrink-width toast-progress-bar" style="animation-duration: 5s;"></div>
                </div>
            </div>
        </template>
    </div>
</div>
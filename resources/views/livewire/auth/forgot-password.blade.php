<div>
    {{-- Header --}}
    <div class="mb-8 text-center">
        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-800 dark:text-white">
            Reset your password
        </h2>
        <p class="mt-3 text-sm sm:text-base text-slate-600 dark:text-slate-400 leading-relaxed max-w-sm mx-auto">
            Enter your email address and we'll send you a link to reset your password.
        </p>
    </div>

    {{-- Success Message --}}
    @if (session('status'))
        <div class="mb-6 rounded-xl bg-green-50 dark:bg-green-900/20 p-4 border border-green-200 dark:border-green-700/30">
            <div class="flex items-start gap-3">
                <div class="shrink-0 mt-0.5">
                    <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 6.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l7-7a1 1 0 000-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-green-700 dark:text-green-300">
                    {{ session('status') }}
                </p>
            </div>
        </div>
    @endif

    <form wire:submit="sendResetLink" class="space-y-6" novalidate>
        {{-- Email --}}
        <flux:field>
            <flux:label>Email address</flux:label>
            <flux:input wire:model="email" type="email" autocomplete="email" placeholder="Enter your email address" />
            <flux:error name="email" />
        </flux:field>

        {{-- Submit Button --}}
        <div class="pt-2">
            <flux:button type="submit" class="auth-btn-primary w-full">
                <span wire:loading.remove class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Send Reset Link
                </span>
                <span wire:loading>Sending...</span>
            </flux:button>
        </div>

        {{-- Back to Login --}}
        <div class="text-center pt-2">
            <a href="{{ route('auth.login') }}" wire:navigate
                class="inline-flex items-center gap-2 text-sm font-medium text-orange-500 hover:text-orange-600 dark:text-orange-400 dark:hover:text-orange-300 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to login
            </a>
        </div>
    </form>

    {{-- Help Text --}}
    <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700/30">
        <p class="text-center text-xs text-slate-500 dark:text-slate-500 leading-relaxed">
            Remember your password?
            <a href="{{ route('auth.login') }}" wire:navigate
                class="text-orange-500 hover:text-orange-600 dark:text-orange-400 transition-colors font-medium">
                Sign in here
            </a>
        </p>
    </div>
</div>
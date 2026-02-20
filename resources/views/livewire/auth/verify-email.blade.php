<div>
    <div class="mb-8 text-center">
        @if($verified)
            {{-- Successfully Verified --}}
            <div
                class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/30 mb-4">
                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">
                Email Verified!
            </h2>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                Your email has been successfully verified. You can now log in to your account.
            </p>
            <div class="mt-6">
                <flux:button href="{{ route('auth.login') }}" wire:navigate class="auth-btn-primary px-8">
                    Go to Login
                </flux:button>
            </div>
        @elseif($alreadyVerified)
            {{-- Already Verified --}}
            <div
                class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900/30 mb-4">
                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">
                Already Verified
            </h2>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                Your email address has already been verified. You can proceed to login.
            </p>
            <div class="mt-6">
                <flux:button href="{{ route('auth.login') }}" wire:navigate class="auth-btn-primary px-8">
                    Go to Login
                </flux:button>
            </div>
        @elseif($invalidLink)
            {{-- Invalid Link --}}
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 mb-4">
                <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">
                Invalid Verification Link
            </h2>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                The verification link is invalid or has expired. Please try registering again.
            </p>
            <div class="mt-6">
                <flux:button href="{{ route('auth.register') }}" wire:navigate class="auth-btn-primary px-8">
                    Register Again
                </flux:button>
            </div>
        @else
            {{-- Loading / Processing --}}
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-zinc-100 dark:bg-zinc-800 mb-4">
                <svg class="w-8 h-8 text-zinc-400 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">
                Verifying...
            </h2>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                Please wait while we verify your email address.
            </p>
        @endif
    </div>
</div>
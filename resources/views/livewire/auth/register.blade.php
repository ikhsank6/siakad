<div>
    @if($registered)
        {{-- Registration Success - Show verification message --}}
        <div class="text-center py-4">
            {{-- Success Icon --}}
            <div class="relative inline-flex mb-6">
                <div class="absolute inset-0 bg-green-500/20 rounded-full blur-xl"></div>
                <div
                    class="relative inline-flex items-center justify-center w-20 h-20 rounded-full bg-linear-to-br from-green-400 to-green-600 shadow-lg shadow-green-500/30">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
            </div>

            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-800 dark:text-white mb-3">
                Check Your Email
            </h2>
            <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 max-w-sm mx-auto leading-relaxed">
                We've sent a verification link to
                <span class="font-semibold text-slate-800 dark:text-white">{{ $email }}</span>.
                Click the link in the email to activate your account.
            </p>

            {{-- Warning Box --}}
            <div
                class="mt-8 p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-700/30">
                <p class="text-sm text-amber-700 dark:text-amber-300">
                    <strong>Didn't receive the email?</strong> Check your spam folder or wait a few minutes.
                </p>
            </div>

            {{-- Go to Login Button --}}
            <div class="mt-8">
                <a href="{{ route('auth.login') }}" wire:navigate
                    class="auth-btn-primary inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl font-semibold text-white transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Go to Login
                </a>
            </div>
        </div>
    @else
        {{-- Registration Form --}}
        <div class="mb-8 text-center">
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-800 dark:text-white">
                Create a new account
            </h2>
            <p class="mt-3 text-sm sm:text-base text-slate-600 dark:text-slate-400">
                Or
                <a href="{{ route('auth.login') }}" wire:navigate
                    class="font-semibold text-orange-500 hover:text-orange-600 dark:text-orange-400 dark:hover:text-orange-300 transition-colors duration-200">
                    sign in to your existing account
                </a>
            </p>
        </div>

        <form wire:submit="register" class="space-y-5" novalidate>
            {{-- Full Name --}}
            <flux:field>
                <flux:label>Full Name</flux:label>
                <flux:input wire:model="name" type="text" autocomplete="name" placeholder="Enter your full name" />
                <flux:error name="name" />
            </flux:field>

            {{-- Email --}}
            <flux:field>
                <flux:label>Email address</flux:label>
                <flux:input wire:model="email" type="email" autocomplete="email" placeholder="Enter your email" />
                <flux:error name="email" />
            </flux:field>

            {{-- Password --}}
            <flux:field>
                <flux:label>Password</flux:label>
                <flux:input wire:model.live="password" type="password" viewable autocomplete="new-password"
                    placeholder="Create a password" />

                <flux:error name="password" />

                <x-password-strength :strength="$this->passwordStrength" :requirements="$this->passwordRequirements" />
            </flux:field>

            {{-- Confirm Password --}}
            <flux:field>
                <flux:label>Confirm Password</flux:label>
                <flux:input wire:model="password_confirmation" type="password" viewable autocomplete="new-password"
                    placeholder="Confirm your password" />
                <flux:error name="password_confirmation" />
            </flux:field>

            {{-- Submit Button --}}
            <div class="pt-3">
                <flux:button type="submit" class="auth-btn-primary w-full">
                    <span wire:loading.remove>Create Account</span>
                    <span wire:loading>Creating account...</span>
                </flux:button>
            </div>
        </form>

        {{-- Terms Notice --}}
        <p class="mt-6 text-center text-xs text-slate-500 dark:text-slate-500 leading-relaxed">
            By creating an account, you agree to our
            <a href="#" class="text-orange-500 hover:text-orange-600 dark:text-orange-400 transition-colors">Terms of
                Service</a>
            and
            <a href="#" class="text-orange-500 hover:text-orange-600 dark:text-orange-400 transition-colors">Privacy
                Policy</a>
        </p>
    @endif
</div>
{{-- Navigation with Glassmorphism & Modern Effects --}}
<nav x-data="{ mobileMenuOpen: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 50)"
    class="z-50 transition-all duration-500" :class="{ 
         'fixed top-0 left-0 right-0': scrolled, 
         'absolute top-0 left-0 right-0': !scrolled 
     }">

    {{-- Glass Container --}}
    <div class="transition-all duration-500" :class="{
             'mx-4 mt-4 rounded-2xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl border border-white/20 dark:border-white/10 shadow-xl shadow-black/5 dark:shadow-black/20': scrolled,
             'bg-transparent': !scrolled
         }">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center h-16" :class="{ 'h-16': scrolled, 'h-20': !scrolled }">
                <a href="/" class="group">
                    @include('website.partials.logo', [
                        'logoClass' => 'h-9 transition-all duration-300 group-hover:scale-105',
                        'textClass' => 'font-bold text-lg transition-all duration-300',
                        'alpineTextClass' => "{ 'text-slate-900 dark:text-white': scrolled, 'text-white drop-shadow-lg': !scrolled }"
                    ])
                </a>

                {{-- Desktop Navigation --}}
                <div class="hidden lg:flex items-center gap-2">
                    {{-- Nav Links Container --}}
                    <div class="flex items-center p-1 rounded-xl transition-all duration-300"
                        :class="{ 'bg-slate-100/80 dark:bg-white/5': scrolled, 'bg-white/10 backdrop-blur-sm': !scrolled }">
                        @php
                            $navItems = [
                                ['url' => '/', 'label' => 'Home', 'active' => request()->is('/')],
                                ['url' => route('news.index'), 'label' => 'News', 'active' => request()->is('news*')],
                                ['url' => route('about'), 'label' => 'About', 'active' => request()->is('about*')],
                                ['url' => route('about') . '#contact', 'label' => 'Contact', 'active' => false],
                            ];
                        @endphp

                        @foreach($navItems as $item)
                            <a href="{{ $item['url'] }}"
                                class="relative px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300 overflow-hidden group">

                                {{-- Background for active/hover --}}
                                @if($item['active'])
                                    <span class="absolute inset-0 rounded-lg transition-all duration-300"
                                        :class="{ 'bg-white dark:bg-slate-800 shadow-sm': scrolled, 'bg-white/20': !scrolled }"></span>
                                @else
                                    <span
                                        class="absolute inset-0 rounded-lg bg-transparent group-hover:bg-white dark:group-hover:bg-slate-800 group-hover:shadow-sm transition-all duration-300 opacity-0 group-hover:opacity-100"
                                        :class="{ '': scrolled, 'group-hover:bg-white/10 group-hover:shadow-none': !scrolled }"></span>
                                @endif

                                {{-- Text --}}
                                <span class="relative z-10 transition-colors duration-300" :class="{ 
                                              '{{ $item['active'] ? 'text-primary dark:text-primary font-semibold' : 'text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white' }}': scrolled,
                                              '{{ $item['active'] ? 'text-white font-semibold' : 'text-white/80 group-hover:text-white' }}': !scrolled
                                          }">
                                    {{ $item['label'] }}
                                </span>

                                {{-- Active indicator dot --}}
                                @if($item['active'])
                                    <span
                                        class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1.5 h-1.5 rounded-full transition-all duration-300"
                                        :class="{ 'bg-primary': scrolled, 'bg-white': !scrolled }"></span>
                                @endif
                            </a>
                        @endforeach
                    </div>

                    {{-- Divider --}}
                    <div class="w-px h-8 mx-3 transition-colors duration-300"
                        :class="{ 'bg-slate-200 dark:bg-slate-700': scrolled, 'bg-white/20': !scrolled }"></div>

                    {{-- Auth Buttons --}}
                    <div class="flex items-center gap-2">
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="group relative px-6 py-2.5 text-sm font-bold text-white bg-orange-500 rounded-xl transition-all duration-300 transform hover:scale-[1.05] active:scale-[0.98] shadow-lg shadow-orange-500/25 hover:shadow-orange-500/40">
                                <span class="relative z-10 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                    Dashboard
                                </span>
                            </a>
                        @else
                            <a href="{{ route('auth.login') }}"
                                class="group relative px-6 py-2.5 text-sm font-bold text-white bg-orange-500 rounded-xl transition-all duration-300 transform hover:scale-[1.05] active:scale-[0.98] shadow-lg shadow-orange-500/25 hover:shadow-orange-500/40">
                                <span class="relative z-10 flex items-center gap-2">
                                    Sign In
                                    <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </span>
                            </a>
                        @endauth
                    </div>

                    {{-- Dark Mode Toggle --}}
                    <button @click="theme = isDark ? 'light' : 'dark'"
                        class="ml-2 p-2.5 rounded-xl transition-all duration-300 hover:scale-110 active:scale-95"
                        :class="{ 
                                'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-700 dark:hover:text-white': scrolled, 
                                'text-white/70 hover:text-white hover:bg-white/10': !scrolled 
                            }">
                        <svg x-show="isDark" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>

                {{-- Mobile: Theme Toggle & Menu Button --}}
                <div class="flex items-center gap-2 lg:hidden">
                    <button @click="theme = isDark ? 'light' : 'dark'" class="p-2 rounded-xl transition-all duration-300" :class="{ 
                                'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-white/10': scrolled, 
                                'text-white/80 hover:bg-white/10': !scrolled 
                            }">
                        <svg x-show="isDark" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-xl transition-all duration-300"
                        :class="{ 
                                'hover:bg-slate-100 dark:hover:bg-white/10': scrolled, 
                                'hover:bg-white/10': !scrolled 
                            }">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6 transition-colors"
                            :class="{ 'text-slate-600 dark:text-slate-300': scrolled, 'text-white': !scrolled }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6 transition-colors"
                            :class="{ 'text-slate-600 dark:text-slate-300': scrolled, 'text-white': !scrolled }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile Navigation Menu --}}
    <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
        class="lg:hidden mx-4 mt-2 py-4 bg-white/80 dark:bg-slate-900/80 backdrop-blur-2xl rounded-2xl shadow-2xl border border-white/20 dark:border-white/10">
        <div class="flex flex-col gap-1 px-3">
            @php
                $navItems = [
                    ['url' => '/', 'label' => 'Home', 'active' => request()->is('/')],
                    ['url' => route('news.index'), 'label' => 'News', 'active' => request()->is('news*')],
                    ['url' => route('about'), 'label' => 'About', 'active' => request()->is('about*')],
                    ['url' => route('about') . '#contact', 'label' => 'Contact', 'active' => false],
                ];
            @endphp

            @foreach($navItems as $item)
                <a href="{{ $item['url'] }}" @click="mobileMenuOpen = false"
                    class="relative px-4 py-3 rounded-xl font-medium transition-all duration-300 {{ $item['active'] ? 'text-primary bg-primary/10 dark:bg-primary/20' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-white/5' }}">
                    {{ $item['label'] }}
                    @if($item['active'])
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-primary rounded-full"></span>
                    @endif
                </a>
            @endforeach

            <div class="border-t border-slate-200/50 dark:border-white/10 mt-3 pt-4 px-1 space-y-2">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center justify-center gap-2 w-full px-4 py-3 text-sm font-semibold text-white bg-linear-to-r from-primary to-teal-600 rounded-xl shadow-md shadow-primary/25">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('auth.login') }}"
                        class="block w-full text-center px-4 py-3 text-sm font-medium text-slate-600 dark:text-slate-300 bg-slate-100/80 dark:bg-white/5 rounded-xl transition-colors hover:bg-slate-200/80 dark:hover:bg-white/10">
                        Sign In
                    </a>
                    <a href="{{ route('auth.register') }}"
                        class="flex items-center justify-center gap-2 w-full px-4 py-3 text-sm font-bold text-white bg-orange-500 rounded-xl shadow-lg shadow-orange-500/25 active:scale-[0.98] transition-all">
                        Get started now
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
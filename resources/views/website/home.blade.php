@extends('layouts.website')

@section('title', ($aboutUs->company_name ?? config('app.name')) . ' - Home')

@section('swiper', true)

@section('content')
    {{-- ==================== HERO CAROUSEL ==================== --}}
    @if($carousels->count() > 0)
        <section class="relative h-screen overflow-hidden">
            <div class="swiper heroCarousel h-full">
                <div class="swiper-wrapper">
                    @foreach($carousels as $index => $carousel)
                        <div class="swiper-slide">
                            {{-- Background with Parallax Effect --}}
                            <div class="absolute inset-0 z-0">
                                <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950"></div>
                                <div class="absolute inset-0 transition-transform duration-[2000ms] scale-105"
                                    style="background-image: url('{{ $carousel->image ? Storage::url($carousel->image) : 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1920' }}'); background-size: cover; background-position: center;">
                                </div>
                                {{-- Gradient Overlay --}}
                                <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/80 to-transparent"></div>
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/50 via-transparent to-slate-950/30">
                                </div>
                                {{-- Animated Orbs --}}
                                <div
                                    class="absolute top-1/4 right-1/4 w-96 h-96 bg-primary/20 rounded-full blur-[120px] animate-pulse">
                                </div>
                                <div
                                    class="absolute bottom-1/4 left-1/4 w-80 h-80 bg-accent/10 rounded-full blur-[100px] animate-pulse delay-1000">
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="relative z-10 h-screen flex items-center">
                                <div class="max-w-7xl mx-auto px-4 sm:px-6 w-full">
                                    <div class="max-w-3xl">
                                        {{-- Badge/Description --}}
                                        @if($carousel->description)
                                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm border border-white/10 rounded-full mb-6 animate-fade-in-up"
                                                style="animation-delay: 0.2s;">
                                                <span class="w-2 h-2 bg-primary rounded-full animate-pulse"></span>
                                                <span
                                                    class="text-white/90 text-sm font-medium tracking-wide">{{ $carousel->description }}</span>
                                            </div>
                                        @endif

                                        {{-- Title with Gradient --}}
                                        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-black leading-[0.9] mb-8 animate-fade-in-up"
                                            style="animation-delay: 0.4s;">
                                            <span class="block text-white">{{ Str::before($carousel->title, ' ') }}</span>
                                            <span
                                                class="block bg-gradient-to-r from-primary via-teal-400 to-accent bg-clip-text text-transparent">
                                                {{ Str::after($carousel->title, ' ') ?: $carousel->title }}
                                            </span>
                                        </h1>

                                        {{-- Subtitle Line --}}
                                        <div class="flex items-center gap-4 mb-10 animate-fade-in-up"
                                            style="animation-delay: 0.6s;">
                                            <div class="h-px flex-1 max-w-[100px] bg-gradient-to-r from-primary to-transparent">
                                            </div>
                                            <p class="text-white/60 text-lg font-light">Discover the possibilities</p>
                                        </div>

                                        {{-- CTA Buttons --}}
                                        @if($carousel->button_text)
                                            <div class="flex flex-wrap items-center gap-4 animate-fade-in-up"
                                                style="animation-delay: 0.8s;">
                                                {{-- Primary Button --}}
                                                <a href="{{ $carousel->button_link ?? '#' }}"
                                                    class="group relative inline-flex items-center gap-3 px-8 py-4 text-lg font-bold text-white overflow-hidden rounded-2xl transition-all duration-500 hover:scale-105 active:scale-95">
                                                    {{-- Button Gradient Background --}}
                                                    <span
                                                        class="absolute inset-0 bg-gradient-to-r from-accent via-orange-500 to-accent bg-[length:200%_100%] animate-gradient-x"></span>
                                                    {{-- Glow Effect --}}
                                                    <span
                                                        class="absolute inset-0 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 blur-xl bg-gradient-to-r from-accent to-orange-500"></span>
                                                    {{-- Button Content --}}
                                                    <span class="relative z-10">{{ $carousel->button_text }}</span>
                                                    <svg class="relative z-10 w-5 h-5 transition-transform duration-300 group-hover:translate-x-1"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                                    </svg>
                                                </a>

                                                {{-- Secondary Button --}}
                                                <a href="#features"
                                                    class="group inline-flex items-center gap-3 px-6 py-4 text-white/80 font-medium border border-white/20 rounded-2xl backdrop-blur-sm hover:bg-white/10 hover:border-white/30 transition-all duration-300">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>Watch Demo</span>
                                                </a>
                                            </div>
                                        @endif

                                        {{-- Stats Row --}}
                                        <div class="flex items-center gap-8 mt-16 pt-8 border-t border-white/10 animate-fade-in-up"
                                            style="animation-delay: 1s;">
                                            <div>
                                                <div class="text-3xl font-bold text-white">10K+</div>
                                                <div class="text-sm text-white/50">Active Users</div>
                                            </div>
                                            <div class="w-px h-12 bg-white/20"></div>
                                            <div>
                                                <div class="text-3xl font-bold text-white">99%</div>
                                                <div class="text-sm text-white/50">Satisfaction</div>
                                            </div>
                                            <div class="w-px h-12 bg-white/20"></div>
                                            <div>
                                                <div class="text-3xl font-bold text-white">24/7</div>
                                                <div class="text-sm text-white/50">Support</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Navigation Arrows (Styled via CSS) --}}
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>

                {{-- Pagination (Styled via CSS) --}}
                <div class="swiper-pagination"></div>
            </div>

            {{-- Scroll Indicator - Enhanced Design --}}
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center gap-3 group cursor-pointer"
                onclick="window.scrollBy({top: window.innerHeight, behavior: 'smooth'})">
                {{-- Glow Effect --}}
                <div class="absolute inset-0 bg-primary/20 blur-2xl rounded-full scale-150 animate-pulse"></div>

                {{-- Text with gradient --}}
                <span
                    class="relative text-xs font-semibold tracking-[0.3em] uppercase text-white/80 group-hover:text-primary transition-colors duration-300">
                    Scroll Down
                </span>

                {{-- Animated Mouse Icon --}}
                <div
                    class="relative w-7 h-12 rounded-full border-2 border-white/40 group-hover:border-primary/80 transition-all duration-300 flex justify-center overflow-hidden shadow-lg shadow-black/20">
                    {{-- Inner glow --}}
                    <div class="absolute inset-0 bg-gradient-to-b from-white/10 to-transparent"></div>
                    {{-- Scroll wheel/dot --}}
                    <div class="w-1.5 h-3 bg-white/80 group-hover:bg-primary rounded-full mt-2 animate-scroll-down"></div>
                </div>

                {{-- Chevron arrows --}}
                <div class="flex flex-col -mt-1 animate-bounce">
                    <svg class="w-4 h-4 text-white/60 group-hover:text-primary transition-colors -mb-2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    <svg class="w-4 h-4 text-white/40 group-hover:text-primary/70 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </section>
    @endif

    {{-- ==================== HERO SECTION ==================== --}}
    <section
        class="relative bg-gradient-to-b from-teal-50 via-white to-white dark:from-dark dark:via-dark dark:to-dark overflow-hidden grid-pattern">
        {{-- Floating Headshots Left --}}
        <div class="absolute left-0 top-32 hidden xl:block">
            <div class="space-y-4 opacity-90 dark:opacity-50">
                <div class="flex gap-2 ml-8">
                    <div
                        class="w-16 h-16 rounded-xl overflow-hidden shadow-lg border-2 border-white dark:border-dark-border bg-blue-200">
                        <img src="https://i.pravatar.cc/100?img=1" class="w-full h-full object-cover" alt=""></div>
                    <div
                        class="w-12 h-12 rounded-xl overflow-hidden shadow-lg border-2 border-white dark:border-dark-border bg-pink-200 mt-4">
                        <img src="https://i.pravatar.cc/100?img=5" class="w-full h-full object-cover" alt=""></div>
                </div>
                <div class="flex gap-2">
                    <div
                        class="w-14 h-14 rounded-xl overflow-hidden shadow-lg border-2 border-white dark:border-dark-border bg-green-200">
                        <img src="https://i.pravatar.cc/100?img=10" class="w-full h-full object-cover" alt=""></div>
                    <div
                        class="w-20 h-20 rounded-xl overflow-hidden shadow-lg border-2 border-white dark:border-dark-border bg-yellow-200">
                        <img src="https://i.pravatar.cc/100?img=15" class="w-full h-full object-cover" alt=""></div>
                </div>
                <div
                    class="w-16 h-16 rounded-xl overflow-hidden shadow-lg border-2 border-white dark:border-dark-border bg-purple-200 ml-12">
                    <img src="https://i.pravatar.cc/100?img=20" class="w-full h-full object-cover" alt=""></div>
            </div>
        </div>

        {{-- Floating Headshots Right --}}
        <div class="absolute right-0 top-32 hidden xl:block">
            <div class="space-y-4 opacity-90 dark:opacity-50">
                <div class="flex gap-2 justify-end mr-8">
                    <div
                        class="w-12 h-12 rounded-xl overflow-hidden shadow-lg border-2 border-white dark:border-dark-border bg-indigo-200 mt-4">
                        <img src="https://i.pravatar.cc/100?img=25" class="w-full h-full object-cover" alt=""></div>
                    <div
                        class="w-16 h-16 rounded-xl overflow-hidden shadow-lg border-2 border-white dark:border-dark-border bg-red-200">
                        <img src="https://i.pravatar.cc/100?img=30" class="w-full h-full object-cover" alt=""></div>
                </div>
                <div class="flex gap-2 justify-end">
                    <div
                        class="w-20 h-20 rounded-xl overflow-hidden shadow-lg border-2 border-white dark:border-dark-border bg-teal-200">
                        <img src="https://i.pravatar.cc/100?img=35" class="w-full h-full object-cover" alt=""></div>
                    <div
                        class="w-14 h-14 rounded-xl overflow-hidden shadow-lg border-2 border-white dark:border-dark-border bg-orange-200">
                        <img src="https://i.pravatar.cc/100?img=40" class="w-full h-full object-cover" alt=""></div>
                </div>
                <div
                    class="w-16 h-16 rounded-xl overflow-hidden shadow-lg border-2 border-white dark:border-dark-border bg-cyan-200 mr-12">
                    <img src="https://i.pravatar.cc/100?img=45" class="w-full h-full object-cover" alt=""></div>
            </div>
        </div>

        {{-- Hero Content --}}
        <div class="max-w-4xl mx-auto px-4 pt-12 pb-20 lg:pt-20 lg:pb-28 text-center relative z-10">
            {{-- Badge --}}
            <div
                class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-sm font-medium mb-6 border border-green-200 dark:border-green-800">
                <span class="text-lg">✨</span>
                New! We just upgraded our platform.
            </div>

            {{-- Subtitle --}}
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-4">The #1 AI Platform for Professional
                Business</p>

            {{-- Main Headline --}}
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 dark:text-white leading-tight mb-6">
                Professional business solutions,<br>
                <span class="text-slate-400 dark:text-slate-500">without the complexity.</span>
            </h1>

            {{-- Description --}}
            <p class="text-lg text-slate-500 dark:text-slate-400 mb-8 max-w-2xl mx-auto">
                Get professional business tools in minutes with our AI platform. Upload your data, pick your styles &
                receive 100+ results.
            </p>

            {{-- CTA Button --}}
            <a href="{{ route('auth.register') }}"
                class="inline-flex items-center gap-2 px-8 py-4 text-lg font-bold text-white bg-accent rounded-xl hover:bg-orange-600 shadow-lg shadow-orange-500/30 transition-all mb-8">
                Get started now
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3">
                    </path>
                </svg>
            </a>

            {{-- Secondary CTA --}}
            <div class="mb-8">
                <a href="#features"
                    class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium transition-colors">
                    Learn more
                    <span class="block text-xl mt-1">↓</span>
                </a>
            </div>

            {{-- Trust Badges --}}
            <div class="flex flex-wrap items-center justify-center gap-4 mb-8">
                <div class="flex items-center gap-1 bg-green-600 px-2 py-1 rounded">
                    @for($i = 0; $i < 5; $i++)<svg class="w-4 h-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>@endfor
                </div>
                <span class="text-sm font-medium text-slate-600 dark:text-slate-400">★ Trustpilot</span>
                <div class="flex -space-x-2">
                    <img src="https://i.pravatar.cc/32?img=1"
                        class="w-8 h-8 rounded-full border-2 border-white dark:border-dark" alt="">
                    <img src="https://i.pravatar.cc/32?img=2"
                        class="w-8 h-8 rounded-full border-2 border-white dark:border-dark" alt="">
                    <img src="https://i.pravatar.cc/32?img=3"
                        class="w-8 h-8 rounded-full border-2 border-white dark:border-dark" alt="">
                </div>
                <span class="text-sm text-slate-500 dark:text-slate-400"><span
                        class="font-semibold text-slate-700 dark:text-white">14,228,221</span> results created for <span
                        class="font-semibold text-slate-700 dark:text-white">86,412+</span> happy customers</span>
            </div>

            {{-- Media Logos --}}
            <div
                class="flex flex-wrap items-center justify-center gap-8 text-slate-400 dark:text-slate-600 text-sm font-semibold">
                <span class="text-xs text-slate-400 dark:text-slate-500">As seen on:</span>
                <span class="text-lg font-black italic">CNN</span>
                <span class="text-lg font-black">MIT TECH REVIEW</span>
                <span class="text-lg font-black italic">VICE</span>
                <span class="text-lg font-black">Bloomberg</span>
                <span class="text-lg font-black italic">FASHIONISTA</span>
            </div>
        </div>

        {{-- 3-Step Process Bar --}}
        <div class="bg-cyan-50 dark:bg-dark-card border-y border-cyan-100 dark:border-dark-border py-6">
            <div class="max-w-4xl mx-auto px-4">
                <div class="flex flex-col md:flex-row items-center justify-center gap-6 md:gap-12">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-100 dark:bg-dark rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-bold text-slate-900 dark:text-white">Step 1:</div>
                            <div class="text-sm text-slate-500 dark:text-slate-400">Upload your data</div>
                        </div>
                    </div>
                    <svg class="w-6 h-6 text-slate-300 dark:text-slate-600 hidden md:block" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3">
                        </path>
                    </svg>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-100 dark:bg-dark rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-bold text-slate-900 dark:text-white">Step 2:</div>
                            <div class="text-sm text-slate-500 dark:text-slate-400">Our AI goes to work</div>
                        </div>
                    </div>
                    <svg class="w-6 h-6 text-slate-300 dark:text-slate-600 hidden md:block" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3">
                        </path>
                    </svg>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-100 dark:bg-dark rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-bold text-slate-900 dark:text-white">Step 3:</div>
                            <div class="text-sm text-slate-500 dark:text-slate-400">Download your results</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== ABOUT SECTION ==================== --}}
    <section id="features" class="py-16 bg-white dark:bg-dark-card">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8 items-center">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">All packages include:</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                            <svg class="w-5 h-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Done in 2 hours or less
                        </li>
                        <li class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                            <svg class="w-5 h-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            8x cheaper than traditional
                        </li>
                        <li class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                            <svg class="w-5 h-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Hundreds of results to choose from
                        </li>
                    </ul>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-extrabold text-slate-900 dark:text-white">$29</div>
                    <div class="text-slate-500 dark:text-slate-400 font-medium">2 hours done</div>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Every package includes:</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                            <svg class="w-5 h-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Indistinguishable from real data
                        </li>
                        <li class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                            <svg class="w-5 h-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Business expense-ready invoice
                        </li>
                        <li class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                            <svg class="w-5 h-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Discounts up to 60% for teams
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== NEWS SECTION ==================== --}}
    @if($news->count() > 0)
        <section class="py-24 bg-white dark:bg-dark-card relative overflow-hidden">
            {{-- Decorative Elements --}}
            {{-- Top Right Grid Pattern --}}
            <div class="absolute top-0 right-0 w-96 h-96 opacity-[0.2] dark:opacity-[0.1] pointer-events-none z-0">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid-pattern-news" x="0" y="0" width="30" height="30" patternUnits="userSpaceOnUse">
                            <path d="M 30 0 L 0 0 0 30" fill="none" class="stroke-slate-400 dark:stroke-slate-500" stroke-width="1" stroke-dasharray="4,4"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid-pattern-news)"/>
                </svg>
                {{-- Gradient mask to fade the pattern --}}
                <div class="absolute inset-0 bg-gradient-to-bl from-transparent via-transparent to-white dark:to-dark-card opacity-100"></div>
            </div>

            <div class="absolute top-0 right-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
            </div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-accent/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2">
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 relative">
                {{-- Section Header --}}
                <div class="text-center mb-16">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 dark:bg-primary/20 text-primary rounded-full text-sm font-semibold mb-6">
                        <span class="w-2 h-2 bg-primary rounded-full animate-pulse"></span>
                        Latest News
                    </div>
                    <h2 class="text-4xl lg:text-5xl font-extrabold text-slate-900 dark:text-white mb-6">
                        Stay Updated With <span class="text-primary">Our News</span>
                    </h2>
                    <p class="text-lg text-slate-500 dark:text-slate-400 max-w-2xl mx-auto">
                        Get the latest updates, insights, and announcements from our team.
                    </p>
                </div>

                {{-- News Grid --}}
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($news as $index => $item)
                        <article
                            class="group bg-white dark:bg-dark rounded-3xl overflow-hidden border border-slate-100 dark:border-dark-border shadow-sm hover:shadow-2xl dark:hover:shadow-primary/10 transition-all duration-500 hover:-translate-y-2">
                            {{-- Image Container with Overlay --}}
                            <div class="relative h-56 overflow-hidden">
                                @if($item->image)
                                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}"
                                        class="w-full h-full object-cover transition-all duration-700 group-hover:scale-110">
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-primary/20 via-teal-500/10 to-accent/20 dark:from-primary/10 dark:to-accent/10 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                        </svg>
                                    </div>
                                @endif

                                {{-- Gradient Overlay --}}
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                </div>

                                {{-- Category Badge --}}
                                @if($item->category)
                                    <div class="absolute top-4 left-4">
                                        <span
                                            class="px-4 py-1.5 bg-white/90 dark:bg-dark-card/90 backdrop-blur-sm text-primary text-xs font-bold rounded-full shadow-lg">
                                            {{ $item->category->name }}
                                        </span>
                                    </div>
                                @endif

                                {{-- Read Time Badge --}}
                                <div
                                    class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0">
                                    <span
                                        class="px-3 py-1.5 bg-white/90 dark:bg-dark-card/90 backdrop-blur-sm text-slate-600 dark:text-slate-300 text-xs font-medium rounded-full flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        5 min read
                                    </span>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-6">
                                {{-- Date --}}
                                <div class="flex items-center gap-2 text-sm text-slate-400 dark:text-slate-500 mb-4">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $item->published_at?->format('d M Y') }}
                                </div>

                                {{-- Title --}}
                                <h3
                                    class="font-bold text-slate-900 dark:text-white text-xl mb-3 group-hover:text-primary transition-colors duration-300 line-clamp-2">
                                    <a href="{{ route('news.show', $item->slug) }}">{{ $item->title }}</a>
                                </h3>

                                {{-- Excerpt --}}
                                <p class="text-slate-500 dark:text-slate-400 text-sm mb-6 line-clamp-2 leading-relaxed">
                                    {{ Str::limit($item->excerpt ?? strip_tags($item->content), 120) }}
                                </p>

                                {{-- Read More Link --}}
                                <a href="{{ route('news.show', $item->slug) }}"
                                    class="inline-flex items-center gap-2 text-primary font-semibold text-sm group/link">
                                    <span>Read Article</span>
                                    <span
                                        class="w-6 h-6 bg-primary/10 dark:bg-primary/20 rounded-full flex items-center justify-center transition-all duration-300 group-hover/link:bg-primary group-hover/link:text-white">
                                        <svg class="w-3 h-3 transition-transform duration-300 group-hover/link:translate-x-0.5"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- View All Button --}}
                <div class="text-center mt-16">
                    <a href="{{ route('news.index') }}"
                        class="group inline-flex items-center gap-3 px-8 py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-2xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-all duration-300 hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl">
                        <span>View All Articles</span>
                        <span
                            class="w-8 h-8 bg-white/20 dark:bg-slate-900/20 rounded-full flex items-center justify-center transition-transform duration-300 group-hover:translate-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ==================== FAQ SECTION ==================== --}}
    <section class="py-20 relative overflow-hidden">
        {{-- Background with Gradient --}}
        <div
            class="absolute inset-0 bg-gradient-to-br from-slate-50 via-teal-50/30 to-slate-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        </div>

        {{-- Full Diamond Pattern --}}
        <div class="absolute inset-0 opacity-[0.08] dark:opacity-[0.05] pointer-events-none">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="diamond-pattern-faq" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                        <rect x="20" y="0" width="16" height="16" transform="rotate(45 20 8)" class="fill-slate-500 dark:fill-slate-400"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#diamond-pattern-faq)"/>
            </svg>
        </div>


        {{-- Gradient Orbs --}}
        <div
            class="absolute top-20 right-0 w-96 h-96 bg-gradient-to-br from-primary/10 to-teal-300/10 dark:from-primary/5 dark:to-teal-500/5 rounded-full blur-3xl">
        </div>
        <div
            class="absolute bottom-0 left-0 w-80 h-80 bg-gradient-to-tr from-teal-300/10 to-cyan-300/10 dark:from-teal-700/5 dark:to-cyan-700/5 rounded-full blur-3xl">
        </div>

        <div class="relative max-w-6xl mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-start">
                {{-- Left Side: Title --}}
                <div class="md:sticky md:top-24">
                    <span
                        class="inline-block px-4 py-1.5 rounded-full bg-primary/10 text-primary text-sm font-medium mb-4">FAQ</span>
                    <h2 class="text-4xl lg:text-5xl font-extrabold text-slate-900 dark:text-white leading-tight">
                        Have Any<br>Questions?
                    </h2>
                </div>

                {{-- Right Side: FAQ Accordion --}}
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-xl dark:shadow-2xl dark:shadow-black/30 p-6 md:p-8 border border-slate-200/50 dark:border-slate-700/50"
                    x-data="{ activeIndex: null }">
                    @php
                        $faqs = [
                            [
                                'question' => 'How can I set up a FiFaster account?',
                                'answer' => 'Getting started is easy! Simply click the "Get Started" button on our homepage, fill in your details, and verify your email. Your account will be ready in minutes.'
                            ],
                            [
                                'question' => 'How do I schedule a delivery?',
                                'answer' => 'Once logged in, navigate to the Dashboard and click "Schedule Delivery". Select your preferred date, time, and location. You can also set recurring deliveries for convenience.'
                            ],
                            [
                                'question' => 'What are your hours of operation?',
                                'answer' => 'Our customer service team is available Monday through Friday, 9 AM to 6 PM (local time). However, our platform is accessible 24/7 for self-service options.'
                            ],
                            [
                                'question' => 'Do you operate 24/7?',
                                'answer' => 'Yes! Our platform operates around the clock. While live support has specific hours, you can access all features, submit requests, and track orders at any time.'
                            ],
                            [
                                'question' => 'What equipment do you have?',
                                'answer' => 'We maintain a diverse fleet including refrigerated trucks, standard delivery vans, and specialized vehicles for fragile items. All equipment is regularly maintained and GPS-tracked.'
                            ],
                            [
                                'question' => 'Can I track my orders?',
                                'answer' => 'Absolutely! Real-time tracking is available for all orders. You\'ll receive updates via email and SMS, plus you can monitor your delivery through our mobile app or website.'
                            ],
                        ];
                    @endphp

                    <div class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach($faqs as $index => $faq)
                            <div class="py-4 first:pt-0 last:pb-0">
                                {{-- Question Header --}}
                                <button @click="activeIndex = activeIndex === {{ $index }} ? null : {{ $index }}"
                                    class="flex items-center justify-between w-full text-left gap-4 group">
                                    <div class="flex items-center gap-4">
                                        {{-- Question Icon --}}
                                        <div class="shrink-0 w-8 h-8 rounded-full flex items-center justify-center border-2 font-bold text-sm transition-all duration-200"
                                            :class="activeIndex === {{ $index }} 
                                                        ? 'bg-primary border-primary text-white' 
                                                        : 'bg-slate-100 dark:bg-dark border-slate-700 dark:border-slate-400 text-slate-700 dark:text-slate-400 group-hover:border-primary group-hover:text-primary'">
                                            ?
                                        </div>
                                        {{-- Question Text --}}
                                        <span class="font-medium transition-colors duration-200" :class="activeIndex === {{ $index }} 
                                                        ? 'text-primary' 
                                                        : 'text-slate-800 dark:text-slate-200 group-hover:text-primary'">
                                            {{ $faq['question'] }}
                                        </span>
                                    </div>
                                    {{-- Chevron Icon --}}
                                    <svg class="w-5 h-5 shrink-0 text-slate-400 transition-transform duration-300"
                                        :class="{ 'rotate-180': activeIndex === {{ $index }} }" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                {{-- Answer Content --}}
                                <div x-show="activeIndex === {{ $index }}" x-collapse x-cloak>
                                    <div class="pt-4 pl-12 text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                                        {{ $faq['answer'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        @keyframes scroll-down {
            0% {
                transform: translateY(0);
                opacity: 1;
            }

            50% {
                transform: translateY(8px);
                opacity: 0.5;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .animate-scroll-down {
            animation: scroll-down 1.5s ease-in-out infinite;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Hero Carousel
        new Swiper('.heroCarousel', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                bulletActiveClass: 'swiper-pagination-bullet-active',
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
            },
            speed: 800,
        });
    </script>
@endpush
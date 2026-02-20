{{-- Page Header with Curved Effect --}}
<section class="relative bg-dark dark:bg-dark overflow-hidden">
    {{-- Background Image with Overlay --}}
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>
        <div class="absolute inset-0 opacity-20"
            style="background-image: url('{{ $bgImage ?? 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=1920' }}'); background-size: cover; background-position: center;">
        </div>
        {{-- Animated Pattern --}}
        <div class="absolute inset-0 opacity-10">
            <div
                class="absolute top-0 left-0 w-96 h-96 bg-primary/30 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2">
            </div>
            <div
                class="absolute bottom-0 right-0 w-96 h-96 bg-accent/20 rounded-full blur-3xl translate-x-1/2 translate-y-1/2">
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="relative z-10 pt-32 pb-24 md:pt-40 md:pb-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center text-white">
            {{-- Breadcrumb --}}
            <nav class="flex items-center justify-center gap-2 text-sm mb-6">
                <a href="/"
                    class="text-white/60 hover:text-white transition-colors duration-300 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Home
                </a>
                <svg class="w-4 h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-primary font-medium">{{ $breadcrumb }}</span>
            </nav>

            {{-- Title --}}
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight">
                <span class="bg-gradient-to-r from-white via-white to-slate-300 bg-clip-text text-transparent">
                    {{ $title }}
                </span>
            </h1>

            {{-- Optional Subtitle --}}
            @if(isset($subtitle))
                <p class="mt-4 text-lg text-white/60 max-w-2xl mx-auto">{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    {{-- Curved Bottom --}}
    <div class="absolute bottom-0 left-0 right-0 z-10">
        <svg class="w-full h-16 md:h-24" viewBox="0 0 1440 100" preserveAspectRatio="none" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path d="M0 100V40C240 80 480 100 720 100C960 100 1200 80 1440 40V100H0Z"
                class="fill-slate-50 dark:fill-dark" />
        </svg>
    </div>

    {{-- Alternative Wave Style (more dynamic) --}}
    {{--
    <div class="absolute bottom-0 left-0 right-0 z-10">
        <svg class="w-full h-20 md:h-32" viewBox="0 0 1440 120" preserveAspectRatio="none" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120V60C180 20 360 0 540 20C720 40 900 80 1080 80C1260 80 1350 60 1440 40V120H0Z"
                class="fill-slate-50 dark:fill-dark" />
        </svg>
    </div>
    --}}
</section>
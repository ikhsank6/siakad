<!DOCTYPE html>
<html lang="id" class="scroll-smooth" x-data="{ 
    theme: localStorage.getItem('theme') || 'light',
    get isDark() {
        return this.theme === 'dark';
    }
}" x-init="$watch('theme', val => localStorage.setItem('theme', val))" :class="{ 'dark': isDark }">


<head>
    @include('partials.meta-base')
    <title>@yield('title', $aboutUs->company_name ?? config('app.name'))</title>
    @include('partials.meta-og')

    @include('partials.favicon')
    @include('partials.fonts')

    <!-- Tailwind & Alpine -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @hasSection('swiper')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    @endif

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#0d9488',
                        accent: '#f97316',
                        dark: '#0a0a0f',
                        'dark-card': '#111118',
                        'dark-border': '#1f1f2e',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    @include('partials.alpine-cloak')

    <style>
        /* Grid pattern for dark mode */
        .dark .grid-pattern {
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        .hero-slide {
            background-size: cover;
            background-position: center;
        }

        /* Swiper navigation */
        .swiper-button-next,
        .swiper-button-prev {
            color: white !important;
            width: 48px !important;
            height: 48px !important;
            background: rgba(255, 255, 255, 0.1) !important;
            border-radius: 9999px !important;
            transition: background 0.3s ease;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background: rgba(255, 255, 255, 0.2) !important;
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 18px !important;
            font-weight: bold;
        }

        @media (max-width: 640px) {

            .swiper-button-next,
            .swiper-button-prev {
                display: none !important;
            }
        }

        .swiper-pagination-bullet {
            background: rgba(255, 255, 255, 0.5) !important;
            width: 10px !important;
            height: 10px !important;
        }

        .swiper-pagination-bullet-active {
            background: #0d9488 !important;
            width: 32px !important;
            border-radius: 6px !important;
        }

        /* Hero Carousel Animations */
        @keyframes fade-in-up {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes gradient-x {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out forwards;
            opacity: 0;
        }

        .animate-gradient-x {
            animation: gradient-x 3s ease infinite;
        }

        .swiper-slide-active .animate-fade-in-up {
            opacity: 0;
            animation: fade-in-up 0.8s ease-out forwards;
        }

        /* Swiper Modern Navigation */
        .heroCarousel .swiper-button-next,
        .heroCarousel .swiper-button-prev {
            color: white !important;
            width: 56px !important;
            height: 56px !important;
            background: rgba(255, 255, 255, 0.1) !important;
            backdrop-filter: blur(12px) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 16px !important;
            transition: all 0.3s ease;
        }

        .heroCarousel .swiper-button-next:hover,
        .heroCarousel .swiper-button-prev:hover {
            background: rgba(255, 255, 255, 0.2) !important;
            transform: scale(1.05);
        }

        .heroCarousel .swiper-button-next::after,
        .heroCarousel .swiper-button-prev::after {
            font-size: 16px !important;
            font-weight: 600;
        }

        @media (max-width: 768px) {

            .heroCarousel .swiper-button-next,
            .heroCarousel .swiper-button-prev {
                display: none !important;
            }
        }

        .heroCarousel .swiper-pagination-bullet {
            background: rgba(255, 255, 255, 0.4) !important;
            width: 12px !important;
            height: 12px !important;
            transition: all 0.3s ease;
        }

        .heroCarousel .swiper-pagination-bullet-active {
            background: #0d9488 !important;
            width: 40px !important;
            border-radius: 6px !important;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .prose {
            max-width: none;
        }

        .prose p {
            margin-bottom: 1rem;
        }

        .prose h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 1.5rem 0 1rem;
        }

        .prose h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 1.25rem 0 0.75rem;
        }

        .prose ul,
        .prose ol {
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .prose li {
            margin-bottom: 0.5rem;
        }

        .prose a {
            color: #0d9488;
        }

        .prose img {
            border-radius: 0.5rem;
            margin: 1rem 0;
        }

        /* Smooth transition for dark mode */
        html.dark {
            color-scheme: dark;
        }

        body,
        nav,
        footer,
        section,
        div,
        article {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        /* Navigation transition */
        nav {
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }
    </style>
    @stack('styles')
</head>

<body class="font-sans antialiased bg-white dark:bg-dark text-slate-800 dark:text-slate-200">
    {{-- Navigation (Absolute positioned, transparent overlay) --}}
    @include('website.partials.nav')

    {{-- Page Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('website.partials.footer')

    {{-- Swiper JS (only if needed) --}}
    @hasSection('swiper')
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    @endif

    @stack('scripts')
</body>

</html>
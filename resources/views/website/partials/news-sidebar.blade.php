{{-- News Sidebar - Modern Design --}}
<div class="space-y-8 lg:sticky lg:top-28">
    {{-- Categories Card --}}
    <div
        class="bg-white dark:bg-dark-card rounded-2xl overflow-hidden shadow-sm dark:shadow-none border border-slate-100 dark:border-dark-border">
        {{-- Card Header --}}
        <div
            class="px-6 py-4 bg-gradient-to-r from-slate-50 to-white dark:from-dark-card dark:to-dark border-b border-slate-100 dark:border-dark-border">
            <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <span class="w-8 h-8 bg-primary/10 dark:bg-primary/20 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </span>
                Categories
            </h3>
        </div>
        {{-- Card Body --}}
        <div class="p-4">
            <ul class="space-y-1">
                @foreach($categories as $category)
                            <li>
                                <a href="{{ route('news.index', ['category' => $category->slug]) }}"
                                    class="flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-300 group
                                               {{ request('category') == $category->slug
                    ? 'bg-primary/10 dark:bg-primary/20 text-primary'
                    : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                                    <span class="font-medium">{{ $category->name }}</span>
                                    <span
                                        class="text-xs px-2 py-0.5 rounded-full transition-colors
                                                     {{ request('category') == $category->slug
                    ? 'bg-primary/20 text-primary'
                    : 'bg-slate-100 dark:bg-dark text-slate-500 dark:text-slate-400 group-hover:bg-primary/10 group-hover:text-primary' }}">
                                        {{ $category->news_count }}
                                    </span>
                                </a>
                            </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Recent Posts Card --}}
    <div
        class="bg-white dark:bg-dark-card rounded-2xl overflow-hidden shadow-sm dark:shadow-none border border-slate-100 dark:border-dark-border">
        {{-- Card Header --}}
        <div
            class="px-6 py-4 bg-gradient-to-r from-slate-50 to-white dark:from-dark-card dark:to-dark border-b border-slate-100 dark:border-dark-border">
            <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <span class="w-8 h-8 bg-accent/10 dark:bg-accent/20 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
                Recent Posts
            </h3>
        </div>
        {{-- Card Body --}}
        <div class="p-4 space-y-3">
            @foreach($recentNews as $recent)
                <a href="{{ route('news.show', $recent->slug) }}"
                    class="flex gap-4 p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-white/5 transition-all duration-300 group">
                    {{-- Thumbnail --}}
                    <div
                        class="w-20 h-20 rounded-xl overflow-hidden shrink-0 ring-2 ring-slate-100 dark:ring-dark-border group-hover:ring-primary/30 transition-all duration-300">
                        @if($recent->image)
                            <img src="{{ Storage::url($recent->image) }}" alt="{{ $recent->title }}"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                            <div
                                class="w-full h-full bg-gradient-to-br from-slate-100 to-slate-200 dark:from-dark dark:to-dark-card flex items-center justify-center">
                                <svg class="w-6 h-6 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <h4
                            class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-primary line-clamp-2 transition-colors duration-300 mb-1">
                            {{ $recent->title }}
                        </h4>
                        <div class="flex items-center gap-2 text-xs text-slate-400 dark:text-slate-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $recent->published_at?->format('d M Y') }}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Newsletter Card --}}
    <div class="bg-gradient-to-br from-primary to-teal-600 rounded-2xl p-6 text-white">
        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
        <h3 class="font-bold text-lg mb-2">Subscribe to Newsletter</h3>
        <p class="text-white/80 text-sm mb-4">Get the latest news and updates delivered straight to your inbox.</p>
        <form class="space-y-3">
            <input type="email" placeholder="Enter your email"
                class="w-full px-4 py-3 bg-white/10 backdrop-blur border border-white/20 rounded-xl text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/30 text-sm">
            <button type="submit"
                class="w-full px-4 py-3 bg-white text-primary font-semibold rounded-xl hover:bg-white/90 transition-colors text-sm">
                Subscribe Now
            </button>
        </form>
    </div>
</div>
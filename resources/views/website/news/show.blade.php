@extends('layouts.website')

@section('title', $news->title . ' - ' . ($aboutUs->company_name ?? config('app.name')))
@section('description', $news->excerpt ?? Str::limit(strip_tags($news->content), 160))

@section('content')
    {{-- Page Header --}}
    @include('website.partials.page-header', [
        'title' => $news->title,
        'breadcrumb' => 'News Details',
        'bgImage' => $news->image ? Storage::url($news->image) : null
    ])

    {{-- News Content --}}
    <section class="py-12 md:py-20 bg-slate-50 dark:bg-dark">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid lg:grid-cols-3 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-2">
                    <article class="bg-white dark:bg-dark-card rounded-xl overflow-hidden shadow-sm dark:shadow-none border border-transparent dark:border-dark-border">
                        @if($news->image)
                            <div class="relative">
                                <img src="{{ Storage::url($news->image) }}" alt="{{ $news->title }}"
                                    class="w-full h-[300px] md:h-[400px] object-cover">
                                <div class="absolute top-4 left-4 bg-primary text-white text-center px-3 py-2 rounded-lg">
                                    <span class="font-bold text-xl block">{{ $news->published_at?->format('d') }}</span>
                                    <span class="text-xs">{{ $news->published_at?->format('M') }}</span>
                                </div>
                            </div>
                        @endif
                        <div class="p-6 md:p-8">
                            <div class="flex items-center gap-4 text-sm text-slate-500 dark:text-slate-400 mb-4 flex-wrap">
                                @if($news->category)
                                    <span class="bg-primary/10 dark:bg-primary/20 text-primary px-3 py-1 rounded-full font-semibold">{{ $news->category->name }}</span>
                                @endif
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $news->published_at?->format('d M Y') }}
                                </span>
                            </div>

                            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white mb-6">{{ $news->title }}</h1>

                            <div class="prose dark:prose-invert text-slate-700 dark:text-slate-300 max-w-none">
                                {!! $news->content !!}
                            </div>

                            {{-- Share --}}
                            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-dark-border">
                                <div class="flex items-center gap-4 flex-wrap">
                                    <span class="font-semibold text-slate-900 dark:text-white">Share:</span>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                        target="_blank"
                                        class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center hover:opacity-80 transition-opacity">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                        </svg>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($news->title) }}"
                                        target="_blank"
                                        class="w-10 h-10 bg-slate-800 text-white rounded-full flex items-center justify-center hover:opacity-80 transition-opacity">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                        </svg>
                                    </a>
                                    <a href="https://wa.me/?text={{ urlencode($news->title . ' ' . request()->url()) }}"
                                        target="_blank"
                                        class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center hover:opacity-80 transition-opacity">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                        </svg>
                                    </a>
                                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($news->title) }}"
                                        target="_blank"
                                        class="w-10 h-10 bg-blue-700 text-white rounded-full flex items-center justify-center hover:opacity-80 transition-opacity">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>

                    {{-- Related News --}}
                    @if($relatedNews->count() > 0)
                        <div class="mt-8">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Related News</h3>
                            <div class="grid sm:grid-cols-2 gap-6">
                                @foreach($relatedNews as $related)
                                    <article class="bg-white dark:bg-dark-card rounded-lg overflow-hidden shadow-sm dark:shadow-none border border-transparent dark:border-dark-border hover:shadow-md dark:hover:shadow-lg transition-all group">
                                        <a href="{{ route('news.show', $related->slug) }}" class="flex gap-4 p-4">
                                            <div class="w-24 h-24 rounded-lg overflow-hidden shrink-0">
                                                @if($related->image)
                                                    <img src="{{ Storage::url($related->image) }}" alt="{{ $related->title }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-slate-200 dark:bg-dark flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-slate-900 dark:text-white group-hover:text-primary line-clamp-2 transition-colors">
                                                    {{ $related->title }}</h4>
                                                <span class="text-xs text-slate-400 dark:text-slate-500 mt-1 block">{{ $related->published_at?->format('d M Y') }}</span>
                                            </div>
                                        </a>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                @include('website.partials.news-sidebar')
            </div>
        </div>
    </section>
@endsection
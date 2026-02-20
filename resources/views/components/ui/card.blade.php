@props([
    'title' => null,
    'description' => null,
    'headerAction' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden']) }}>
    @if($title || $description || $headerAction)
        <!-- Card Header -->
        <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-800">
            <div class="sm:flex sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    @if(isset($headerLeading))
                        <div class="shrink-0">
                            {{ $headerLeading }}
                        </div>
                    @endif
                    <div>
                        @if($title)
                            <h1 class="text-xl font-bold text-zinc-900 dark:text-white">{{ $title }}</h1>
                        @endif
                        @if($description)
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $description }}</p>
                        @endif
                        
                        {{ $headerExtras ?? '' }}
                    </div>
                </div>
                
                @if($headerAction)
                    <div class="mt-4 sm:mt-0 sm:flex-none">
                        {{ $headerAction }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Card Body -->
    <div class="p-6">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <!-- Card Footer -->
        <div class="px-6 py-4 bg-zinc-50/50 dark:bg-zinc-800/50 border-t border-zinc-200 dark:border-zinc-800">
            {{ $footer }}
        </div>
    @endif
</div>

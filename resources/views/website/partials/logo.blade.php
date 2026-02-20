<div class="flex items-center gap-3 {{ $containerClass ?? '' }}">
    {{-- Logo Image/Icon --}}
    @if($aboutUs?->logo)
        <img src="{{ Storage::url($aboutUs->logo) }}" alt="{{ $aboutUs->company_name ?? config('app.name') }}"
            class="{{ $logoClass ?? 'h-9 w-auto' }} transition-all duration-300">
    @else
        <div
            class="shrink-0 w-10 h-10 bg-gradient-to-br from-primary to-teal-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary/20 transition-all duration-300 {{ $logoClass ?? '' }}">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
        </div>
    @endif

    {{-- Company Name --}}
    <span class="{{ $textClass ?? 'font-bold text-lg' }}" @if(isset($alpineTextClass)) :class="{{ $alpineTextClass }}"
    @endif>
        {{ $aboutUs->company_name ?? config('app.name') }}
    </span>
</div>
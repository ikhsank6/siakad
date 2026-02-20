@props(['strength' => 0, 'requirements' => []])

<div class="mt-3 space-y-3">
    {{-- Strength Meter --}}
    <div class="space-y-1.5">
        <div class="flex justify-between items-center px-1">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                Password Strength
            </span>
            <span @class([
                'text-xs font-bold' => true,
                'text-slate-400' => $strength == 0,
                'text-red-500' => $strength == 1,
                'text-orange-500' => $strength == 2,
                'text-yellow-500' => $strength == 3,
                'text-green-500' => $strength == 4,
            ])>
                @if($strength == 0) Too Weak @elseif($strength == 1) Weak @elseif($strength == 2) Moderate
                @elseif($strength == 3) Strong @else Very Strong @endif
            </span>
        </div>

        <div class="flex gap-1.5 h-1.5">
            @for ($i = 1; $i <= 4; $i++)
                <div @class([
                    'flex-1 rounded-full transition-all duration-500 ease-out',
                    'bg-slate-200 dark:bg-slate-700' => $strength < $i,
                    'bg-red-500' => $strength >= $i && $strength == 1,
                    'bg-orange-500' => $strength >= $i && $strength == 2,
                    'bg-yellow-500' => $strength >= $i && $strength == 3,
                    'bg-green-500' => $strength >= $i && $strength == 4,
                ])></div>
            @endfor
        </div>
    </div>

    {{-- Requirements Checklist --}}
    <div
        class="grid grid-cols-1 sm:grid-cols-2 gap-2 p-3 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-100 dark:border-slate-800/50">
        @php
            $checks = [
                'min_length' => 'At least 12 characters',
                'mixed_case' => 'Upper & lower case',
                'numbers' => 'At least 1 number',
                'symbols' => 'Special character',
            ];
        @endphp

        @foreach($checks as $key => $label)
            <div class="flex items-center gap-2">
                <div @class([
                    'flex-shrink-0 w-4 h-4 rounded-full flex items-center justify-center transition-all duration-300',
                    'bg-green-500 text-white' => $requirements[$key] ?? false,
                    'bg-slate-200 dark:bg-slate-700 text-slate-400 dark:text-slate-500' => !($requirements[$key] ?? false),
                ])>
                    @if($requirements[$key] ?? false)
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    @else
                        <div class="w-1 h-1 bg-current rounded-full"></div>
                    @endif
                </div>
                <span @class([
                    'text-xs transition-colors duration-300',
                    'text-slate-800 dark:text-slate-200 font-medium' => $requirements[$key] ?? false,
                    'text-slate-500 dark:text-slate-400' => !($requirements[$key] ?? false),
                ])>
                    {{ $label }}
                </span>
            </div>
        @endforeach
    </div>
</div>
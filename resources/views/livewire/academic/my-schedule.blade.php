<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Academic</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>My Schedule</flux:breadcrumbs.item>
</x-slot>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-zinc-900 dark:text-white">My Schedule</h2>
            <p class="text-sm text-zinc-500">View the finalized and active school schedules.</p>
        </div>
        <div class="flex gap-2">
            <flux:button
                href="{{ route('academic.my-schedule.export', ['class_id' => $filterClassId, 'teacher_id' => $filterTeacherId]) }}"
                icon="document-text" variant="filled">Export PDF</flux:button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Filters -->
        <div class="lg:col-span-1 space-y-6">
            <x-ui.card>
                <div class="space-y-4">
                    <h3 class="text-sm font-black text-zinc-900 dark:text-white uppercase tracking-widest">Filters</h3>

                    <flux:select wire:model.live="filterClassId" label="Class" placeholder="All Classes"
                        :disabled="$isStudent">
                        <flux:select.option value="">All Classes</flux:select.option>
                        @if($classes instanceof \Illuminate\Support\Collection || is_array($classes))
                            @foreach($classes as $class)
                                <flux:select.option value="{{ $class->id }}">{{ $class->name }}</flux:select.option>
                            @endforeach
                        @endif
                    </flux:select>

                    @unless($isStudent)
                        <flux:select wire:model.live="filterTeacherId" label="Teacher" placeholder="All Teachers"
                            :disabled="$isTeacher">
                            <flux:select.option value="">All Teachers</flux:select.option>
                            @if($teachers instanceof \Illuminate\Support\Collection || is_array($teachers))
                                @foreach($teachers as $teacher)
                                    <flux:select.option value="{{ $teacher->id }}">{{ $teacher->name }}</flux:select.option>
                                @endforeach
                            @endif
                        </flux:select>
                    @endunless
                </div>
            </x-ui.card>

            <div class="p-4 bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-800 rounded-2xl">
                <div class="flex gap-3">
                    <flux:icon.information-circle class="w-5 h-5 text-amber-600" />
                    <p class="text-[11px] text-amber-700 dark:text-amber-400 font-medium">
                        This view only shows schedules that have been <span class="font-bold">Published</span> or <span
                            class="font-bold">Locked</span>.
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Schedule View -->
        <div class="lg:col-span-3">
            @if(empty($calendarData) || $headerSlots->isEmpty())
                <div
                    class="p-12 text-center bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200 dark:border-zinc-800">
                    <flux:icon.calendar-days class="mx-auto h-12 w-12 text-zinc-400 mb-4" />
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white">No Schedule Found</h3>
                    <p class="text-sm text-zinc-500 max-w-xs mx-auto mt-2">
                        There are no active schedules published for the current academic year yet.
                    </p>
                </div>
            @else
                <div
                    class="overflow-x-auto border border-zinc-200 dark:border-zinc-800 rounded-2xl bg-white dark:bg-zinc-900 style-scrollbar">
                    @php
                        $dayShortLabels = \App\Constants\AcademicConstants::DAY_SHORT_LABELS;
                    @endphp

                    <table class="w-full text-xs text-center border-collapse">
                        <thead
                            class="bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white font-black sticky top-0 z-20">
                            <tr>
                                <th rowspan="2"
                                    class="border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 w-16">
                                </th>
                                @php $periodIdx = 1; @endphp
                                @foreach($headerSlots ?? [] as $slot)
                                    <th
                                        class="border border-zinc-200 dark:border-zinc-700 px-2 py-4 text-xl font-bold text-zinc-400 dark:text-zinc-500">
                                        @if(!$slot->is_break) {{ $periodIdx++ }} @endif
                                    </th>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach($headerSlots ?? [] as $slot)
                                    <th
                                        class="border border-zinc-200 dark:border-zinc-700 px-1 py-1.5 text-[10px] font-bold text-zinc-500 bg-zinc-50/50 dark:bg-zinc-800/50">
                                        {{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($days as $dayNum => $dayName)
                                <tr class="h-32">
                                    <td
                                        class="border border-zinc-200 dark:border-zinc-700 text-2xl font-black px-4 bg-zinc-50/50 dark:bg-zinc-800/50">
                                        {{ $dayShortLabels[$dayNum] ?? '' }}
                                    </td>
                                    @foreach($calendarData[$dayNum] ?? [] as $block)
                                        <td colspan="{{ $block['span'] }}"
                                            class="border border-zinc-200 dark:border-zinc-700 p-1 relative min-w-[140px] {{ $block['type'] === 'break' ? 'bg-zinc-50 dark:bg-zinc-800/20' : '' }}">
                                            @if($block['type'] === 'break')
                                                <div class="flex flex-col items-center justify-center p-2 h-full">
                                                    <span
                                                        class="text-[10px] font-bold uppercase tracking-[0.4em] text-zinc-300 dark:text-zinc-600 -rotate-90 [writing-mode:vertical-lr] whitespace-nowrap">
                                                        {{ $block['name'] }}
                                                    </span>
                                                </div>
                                            @elseif($block['type'] === 'subject')
                                                <div class="flex flex-col gap-2 p-2 h-full justify-center">
                                                    @foreach($block['items'] as $item)
                                                        <div
                                                            class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-3 shadow-sm flex flex-col items-center text-center group transition-all hover:shadow-md hover:border-zinc-300 print:shadow-none print:border-zinc-300">
                                                            <div
                                                                class="text-[11px] font-bold text-zinc-900 dark:text-zinc-100 mb-0.5 leading-tight uppercase">
                                                                {{ $item->subject->name }}
                                                            </div>
                                                            <div class="text-[9px] text-zinc-400 dark:text-zinc-500 mb-3 font-medium">
                                                                {{ $item->teacher->name }}
                                                            </div>
                                                            <div class="flex items-center gap-1.5 mt-auto">
                                                                <div
                                                                    class="px-1.5 py-0.5 rounded border border-green-500/30 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 text-[10px] font-black leading-none uppercase">
                                                                    R.{{ preg_replace('/[^a-zA-Z0-9 ]/', '', $item->room->name) ?: $item->room->name }}
                                                                </div>
                                                                <div
                                                                    class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400 whitespace-nowrap uppercase tracking-tighter">
                                                                    {{ $item->academicClass->name }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
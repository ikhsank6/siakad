<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Academic</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Auto Scheduling</flux:breadcrumbs.item>
</x-slot>

<div>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Config -->
        <div class="lg:col-span-1 space-y-6">
            <x-ui.card title="Academic Year" description="Select the active academic year for scheduling.">
                <div class="space-y-4">
                    <flux:select wire:model.live="activeYearId" placeholder="Select Academic Year">
                        @foreach($academicYears as $year)
                            <flux:select.option value="{{ $year->id }}">{{ $year->name }} - {{ $year->semester }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>

                    <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800 space-y-4">
                        <flux:checkbox wire:model="useDynamicRooms" label="Use Dynamic Rooms" description="If checked, classes will not be strictly assigned to a single room." />
                        
                        <flux:button wire:click="generateSchedule" variant="primary" class="w-full" icon="sparkles"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="generateSchedule">Simulate Schedule</span>
                            <span wire:loading wire:target="generateSchedule">Processing...</span>
                        </flux:button>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card class="overflow-hidden">
                <div class="p-5 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/20">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-xl bg-metronic-primary/10 text-metronic-primary">
                            <flux:icon name="clock" variant="mini" />
                        </div>
                        <div>
                            <h3 class="text-base font-black text-zinc-900 dark:text-white leading-none">School Hours</h3>
                            <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest mt-1">Configure daily limits</p>
                        </div>
                    </div>
                </div>

                <div class="p-5 space-y-6">
                    <div class="space-y-4">
                        @foreach($days as $dayNum => $dayName)
                            <div class="space-y-2">
                                <div class="flex items-center justify-between px-1">
                                    <span class="text-xs font-black text-zinc-900 dark:text-zinc-200 uppercase tracking-tight">{{ $dayName }}</span>
                                    <span class="text-[9px] font-bold text-zinc-400">Time Window</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="relative group">
                                        <div class="absolute -top-2 left-2 px-1 bg-white dark:bg-zinc-900 text-[8px] font-black text-zinc-400 uppercase z-10 transition-colors group-focus-within:text-metronic-primary">In</div>
                                        <flux:input wire:model="daySpecificConfig.{{ $dayNum }}.entry" type="time" size="sm" class="!text-xs font-bold" />
                                    </div>
                                    <div class="relative group">
                                        <div class="absolute -top-2 left-2 px-1 bg-white dark:bg-zinc-900 text-[8px] font-black text-zinc-400 uppercase z-10 transition-colors group-focus-within:text-metronic-primary">Out</div>
                                        <flux:input wire:model="daySpecificConfig.{{ $dayNum }}.exit" type="time" size="sm" class="!text-xs font-bold" />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800 space-y-4">
                        <flux:input wire:model="periodDuration" type="number" label="Session Duration" suffix="mins" />
                        
                        <flux:button wire:click="regenerateTimeSlots" variant="primary" class="w-full shadow-lg shadow-metronic-primary/10" wire:loading.attr="disabled">
                            <flux:icon name="arrow-path" variant="mini" class="mr-2" wire:loading.remove wire:target="regenerateTimeSlots" />
                            <flux:icon.loading class="mr-2" wire:loading wire:target="regenerateTimeSlots" />
                            Update Time Structure
                        </flux:button>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card title="Global Constraints" description="Set default teaching hours per week.">
                <div class="space-y-4">
                    <flux:input wire:model="globalMinHours" type="number" label="Global Min Hours" suffix="hrs/week" />
                    <flux:input wire:model="globalMaxHours" type="number" label="Global Max Hours" suffix="hrs/week" />

                    <flux:button wire:click="saveGlobalRules" variant="filled" class="w-full" wire:loading.attr="disabled">
                        <flux:icon name="check-circle" variant="mini" class="mr-2" wire:loading.remove wire:target="saveGlobalRules" />
                        <flux:icon.loading class="mr-2" wire:loading wire:target="saveGlobalRules" />
                        Save Global Rules
                    </flux:button>
                </div>
            </x-ui.card>

            <!-- Break Times -->
            <x-ui.card title="Break Periods" description="Set times when no classes can be scheduled.">
                <div class="space-y-4">
                    @foreach($breakTimes as $index => $break)
                        <div class="flex items-center gap-2 group">
                            <div class="flex-1 grid grid-cols-2 gap-2">
                                <flux:input wire:model="breakTimes.{{ $index }}.start" type="time"
                                    label="{{ $index === 0 ? 'Start Time' : '' }}" />
                                <flux:input wire:model="breakTimes.{{ $index }}.end" type="time"
                                    label="{{ $index === 0 ? 'End Time' : '' }}" />
                            </div>
                            <div class="{{ $index === 0 ? 'pt-6' : '' }}">
                                <flux:button wire:click="removeBreakTime({{ $index }})" variant="ghost" icon="trash"
                                    size="sm" class="text-red-500 opacity-0 group-hover:opacity-100 transition-opacity" />
                            </div>
                        </div>
                    @endforeach

                    <div class="pt-4 flex items-center justify-between">
                        <flux:button wire:click="addBreakTime" variant="ghost" icon="plus" size="sm">Add Break
                        </flux:button>
                        <flux:button wire:click="saveBreakTimes" variant="filled" size="sm" wire:loading.attr="disabled">
                            <flux:icon name="check-circle" variant="mini" class="mr-2" wire:loading.remove wire:target="saveBreakTimes" />
                            <flux:icon.loading class="mr-2" wire:loading wire:target="saveBreakTimes" />
                            Save Breaks
                        </flux:button>
                    </div>

                    <div class="p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800 rounded-lg">
                        <p class="text-[9px] text-amber-700 dark:text-amber-400">
                            <flux:icon.information-circle class="w-3 h-3 inline mr-1" />
                            Breaks apply to all days (Mon-Sat).
                        </p>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <div class="flex items-center gap-2 mb-6">
                <flux:button wire:click="$set('activeTab', 'config')" wire:loading.attr="disabled"
                    :variant="$activeTab === 'config' ? 'primary' : 'ghost'">
                    <flux:icon name="cog-6-tooth" variant="mini" class="mr-2" wire:loading.remove wire:target="$set('activeTab', 'config')" />
                    <flux:icon.loading class="mr-2" wire:loading wire:target="$set('activeTab', 'config')" />
                    Configuration
                </flux:button>
                <flux:button wire:click="$set('activeTab', 'simulate')" wire:loading.attr="disabled"
                    :variant="$activeTab === 'simulate' ? 'primary' : 'ghost'">
                    <flux:icon name="play-circle" variant="mini" class="mr-2" wire:loading.remove wire:target="$set('activeTab', 'simulate')" />
                    <flux:icon.loading class="mr-2" wire:loading wire:target="$set('activeTab', 'simulate')" />
                    Simulation Results
                </flux:button>
                <flux:button wire:click="$set('activeTab', 'publish')" wire:loading.attr="disabled"
                    :variant="$activeTab === 'publish' ? 'primary' : 'ghost'">
                    <flux:icon name="cloud-arrow-up" variant="mini" class="mr-2" wire:loading.remove wire:target="$set('activeTab', 'publish')" />
                    <flux:icon.loading class="mr-2" wire:loading wire:target="$set('activeTab', 'publish')" />
                    Publish & Approval
                </flux:button>
            </div>

            @if($activeTab === 'config')
                <!-- Teacher Overrides -->
                <x-ui.card title="Teacher Overrides" description="Set custom teaching hour limits for specific teachers.">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-zinc-50 dark:bg-zinc-800 text-zinc-500 uppercase text-[10px] font-bold">
                                <tr>
                                    <th class="px-4 py-3">Teacher</th>
                                    <th class="px-4 py-3">Min Hours</th>
                                    <th class="px-4 py-3">Max Hours</th>
                                    <th class="px-4 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                @foreach($teachers as $teacher)
                                    <tr>
                                        <td class="px-4 py-3 font-medium">{{ $teacher->name }}</td>
                                        <td class="px-4 py-3 text-zinc-500">
                                            {{ $teacher->config->min_hours_per_week ?? 'Global' }}
                                        </td>
                                        <td class="px-4 py-3 text-zinc-500">
                                            {{ $teacher->config->max_hours_per_week ?? 'Global' }}
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <flux:button
                                                wire:click="editTeacher({{ $teacher->id }}, '{{ addslashes($teacher->name) }}', {{ $teacher->config->min_hours_per_week ?? 'null' }}, {{ $teacher->config->max_hours_per_week ?? 'null' }})"
                                                wire:loading.attr="disabled"
                                                variant="ghost" size="sm" tooltip="Override">
                                                <flux:icon name="pencil-square" variant="mini" class="w-4 h-4" wire:loading.remove wire:target="editTeacher({{ $teacher->id }}, '{{ addslashes($teacher->name) }}', {{ $teacher->config->min_hours_per_week ?? 'null' }}, {{ $teacher->config->max_hours_per_week ?? 'null' }})" />
                                                <flux:icon.loading class="w-4 h-4" wire:loading wire:target="editTeacher({{ $teacher->id }}, '{{ addslashes($teacher->name) }}', {{ $teacher->config->min_hours_per_week ?? 'null' }}, {{ $teacher->config->max_hours_per_week ?? 'null' }})" />
                                            </flux:button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-ui.card>

                <x-ui.modal wire:model="showOverrideModal" title="Override Teaching Hours" formId="teacher-override-form">
                    <form wire:submit="saveTeacherConfig" id="teacher-override-form" class="space-y-4" novalidate>
                        <div class="mb-4 p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                            <span class="text-xs text-zinc-500 block">Teacher</span>
                            <span class="font-bold text-zinc-900 dark:text-white">{{ $editingTeacherName }}</span>
                        </div>

                        <flux:input wire:model="editingMinHours" type="number" label="Minimum Hours" suffix="hrs/week" />
                        <flux:input wire:model="editingMaxHours" type="number" label="Maximum Hours" suffix="hrs/week" />
                    </form>
                </x-ui.modal>
            @endif

            @if($activeTab === 'simulate')
                <x-ui.card title="Draft Preview" description="Preview of generated schedules before publishing.">
                    @if(count($draftSchedules) > 0 || $previewFilterClass || $previewFilterTeacher)
                        <div class="mb-6 flex flex-col sm:flex-row gap-4">
                            <flux:select wire:model.live="previewFilterClass" placeholder="Filter by Class" class="flex-1">
                                <flux:select.option value="">All Classes</flux:select.option>
                                @foreach($classes as $c)
                                    <flux:select.option value="{{ $c->id }}">{{ $c->name }}</flux:select.option>
                                @endforeach
                            </flux:select>

                            <flux:select wire:model.live="previewFilterTeacher" placeholder="Filter by Teacher" class="flex-1">
                                <flux:select.option value="">All Teachers</flux:select.option>
                                @foreach($teachers as $t)
                                    <flux:select.option value="{{ $t->id }}">{{ $t->name }}</flux:select.option>
                                @endforeach
                            </flux:select>
                        </div>

                        <div class="relative overflow-hidden group">
                            <!-- Loading Overlay for table updates -->
                            <div wire:loading wire:target="previewFilterClass, previewFilterTeacher, generateSchedule"
                                class="absolute inset-0 z-30 bg-white/60 dark:bg-zinc-900/60 backdrop-blur-[2px] flex items-center justify-center">
                                <div class="flex flex-col items-center gap-3 p-6 bg-white dark:bg-zinc-800 rounded-2xl shadow-xl border border-zinc-200 dark:border-zinc-700">
                                    <flux:icon.loading class="w-10 h-10 text-metronic-primary" />
                                    <span class="text-sm font-bold text-zinc-900 dark:text-white">Updating Schedule...</span>
                                </div>
                            </div>

                            <div class="overflow-x-auto overflow-y-auto max-h-[700px] border border-zinc-200 dark:border-zinc-800 rounded-lg style-scrollbar">
                                @php 
                                    $dayShortLabels = [1 => 'Sen', 2 => 'Sel', 3 => 'Ra', 4 => 'Ka', 5 => 'Ju', 6 => 'Sab'];
                                    $headerSlots = \App\Models\TimeSlot::where('day', 1)->orderBy('start_time')->get();
                                @endphp

                                <table class="w-full text-xs text-center border-collapse border border-zinc-200 dark:border-zinc-800">
                                    <thead class="bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white font-bold sticky top-0 z-20">
                                        <tr>
                                            <th rowspan="2" class="border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 w-16"></th>
                                            @php $periodIdx = 1; @endphp
                                            @foreach($headerSlots as $slot)
                                                <th class="border border-zinc-200 dark:border-zinc-700 px-2 py-2 text-base">
                                                    @if(!$slot->is_break)
                                                        {{ $periodIdx++ }}
                                                    @endif
                                                </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach($headerSlots as $slot)
                                                <th class="border border-zinc-200 dark:border-zinc-700 px-1 py-1 text-[9px] font-normal text-zinc-500">
                                                    {{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($days as $dayNum => $dayName)
                                            <tr class="h-32">
                                                <td class="border border-zinc-200 dark:border-zinc-700 text-2xl font-black px-4 bg-zinc-50/50 dark:bg-zinc-800/50">
                                                    {{ $dayShortLabels[$dayNum] ?? '' }}
                                                </td>
                                                @php $dayBlocks = $calendarData[$dayNum] ?? []; @endphp
                                                @foreach($dayBlocks as $block)
                                                    <td colspan="{{ $block['span'] }}" class="border border-zinc-200 dark:border-zinc-700 p-1 relative min-w-[120px] {{ $block['type'] === 'break' ? 'bg-zinc-100/40 dark:bg-zinc-800/40' : '' }}">
                                                        @if($block['type'] === 'break')
                                                            <div class="flex items-center justify-center p-2">
                                                                <span class="text-[9px] font-black uppercase tracking-widest text-zinc-400 rotate-90 whitespace-nowrap">{{ $block['name'] }}</span>
                                                            </div>
                                                        @elseif($block['type'] === 'subject')
                                                            <div class="flex flex-col gap-1.5 h-full">
                                                                @foreach($block['items'] as $item)
                                                                    <div class="p-2 rounded-lg border bg-white dark:bg-zinc-900 border-zinc-200 dark:border-zinc-800 shadow-sm">
                                                                        <div class="font-bold text-zinc-900 dark:text-zinc-100 text-[11px] leading-tight mb-1">
                                                                            {{ $item->subject->name }}
                                                                        </div>
                                                                        <div class="text-[9px] text-zinc-500 truncate mb-2">
                                                                            {{ $item->teacher->name }}
                                                                        </div>
                                                                        <div class="flex gap-1 justify-center">
                                                                            <span class="px-1 py-0.5 rounded bg-green-50 text-green-700 border border-green-100 text-[8px] font-bold">R.{{ preg_replace('/[^0-9]/', '', $item->room->name) ?: $item->room->name }}</span>
                                                                            <span class="px-1 py-0.5 rounded bg-zinc-50 text-zinc-600 border border-zinc-100 text-[8px] font-bold">{{ $item->academicClass->name }}</span>
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
                        </div>
                    @else
                        <div
                            class="flex items-center justify-center p-12 bg-zinc-50 dark:bg-zinc-800/50 rounded-xl border-2 border-dashed border-zinc-200 dark:border-zinc-700">
                            <div class="text-center">
                                <flux:icon.sparkles class="mx-auto h-12 w-12 text-zinc-400 mb-4" />
                                <h3 class="text-lg font-medium text-zinc-900 dark:text-white">Simulation Engine Ready</h3>
                                <p class="text-sm text-zinc-500 max-w-xs mx-auto mt-2">
                                    Click "Simulate Schedule" to run the dynamic constraints solver and generate a draft.
                                </p>
                            </div>
                        </div>
                    @endif
                </x-ui.card>
            @endif

            @if($activeTab === 'publish')
                <x-ui.card title="Publishing Control" description="Finalize and lock the generated schedules.">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="p-3 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600">
                                    <flux:icon.cloud-arrow-up class="w-6 h-6" />
                                </div>
                                <div>
                                    <h4 class="font-bold">Publish Schedule</h4>
                                    <p class="text-xs text-zinc-500">Make draft schedules visible to students and teachers.
                                    </p>
                                </div>
                            </div>
                            <flux:button wire:click="publishSchedule" variant="primary" class="w-full" wire:loading.attr="disabled">
                                <flux:icon name="cloud-arrow-up" variant="mini" class="mr-2" wire:loading.remove wire:target="publishSchedule" />
                                <flux:icon.loading class="mr-2" wire:loading wire:target="publishSchedule" />
                                Approve & Publish
                            </flux:button>
                        </div>

                        <div class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="p-3 rounded-xl bg-zinc-50 dark:bg-zinc-800 text-zinc-600">
                                    <flux:icon.lock-closed class="w-6 h-6" />
                                </div>
                                <div>
                                    <h4 class="font-bold">Lock Schedule</h4>
                                    <p class="text-xs text-zinc-500">Prevent any further changes to the active schedule.</p>
                                </div>
                            </div>
                            <flux:button wire:click="lockSchedule" variant="filled" class="w-full" wire:loading.attr="disabled">
                                <flux:icon name="lock-closed" variant="mini" class="mr-2" wire:loading.remove wire:target="lockSchedule" />
                                <flux:icon.loading class="mr-2" wire:loading wire:target="lockSchedule" />
                                Finalize & Lock
                            </flux:button>
                        </div>
                    </div>
                </x-ui.card>
            @endif
        </div>
    </div>
</div>
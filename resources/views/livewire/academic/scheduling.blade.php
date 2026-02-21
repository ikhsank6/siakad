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

            <x-ui.card title="Global Constraints" description="Set default teaching hours per week.">
                <div class="space-y-4">
                    <flux:input wire:model="globalMinHours" type="number" label="Global Min Hours" suffix="hrs/week" />
                    <flux:input wire:model="globalMaxHours" type="number" label="Global Max Hours" suffix="hrs/week" />

                    <flux:button wire:click="saveGlobalRules" variant="filled" class="w-full">
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
                        <flux:button wire:click="saveBreakTimes" variant="filled" size="sm" icon="check-circle">
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
                <flux:button wire:click="$set('activeTab', 'config')"
                    :variant="$activeTab === 'config' ? 'primary' : 'ghost'" icon="cog-6-tooth">
                    Configuration
                </flux:button>
                <flux:button wire:click="$set('activeTab', 'simulate')"
                    :variant="$activeTab === 'simulate' ? 'primary' : 'ghost'" icon="play-circle">
                    Simulation Results
                </flux:button>
                <flux:button wire:click="$set('activeTab', 'publish')"
                    :variant="$activeTab === 'publish' ? 'primary' : 'ghost'" icon="cloud-arrow-up">
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
                                                variant="ghost" size="sm" icon="pencil-square" tooltip="Override" />
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

                        <div class="overflow-x-auto overflow-y-auto max-h-[600px] border border-zinc-200 dark:border-zinc-800 rounded-lg">
                            <table class="w-full text-xs text-left min-w-max">
                                <thead class="bg-zinc-50 dark:bg-zinc-800 text-zinc-500 font-bold sticky top-0 z-10">
                                    <tr>
                                        <th class="px-4 py-3 min-w-[120px] border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 sticky left-0 z-20">Time</th>
                                        @foreach($days as $dayNum => $dayName)
                                            <th class="px-4 py-3 min-w-[220px] border-b border-l border-zinc-200 dark:border-zinc-700">{{ $dayName }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                                    @foreach($timeSlots as $timeKey => $slotsInGroup)
                                        @php
                                            $firstSlotData = (array) ($calendarData[1][$timeKey] ?? []);
                                        @endphp
                                        <tr>
                                            <td class="px-4 py-3 border-r border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 sticky left-0 shadow-[2px_0_5px_rgba(0,0,0,0.05)] dark:shadow-[2px_0_5px_rgba(0,0,0,0.5)] z-10">
                                                <div class="font-medium text-zinc-900 dark:text-white">{{ substr($firstSlotData['start_time'] ?? '', 0, 5) }}</div>
                                                <div class="text-[10px] text-zinc-500">{{ substr($firstSlotData['end_time'] ?? '', 0, 5) }}</div>
                                            </td>
                                            
                                            @foreach($days as $dayNum => $dayName)
                                                @php
                                                    $cellData = (array) ($calendarData[$dayNum][$timeKey] ?? ['items' => collect(), 'is_break' => false, 'name' => '']);
                                                    $items = $cellData['items'] ?? collect();
                                                @endphp
                                                <td class="px-5 py-4 h-32 align-top min-w-[320px] border-l border-zinc-200 dark:border-zinc-800 relative bg-zinc-50/30 dark:bg-zinc-800/20">
                                                    @if($cellData['is_break'] ?? false)
                                                        <div class="absolute inset-0 flex items-center justify-center bg-zinc-100/50 dark:bg-zinc-800/80">
                                                            <span class="text-zinc-400 font-bold uppercase tracking-widest opacity-50">{{ $cellData['name'] }}</span>
                                                        </div>
                                                    @else
                                                        <div class="flex flex-col gap-3">
                                                            @foreach($items as $item)
                                                                <div class="p-3 rounded-xl border text-left transition-all hover:shadow-md
                                                                    {{ $previewFilterClass ? 'bg-indigo-50 border-indigo-200 dark:bg-indigo-900/30 dark:border-indigo-800/50' : 'bg-white border-zinc-200 dark:bg-zinc-800 dark:border-zinc-700' }} shadow-sm">
                                                                    
                                                                    <div class="font-bold text-zinc-900 dark:text-zinc-100 truncate text-xs" title="{{ $item->subject->name }}">
                                                                        {{ $item->subject->name }}
                                                                    </div>
                                                                    
                                                                    <div class="mt-1 flex items-center gap-2 text-[10px] text-zinc-500">
                                                                        <flux:icon.user class="w-2.5 h-2.5" />
                                                                        <span class="font-medium truncate" title="{{ $item->teacher->name }}">{{ $item->teacher->name }}</span>
                                                                    </div>

                                                                    <div class="mt-2 flex items-center gap-2">
                                                                        <x-ui.badge variant="success" class="px-2! py-0.5! text-[10px]! font-bold">
                                                                            <flux:icon.building-library class="w-2 h-2 mr-1 inline" />
                                                                            {{ $item->room->name }}
                                                                        </x-ui.badge>
                                                                        <x-ui.badge variant="primary" class="px-2! py-0.5! text-[10px]! font-bold">
                                                                            <flux:icon.users class="w-2 h-2 mr-1 inline" />
                                                                            {{ $item->academicClass->name }}
                                                                        </x-ui.badge>
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
                            <flux:button wire:click="publishSchedule" variant="primary" class="w-full">Approve & Publish
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
                            <flux:button wire:click="lockSchedule" variant="filled" class="w-full">Finalize & Lock
                            </flux:button>
                        </div>
                    </div>
                </x-ui.card>
            @endif
        </div>
    </div>
</div>
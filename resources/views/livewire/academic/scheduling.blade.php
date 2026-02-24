<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Academic</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Auto Scheduling</flux:breadcrumbs.item>
</x-slot>

<div>
    <div class="space-y-6">
        <!-- System Configuration (Always at the Top) -->
        <x-ui.card title="System Configuration" description="Manage all scheduling parameters in one place.">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 p-1">
                <!-- Academic Year & Session -->
                <div class="md:col-span-3 space-y-6">
                    <div>
                        <h4 class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-4">Academic Context
                        </h4>
                        <div class="space-y-4">
                            <flux:select wire:model.live="activeYearId" label="Academic Year"
                                placeholder="Select Academic Year">
                                @foreach($academicYears as $year)
                                    <flux:select.option value="{{ $year->id }}">{{ $year->name }} - {{ $year->semester }}
                                    </flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:checkbox wire:model="useDynamicRooms" label="Use Dynamic Rooms"
                                description="Auto-assign rooms based on availability." />
                        </div>
                    </div>
                    <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800">
                        <flux:button wire:click="generateSchedule" variant="primary"
                            class="w-full shadow-lg shadow-metronic-primary/20" icon="sparkles"
                            wire:loading.attr="disabled">
                            Simulate Schedule
                        </flux:button>
                    </div>
                </div>

                <!-- School Hours -->
                <div class="md:col-span-6 border-l border-zinc-100 dark:border-zinc-800 pl-8">
                    <h4 class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-5">Weekly School
                        Schedule</h4>
                    <div class="max-h-[320px] overflow-y-auto pr-4 style-scrollbar">
                        <div class="space-y-4">
                            @foreach($days ?? [] as $dayNum => $dayName)
                                <div
                                    class="flex items-center gap-6 group hover:bg-zinc-50 dark:hover:bg-zinc-800/50 p-2 rounded-xl transition-all duration-200">
                                    <div class="w-24">
                                        <span
                                            class="text-xs font-black text-zinc-600 dark:text-zinc-400 uppercase tracking-tighter group-hover:text-metronic-primary transition-colors">{{ $dayName }}</span>
                                    </div>
                                    <div class="flex-1 grid grid-cols-2 gap-4">
                                        <div class="space-y-1 text-zinc-950 dark:text-white">
                                            <flux:input wire:model="daySpecificConfig.{{ $dayNum }}.entry" type="time"
                                                size="sm" label="Check In" class="text-sm! font-black" />
                                        </div>
                                        <div class="space-y-1 text-zinc-950 dark:text-white">
                                            <flux:input wire:model="daySpecificConfig.{{ $dayNum }}.exit" type="time"
                                                size="sm" label="Check Out" class="text-sm! font-black" />
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div
                        class="mt-6 pt-6 border-t border-zinc-100 dark:border-zinc-800 flex items-end gap-4 text-zinc-950 dark:text-white">
                        <div class="flex-1">
                            <flux:input wire:model="periodDuration" type="number" size="sm" label="Session Duration"
                                suffix="min" class="font-bold" />
                        </div>
                        <flux:button wire:click="regenerateTimeSlots" variant="filled" size="sm" class="mb-0.5 px-6"
                            wire:loading.attr="disabled">Update Structure</flux:button>
                    </div>
                </div>

                <!-- Constraints & Breaks -->
                <div class="md:col-span-3 border-l border-zinc-100 dark:border-zinc-800 pl-8 space-y-8">
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Global Constraints -->
                        <div>
                            <h4 class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-3">Teaching
                                Limits</h4>
                            <div class="space-y-3">
                                <flux:input wire:model="globalMinHours" type="number" label="Min (hrs/wk)" size="sm" />
                                <flux:input wire:model="globalMaxHours" type="number" label="Max (hrs/wk)" size="sm" />
                                <flux:button wire:click="saveGlobalRules" variant="ghost" size="xs" class="w-full"
                                    wire:loading.attr="disabled">Save Limits</flux:button>
                            </div>
                        </div>

                        <!-- Break Periods -->
                        <div>
                            <h4 class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-3">Break
                                Periods</h4>
                            <div
                                class="max-h-[160px] overflow-y-auto space-y-2 pr-2 style-scrollbar mb-3 border-b border-zinc-50 dark:border-zinc-800/50 pb-2">
                                @foreach($breakTimes as $index => $break)
                                    <div class="flex items-center gap-1.5 group">
                                        <div class="flex-1 grid grid-cols-2 gap-1">
                                            <flux:input wire:model="breakTimes.{{ $index }}.start" type="time" size="sm" />
                                            <flux:input wire:model="breakTimes.{{ $index }}.end" type="time" size="sm" />
                                        </div>
                                        <flux:button wire:click="removeBreakTime({{ $index }})" variant="ghost" icon="trash"
                                            size="xs" class="text-red-500 opacity-0 group-hover:opacity-100" />
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex gap-2">
                                <flux:button wire:click="addBreakTime" variant="ghost" size="xs" class="flex-1">Add
                                </flux:button>
                                <flux:button wire:click="saveBreakTimes" variant="filled" size="xs" class="flex-1"
                                    wire:loading.attr="disabled">Save</flux:button>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800">
                        <div
                            class="p-3 bg-amber-50/50 dark:bg-amber-900/10 border border-amber-100/50 dark:border-amber-800/50 rounded-xl">
                            <div class="flex gap-2 items-start">
                                <flux:icon.information-circle class="w-4 h-4 text-amber-600 mt-0.5" />
                                <p class="text-[10px] text-amber-700 dark:text-amber-400 leading-tight">
                                    Configuration changes will require a <span class="font-bold underline">new
                                        simulation</span> to take effect in the draft schedule.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-ui.card>

        <!-- Tab Switcher (Now after System Configuration) -->
        <div class="flex items-center gap-2 pb-2">
            <flux:button wire:click="$set('activeTab', 'config')" wire:loading.attr="disabled"
                :variant="$activeTab === 'config' ? 'primary' : 'ghost'">
                <flux:icon name="cog-6-tooth" variant="mini" class="mr-2" />
                Configuration
            </flux:button>
            <flux:button wire:click="$set('activeTab', 'simulate')" wire:loading.attr="disabled"
                :variant="$activeTab === 'simulate' ? 'primary' : 'ghost'">
                <flux:icon name="play-circle" variant="mini" class="mr-2" />
                Simulation Results
            </flux:button>
            <flux:button wire:click="$set('activeTab', 'publish')" wire:loading.attr="disabled"
                :variant="$activeTab === 'publish' ? 'primary' : 'ghost'">
                <flux:icon name="cloud-arrow-up" variant="mini" class="mr-2" />
                Publish & Approval
            </flux:button>
        </div>

        <!-- Main Content Area (Tab Content) -->
        <div class="w-full">
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
                                                wire:loading.attr="disabled" variant="ghost" size="sm" tooltip="Override">
                                                <flux:icon name="pencil-square" variant="mini" class="w-4 h-4"
                                                    wire:loading.remove
                                                    wire:target="editTeacher({{ $teacher->id }}, '{{ addslashes($teacher->name) }}', {{ $teacher->config->min_hours_per_week ?? 'null' }}, {{ $teacher->config->max_hours_per_week ?? 'null' }})" />
                                                <flux:icon.loading class="w-4 h-4" wire:loading
                                                    wire:target="editTeacher({{ $teacher->id }}, '{{ addslashes($teacher->name) }}', {{ $teacher->config->min_hours_per_week ?? 'null' }}, {{ $teacher->config->max_hours_per_week ?? 'null' }})" />
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

            <!-- Manual Schedule Modal -->
            <x-ui.modal wire:model="showManualModal" :title="$editingScheduleId ? 'Edit Schedule' : 'Manual Entry'"
                formId="manual-schedule-form">
                <form wire:submit="saveManualSchedule" id="manual-schedule-form" class="space-y-4">
                    {{ $this->form }}

                    @if($editingScheduleId)
                        <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800">
                            <flux:button wire:click="deleteSchedule({{ $editingScheduleId }})" variant="danger"
                                class="w-full" wire:loading.attr="disabled">
                                <flux:icon.loading class="mr-2" wire:loading wire:target="deleteSchedule" />
                                Delete Schedule
                            </flux:button>
                        </div>
                    @endif
                </form>

                <x-slot name="footer">
                    <flux:button variant="ghost" @click="show = false">Cancel</flux:button>
                    <flux:button type="submit" form="manual-schedule-form" variant="primary">
                        <flux:icon.loading class="mr-2" wire:loading wire:target="saveManualSchedule" />
                        {{ $editingScheduleId ? 'Save Changes' : 'Add to Schedule' }}
                    </flux:button>
                </x-slot>
            </x-ui.modal>

            @if($activeTab === 'simulate')
                <x-ui.card title="Draft Preview" description="Preview of generated schedules before publishing.">
                    @if(count($draftSchedules) > 0 || $previewFilterClass || $previewFilterTeacher)
                        <div class="mb-6 flex flex-col sm:flex-row gap-4">
                            <flux:select wire:model.live="previewFilterClass" placeholder="Filter by Class" class="flex-1">
                                <flux:select.option value="">All Classes</flux:select.option>
                                @foreach($classList ?? collect() as $c)
                                    <flux:select.option value="{{ $c->id }}">{{ $c->name }}</flux:select.option>
                                @endforeach
                            </flux:select>

                            <flux:select wire:model.live="previewFilterTeacher" placeholder="Filter by Teacher" class="flex-1">
                                <flux:select.option value="">All Teachers</flux:select.option>
                                @foreach($teachers ?? collect() as $t)
                                    <flux:select.option value="{{ $t->id }}">{{ $t->name }}</flux:select.option>
                                @endforeach
                            </flux:select>
                        </div>

                        <div class="relative overflow-hidden group">
                            <!-- Loading Overlay -->
                            <div wire:loading wire:target="previewFilterClass, previewFilterTeacher, generateSchedule"
                                class="absolute inset-0 z-30 bg-white/60 dark:bg-zinc-900/60 backdrop-blur-[2px] flex items-center justify-center">
                                <div
                                    class="flex flex-col items-center gap-3 p-6 bg-white dark:bg-zinc-800 rounded-2xl shadow-xl border border-zinc-200 dark:border-zinc-700">
                                    <flux:icon.loading class="w-10 h-10 text-metronic-primary" />
                                    <span class="text-sm font-bold text-zinc-900 dark:text-white">Updating Schedule...</span>
                                </div>
                            </div>

                            <div
                                class="overflow-x-auto overflow-y-auto max-h-[700px] border border-zinc-200 dark:border-zinc-800 rounded-lg style-scrollbar">
                                @php
                                    $dayShortLabels = [1 => 'Sen', 2 => 'Sel', 3 => 'Ra', 4 => 'Ka', 5 => 'Ju', 6 => 'Sab'];
                                    $headerSlots = \App\Models\TimeSlot::where('day', 1)->orderBy('start_time')->get();
                                @endphp

                                <table
                                    class="w-full text-xs text-center border-collapse border border-zinc-200 dark:border-zinc-800">
                                    <thead
                                        class="bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white font-black sticky top-0 z-20">
                                        <tr>
                                            <th rowspan="2"
                                                class="border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 w-16">
                                            </th>
                                            @php $periodIdx = 1; @endphp
                                            @foreach($headerSlots ?? [] as $slot)
                                                <th
                                                    class="border border-zinc-200 dark:border-zinc-700 px-2 py-3 text-lg font-black text-metronic-primary">
                                                    @if(!$slot->is_break)
                                                        {{ $periodIdx++ }}
                                                    @endif
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
                                                @php $dayBlocks = $calendarData[$dayNum] ?? []; @endphp
                                                @foreach($dayBlocks as $block)
                                                    <td colspan="{{ $block['span'] }}"
                                                        class="border border-zinc-200 dark:border-zinc-700 p-1 relative min-w-[120px] {{ $block['type'] === 'break' ? 'bg-zinc-100/40 dark:bg-zinc-800/40' : '' }}">
                                                        @if($block['type'] === 'break')
                                                            <div class="flex items-center justify-center p-2">
                                                                <span
                                                                    class="text-[9px] font-black uppercase tracking-widest text-zinc-400 rotate-90 whitespace-nowrap">{{ $block['name'] }}</span>
                                                            </div>
                                                        @elseif($block['type'] === 'subject')
                                                            <div class="flex flex-col gap-1.5 h-full">
                                                                @foreach($block['items'] as $item)
                                                                    <div wire:click="openEditModal({{ $item->id }})"
                                                                        class="p-2 rounded-lg border bg-white dark:bg-zinc-900 border-zinc-200 dark:border-zinc-800 shadow-sm cursor-pointer hover:border-metronic-primary transition-all group relative">
                                                                        <div wire:loading wire:target="openEditModal({{ $item->id }})"
                                                                            class="absolute inset-0 z-10 bg-white/40 dark:bg-zinc-900/40 backdrop-blur-[1px] flex items-center justify-center rounded-lg">
                                                                            <flux:icon.loading class="w-4 h-4 text-metronic-primary" />
                                                                        </div>
                                                                        <div
                                                                            class="font-bold text-zinc-900 dark:text-zinc-100 text-[11px] leading-tight mb-1">
                                                                            {{ $item->subject->name }}
                                                                        </div>
                                                                        <div class="text-[9px] text-zinc-500 truncate mb-2">
                                                                            {{ $item->teacher->name }}
                                                                        </div>
                                                                        <div class="flex gap-1 justify-center">
                                                                            <span
                                                                                class="px-1 py-0.5 rounded bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-100 dark:border-green-800 text-[8px] font-bold">R.{{ preg_replace('/[^0-9]/', '', $item->room->name) ?: $item->room->name }}</span>
                                                                            <span
                                                                                class="px-1 py-0.5 rounded bg-zinc-50 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 border border-zinc-100 dark:border-zinc-700 text-[8px] font-bold">{{ $item->academicClass->name }}</span>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @elseif($block['type'] === 'empty')
                                                            <div wire:click="openAddModal({{ $block['id'] }})"
                                                                class="w-full h-full min-h-[100px] cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800/20 group flex items-center justify-center transition-colors relative">
                                                                <div wire:loading wire:target="openAddModal({{ $block['id'] }})"
                                                                    class="absolute inset-0 z-10 flex items-center justify-center bg-zinc-50/40 dark:bg-zinc-800/20">
                                                                    <flux:icon.loading class="w-4 h-4 text-metronic-primary" />
                                                                </div>
                                                                <flux:icon.plus
                                                                    class="w-4 h-4 text-zinc-300 opacity-0 group-hover:opacity-100 transition-opacity" />
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
                            <flux:button wire:click="publishSchedule" variant="primary" class="w-full"
                                wire:loading.attr="disabled">
                                <flux:icon name="cloud-arrow-up" variant="mini" class="mr-2" wire:loading.remove
                                    wire:target="publishSchedule" />
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
                            <flux:button wire:click="lockSchedule" variant="filled" class="w-full"
                                wire:loading.attr="disabled">
                                <flux:icon name="lock-closed" variant="mini" class="mr-2" wire:loading.remove
                                    wire:target="lockSchedule" />
                                <flux:icon.loading class="mr-2" wire:loading wire:target="lockSchedule" />
                                Finalize & Lock
                            </flux:button>
                        </div>
                    </div>

                    @if(count($draftSchedules) > 0 || $previewFilterClass || $previewFilterTeacher)
                        <div class="mt-8 pt-8 border-t border-zinc-100 dark:border-zinc-800">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h4 class="text-base font-bold text-zinc-900 dark:text-white">Active Schedule View</h4>
                                    <p class="text-xs text-zinc-500">Overview of the currently active/locked schedules.</p>
                                </div>
                            </div>

                            <div class="mb-6 flex flex-col sm:flex-row gap-4">
                                <flux:select wire:model.live="previewFilterClass" placeholder="Filter by Class" class="flex-1">
                                    <flux:select.option value="">All Classes</flux:select.option>
                                    @foreach($classList ?? collect() as $c)
                                        <flux:select.option value="{{ $c->id }}">{{ $c->name }}</flux:select.option>
                                    @endforeach
                                </flux:select>

                                <flux:select wire:model.live="previewFilterTeacher" placeholder="Filter by Teacher"
                                    class="flex-1">
                                    <flux:select.option value="">All Teachers</flux:select.option>
                                    @foreach($teachers ?? collect() as $t)
                                        <flux:select.option value="{{ $t->id }}">{{ $t->name }}</flux:select.option>
                                    @endforeach
                                </flux:select>
                            </div>

                            <div class="relative overflow-hidden group">
                                <div
                                    class="overflow-x-auto border border-zinc-200 dark:border-zinc-800 rounded-xl bg-zinc-50/30 style-scrollbar">
                                    @php
                                        $dayShortLabels = \App\Constants\AcademicConstants::DAY_SHORT_LABELS;
                                        $headerSlots = \App\Models\TimeSlot::where('day', 1)->orderBy('start_time')->get();
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
                                                        class="border border-zinc-200 dark:border-zinc-700 px-2 py-4 text-lg font-black text-metronic-primary">
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
                                                            class="border border-zinc-200 dark:border-zinc-700 p-1 relative min-w-[120px] {{ $block['type'] === 'break' ? 'bg-zinc-50/30' : '' }}">
                                                            @if($block['type'] === 'break')
                                                                <div class="flex flex-col items-center justify-center p-2 h-full">
                                                                    <span
                                                                        class="text-[9px] font-bold uppercase tracking-[0.3em] text-zinc-300 dark:text-zinc-600 -rotate-180 [writing-mode:vertical-lr] whitespace-nowrap">
                                                                        {{ $block['name'] }}
                                                                    </span>
                                                                </div>
                                                            @elseif($block['type'] === 'subject')
                                                                <div class="flex flex-col gap-1.5 p-1 h-full justify-center">
                                                                    @foreach($block['items'] as $item)
                                                                        <div
                                                                            class="bg-white dark:bg-zinc-900 border border-zinc-100 dark:border-zinc-800 rounded-lg p-2 shadow-sm flex flex-col items-center text-center">
                                                                            <div
                                                                                class="text-[10px] font-bold text-zinc-900 dark:text-zinc-100 mb-0.5 leading-tight uppercase">
                                                                                {{ $item->subject->name }}
                                                                            </div>
                                                                            <div
                                                                                class="text-[8px] text-zinc-400 dark:text-zinc-500 mb-2 font-medium">
                                                                                {{ $item->teacher->name }}
                                                                            </div>
                                                                            <div class="flex items-center gap-1 mt-auto">
                                                                                <div
                                                                                    class="px-1 py-0.5 rounded border border-green-500/20 bg-green-50 text-green-600 text-[8px] font-black uppercase">
                                                                                    R.{{ preg_replace('/[^0-9]/', '', $item->room->name) ?: $item->room->name }}
                                                                                </div>
                                                                                <div
                                                                                    class="text-[8px] font-bold text-zinc-500 uppercase tracking-tighter">
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
                            </div>
                        </div>
                    @else
                        <div
                            class="mt-8 p-12 text-center bg-zinc-50 dark:bg-zinc-800/50 rounded-2xl border-2 border-dashed border-zinc-200 dark:border-zinc-700">
                            <flux:icon.document-text class="mx-auto h-12 w-12 text-zinc-400 mb-4" />
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">No Final Schedule Yet</h3>
                            <p class="text-sm text-zinc-500 max-w-xs mx-auto mt-2">
                                Complete the simulation and publish the results to see the final schedule here.
                            </p>
                        </div>
                    @endif
                </x-ui.card>
            @endif
        </div>
    </div>
</div>
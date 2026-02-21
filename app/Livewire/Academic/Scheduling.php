<?php

namespace App\Livewire\Academic;

use App\Models\AcademicYear;
use App\Models\Teacher;
use App\Repositories\Contracts\AcademicYearRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use App\Repositories\Contracts\ScheduleRuleRepositoryInterface;
use App\Repositories\Contracts\TeacherRepositoryInterface;
use App\Services\SchedulingEngine;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Auto Scheduling')]
class Scheduling extends Component
{
    public $activeYearId;
    public $globalMinHours = 18;
    public $globalMaxHours = 24;
    public $activeTab = 'config'; // config, simulate, publish
    public $useDynamicRooms = false;
    public $periodDuration = 45;
    public $entryTime = '07:00';
    public $exitTime = '13:30';
    public $daySpecificConfig = [
        1 => ['entry' => '07:00', 'exit' => '13:30'],
        2 => ['entry' => '07:00', 'exit' => '13:30'],
        3 => ['entry' => '07:00', 'exit' => '13:30'],
        4 => ['entry' => '07:00', 'exit' => '13:30'],
        5 => ['entry' => '07:00', 'exit' => '11:00'], // Jumat lebih awal
    ];
    public $breakTimes = []; // [['start' => '09:15', 'end' => '10:00']]

    public $showOverrideModal = false;
    public $editingTeacherId = null;
    public $editingTeacherName = '';
    public $editingMinHours = null;
    public $editingMaxHours = null;
    public $days = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday'];

    // Filters for Preview
    public $previewFilterClass = null;
    public $previewFilterTeacher = null;

    protected $academicYearRepository;
    protected $scheduleRepository;
    protected $scheduleRuleRepository;
    protected $teacherRepository;

    public function boot(
        AcademicYearRepositoryInterface $academicYearRepository,
        ScheduleRepositoryInterface $scheduleRepository,
        ScheduleRuleRepositoryInterface $scheduleRuleRepository,
        TeacherRepositoryInterface $teacherRepository
    ) {
        $this->academicYearRepository = $academicYearRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->scheduleRuleRepository = $scheduleRuleRepository;
        $this->teacherRepository = $teacherRepository;
    }

    public function mount()
    {
        $activeYear = $this->academicYearRepository->getActiveYear();
        if ($activeYear) {
            $this->activeYearId = $activeYear->id;
            $this->loadRules();
            $this->loadBreakTimes();
        }
    }

    public function loadRules()
    {
        $rules = $this->scheduleRuleRepository->getGlobalRules($this->activeYearId);
        $min = $rules->where('rule_type', 'global_min_hours')->first();
        $max = $rules->where('rule_type', 'global_max_hours')->first();

        if ($min) $this->globalMinHours = $min->value;
        if ($max) $this->globalMaxHours = $max->value;
    }

    public function loadBreakTimes()
    {
        // For simplicity, we assume breaks are the same across all days
        // We get unique break time ranges from Monday
        $breaks = \App\Models\TimeSlot::where('day', 1)
            ->where('is_break', true)
            ->orderBy('start_time')
            ->get();

        $this->breakTimes = $breaks->map(function ($slot) {
            return [
                'start' => substr($slot->start_time, 0, 5),
                'end' => substr($slot->end_time, 0, 5),
            ];
        })->toArray();

        if (empty($this->breakTimes)) {
            $this->breakTimes = [['start' => '09:15', 'end' => '10:00']];
        }
    }

    public function addBreakTime()
    {
        $this->breakTimes[] = ['start' => '', 'end' => ''];
    }

    public function removeBreakTime($index)
    {
        unset($this->breakTimes[$index]);
        $this->breakTimes = array_values($this->breakTimes);
    }

    public function saveBreakTimes()
    {
        // Update all TimeSlots across all days
        // First, reset all is_break to false
        \App\Models\TimeSlot::query()->update(['is_break' => false]);

        foreach ($this->breakTimes as $break) {
            if (empty($break['start']) || empty($break['end'])) continue;

            // Mark slots that exactly match or overlap
            \App\Models\TimeSlot::where('start_time', '>=', $break['start'] . ':00')
                ->where('end_time', '<=', $break['end'] . ':00')
                ->update(['is_break' => true]);
        }

        $this->dispatch('notify', text: 'Break times updated successfully.', variant: 'success');
    }

    public function regenerateTimeSlots()
    {
        \App\Models\TimeSlot::truncate();

        foreach ($this->days as $dayNum => $dayName) {
            $config = $this->daySpecificConfig[$dayNum] ?? ['entry' => $this->entryTime, 'exit' => $this->exitTime];
            $currentStartTime = \Carbon\Carbon::createFromFormat('H:i', $config['entry']);
            $finalExitTime = \Carbon\Carbon::createFromFormat('H:i', $config['exit']);

            while ($currentStartTime->copy()->addMinutes($this->periodDuration)->lte($finalExitTime)) {
                $endTime = $currentStartTime->copy()->addMinutes($this->periodDuration);
                
                // Check if this slot overlaps with any break
                $isBreak = false;
                $breakName = null;
                foreach ($this->breakTimes as $break) {
                    $bStart = \Carbon\Carbon::createFromFormat('H:i', $break['start']);
                    $bEnd = \Carbon\Carbon::createFromFormat('H:i', $break['end']);
                    
                    if ($currentStartTime->between($bStart, $bEnd, false) || $endTime->between($bStart, $bEnd, false)) {
                        $isBreak = true;
                        $breakName = $break['name'] ?? 'Istirahat';
                        break;
                    }
                }

                \App\Models\TimeSlot::create([
                    'day' => $dayNum,
                    'start_time' => $currentStartTime->format('H:i:s'),
                    'end_time' => $endTime->format('H:i:s'),
                    'is_break' => $isBreak,
                    'name' => $isBreak ? $breakName : "Jam Ke",
                ]);

                $currentStartTime->addMinutes($this->periodDuration);
            }
        }

        $this->activeTab = 'config';
        $this->dispatch('notify', text: 'Time slots regenerated based on day-specific config.', variant: 'success');
    }

    public function updatedActiveTab($value)
    {
        // Data loading handled in render
    }

    public function saveGlobalRules()
    {
        $this->scheduleRuleRepository->updateGlobalRule($this->activeYearId, 'global_min_hours', $this->globalMinHours);
        $this->scheduleRuleRepository->updateGlobalRule($this->activeYearId, 'global_max_hours', $this->globalMaxHours);
        
        $this->dispatch('notify', text: 'Global rules saved successfully.', variant: 'success');
    }

    public function generateSchedule()
    {
        $year = AcademicYear::find($this->activeYearId);
        $engine = new SchedulingEngine($year, $this->scheduleRepository, $this->useDynamicRooms);
        $count = $engine->generate();

        $this->dispatch('notify', text: "Successfully generated $count schedule slots in draft.", variant: 'success');
        $this->activeTab = 'simulate';
    }

    public function publishSchedule()
    {
        $this->scheduleRepository->publish($this->activeYearId);
        $this->dispatch('notify', text: 'Schedules published successfully.', variant: 'success');
    }

    public function editTeacher($id, $name, $minHours, $maxHours)
    {
        $this->editingTeacherId = $id;
        $this->editingTeacherName = $name;
        $this->editingMinHours = $minHours ?? $this->globalMinHours;
        $this->editingMaxHours = $maxHours ?? $this->globalMaxHours;
        $this->showOverrideModal = true;
    }

    public function saveTeacherConfig()
    {
        $this->teacherRepository->updateConfig($this->editingTeacherId, [
            'academic_year_id' => $this->activeYearId,
            'min_hours_per_week' => $this->editingMinHours,
            'max_hours_per_week' => $this->editingMaxHours,
        ]);
        
        $this->showOverrideModal = false;
        $this->dispatch('notify', text: 'Teacher override saved successfully.', variant: 'success');
    }

    public function lockSchedule()
    {
        $this->scheduleRepository->lock($this->activeYearId);
        $this->dispatch('notify', text: 'Schedules locked successfully.', variant: 'success');
    }

    public function render()
    {
        $drafts = collect();
        $calendarData = [];
        $timeSlots = collect();
        $days = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday'];

        if ($this->activeTab === 'simulate' && $this->activeYearId) {
            $query = \App\Models\Schedule::with(['academicClass', 'teacher', 'subject', 'room', 'timeSlot'])
                ->where('academic_year_id', $this->activeYearId)
                ->where('status', 'draft');

            if ($this->previewFilterClass) {
                $query->where('class_id', $this->previewFilterClass);
            }

            if ($this->previewFilterTeacher) {
                $query->where('teacher_id', $this->previewFilterTeacher);
            }

            $drafts = $query->orderBy('day')->orderBy('time_slot_id')->get();
            $allTimeSlots = \App\Models\TimeSlot::orderBy('start_time')->get();
            $uniqueTimeRanges = $allTimeSlots->groupBy(function($slot) {
                return substr($slot->start_time, 0, 5) . '-' . substr($slot->end_time, 0, 5);
            })->keys()->toArray();

            // Structure data: $calendarData[day] = [blocks...]
            // We'll use Monday (day 1) as reference for time slots structure
            $refSlots = \App\Models\TimeSlot::where('day', 1)->orderBy('start_time')->get();
            
            foreach ($days as $dayNum => $dayName) {
                $calendarData[$dayNum] = [];
                $processedIndices = [];
                $daySlots = \App\Models\TimeSlot::where('day', $dayNum)->orderBy('start_time')->get()->values();
                $breakCount = 0;

                for ($i = 0; $i < $daySlots->count(); $i++) {
                    if (in_array($i, $processedIndices)) continue;

                    $currentSlot = $daySlots[$i];
                    $items = $drafts->where('day', $dayNum)->where('time_slot_id', $currentSlot->id);

                    if ($currentSlot->is_break) {
                        $breakCount++;
                        $span = 1;
                        for ($j = $i + 1; $j < $daySlots->count(); $j++) {
                            if ($daySlots[$j]->is_break) {
                                $span++;
                                $processedIndices[] = $j;
                            } else { break; }
                        }
                        $calendarData[$dayNum][] = [
                            'type' => 'break',
                            'name' => 'Istirahat ' . $breakCount,
                            'span' => $span
                        ];
                        $processedIndices[] = $i;
                        continue;
                    }

                    if ($items->isEmpty()) {
                        $calendarData[$dayNum][] = [
                            'type' => 'empty',
                            'span' => 1
                        ];
                        $processedIndices[] = $i;
                        continue;
                    }

                    $span = 1;
                    if ($items->count() === 1) {
                        $item = $items->first();
                        for ($j = $i + 1; $j < $daySlots->count(); $j++) {
                            $nextSlot = $daySlots[$j];
                            if ($nextSlot->is_break) break;
                            $nextItems = $drafts->where('day', $dayNum)->where('time_slot_id', $nextSlot->id);
                            if ($nextItems->count() === 1) {
                                $nextItem = $nextItems->first();
                                if ($nextItem->subject_id === $item->subject_id && 
                                    $nextItem->class_id === $item->class_id &&
                                    $nextItem->teacher_id === $item->teacher_id) {
                                    $span++;
                                    $processedIndices[] = $j;
                                } else { break; }
                            } else { break; }
                        }
                    }

                    $calendarData[$dayNum][] = [
                        'type' => 'subject',
                        'items' => $items,
                        'span' => $span
                    ];
                    $processedIndices[] = $i;
                }
            }
        }

        return view('livewire.academic.scheduling', [
            'academicYears' => $this->academicYearRepository->all(),
            'teachers' => Teacher::with(['config' => function($q) {
                $q->where('academic_year_id', $this->activeYearId);
            }])->get(),
            'classes' => \App\Models\AcademicClass::all(),
            'draftSchedules' => $drafts,
            'calendarData' => $calendarData,
            'timeSlots' => $timeSlots,
            'days' => $days,
        ]);
    }
}

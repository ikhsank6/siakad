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

    public $showOverrideModal = false;
    public $editingTeacherId = null;
    public $editingTeacherName = '';
    public $editingMinHours = null;
    public $editingMaxHours = null;

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
            
            // Group time slots by time range
            $timeSlots = $allTimeSlots->groupBy(function($slot) {
                return substr($slot->start_time, 0, 5) . '-' . substr($slot->end_time, 0, 5);
            });

            // Structure data: $calendarData[day][time_key] = [schedules...]
            foreach ($days as $dayNum => $dayName) {
                $calendarData[$dayNum] = [];
                foreach ($timeSlots as $timeKey => $slotsInGroup) {
                    $slotIds = $slotsInGroup->pluck('id')->toArray();
                    $calendarData[$dayNum][$timeKey] = [
                        'items' => $drafts->where('day', $dayNum)->whereIn('time_slot_id', $slotIds),
                        'is_break' => $slotsInGroup->first()->is_break,
                        'name' => $slotsInGroup->first()->name,
                        'start_time' => $slotsInGroup->first()->start_time,
                        'end_time' => $slotsInGroup->first()->end_time,
                    ];
                }
            }
        }

        return view('livewire.academic.scheduling', [
            'academicYears' => $this->academicYearRepository->all(),
            'teachers' => Teacher::with(['config' => function($q) {
                $q->where('academic_year_id', $this->activeYearId);
            }])->get(),
            'classes' => \App\Models\AcademicClass::where('academic_year_id', $this->activeYearId)->get(),
            'draftSchedules' => $drafts,
            'calendarData' => $calendarData,
            'timeSlots' => $timeSlots,
            'days' => $days,
        ]);
    }
}

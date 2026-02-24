<?php

namespace App\Livewire\Academic;

use App\Repositories\Contracts\AcademicYearRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use App\Repositories\Contracts\ScheduleRuleRepositoryInterface;
use App\Repositories\Contracts\TeacherRepositoryInterface;
use App\Repositories\Contracts\AcademicClassRepositoryInterface;
use App\Repositories\Contracts\SubjectRepositoryInterface;
use App\Repositories\Contracts\RoomRepositoryInterface;
use App\Repositories\Contracts\TimeSlotRepositoryInterface;
use App\Services\SchedulingEngine;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
#[Layout('components.layouts.app')]
#[Title('Auto Scheduling')]
class Scheduling extends Component implements HasForms
{
    use InteractsWithForms;
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
    public $days;

    // Filters for Preview
    public $previewFilterClass = null;
    public $previewFilterTeacher = null;

    // Manual Entry
    public $showManualModal = false;
    public $selectedTimeSlotId = null;
    public $editingScheduleId = null;
    public ?array $manualData = [];

    public $classList;
    public $subjectList;
    public $roomList;

    protected $academicYearRepository;
    protected $scheduleRepository;
    protected $scheduleRuleRepository;
    protected $teacherRepository;
    protected $academicClassRepository;
    protected $subjectRepository;
    protected $roomRepository;
    protected $timeSlotRepository;

    public function boot(
        AcademicYearRepositoryInterface $academicYearRepository,
        ScheduleRepositoryInterface $scheduleRepository,
        ScheduleRuleRepositoryInterface $scheduleRuleRepository,
        TeacherRepositoryInterface $teacherRepository,
        AcademicClassRepositoryInterface $academicClassRepository,
        SubjectRepositoryInterface $subjectRepository,
        RoomRepositoryInterface $roomRepository,
        TimeSlotRepositoryInterface $timeSlotRepository
    ) {
        $this->academicYearRepository = $academicYearRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->scheduleRuleRepository = $scheduleRuleRepository;
        $this->teacherRepository = $teacherRepository;
        $this->academicClassRepository = $academicClassRepository;
        $this->subjectRepository = $subjectRepository;
        $this->roomRepository = $roomRepository;
        $this->timeSlotRepository = $timeSlotRepository;

        $this->days = \App\Constants\AcademicConstants::DAYS;
        $this->classList = $this->academicClassRepository->all();
        $this->subjectList = $this->subjectRepository->all();
        $this->roomList = $this->roomRepository->all();
    }

    public function mount()
    {
        $activeYear = $this->academicYearRepository->getActiveYear();
        if ($activeYear) {
            $this->activeYearId = $activeYear->id;
            $this->loadRules();
            $this->loadBreakTimes();
        }

        $this->manualData = [
            'class_id' => null,
            'subject_id' => null,
            'teacher_id' => null,
            'room_id' => null,
        ];
        $this->form->fill();
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
        $breaks = $this->timeSlotRepository->findBy(['day' => 1, 'is_break' => true]);

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
        $this->timeSlotRepository->updateBreaks($this->breakTimes);
        $this->dispatch('notify', text: 'Break times updated successfully.', variant: 'success');
    }

    public function regenerateTimeSlots()
    {
        $this->timeSlotRepository->truncate();

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

                $this->timeSlotRepository->create([
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
        $year = $this->academicYearRepository->find($this->activeYearId);
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('class_id')
                    ->label('Class')
                    ->placeholder('Select Class')
                    ->options($this->academicClassRepository->all()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                Select::make('subject_id')
                    ->label('Subject')
                    ->placeholder('Select Subject')
                    ->options($this->subjectList->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn ($set) => $set('teacher_id', null)),
                Select::make('teacher_id')
                    ->label('Teacher')
                    ->placeholder('Select Teacher')
                    ->options(function (Get $get) {
                        $subjectId = $get('subject_id');
                        
                        if (! $subjectId) return [];

                        return $this->teacherRepository->getTeachersBySubject($subjectId);
                    })
                    ->required()
                    ->native(false)
                    ->live(),
                Select::make('room_id')
                    ->label('Room')
                    ->placeholder('Select Room')
                    ->options($this->roomRepository->all()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
            ])
            ->statePath('manualData');
    }

    public function openAddModal($timeSlotId)
    {
        $this->resetManualForm();
        $this->selectedTimeSlotId = $timeSlotId;
        $this->form->fill();
        $this->showManualModal = true;
    }

    public function openEditModal($scheduleId)
    {
        $this->resetManualForm();
        $schedule = $this->scheduleRepository->find($scheduleId);
        if ($schedule) {
            $this->editingScheduleId = $schedule->id;
            $this->selectedTimeSlotId = $schedule->time_slot_id;
            $this->form->fill($schedule->toArray());
            $this->showManualModal = true;
        }
    }

    public function resetManualForm()
    {
        $this->editingScheduleId = null;
        $this->selectedTimeSlotId = null;
        $this->manualData = [
            'class_id' => null,
            'subject_id' => null,
            'teacher_id' => null,
            'room_id' => null,
        ];
        $this->form->fill();
        $this->resetValidation();
    }

    public function saveManualSchedule()
    {
        $data = $this->form->getState();
        
        $slot = $this->timeSlotRepository->find($this->selectedTimeSlotId);
        if (!$slot) return;

        $scheduleData = array_merge($data, [
            'academic_year_id' => $this->activeYearId,
            'day' => $slot->day,
            'time_slot_id' => $this->selectedTimeSlotId,
            'status' => 'draft',
        ]);

        if ($this->editingScheduleId) {
            $this->scheduleRepository->update($this->editingScheduleId, $scheduleData);
        } else {
            $this->scheduleRepository->create($scheduleData);
        }

        $this->showManualModal = false;
        $this->dispatch('notify', text: 'Schedule saved manually.', variant: 'success');
    }

    public function deleteSchedule($id)
    {
        $this->scheduleRepository->delete($id);
        $this->showManualModal = false;
        $this->dispatch('notify', text: 'Schedule deleted.', variant: 'success');
    }

    public function render()
    {
        $drafts = collect();
        $calendarData = [];
        $timeSlots = collect();
        $days = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday'];

        if (in_array($this->activeTab, ['simulate', 'publish']) && $this->activeYearId) {
            $status = $this->activeTab === 'simulate' ? ['draft'] : ['published', 'locked'];
            
            $filters = [];
            if ($this->previewFilterClass) {
                $filters['class_id'] = $this->previewFilterClass;
            }
            if ($this->previewFilterTeacher) {
                $filters['teacher_id'] = $this->previewFilterTeacher;
            }

            $drafts = $this->scheduleRepository->getSchedulesWithRelations($this->activeYearId, $status, $filters);
            $allTimeSlots = $this->timeSlotRepository->all();
            $uniqueTimeRanges = $allTimeSlots->sortBy('start_time')->groupBy(function($slot) {
                return substr($slot->start_time, 0, 5) . '-' . substr($slot->end_time, 0, 5);
            })->keys()->toArray();

            // Structure data: $calendarData[day] = [blocks...]
            // We'll use Monday (day 1) as reference for time slots structure
            $refSlots = $this->timeSlotRepository->getByDay(1);
            
            foreach ($days as $dayNum => $dayName) {
                $calendarData[$dayNum] = [];
                $processedIndices = [];
                $daySlots = $this->timeSlotRepository->getByDay($dayNum)->values();
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
                            'id' => $currentSlot->id,
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
            'teachers' => $this->teacherRepository->getTeachersWithConfig($this->activeYearId ?? 0),
            'draftSchedules' => $drafts,
            'calendarData' => $calendarData,
            'timeSlots' => $timeSlots,
            'days' => $days,
        ]);
    }
}

<?php

namespace App\Livewire\Academic;

use App\Constants\AcademicConstants;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Teacher;
use App\Models\TimeSlot;
use App\Repositories\Contracts\AcademicYearRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use App\Repositories\Contracts\TeacherRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('My Schedule')]
class MySchedule extends Component
{
    public $activeYearId;
    public $filterClassId = '';
    public $filterTeacherId = '';
    public $isTeacher = false;

    protected $academicYearRepository;
    protected $scheduleRepository;
    protected $teacherRepository;

    public function boot(
        AcademicYearRepositoryInterface $academicYearRepository,
        ScheduleRepositoryInterface $scheduleRepository,
        TeacherRepositoryInterface $teacherRepository
    ) {
        $this->academicYearRepository = $academicYearRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->teacherRepository = $teacherRepository;
    }

    public function mount()
    {
        $activeYear = $this->academicYearRepository->getActiveYear();
        if ($activeYear) {
            $this->activeYearId = $activeYear->id;
        }

        $teacher = $this->teacherRepository->findByUserId(Auth::id());
        if ($teacher) {
            $this->filterTeacherId = $teacher->id;
            $this->isTeacher = true;
        }
    }

    public function render()
    {
        $days = AcademicConstants::DAYS;
        $calendarData = [];
        $headerSlots = collect();
        $classes = AcademicClass::all();
        $teachers = Teacher::all();

        if ($this->activeYearId) {
            $query = \App\Models\Schedule::with(['academicClass', 'teacher', 'subject', 'room', 'timeSlot'])
                ->where('academic_year_id', $this->activeYearId)
                ->whereIn('status', [AcademicConstants::SCHEDULE_STATUS_PUBLISHED, AcademicConstants::SCHEDULE_STATUS_LOCKED]);

            if ($this->filterClassId) {
                $query->where('class_id', $this->filterClassId);
            }

            if ($this->filterTeacherId) {
                $query->where('teacher_id', $this->filterTeacherId);
            }

            $schedules = $query->get();
            $headerSlots = TimeSlot::where('day', 1)->orderBy('start_time')->get();

            foreach ($days as $dayNum => $dayName) {
                $calendarData[$dayNum] = [];
                $processedIndices = [];
                $daySlots = TimeSlot::where('day', $dayNum)->orderBy('start_time')->get()->values();

                for ($i = 0; $i < $daySlots->count(); $i++) {
                    if (in_array($i, $processedIndices)) continue;

                    $currentSlot = $daySlots[$i];
                    $items = $schedules->where('time_slot_id', $currentSlot->id);

                    if ($currentSlot->is_break) {
                        $span = 1;
                        for ($j = $i + 1; $j < $daySlots->count(); $j++) {
                            if ($daySlots[$j]->is_break) {
                                $span++;
                                $processedIndices[] = $j;
                            } else { break; }
                        }
                        $calendarData[$dayNum][] = [
                            'type' => 'break',
                            'name' => $currentSlot->name ?? 'Istirahat',
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

                    // For the overall view, we show all subjects in that slot
                    $calendarData[$dayNum][] = [
                        'type' => 'subject',
                        'items' => $items,
                        'span' => 1 // Spanning overall is complex, kept simple per slot
                    ];
                    $processedIndices[] = $i;
                }
            }
        }

        return view('livewire.academic.my-schedule', [
            'calendarData' => $calendarData,
            'headerSlots' => $headerSlots,
            'days' => $days,
            'classes' => $classes,
            'teachers' => $teachers,
        ]);
    }
}

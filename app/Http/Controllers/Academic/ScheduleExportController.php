<?php

namespace App\Http\Controllers\Academic;

use App\Constants\AcademicConstants;
use App\Http\Controllers\Controller;
use App\Models\TimeSlot;
use App\Repositories\Contracts\AcademicYearRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use App\Repositories\Contracts\TeacherRepositoryInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleExportController extends Controller
{
    protected $academicYearRepository;
    protected $scheduleRepository;
    protected $teacherRepository;

    public function __construct(
        AcademicYearRepositoryInterface $academicYearRepository,
        ScheduleRepositoryInterface $scheduleRepository,
        TeacherRepositoryInterface $teacherRepository
    ) {
        $this->academicYearRepository = $academicYearRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->teacherRepository = $teacherRepository;
    }

    public function exportTeacherSchedule(Request $request)
    {
        $activeYear = $this->academicYearRepository->getActiveYear();
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Active academic year not found.');
        }

        $filters = [];
        $title = "Academic Schedule";

        // If no explicit filters, default to current teacher if available
        if (!$request->has('teacher_id') && !$request->has('class_id')) {
            $teacher = $this->teacherRepository->findByUserId(Auth::id());
            if ($teacher) {
                $filters['teacher_id'] = $teacher->id;
                $title = "Schedule - {$teacher->name}";
            }
        } else {
            $filters['teacher_id'] = $request->query('teacher_id');
            $filters['class_id'] = $request->query('class_id');
            
            if ($filters['teacher_id']) {
                $teacher = \App\Models\Teacher::find($filters['teacher_id']);
                if ($teacher) $title = "Schedule - {$teacher->name}";
            } elseif ($filters['class_id']) {
                $class = \App\Models\AcademicClass::find($filters['class_id']);
                if ($class) $title = "Schedule - {$class->name}";
            }
        }

        $schedules = $this->scheduleRepository->getFilteredSchedules($activeYear->id, $filters);
        $days = AcademicConstants::DAYS;
        $calendarData = [];
        
        $maxDay = 5; 
        $headerSlots = TimeSlot::where('day', 1)->orderBy('start_time')->get();

        foreach ($days as $dayNum => $dayName) {
            if ($dayNum > $maxDay) continue; 
            
            $calendarData[$dayNum] = [];
            $processedIndices = [];
            $daySlots = TimeSlot::where('day', $dayNum)->orderBy('start_time')->get()->values();

            if ($daySlots->isEmpty()) continue;

            for ($i = 0; $i < $daySlots->count(); $i++) {
                if (in_array($i, $processedIndices)) continue;

                $currentSlot = $daySlots[$i];
                $items = $schedules->where('time_slot_id', $currentSlot->id);

                if ($currentSlot->is_break) {
                    $calendarData[$dayNum][] = [
                        'type' => 'break',
                        'name' => $currentSlot->name ?? 'Istirahat',
                        'span' => 1
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

                // Support multiple items per slot (plural) to align with global view
                $calendarData[$dayNum][] = [
                    'type' => 'subject',
                    'items' => $items,
                    'span' => 1 
                ];
                $processedIndices[] = $i;
            }
        }

        $pdf = Pdf::loadView('pdf.teacher-schedule', [
            'calendarData' => $calendarData,
            'headerSlots' => $headerSlots,
            'days' => $days,
            'maxDay' => $maxDay,
            'title' => $title,
            'activeYear' => $activeYear
        ])->setPaper('a4', 'landscape');

        $filename = str_replace(' ', '_', $title) . ".pdf";
        return $pdf->download($filename);
    }
}

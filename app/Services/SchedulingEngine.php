<?php

namespace App\Services;

use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\ClassSubject;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\ScheduleRule;
use App\Models\Teacher;
use App\Models\TeacherConfig;
use App\Models\TimeSlot;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SchedulingEngine
{
    protected $academicYear;
    protected $timeSlots;
    protected $rooms;
    protected $teachers;
    protected $classes;
    protected $globalRules;
    protected $results = [];
    protected $busyTeachers = [];
    protected $busyClasses = [];
    protected $busyRooms = [];
    protected $teacherHours = [];
    protected $classRoomMap = []; // Maps class_id to fixed room_id
    protected $useDynamicRooms = false;

    protected $scheduleRepository;

    public function __construct(AcademicYear $academicYear, ?\App\Repositories\Contracts\ScheduleRepositoryInterface $scheduleRepository = null, bool $useDynamicRooms = false)
    {
        $this->academicYear = $academicYear;
        $this->scheduleRepository = $scheduleRepository ?? app(\App\Repositories\Contracts\ScheduleRepositoryInterface::class);
        $this->useDynamicRooms = $useDynamicRooms;
    }

    public function generate()
    {
        $this->loadData();
        
        // Use repository to clear draft schedules
        $this->scheduleRepository->clearDrafts($this->academicYear->id);

        // 1. Get all subject requirements per class (assuming global or single active year context for prototype)
        $requirements = ClassSubject::all();

        // 2. Sort requirements by hours (most hours first) and teacher priority
        $requirements = $requirements->sortBy(function($req) {
            $teacherId = $req->teacher_id;
            $currentHours = $this->teacherHours[$teacherId] ?? 0;
            $minHours = $this->getTeacherMinHours($teacherId);
            
            // Prioritize higher hours needed AND higher deficit
            // We return a score. Higher score = earlier placement.
            // Using a negative value because sortBy is ascending.
            return -($req->hours_per_week * 100 + ($minHours - $currentHours));
        });

        $scheduledItems = [];

        foreach ($requirements as $req) {
            $hoursNeeded = $req->hours_per_week;
            $classId = $req->class_id;
            $subjectId = $req->subject_id;
            $teacherId = $req->teacher_id;

            if (!$teacherId) {
                $teacherId = $this->findTeacherForSubject($subjectId, $hoursNeeded);
            }

            if (!$teacherId) continue;

            $remainingHours = $hoursNeeded;
            
            // Try to place hours in blocks
            // For block scheduling, we prefer fewer, longer sessions
            // If hours <= 4, try to place in 1 block.
            // If hours > 4, maybe 2 blocks.
            $maxBlockSize = min($remainingHours, 4); // Max 4 consecutive hours per block for variety
            
            // Shuffle days to distribute subjects across the week
            $dayOrder = [1, 2, 3, 4, 5, 6];
            shuffle($dayOrder);

            foreach ($dayOrder as $day) {
                if ($remainingHours <= 0) break;

                $daySlots = $this->timeSlots->where('day', $day)->where('is_break', false)->values();
                if ($daySlots->isEmpty()) continue;

                // Subjects already in this day for this class
                $subjectsInDayIds = collect($scheduledItems)
                    ->where('class_id', $classId)
                    ->where('day', $day)
                    ->pluck('subject_id')
                    ->unique();
                
                $subjectsInDayCount = $subjectsInDayIds->count();

                // If this subject is already in this day, we can continue adding to it (though rare in block scheduling)
                // or if we reached the limit of 3-4 subjects per day, stop.
                if ($subjectsInDayCount >= 4 && !$subjectsInDayIds->contains($subjectId)) continue; 

                // Target size: ideally the entire remaining hours for this subject
                // but capped at a sensible day-limit (e.g. 5 hours)
                $maxPossibleInThisDay = min($remainingHours, 5);
                
                for ($size = $maxPossibleInThisDay; $size >= 2; $size--) {
                    $foundBlock = false;
                    
                    // Shuffle starting indices to vary when subjects start in the day
                    $possibleStarts = range(0, $daySlots->count() - $size);
                    shuffle($possibleStarts);

                    foreach ($possibleStarts as $slotIdx) {
                        $potentialSlots = $daySlots->slice($slotIdx, $size);
                        
                        $blockAvailable = true;
                        $roomForBlock = null;

                        foreach ($potentialSlots as $slot) {
                            $foundRoom = $this->findAvailableRoom($slot->id, $classId);
                            if (!$foundRoom || !$this->isAvailable($teacherId, $classId, $slot->id)) {
                                $blockAvailable = false;
                                break;
                            }
                            $roomForBlock = $foundRoom;
                        }

                        if ($blockAvailable) {
                            foreach ($potentialSlots as $slot) {
                                $scheduledItems[] = [
                                    'uuid' => (string) \Illuminate\Support\Str::uuid(),
                                    'academic_year_id' => $this->academicYear->id,
                                    'class_id' => $classId,
                                    'subject_id' => $subjectId,
                                    'teacher_id' => $teacherId,
                                    'room_id' => $roomForBlock,
                                    'time_slot_id' => $slot->id,
                                    'day' => $slot->day,
                                    'status' => 'draft',
                                    'created_by' => Auth::check() ? Auth::user()->name : 'System',
                                    'updated_by' => Auth::check() ? Auth::user()->name : 'System',
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                                $this->markAsBusy($teacherId, $classId, $roomForBlock, $slot->id);
                            }
                            $remainingHours -= $size;
                            $foundBlock = true;
                            break;
                        }
                    }
                    if ($foundBlock) break; 
                }
            }
            
            // Fallback: If still has remaining hours, try placing in smaller pieces (singular slots)
            if ($remainingHours > 0) {
                 $availableSlots = $this->timeSlots->where('is_break', false)->shuffle();
                 foreach ($availableSlots as $slot) {
                     if ($remainingHours <= 0) break;
                     $roomId = $this->findAvailableRoom($slot->id, $classId);
                     if ($roomId && $this->isAvailable($teacherId, $classId, $slot->id)) {
                         $scheduledItems[] = [
                             // ... singular placement ...
                             'uuid' => (string) \Illuminate\Support\Str::uuid(),
                             'academic_year_id' => $this->academicYear->id,
                             'class_id' => $classId,
                             'subject_id' => $subjectId,
                             'teacher_id' => $teacherId,
                             'room_id' => $roomId,
                             'time_slot_id' => $slot->id,
                             'day' => $slot->day,
                             'status' => 'draft',
                             'created_by' => Auth::check() ? Auth::user()->name : 'System',
                             'updated_by' => Auth::check() ? Auth::user()->name : 'System',
                             'created_at' => now(),
                             'updated_at' => now(),
                         ];
                         $this->markAsBusy($teacherId, $classId, $roomId, $slot->id);
                         $remainingHours--;
                     }
                 }
            }
        }

        // Use repository to bulk insert
        $this->scheduleRepository->bulkInsert($scheduledItems);

        return count($scheduledItems);
    }

    protected function loadData()
    {
        $this->timeSlots = TimeSlot::orderBy('day')->orderBy('start_time')->get();
        $this->rooms = Room::all();
        $this->teachers = Teacher::with(['subjects', 'config' => function($q) {
            $q->where('academic_year_id', $this->academicYear->id);
        }])->get();
        $this->classes = AcademicClass::where('academic_year_id', $this->academicYear->id)->get();
        $this->globalRules = ScheduleRule::where('academic_year_id', $this->academicYear->id)->get();
        
        $this->busyTeachers = []; // [slot_id => [teacher_ids]]
        $this->busyClasses = [];  // [slot_id => [class_ids]]
        $this->busyRooms = [];    // [slot_id => [room_ids]]

        // Map classes to fixed rooms if not using dynamic rooms
        $this->classRoomMap = [];
        if (!$this->useDynamicRooms) {
            foreach ($this->classes as $class) {
                if ($class->room_id) {
                    $this->classRoomMap[$class->id] = $class->room_id;
                }
            }
        }
    }

    protected function findTeacherForSubject($subjectId, $hoursNeeded)
    {
        // Find teachers who specialize in this subject
        $eligibleTeachers = $this->teachers->filter(function($teacher) use ($subjectId) {
            return $teacher->subjects->contains('id', $subjectId);
        });

        if ($eligibleTeachers->isEmpty()) {
            return null; // Fallback or strict error
        }

        // Sort by deficit in hours to balance workload
        $eligibleTeachers = $eligibleTeachers->sortByDesc(function($teacher) use ($hoursNeeded) {
            $currentHours = $this->getTeacherCurrentHours($teacher->id);
            $maxHours = $this->getTeacherMaxHours($teacher->id);
            
            // If they can't take this class at all, give lowest priority or negative priority
            if ($currentHours + $hoursNeeded > $maxHours) {
                return -1000; 
            }

            $minHours = $this->getTeacherMinHours($teacher->id);
            return $minHours - $currentHours;
        });

        $bestTeacher = $eligibleTeachers->first();
        
        // Ensure they can still fit the required hours
        if ($this->getTeacherCurrentHours($bestTeacher->id) + $hoursNeeded > $this->getTeacherMaxHours($bestTeacher->id)) {
            return null;
        }

        return $bestTeacher->id;
    }

    protected function findAvailableRoom($slotId, $classId)
    {
        // 1. If not dynamic, try the assigned room first
        if (!$this->useDynamicRooms && isset($this->classRoomMap[$classId])) {
            $roomId = $this->classRoomMap[$classId];
            
            if (!isset($this->busyRooms[$slotId]) || !in_array($roomId, $this->busyRooms[$slotId])) {
                 return $roomId;
            }
        }

        // 2. Dynamic path: find any available room
        $availableRooms = $this->rooms->filter(function($room) use ($slotId) {
            return !isset($this->busyRooms[$slotId]) || !in_array($room->id, $this->busyRooms[$slotId]);
        });

        if ($availableRooms->isEmpty()) return null;

        // If useDynamicRooms is true, pick randomly to distribute load
        // If false, pick the first one as fallback
        return $this->useDynamicRooms 
            ? $availableRooms->random()->id 
            : $availableRooms->first()->id;
    }

    protected function isAvailable($teacherId, $classId, $slotId)
    {
        // Teacher busy?
        if (isset($this->busyTeachers[$slotId]) && in_array($teacherId, $this->busyTeachers[$slotId])) {
            return false;
        }

        // Class busy?
        if (isset($this->busyClasses[$slotId]) && in_array($classId, $this->busyClasses[$slotId])) {
            return false;
        }

        // Check teacher max hours
        $currentHours = $this->getTeacherCurrentHours($teacherId);
        $maxHours = $this->getTeacherMaxHours($teacherId);
        
        if ($currentHours >= $maxHours) {
            return false;
        }

        return true;
    }

    protected function markAsBusy($teacherId, $classId, $roomId, $slotId)
    {
        $this->busyTeachers[$slotId][] = $teacherId;
        $this->busyClasses[$slotId][] = $classId;
        $this->busyRooms[$slotId][] = $roomId;
        
        $this->teacherHours[$teacherId] = ($this->teacherHours[$teacherId] ?? 0) + 1;
    }



    protected function getTeacherCurrentHours($teacherId)
    {
        return $this->teacherHours[$teacherId] ?? 0;
    }

    protected function getTeacherMaxHours($teacherId)
    {
        $teacher = $this->teachers->where('id', $teacherId)->first();
        if ($teacher && $teacher->config) {
            return $teacher->config->max_hours_per_week;
        }

        $globalMax = $this->globalRules->where('rule_type', 'global_max_hours')->first();
        return $globalMax ? (int)$globalMax->value : 24;
    }

    protected function getTeacherMinHours($teacherId)
    {
        $teacher = $this->teachers->where('id', $teacherId)->first();
        if ($teacher && $teacher->config) {
            return $teacher->config->min_hours_per_week;
        }

        $globalMin = $this->globalRules->where('rule_type', 'global_min_hours')->first();
        return $globalMin ? (int)$globalMin->value : 18;
    }
}

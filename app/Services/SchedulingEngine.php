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

            // If teacher is not explicitly assigned, find an eligible teacher for the subject
            if (!$teacherId) {
                $teacherId = $this->findTeacherForSubject($subjectId, $hoursNeeded);
            }

            if (!$teacherId) {
                // Could not find any teacher to accommodate this class
                continue;
            }

            for ($i = 0; $i < $hoursNeeded; $i++) {
                $placed = false;
                
                // Shuffle time slots to get varied distribution
                $availableSlots = $this->timeSlots->where('is_break', false)->shuffle();

                foreach ($availableSlots as $slot) {
                    // Check room availability
                    $roomId = $this->findAvailableRoom($slot->id, $classId);

                    if ($roomId && $this->isAvailable($teacherId, $classId, $slot->id)) {
                        $scheduledItems[] = [
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
                        $placed = true;
                        
                        // Track usage locally for faster checks
                        $this->markAsBusy($teacherId, $classId, $roomId, $slot->id);
                        break;
                    }
                }

                if (!$placed) {
                    // Handle failure to place a slot
                    // Log or throw warning
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
        if (!$this->useDynamicRooms) {
            $this->classRoomMap = [];
            foreach ($this->classes as $index => $class) {
                // simple 1 to 1 assignment
                $room = $this->rooms->get($index);
                if ($room) {
                    $this->classRoomMap[$class->id] = $room->id;
                } else {
                    // Fallback to a random room if there are more classes than rooms
                    $this->classRoomMap[$class->id] = $this->rooms->random()->id;
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
        if (!$this->useDynamicRooms && isset($this->classRoomMap[$classId])) {
            $roomId = $this->classRoomMap[$classId];
            
            // Still check if the room is busy by another class to avoid collisions if fallbacks were made
            if (!isset($this->busyRooms[$slotId]) || !in_array($roomId, $this->busyRooms[$slotId])) {
                 return $roomId;
            }
        }

        // Logical path for dynamic rooms or if fixed room is busy: find any available room
        foreach ($this->rooms as $room) {
            if (!isset($this->busyRooms[$slotId]) || !in_array($room->id, $this->busyRooms[$slotId])) {
                return $room->id;
            }
        }
        return null;
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

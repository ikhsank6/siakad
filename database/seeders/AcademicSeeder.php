<?php

namespace Database\Seeders;

use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\ClassSubject;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherConfig;
use App\Models\TimeSlot;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        // Clear tables
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        ClassSubject::truncate();
        Schedule::truncate();
        Student::truncate();
        TimeSlot::truncate();
        Room::truncate();
        Subject::truncate();
        TeacherConfig::truncate();
        Teacher::truncate();
        AcademicClass::truncate();
        AcademicYear::truncate();
        User::truncate();
        \Illuminate\Database\Eloquent\Model::unguard();
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        \Illuminate\Database\Eloquent\Model::reguard();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $teacherRole = Role::where('slug', 'teacher')->first();
        $studentRole = Role::where('slug', 'student')->first();

        // 1. Academic Year
        $ay = AcademicYear::create([
            'name' => '2025/2026',
            'semester' => 'Ganjil',
            'is_active' => true,
            'start_date' => '2025-07-01',
            'end_date' => '2025-12-31',
        ]);

        // 2. Subjects & Teachers data from image
        $data = [
            ['code' => 'INF', 'name' => 'Informatika', 'teacher' => 'Moch. Safrudin', 'email' => 'safrudin@example.com', 'hours' => 4],
            ['code' => 'GEO', 'name' => 'Geografi', 'teacher' => 'Ida Surtikanti', 'email' => 'ida@example.com', 'hours' => 2],
            ['code' => 'KIM', 'name' => 'Kimia', 'teacher' => 'Neneng Rohayati', 'email' => 'neneng@example.com', 'hours' => 3],
            ['code' => 'MAT', 'name' => 'Matematika', 'teacher' => 'Abdul Halim', 'email' => 'halim@example.com', 'hours' => 4],
            ['code' => 'SOS', 'name' => 'Sosiologi', 'teacher' => 'Uli Alba', 'email' => 'uli@example.com', 'hours' => 2],
            ['code' => 'EKO', 'name' => 'Ekonomi', 'teacher' => 'Moch. Gaponi', 'email' => 'gaponi@example.com', 'hours' => 3],
            ['code' => 'OR', 'name' => 'Olahraga', 'teacher' => 'Lukman Firmasyah', 'email' => 'lukman@example.com', 'hours' => 3],
            ['code' => 'BIG', 'name' => 'B. Inggris', 'teacher' => 'Fauzia Mariana', 'email' => 'fauzia@example.com', 'hours' => 2],
            ['code' => 'PPKN', 'name' => 'PPKN', 'teacher' => 'Darul Qutni', 'email' => 'darul@example.com', 'hours' => 2],
            ['code' => 'FIS', 'name' => 'Fisika', 'teacher' => 'Dhea Anggita', 'email' => 'dhea@example.com', 'hours' => 3],
            ['code' => 'PAI', 'name' => 'PA. Islam', 'teacher' => 'Siti Mujilah', 'email' => 'siti@example.com', 'hours' => 3],
            ['code' => 'BK', 'name' => 'BK/BP', 'teacher' => 'Erfi', 'email' => 'erfi@example.com', 'hours' => 1],
            ['code' => 'BIO', 'name' => 'Biologi', 'teacher' => 'Ninesti Handayani', 'email' => 'ninesti@example.com', 'hours' => 3],
            ['code' => 'SEJ', 'name' => 'Sejarah', 'teacher' => 'Abdul Rozak', 'email' => 'rozak@example.com', 'hours' => 3],
            ['code' => 'IND', 'name' => 'B. Indonesia', 'teacher' => 'Sartika', 'email' => 'sartika@example.com', 'hours' => 3],
            ['code' => 'SBK', 'name' => 'Seni Budaya', 'teacher' => 'Arini Camelia', 'email' => 'arini@example.com', 'hours' => 2],
        ];

        $subjectModels = [];
        $teacherModels = [];
        $teacherIndex = 0;
        $uniqueTeachers = [];

        foreach ($data as $d) {
            // 1. Create Subject with unique name
            $subject = Subject::create([
                'code' => $d['code'],
                'name' => $d['name'],
                'default_hours_per_week' => $d['hours'],
            ]);
            $subjectModels[] = $subject;

            // 2. Check if we have already created this teacher
            if (!isset($uniqueTeachers[$d['teacher']])) {
                $user = User::create([
                    'name' => $d['teacher'],
                    'email' => $d['email'],
                    'password' => bcrypt('password'),
                    'is_active' => true,
                ]);
                $user->syncRoles([$teacherRole->id]);

                $teacher = Teacher::create([
                    'user_id' => $user->id,
                    'nip' => '198' . rand(100000000, 999999999),
                    'name' => $user->name,
                ]);

                $teacher->config()->create([
                    'min_hours_per_week' => 18,
                    'max_hours_per_week' => 40,
                    'academic_year_id' => $ay->id,
                ]);

                $uniqueTeachers[$d['teacher']] = $teacher;
            }

            $currentTeacher = $uniqueTeachers[$d['teacher']];
            
            // 3. Attach subject to teacher
            // CRITICAL: Check how many subjects this teacher already has.
            // If they already have 2, do NOT attach this new unique subject to them.
            // Instead, this subject will just exist but might not have a teacher assigned 
            // OR the seeder data already respects the "max 2" limit.
            $assignedSubjectsCount = $currentTeacher->subjects()->count();
            
            if ($assignedSubjectsCount < 2) {
                if (!$currentTeacher->subjects->contains($subject->id)) {
                    $currentTeacher->subjects()->attach($subject->id);
                }
            }
            
            $teacherModels[$subject->id] = $currentTeacher;
        }

        // 3. Rooms
        for ($i = 1; $i <= 20; $i++) {
            Room::create([
                'name' => 'Ruang ' . $i,
                'capacity' => 40,
                'type' => 'General',
            ]);
        }

        // 4. Time Slots (Monday - Friday, 07:00 - 15:30, 45 mins per period)
        // Breaks: 09:15-09:45 and 12:00-12:30
        $breaks = [
            ['start' => '09:15', 'end' => '09:45', 'name' => 'Istirahat 1'],
            ['start' => '12:00', 'end' => '12:30', 'name' => 'Istirahat 2'],
        ];

        for ($day = 1; $day <= 5; $day++) {
            $currentTime = strtotime("07:00:00");
            $exitTime = strtotime("15:30:00");
            $period = 1;

            while ($currentTime < $exitTime) {
                $startTimeStr = date('H:i:s', $currentTime);
                
                // Check if this time matches a break
                $isBreak = false;
                $breakName = null;
                foreach ($breaks as $b) {
                    if (date('H:i', $currentTime) === $b['start']) {
                        $isBreak = true;
                        $breakName = $b['name'];
                        $duration = (strtotime($b['end']) - strtotime($b['start'])) / 60;
                        $endTimeStr = date('H:i:s', strtotime($b['end']));
                        break;
                    }
                }

                if (!$isBreak) {
                    $endTimeStr = date('H:i:s', strtotime('+45 minutes', $currentTime));
                    $duration = 45;
                }

                TimeSlot::create([
                    'day' => $day,
                    'start_time' => $startTimeStr,
                    'end_time' => $endTimeStr,
                    'is_break' => $isBreak,
                    'name' => $breakName ?? "Jam Ke-{$period}",
                ]);

                $currentTime = strtotime($endTimeStr);
                if (!$isBreak) $period++;
            }
        }

        // 6. Classes
        $classNames = ['X-1', 'X-2', 'X-3', 'XI IPA 1', 'XI IPS 1', 'XII IPA 1'];
        $rooms = Room::all();

        foreach ($classNames as $index => $name) {
            $gradeLevel = str_starts_with($name, 'X-') ? 10 : (str_starts_with($name, 'XI ') ? 11 : 12);
            $roomId = isset($rooms[$index]) ? $rooms[$index]->id : null;

            $class = AcademicClass::create([
                'name' => $name,
                'grade_level' => $gradeLevel,
                'room_id' => $roomId,
            ]);

            // Assign subjects to class
            foreach ($subjectModels as $sm) {
                $teacher = $teacherModels[$sm->id];
                ClassSubject::create([
                    'class_id' => $class->id,
                    'subject_id' => $sm->id,
                    'teacher_id' => $teacher->id,
                    'hours_per_week' => $sm->default_hours_per_week,
                ]);
            }

            // 7. Students per class
            for ($j = 1; $j <= 5; $j++) {
                $studentUser = User::create([
                    'name' => "Student {$name}-{$j}",
                    'email' => "student-" . \Illuminate\Support\Str::slug($name) . "-{$j}@example.com",
                    'password' => bcrypt('password'),
                    'is_active' => true,
                ]);
                $studentUser->syncRoles([$studentRole->id]);
                Student::create([
                    'user_id' => $studentUser->id,
                    'class_id' => $class->id,
                    'nisn' => '2025' . str_pad($gradeLevel ?? 10, 2, '0', STR_PAD_LEFT) . str_pad($index, 2, '0', STR_PAD_LEFT) . str_pad($j, 2, '0', STR_PAD_LEFT),
                    'name' => $studentUser->name,
                ]);
            }
        }
    }
}

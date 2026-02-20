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

        // 2. Subjects
        $subjects = [
            ['code' => 'MAT-01', 'name' => 'Matematika', 'hours' => 4],
            ['code' => 'BIN-01', 'name' => 'Bahasa Indonesia', 'hours' => 4],
            ['code' => 'BIG-01', 'name' => 'Bahasa Inggris', 'hours' => 4],
            ['code' => 'FIS-01', 'name' => 'Fisika', 'hours' => 3],
            ['code' => 'BIO-01', 'name' => 'Biologi', 'hours' => 3],
            ['code' => 'KIM-01', 'name' => 'Kimia', 'hours' => 3],
        ];

        $subjectModels = [];
        foreach ($subjects as $s) {
            $subjectModels[] = Subject::create([
                'code' => $s['code'],
                'name' => $s['name'],
                'default_hours_per_week' => $s['hours'],
            ]);
        }

        // 3. Rooms
        for ($i = 1; $i <= 40; $i++) {
            Room::create([
                'name' => 'Ruang ' . $i,
                'capacity' => 40,
                'type' => 'General',
            ]);
        }

        // 4. Time Slots (Monday - Friday, 07:00 - 12:00, 45 mins per period)
        for ($day = 1; $day <= 5; $day++) {
            $startTime = "07:00:00";
            for ($period = 1; $period <= 6; $period++) {
                $endTime = date('H:i:s', strtotime($startTime . ' +45 minutes'));
                TimeSlot::create([
                    'day' => $day,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'is_break' => $period == 4, // Break at 4th period
                ]);
                $startTime = $endTime;
            }
        }

        // 5. Teachers
        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name' => 'Teacher ' . $i,
                'email' => 'teacher' . $i . '@example.com',
                'password' => bcrypt('password'),
                'is_active' => true,
            ]);
            $user->syncRoles([$teacherRole->id]);
            
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'nip' => '19800101' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => $user->name,
            ]);

            $teacher->config()->create([
                'min_hours_per_week' => 18,
                'max_hours_per_week' => 24,
                'academic_year_id' => $ay->id,
            ]);
        }

        // 6. Classes
        $classNames = [
            'X-1', 'X-2', 'X-3', 'X-4', 'X-5', 'X-6', 'X-7', 'X-8',
            'XI IPA 1', 'XI IPA 2', 'XI IPS 1', 'XI IPS 2', 'XI Bahasa',
            'XII IPA 1', 'XII IPA 2', 'XII IPS 1', 'XII IPS 2', 'XII Bahasa',
        ];

        $rooms = Room::all();

        foreach ($classNames as $index => $name) {
            $gradeLevel = str_starts_with($name, 'X-') ? 10 : (str_starts_with($name, 'XI ') ? 11 : 12);
            $major = null;
            if (str_contains($name, 'IPA')) $major = 'IPA';
            elseif (str_contains($name, 'IPS')) $major = 'IPS';
            elseif (str_contains($name, 'Bahasa')) $major = 'Bahasa';
            
            // Assign room from pool
            $roomId = isset($rooms[$index]) ? $rooms[$index]->id : null;

            $class = AcademicClass::create([
                'name' => $name,
                'grade_level' => $gradeLevel,
                'major' => $major,
                'room_id' => $roomId,
            ]);

            // Assign subjects to class
            $teacherModels = Teacher::all();
            foreach ($subjectModels as $sIndex => $sm) {
                // Distribute subjects logically
                $teacher = $teacherModels[($index + $sIndex) % 10];
                
                $teacher->subjects()->syncWithoutDetaching([$sm->id]);

                ClassSubject::create([
                    'class_id' => $class->id,
                    'subject_id' => $sm->id,
                    'teacher_id' => $teacher->id,
                    'hours_per_week' => $sm->default_hours_per_week,
                ]);
            }

            // 7. Students per class
            for ($j = 1; $j <= 10; $j++) {
                // Reduce student count per class for performance of seeder
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
                    'nisn' => '2025' . str_pad($gradeLevel, 2, '0', STR_PAD_LEFT) . str_pad($index, 2, '0', STR_PAD_LEFT) . str_pad($j, 2, '0', STR_PAD_LEFT),
                    'name' => $studentUser->name,
                ]);
            }
        }
    }
}

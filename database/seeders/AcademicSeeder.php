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
        for ($i = 1; $i <= 5; $i++) {
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
        for ($i = 1; $i <= 3; $i++) {
            $class = AcademicClass::create([
                'name' => 'X-IPA-' . $i,
                'academic_year_id' => $ay->id,
                'grade_level' => 10,
            ]);

            // Assign subjects to class
            $teacherModels = Teacher::all();
            foreach ($subjectModels as $index => $sm) {
                ClassSubject::create([
                    'class_id' => $class->id,
                    'subject_id' => $sm->id,
                    'teacher_id' => $teacherModels[$index % 10]->id, // Simple distribution
                    'hours_per_week' => $sm->default_hours_per_week,
                ]);
            }

            // 7. Students per class
            for ($j = 1; $j <= 20; $j++) {
                $studentUser = User::create([
                    'name' => "Student {$i}-{$j}",
                    'email' => "student-class{$i}-{$j}@example.com",
                    'password' => bcrypt('password'),
                    'is_active' => true,
                ]);

                Student::create([
                    'user_id' => $studentUser->id,
                    'class_id' => $class->id,
                    'nisn' => '2024' . str_pad($i, 2, '0', STR_PAD_LEFT) . str_pad($j, 3, '0', STR_PAD_LEFT),
                    'name' => $studentUser->name,
                ]);
            }
        }
    }
}

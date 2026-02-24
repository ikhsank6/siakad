<?php

use App\Livewire\Academic\AcademicYearIndex;
use App\Livewire\Academic\ClassIndex;
use App\Livewire\Academic\RoomIndex;
use App\Livewire\Academic\Scheduling;
use App\Livewire\Academic\StudentIndex;
use App\Livewire\Academic\SubjectIndex;
use App\Livewire\Academic\TeacherIndex;
use Illuminate\Support\Facades\Route;

Route::prefix('academic')->name('academic.')->group(function () {
    Route::get('/scheduling', Scheduling::class)->name('scheduling.index');
    Route::get('/my-schedule', \App\Livewire\Academic\MySchedule::class)->name('my-schedule');
    Route::get('/my-schedule/export', [\App\Http\Controllers\Academic\ScheduleExportController::class, 'exportTeacherSchedule'])->name('my-schedule.export');
    Route::get('/academic-years', AcademicYearIndex::class)->name('academic-years.index');
    Route::get('/subjects', SubjectIndex::class)->name('subjects.index');
    Route::get('/classes', ClassIndex::class)->name('classes.index');
    Route::get('/teachers', TeacherIndex::class)->name('teachers.index');
    Route::get('/students', StudentIndex::class)->name('students.index');
    Route::get('/rooms', RoomIndex::class)->name('rooms.index');
});

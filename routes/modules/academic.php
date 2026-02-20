<?php

use App\Livewire\Academic\Scheduling;
use App\Livewire\Academic\SubjectIndex;
use App\Livewire\Academic\TeacherIndex;
use App\Livewire\Academic\StudentIndex;
use Illuminate\Support\Facades\Route;

Route::prefix('academic')->name('academic.')->group(function () {
    Route::get('/scheduling', Scheduling::class)->name('scheduling.index');
    Route::get('/subjects', SubjectIndex::class)->name('subjects.index');
    Route::get('/teachers', TeacherIndex::class)->name('teachers.index');
    Route::get('/students', StudentIndex::class)->name('students.index');
});

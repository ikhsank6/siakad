<?php

use App\Livewire\Settings\JobIndex;
use App\Livewire\Settings\LogIndex;
use App\Livewire\Settings\SystemSettingIndex;
use Illuminate\Support\Facades\Route;

Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/system', SystemSettingIndex::class)->name('system.index');
    Route::get('/logs', LogIndex::class)->name('log.index');
    Route::get('/jobs', JobIndex::class)->name('job.index');
});

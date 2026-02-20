<?php

use App\Livewire\Menus\MenuIndex;
use App\Livewire\Menus\RoleMenuAccess;
use App\Livewire\Roles\RoleIndex;
use App\Livewire\Users\UserIndex;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Master Data Routes
|--------------------------------------------------------------------------
*/

Route::prefix('master-data')->name('master-data.')->group(function () {
    Route::get('/users', UserIndex::class)->name('users.index');
    Route::get('/roles', RoleIndex::class)->name('roles.index');
    Route::get('/menus', MenuIndex::class)->name('menus.index');
    Route::get('/menu-access', RoleMenuAccess::class)->name('menu-access.index');
});

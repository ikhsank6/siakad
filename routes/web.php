<?php

use App\Actions\SwitchRoleAction;
use App\Livewire\Dashboard;
use App\Livewire\Layout\NotificationIndex;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Website Modules
|--------------------------------------------------------------------------
|
| Higher-level website routes that are accessible to the public.
|
*/

require base_path('routes/modules/website.php');

/*
|--------------------------------------------------------------------------
| Authentication Modules
|--------------------------------------------------------------------------
|
| Includes all authentication-related routes (login, register, logout, etc.)
|
*/

require base_path('routes/modules/auth.php');

/*
|--------------------------------------------------------------------------
| Secured Routes
|--------------------------------------------------------------------------
|
| Basic authenticated routes without role-based menu access restrictions.
|
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', NotificationIndex::class)->name('notifications.index');

    // Switch role - accessible to all authenticated users regardless of menu access
    Route::get('/roles/switch/{role}', SwitchRoleAction::class)->name('roles.switch');
});

/*
|--------------------------------------------------------------------------
| Protected Management Routes
|--------------------------------------------------------------------------
|
| Routes that require both authentication and role-based menu access.
|
*/

Route::middleware(['auth', 'menu.access'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Load CMS Module
    require base_path('routes/modules/cms.php');

    // Load Master Data Module
    require base_path('routes/modules/master_data.php');

    // Load Settings Module
    require base_path('routes/modules/settings.php');

    // Load Academic Module
    require base_path('routes/modules/academic.php');
});

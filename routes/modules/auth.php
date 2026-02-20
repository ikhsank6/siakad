<?php

use App\Actions\LogoutAction;
use App\Livewire\Auth\ChangePassword;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Profile;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyEmail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
| These routes are protected by rate limiting to prevent brute force attacks.
| Rate limit: 10 requests per minute per IP address for login/register.
*/

Route::middleware(['guest', 'throttle:website-forms'])->prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

// Alias for Laravel's default 'login' route name requirement
Route::get('/login', function () {
    return redirect()->route('auth.login');
})->name('login');

// Email verification route
Route::get('/auth/verify-email/{id}/{hash}', VerifyEmail::class)
    ->middleware(['throttle:6,1'])
    ->name('auth.verification.verify');

// Routes that need auth but NOT menu.access restriction
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', LogoutAction::class)->name('logout');
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/password/change', ChangePassword::class)->name('password.change');
});

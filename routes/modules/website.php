<?php

use App\Actions\Website\ShowAboutPage;
use App\Actions\Website\ShowHomePage;
use App\Actions\Website\ShowNewsDetail;
use App\Actions\Website\ShowNewsList;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Website Public Routes
|--------------------------------------------------------------------------
| These routes are protected by rate limiting to prevent brute force attacks.
| Rate limit: 60 requests per minute per IP address.
*/

Route::middleware(['throttle:website'])->group(function () {
    Route::get('/', ShowHomePage::class)->name('landing');
    Route::get('/news', ShowNewsList::class)->name('news.index');
    Route::get('/news/{slug}', ShowNewsDetail::class)->name('news.show');
    Route::get('/about', ShowAboutPage::class)->name('about');
});

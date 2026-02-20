<?php

use App\Livewire\AboutUs\AboutUsIndex;
use App\Livewire\Carousels\CarouselIndex;
use App\Livewire\News\NewsIndex;
use App\Livewire\NewsCategories\NewsCategoryIndex;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CMS Management Routes
|--------------------------------------------------------------------------
*/

Route::prefix('cms')->name('cms.')->group(function () {
    Route::get('/carousels', CarouselIndex::class)->name('carousels.index');
    Route::get('/news-categories', NewsCategoryIndex::class)->name('news-categories.index');
    Route::get('/news', NewsIndex::class)->name('news.index');
    Route::get('/about-us', AboutUsIndex::class)->name('about-us.index');
});

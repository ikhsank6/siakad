<?php

namespace App\Actions\Website;

use App\Repositories\Contracts\AboutUsRepositoryInterface;
use App\Repositories\Contracts\CarouselRepositoryInterface;
use App\Repositories\Contracts\NewsRepositoryInterface;
use Illuminate\View\View;

class ShowHomePage
{
    public function __construct(
        protected CarouselRepositoryInterface $carouselRepository,
        protected NewsRepositoryInterface $newsRepository,
        protected AboutUsRepositoryInterface $aboutUsRepository
    ) {}

    /**
     * Display the homepage with carousel and latest news.
     */
    public function __invoke(): View
    {
        $carousels = $this->carouselRepository->getActiveOrdered();
        $news = $this->newsRepository->getLatest(6);
        $aboutUs = $this->aboutUsRepository->getActive();

        return view('website.home', compact('carousels', 'news', 'aboutUs'));
    }
}

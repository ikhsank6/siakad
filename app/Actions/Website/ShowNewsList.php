<?php

namespace App\Actions\Website;

use App\Repositories\Contracts\AboutUsRepositoryInterface;
use App\Repositories\Contracts\NewsCategoryRepositoryInterface;
use App\Repositories\Contracts\NewsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowNewsList
{
    public function __construct(
        protected NewsRepositoryInterface $newsRepository,
        protected NewsCategoryRepositoryInterface $categoryRepository,
        protected AboutUsRepositoryInterface $aboutUsRepository
    ) {}

    /**
     * Display paginated news list with categories sidebar.
     */
    public function __invoke(Request $request): View
    {
        $news = $this->newsRepository->getActiveWithFilter(
            $request->get('category')
        );

        $categories = $this->categoryRepository->getActiveWithNewsCount();
        $recentNews = $this->newsRepository->getLatest(5);
        $aboutUs = $this->aboutUsRepository->getActive();

        return view('website.news.index', compact('news', 'categories', 'recentNews', 'aboutUs'));
    }
}

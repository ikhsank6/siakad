<?php

namespace App\Actions\Website;

use App\Repositories\Contracts\AboutUsRepositoryInterface;
use App\Repositories\Contracts\NewsCategoryRepositoryInterface;
use App\Repositories\Contracts\NewsRepositoryInterface;
use Illuminate\View\View;

class ShowNewsDetail
{
    public function __construct(
        protected NewsRepositoryInterface $newsRepository,
        protected NewsCategoryRepositoryInterface $categoryRepository,
        protected AboutUsRepositoryInterface $aboutUsRepository
    ) {}

    /**
     * Display single news article with related news.
     */
    public function __invoke(string $slug): View
    {
        $news = $this->newsRepository->findActiveBySlugOrFail($slug);

        $categories = $this->categoryRepository->getActiveWithNewsCount();
        $recentNews = $this->newsRepository->getRecentExcept($news->id);
        $relatedNews = $this->newsRepository->getRelated(
            $news->news_category_id,
            $news->id
        );
        $aboutUs = $this->aboutUsRepository->getActive();

        return view('website.news.show', compact(
            'news',
            'categories',
            'recentNews',
            'relatedNews',
            'aboutUs'
        ));
    }
}

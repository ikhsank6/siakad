<?php

namespace App\Actions\Website;

use App\Repositories\Contracts\AboutUsRepositoryInterface;
use Illuminate\View\View;

class ShowAboutPage
{
    public function __construct(
        protected AboutUsRepositoryInterface $aboutUsRepository
    ) {}

    /**
     * Display the about us page.
     */
    public function __invoke(): View
    {
        $aboutUs = $this->aboutUsRepository->getActive();

        return view('website.about', compact('aboutUs'));
    }
}

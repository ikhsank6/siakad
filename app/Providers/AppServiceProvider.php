<?php

namespace App\Providers;

use App\Models\Menu;
use App\Policies\MenuPolicy;
use App\Services\MenuService;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected array $policies = [
        Menu::class => MenuPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(MenuService::class, function ($app) {
            return new MenuService;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        FilamentColor::register([
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::Blue,
            'primary' => Color::Indigo,
            'success' => Color::Green,
            'warning' => Color::Amber,
        ]);

        $this->configureRateLimiting();

        $this->registerPolicies();
        $this->registerGates();

        // Share cached system settings and about us with all views
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $systemSettingRepo = app(\App\Repositories\Contracts\SystemSettingRepositoryInterface::class);
            $aboutUsRepo = app(\App\Repositories\Contracts\AboutUsRepositoryInterface::class);

            $view->with('settings', $systemSettingRepo->getCachedSettings());
            $view->with('aboutUs', $aboutUsRepo->getCached());
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // Rate limit for public website routes (60 requests per minute per IP)
        RateLimiter::for('website', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        // Stricter rate limit for form submissions (10 requests per minute per IP)
        RateLimiter::for('website-forms', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        // Rate limit for API-like endpoints (30 requests per minute per IP)
        RateLimiter::for('website-api', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip());
        });
    }

    /**
     * Register the application's policies.
     */
    protected function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    /**
     * Register custom gates.
     */
    protected function registerGates(): void
    {
        Gate::define('access-route', function ($user, string $routeName) {
            $menuService = app(MenuService::class);

            return $menuService->userHasAccessToRoute($routeName);
        });

        Gate::define('access-menu', function ($user, $menu) {
            if (! $user->role) {
                return false;
            }

            return $user->role->menus()->where('menus.id', $menu->id)->exists();
        });

        Gate::define('viewLogViewer', function ($user) {
            return $user->role && $user->role->slug === 'super-admin';
        });
    }
}

<?php

namespace App\Providers;

use App\Repositories\AboutUsRepository;
use App\Repositories\CarouselRepository;
use App\Repositories\Contracts\AboutUsRepositoryInterface;
use App\Repositories\Contracts\CarouselRepositoryInterface;
use App\Repositories\Contracts\MenuRepositoryInterface;
use App\Repositories\Contracts\NewsCategoryRepositoryInterface;
use App\Repositories\Contracts\NewsRepositoryInterface;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\SystemSettingRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\MenuRepository;
use App\Repositories\NewsCategoryRepository;
use App\Repositories\NewsRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\RoleRepository;
use App\Repositories\SystemSettingRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * All repository bindings.
     *
     * @var array<string, string>
     */
    public array $bindings = [
        UserRepositoryInterface::class => UserRepository::class,
        RoleRepositoryInterface::class => RoleRepository::class,
        MenuRepositoryInterface::class => MenuRepository::class,
        NotificationRepositoryInterface::class => NotificationRepository::class,
        CarouselRepositoryInterface::class => CarouselRepository::class,
        NewsCategoryRepositoryInterface::class => NewsCategoryRepository::class,
        NewsRepositoryInterface::class => NewsRepository::class,
        AboutUsRepositoryInterface::class => AboutUsRepository::class,
        SystemSettingRepositoryInterface::class => SystemSettingRepository::class,
        \App\Repositories\Contracts\MediaRepositoryInterface::class => \App\Repositories\MediaRepository::class,
        \App\Repositories\Contracts\AcademicYearRepositoryInterface::class => \App\Repositories\AcademicYearRepository::class,
        \App\Repositories\Contracts\ScheduleRepositoryInterface::class => \App\Repositories\ScheduleRepository::class,
        \App\Repositories\Contracts\TeacherRepositoryInterface::class => \App\Repositories\TeacherRepository::class,
        \App\Repositories\Contracts\ScheduleRuleRepositoryInterface::class => \App\Repositories\ScheduleRuleRepository::class,
        \App\Repositories\Contracts\SubjectRepositoryInterface::class => \App\Repositories\SubjectRepository::class,
        \App\Repositories\Contracts\StudentRepositoryInterface::class => \App\Repositories\StudentRepository::class,
        \App\Repositories\Contracts\RoomRepositoryInterface::class => \App\Repositories\RoomRepository::class,
        \App\Repositories\Contracts\AcademicClassRepositoryInterface::class => \App\Repositories\AcademicClassRepository::class,
        \App\Repositories\Contracts\TimeSlotRepositoryInterface::class => \App\Repositories\TimeSlotRepository::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        foreach ($this->bindings as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

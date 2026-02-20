<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Dashboard',
                'slug' => 'dashboard',
                'icon' => 'home',
                'route' => 'dashboard',
                'order' => 1,
                'is_active' => true,
                'children' => [],
            ],
            [
                'name' => 'Master Data',
                'slug' => 'master-data',
                'icon' => 'circle-stack',
                'route' => null,
                'order' => 2,
                'is_active' => true,
                'children' => [
                    [
                        'name' => 'Users',
                        'slug' => 'users',
                        'icon' => 'users',
                        'route' => 'master-data.users.index',
                        'order' => 1,
                        'is_active' => true,
                    ],
                    [
                        'name' => 'Roles',
                        'slug' => 'roles',
                        'icon' => 'shield-check',
                        'route' => 'master-data.roles.index',
                        'order' => 2,
                        'is_active' => true,
                    ],
                    [
                        'name' => 'Menus',
                        'slug' => 'menus',
                        'icon' => 'list-bullet',
                        'route' => 'master-data.menus.index',
                        'order' => 3,
                        'is_active' => true,
                    ],
                    [
                        'name' => 'Menu Access',
                        'slug' => 'menu-access',
                        'icon' => 'key',
                        'route' => 'master-data.menu-access.index',
                        'order' => 4,
                        'is_active' => true,
                    ],
                ],
            ],
            [
                'name' => 'CMS',
                'slug' => 'cms',
                'icon' => 'cog-6-tooth',
                'route' => null,
                'order' => 3,
                'is_active' => true,
                'children' => [
                    [
                        'name' => 'Carousels',
                        'slug' => 'carousels',
                        'icon' => 'photo',
                        'route' => 'cms.carousels.index',
                        'order' => 1,
                        'is_active' => true,
                    ],
                    [
                        'name' => 'News Categories',
                        'slug' => 'news-categories',
                        'icon' => 'tag',
                        'route' => 'cms.news-categories.index',
                        'order' => 2,
                        'is_active' => true,
                    ],
                    [
                        'name' => 'News',
                        'slug' => 'news',
                        'icon' => 'newspaper',
                        'route' => 'cms.news.index',
                        'order' => 3,
                        'is_active' => true,
                    ],
                    [
                        'name' => 'About Us',
                        'slug' => 'about-us',
                        'icon' => 'building-office',
                        'route' => 'cms.about-us.index',
                        'order' => 4,
                        'is_active' => true,
                    ],
                ],
            ],
            [
                'name' => 'Settings',
                'slug' => 'settings',
                'icon' => 'cog-8-tooth',
                'route' => null,
                'order' => 4,
                'is_active' => true,
                'children' => [
                    [
                        'name' => 'System',
                        'slug' => 'system-settings',
                        'icon' => 'server',
                        'route' => 'settings.system.index',
                        'order' => 1,
                        'is_active' => true,
                    ],
                    [
                        'name' => 'Logs',
                        'slug' => 'logs',
                        'icon' => 'document-magnifying-glass',
                        'route' => 'settings.log.index',
                        'order' => 2,
                        'is_active' => true,
                    ],
                    [
                        'name' => 'Jobs',
                        'slug' => 'jobs',
                        'icon' => 'queue-list',
                        'route' => 'settings.job.index',
                        'order' => 3,
                        'is_active' => true,
                    ],
                ],
            ],
            [
                'name' => 'Academic',
                'slug' => 'academic',
                'icon' => 'academic-cap',
                'route' => null,
                'order' => 5,
                'is_active' => true,
                'children' => [
                    [
                        'name' => 'Scheduling',
                        'slug' => 'scheduling',
                        'icon' => 'calendar-days',
                        'route' => 'academic.scheduling.index',
                        'order' => 1,
                        'is_active' => true,
                    ],
                    [
                        'name' => 'Academic Years',
                        'slug' => 'academic-years',
                        'icon' => 'calendar',
                        'route' => 'academic.academic-years.index',
                        'order' => 2,
                        'is_active' => true,
                    ],
                    [
                        'name' => 'Subjects',
                        'slug' => 'subjects',
                        'icon' => 'book-open',
                        'route' => 'academic.subjects.index',
                        'order' => 3,
                        'is_active' => true,
                    ],
                    [
                        'name' => 'Teachers',
                        'slug' => 'teachers',
                        'icon' => 'user-group',
                        'route' => 'academic.teachers.index',
                        'order' => 4,
                        'is_active' => true,
                    ],
                    [
                        'name' => 'Students',
                        'slug' => 'students',
                        'icon' => 'users',
                        'route' => 'academic.students.index',
                        'order' => 5,
                        'is_active' => true,
                    ],
                    [
                        'name' => 'Rooms',
                        'slug' => 'rooms',
                        'icon' => 'building-library',
                        'route' => 'academic.rooms.index',
                        'order' => 6,
                        'is_active' => true,
                    ],
                    [
                        'name' => 'Classes',
                        'slug' => 'classes',
                        'icon' => 'rectangle-group',
                        'route' => 'academic.classes.index',
                        'order' => 7,
                        'is_active' => true,
                    ],
                ],
            ],
        ];

        foreach ($menus as $menuData) {
            $children = $menuData['children'] ?? [];
            unset($menuData['children']);

            $menu = Menu::updateOrCreate(
                ['slug' => $menuData['slug']],
                $menuData
            );

            foreach ($children as $childData) {
                $childData['parent_id'] = $menu->id;
                Menu::updateOrCreate(
                    ['slug' => $childData['slug']],
                    $childData
                );
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = Role::where('slug', 'super-admin')->first();
        $admin = Role::where('slug', 'admin')->first();

        if ($superAdmin) {
            // Super Admin gets access to all menus
            $allMenus = Menu::pluck('id')->toArray();
            $superAdmin->menus()->syncWithoutDetaching($allMenus);
        }

        if ($admin) {
            // Admin gets access to Dashboard and Users only
            $adminMenus = Menu::whereIn('slug', ['dashboard', 'master-data', 'users'])
                ->pluck('id')
                ->toArray();
            $admin->menus()->syncWithoutDetaching($adminMenus);
        }
    }
}

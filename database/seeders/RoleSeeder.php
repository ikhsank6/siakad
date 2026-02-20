<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full access to all features and settings',
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrative access with some restrictions',
            ],
            [
                'name' => 'Teacher',
                'slug' => 'teacher',
                'description' => 'Teacher access for academic features',
            ],
            [
                'name' => 'Student',
                'slug' => 'student',
                'description' => 'Student access for learning and academic features',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}

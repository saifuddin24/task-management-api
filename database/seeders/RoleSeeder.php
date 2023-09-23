<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //            'managing-director'|'project-manager'|'product-manager'|'designer'|'ui-ux-designer'|'frontend-developer'|'backend-developer'|'tester';
        //

        Role::query()->insertOrIgnore([
            [
                'id' => 1,
                'name'=> 'Managing Director',
                'guard_name' => 'sanctum'
            ],
            [
                'id' => 2,
                'name'=> 'Project Manager',
                'guard_name' => 'sanctum'
            ],
            [
                'id' => 3,
                'name'=> 'Product Manager',
                'guard_name' => 'sanctum'
            ],
            [
                'id' => 4,
                'name'=> 'Designer',
                'guard_name' => 'sanctum'
            ],
            [
                'id' => 5,
                'name'=> 'UI/UX Designer',
                'guard_name' => 'sanctum'
            ],
            [
                'id' => 6,
                'name'=> 'Frontend Developer',
                'guard_name' => 'sanctum'
            ],
            [
                'id' => 7,
                'name'=> 'Backend Developer',
                'guard_name' => 'sanctum'
            ],
            [
                'id' => 8,
                'name'=> 'Tester',
                'guard_name' => 'sanctum'
            ],
        ]);

    }
}

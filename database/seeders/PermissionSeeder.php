<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Permission::query()->insertOrIgnore([
            [
                'title'=> 'project.read',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'project.create',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'project.update',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'project.delete',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'task.read',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'task.create',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'task.update',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'task.delete',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'task-activity.read',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'task-activity.create',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'task-activity.update',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'task-activity.delete',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'role.read',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'role.create',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'role.update',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'role.delete',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'permission.read',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'permission.create',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'permission.update',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'permission.delete',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'user-role.read',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'user-role.create',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'user-role.update',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'user-role.delete',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'user-permission.read',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'user-permission.create',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'user-permission.update',
                'guard_name' => 'sanctum'
            ],
            [
                'title'=> 'user-permission.delete',
                'guard_name' => 'sanctum'
            ]

        ]);

    }
}

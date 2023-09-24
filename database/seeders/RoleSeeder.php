<?php

namespace Database\Seeders;

use App\Models\Permission;
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

        $available_permissions = Permission::available_permissions();

        $worker_permission = $available_permissions->only([
            'project.read',
            'task.read', 'task.create', 'task.update', 'task.delete',
            'activity.read', 'activity.create', 'activity.update', 'activity.delete',
        ]);

        $roles = [
            'Managing Director' => $available_permissions->all(),

            'Project Manager' => $available_permissions->only([
                "project.read", "project.create", "project.update", "project.delete",
                "task.read","task.create","task.update","task.delete",
                "task-activity.read","task-activity.create","task-activity.update",
            ]),
            'Product Manager' => $available_permissions->only([
                "project.read", "project.create", "project.update", "project.delete",
                "task.read","task.create","task.update","task.delete",
                "task-activity.read","task-activity.create","task-activity.update",
            ]),
            'Designer' => $worker_permission,
            'UI/UX Designer' => $worker_permission,
            'Frontend Developer' => $worker_permission,
            'Backend Developer' => $worker_permission,
            'Tester' => []
        ];

        $inserting_roles = [];
        $inserting_permissions = [];

        $index = 1;

        foreach ( $roles as $role => $permissions ) {
            $inserting_roles[] = [
                'id' => $index,
                'name'=> $role,
                'guard_name' => 'sanctum'
            ];
            $index++;
            $inserting_permissions[$role] = function (Role $_role) use ($permissions){
                $ids = Permission::query()
                    ->whereIn('title', $permissions)
                    ->pluck('id');

                $_role->permissions()->sync( $ids );
            };
        }

        Role::query()->insertOrIgnore( $inserting_roles );

        Role::query()->each(function (Role $role) use ($inserting_permissions){
            if( isset($inserting_permissions[$role->name])) {
                $inserting_permissions[$role->name]($role);
            }
        });

    }
}

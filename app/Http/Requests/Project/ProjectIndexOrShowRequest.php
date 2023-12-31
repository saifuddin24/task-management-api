<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\IndexOrShowRequest;
use JetBrains\PhpStorm\ArrayShape;

class ProjectIndexOrShowRequest extends IndexOrShowRequest
{

    public function authorize(){
        return true;
    }

    protected function getRelations(): array
     {
         return  [
            'tasks' => 'task.read',
            'manager' => 'user.read',
            'task_activities' => 'task-activity.read',
            'assigned_teams' => 'team.read',
            'assigned_persons' => 'user.read',
         ];
     }

}

<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\IndexOrShowRequest;
use JetBrains\PhpStorm\ArrayShape;

class TaskIndexOrShowRequest extends IndexOrShowRequest
{

    public function authorize( ){
        return true;
    }

     protected function getRelations( ): array
     {
         return  [
            'employee' => 'user.read',
            'project' => 'project.read',
            'task_activities' => ['task-activity.read', function($activity){
                $activity->orderBy( 'progress_percentage', 'DESC' );
            }],
            'task_activities.assign' => 'task-activity.read',
            'task_activities.assign.employee' => 'user.read',
         ];
     }

}

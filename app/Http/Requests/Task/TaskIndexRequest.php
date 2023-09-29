<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\IndexRequest;
use JetBrains\PhpStorm\ArrayShape;

class TaskIndexRequest extends IndexRequest
{

    public function authorize( ){
        return true;
    }

     protected function getRelations( ): array
     {
         return  [
            'employee' => 'user.read',
            'project' => 'project.read',
            'task_activities' => 'task-activity.read',
         ];
     }

}

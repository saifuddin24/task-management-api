<?php

namespace App\Http\Requests\Indexes;

class ProjectIndexRequest extends IndexRequest
{

     protected function getRelations(): array
     {
         return  [
                 'tasks' => 'task.read',
                 'tasks.task_activities' => 'task-activity.read'
         ];
     }

}

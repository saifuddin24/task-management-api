<?php

namespace App\Http\Requests\Indexes;

use JetBrains\PhpStorm\ArrayShape;

class TaskIndexRequest extends IndexRequest
{

     #[ArrayShape(['task_activities' => "string"])] protected function getRelations(): array
     {
         return  [
            'task_activities' => 'task-activity.read'
         ];
     }

}

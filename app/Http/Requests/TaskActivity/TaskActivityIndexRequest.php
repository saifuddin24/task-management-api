<?php

namespace App\Http\Requests\TaskActivity;

use App\Http\Requests\IndexRequest;

class TaskActivityIndexRequest extends IndexRequest
{
    public function authorize(){
        return true;
    }

    protected function getRelations(): array
    {
        return  [
            'task_activities' => 'task-activity.read'
        ];
    }

}

<?php

namespace App\Http\Requests\TaskActivity;

use App\Http\Requests\IndexOrShowRequest;

class TaskActivityIndexOrShowRequest extends IndexOrShowRequest
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

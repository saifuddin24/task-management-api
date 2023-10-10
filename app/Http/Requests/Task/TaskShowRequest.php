<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\IndexOrShowRequest;
use Illuminate\Foundation\Http\FormRequest;

class TaskShowRequest extends IndexOrShowRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    protected function getRelations( ): array
    {
        return  [
            'employee' => 'user.read',
            'project' => 'project.read',
            'task_activities' => 'task-activity.read',
            'employees' => 'user.read',
        ];
    }
}

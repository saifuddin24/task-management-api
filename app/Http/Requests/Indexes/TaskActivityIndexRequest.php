<?php

namespace App\Http\Requests\Indexes;

class TaskActivityIndexRequest extends IndexRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->tokenCan( 'task-activity.read' );
    }

    protected function getRelations(): array
    {
        return  [
             'tasks' => 'task.read',
             'tasks.task_activities' => 'task-activity.read'
        ];
    }

}

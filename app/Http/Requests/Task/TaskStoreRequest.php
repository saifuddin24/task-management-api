<?php

namespace App\Http\Requests\Task;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function attach_employee(Task $task){
        $employee_ids = $this->get('employee_ids' );

        if( $task->id && is_array($employee_ids) ) {
            $task->employees()->attach( $employee_ids, ['created_at' => now()] );
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => [ 'required', 'string' ],
            'deadline' => [ 'required', 'date_format:Y-m-d H:i:s' ],
            'description' => [ 'nullable', 'string' ],
            'employee_ids.*' => [ 'sometimes', 'integer','exists:users,id' ],
            'project_id' => [ 'sometimes', 'integer','exists:projects,id' ],
        ];
    }
}

<?php

namespace App\Http\Requests\Task;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function sync_employee(){
        $task = $this->route('task');
        $employee_ids = $this->get('employee_ids' );

        if( $task instanceof Task && $task->id && is_array($employee_ids) ) {
            $task->employees()->sync( $employee_ids );
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [
            'title' => ['sometimes', 'string'],
            'description' => ['nullable', 'string'],
        ];
    }
}

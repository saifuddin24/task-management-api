<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->tokenCan( 'task.create' );
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'deadline' => ['required', 'date_format:Y-m-d H:i:s'],
            'description' => ['nullable', 'string'],
            'employee_id' => ['required', 'integer'],
            'project_id' => ['nullable', 'integer'],
        ];
    }
}

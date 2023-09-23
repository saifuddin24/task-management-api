<?php

namespace App\Http\Requests;

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

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'deadline' => ['required'],
            'description' => ['nullable', 'string'],
            'employee_id' => ['required', 'integer'],
            'project_id' => ['nullable', 'integer'],
            'created_at' => ['required'],
            'updated_at' => ['nullable'],
        ];
    }
}

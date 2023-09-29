<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
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
            'title' => ['required', 'unique:projects,title', 'string', 'max:255'],
            'slogan' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'deadline' => ['nullable', 'date_format:Y-m-d'],
            'working_days_needed' => ['nullable', 'integer'],
            'manager_id' => ['sometimes', 'integer','exists:users,id']
        ];
    }
}

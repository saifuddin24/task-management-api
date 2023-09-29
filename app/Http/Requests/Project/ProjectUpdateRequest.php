<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateRequest extends FormRequest
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
        $project = $this->route('project');

        return [
            'title' => [ 'required', 'string', 'max:255', "unique:projects,title,{$project->id},id" ],
            'slogan' => [ 'nullable', 'string', 'max:255' ],
            'description' => [ 'nullable', 'string' ],
            'deadline' => ['nullable', 'date_format:Y-m-d'],
            'working_days_needed' => ['nullable', 'integer'],
            'manager_id' => ['sometimes', 'integer','exists:users,id']
        ];
    }
}

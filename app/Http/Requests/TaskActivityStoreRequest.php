<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskActivityStoreRequest extends FormRequest
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
            'progress_percentage' => ['required', 'integer'],
            'task_id' => ['required', 'integer'],
            'created_at' => ['nullable'],
            'finished_at' => ['nullable'],
        ];
    }
}

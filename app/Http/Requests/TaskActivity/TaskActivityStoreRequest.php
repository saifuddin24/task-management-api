<?php

namespace App\Http\Requests\TaskActivity;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class TaskActivityStoreRequest extends TaskActivityFormRequest
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
            'created_at' => [
                'sometimes','date_format:Y-m-d H:i:s', $this->last_finished_at_validation()
            ],
            'finished_at' => [
                'required', 'date_format:Y-m-d H:i:s', 'after:created_at'
            ],
            'progress_percentage' => [
                "required", 'integer',  $this->max_progress_validation()
            ],
        ];
    }

}

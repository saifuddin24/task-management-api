<?php

namespace App\Http\Requests\TaskActivity;

class TaskActivityFinishRequest extends TaskActivityFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize():bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        $activity = $this->route('unfinished_activity');

        return [
            'progress_percentage' => [
                'required', 'integer', $this->max_progress_validation( )
            ],
            'finished_at' => [
                'required', 'date_format:Y-m-d H:i:s', 'after:'.$activity->created_at,
            ],
        ];
    }
}

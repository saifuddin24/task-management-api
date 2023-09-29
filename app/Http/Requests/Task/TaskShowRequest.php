<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class TaskShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->tokenCan('task.read');
    }

}

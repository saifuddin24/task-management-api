<?php

namespace App\Http\Requests\TaskActivity;

use Illuminate\Foundation\Http\FormRequest;

class TaskActivityShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

}

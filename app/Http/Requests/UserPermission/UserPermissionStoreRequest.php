<?php

namespace App\Http\Requests\UserPermission;

use Illuminate\Foundation\Http\FormRequest;

class UserPermissionStoreRequest extends FormRequest
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
            'permission_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
        ];
    }
}

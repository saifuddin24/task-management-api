<?php

namespace App\Http\Requests\RolePermission;

use Illuminate\Foundation\Http\FormRequest;

class RolePermissionAssignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize( ): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

        ];
    }
}

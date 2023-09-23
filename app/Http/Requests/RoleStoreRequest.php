<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class RoleStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);

        $data['guard_name'] = $data['guard_name'] ?? 'sanctum';

        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [ 'required', 'string', 'max:50', 'unique:roles,name' ],
            'guard_name' => [ 'nullable', 'string', 'max:20', 'min:2' ] ,
        ];
    }
}

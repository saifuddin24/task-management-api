<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() ? $this->user()->tokenCan( 'update-user' ):true;
    }


    public function withValidator(Validator $data)
    {
        dd( $data->validated() );
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20' , "unique:users,phone,{$user->id},id"],
            'email' => ['nullable', 'email', 'max:100', "unique:users,email,{$user->id},id"],
        ];
    }
}

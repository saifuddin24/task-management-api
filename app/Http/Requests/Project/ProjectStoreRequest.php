<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        if( $this->has('manager_id' ) ) {
            return $this->get('manager_id' ) && $this->user()->primary_role->level <= 2;
        }

        return true;
    }

    public function validated($key = null, $default = null){

        $data = parent::validated($key, $default);

        if( !isset($data['manager_id']) && $this->user()->primary_role->level <= 2 ) {
            $data['manager_id'] = $this->user()->id;
        }

        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'unique:projects,title', 'string', 'max:255'],
            'slogan' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'deadline' => ['nullable', 'date_format:Y-m-d','after:today'],
            'working_days_needed' => ['nullable', 'integer'],
            'manager_id' => ['sometimes', 'integer','exists:users,id']
        ];
    }
}

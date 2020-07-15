<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTenantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required', 'string',
            ],
            'email' => [
                'required', 'email', 'unique:users,email,' . request()->route('tenant')->id,
            ],
            'domain' => [
                'required', 'alpha_num', 'unique:users,domain,' . request()->route('tenant')->id,
            ],
        ];
    }
}

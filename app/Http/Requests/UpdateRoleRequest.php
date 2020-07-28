<?php

namespace App\Http\Requests;

use App\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        abort_if(Gate::denies('role_management_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $permissions = Role::withoutGlobalScopes()
            ->findOrFail(3)
            ->permissions()
            ->pluck('id');

        return [
            'title' => [
                'required', 'string',
            ],
            'permissions' => [
                'array',
            ],
            'permissions.*' => [
                'integer', 'in:' . $permissions->implode(','),
            ],
        ];
    }
}

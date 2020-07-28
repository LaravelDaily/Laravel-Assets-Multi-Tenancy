<?php

namespace App\Http\Requests;

use App\AssetGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class StoreAssetGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        abort_if(Gate::denies('asset_group_management_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
            'parent_id' => [
                'nullable', 'integer', 'in:' . AssetGroup::whereNull('parent_id')->pluck('id')->implode(',')
            ],
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\AssetGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class UpdateAssetGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        abort_if(Gate::denies('asset_group_management_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $availableGroups = AssetGroup::whereNull('parent_id')
            ->where('id', '!=', $this->route('asset_group')->id)
            ->pluck('id')
            ->implode(',');

        return [
            'name' => [
                'required', 'string',
            ],
            'parent_id' => [
                'nullable', 'integer', 'in:' . $availableGroups,
            ],
        ];
    }
}

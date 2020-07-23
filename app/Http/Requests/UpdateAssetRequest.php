<?php

namespace App\Http\Requests;

use App\AssetGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class UpdateAssetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        abort_if(Gate::denies('asset_management_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
            'description' => [
                'nullable', 'string',
            ],
            'serial_number' => [
                'nullable', 'string',
            ],
            'price' => [
                'nullable', 'numeric', 'min:0',
            ],
            'warranty_expiry_date' => [
                'nullable', 'date',
            ],
            'sub_group_id' => [
                'required', 'integer', 'in:' . AssetGroup::whereNotNull('parent_id')->pluck('id')->implode(','),
            ],
        ];
    }
}

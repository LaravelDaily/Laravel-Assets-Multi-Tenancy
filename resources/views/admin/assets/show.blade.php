@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        Asset {{ $asset->name }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tr>
                <th>
                    ID
                </th>
                <td>
                    {{ $asset->id }}
                </td>
            </tr>
            <tr>
                <th>
                    Name
                </th>
                <td>
                    {{ $asset->name ?? '' }}
                </td>
            </tr>
            <tr>
                <th>
                    Description
                </th>
                <td>
                    {{ $asset->description ?? '' }}
                </td>
            </tr>
            <tr>
                <th>
                    Serial Number
                </th>
                <td>
                    {{ $asset->serial_number ?? '' }}
                </td>
            </tr>
            <tr>
                <th>
                    Price
                </th>
                <td>
                    {{ $asset->price ?? '' }}
                </td>
            </tr>
            <tr>
                <th>
                    Warranty Expiry Date
                </th>
                <td>
                    {{ $asset->warranty_expiry_date ?? '' }}
                </td>
            </tr>
            <tr>
                <th>
                    Group
                </th>
                <td>
                    {{ optional($asset->subGroup)->parent ? $asset->subGroup->parent->name : '' }}
                </td>
            </tr>
            <tr>
                <th>
                    Sub-group
                </th>
                <td>
                    {{ $asset->subGroup ? $asset->subGroup->name : '' }}
                </td>
            </tr>
        </table>
        <div class="form-group">
            <a href="{{ route('admin.assets.index') }}" class="btn btn-info">
                Back to list
            </a>
        </div>
    </div>
</div>
@endsection

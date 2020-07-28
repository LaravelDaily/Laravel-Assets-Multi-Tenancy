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

<div class="card">
    <div class="card-header">
        Related data
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        @can('image_management_access')
            <li class="nav-item">
                <a class="nav-link" href="#images" role="tab" data-toggle="tab">
                    Images
                </a>
            </li>
        @endcan
        @can('document_management_access')
            <li class="nav-item">
                <a class="nav-link" href="#documents" role="tab" data-toggle="tab">
                    Documents
                </a>
            </li>
        @endcan
        @can('note_management_access')
            <li class="nav-item">
                <a class="nav-link" href="#notes" role="tab" data-toggle="tab">
                    Notes
                </a>
            </li>
        @endcan
    </ul>
    <div class="tab-content">
        @can('image_management_access')
            <div class="tab-pane p-2" role="tabpanel" id="images">
                @includeIf('admin.assets.relationships.images', ['id' => $asset->id])
            </div>
        @endcan
        @can('document_management_access')
            <div class="tab-pane p-2" role="tabpanel" id="documents">
                @includeIf('admin.assets.relationships.documents', ['id' => $asset->id])
            </div>
        @endcan
        @can('note_management_access')
            <div class="tab-pane p-2" role="tabpanel" id="notes">
                @includeIf('admin.assets.relationships.notes', ['id' => $asset->id])
            </div>
        @endcan
    </div>
</div>
@endsection

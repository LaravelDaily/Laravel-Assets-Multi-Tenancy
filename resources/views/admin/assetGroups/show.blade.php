@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        Asset group {{ $assetGroup->name }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tr>
                <th>
                    ID
                </th>
                <td>
                    {{ $assetGroup->id }}
                </td>
            </tr>
            <tr>
                <th>
                    Name
                </th>
                <td>
                    @if ($assetGroup->parent)
                        {{ $assetGroup->parent->name }}
                        <i class="fas fa-arrow-right"></i>
                    @endif
                    {{ $assetGroup->name ?? '' }}
                </td>
            </tr>
        </table>
        <div class="form-group">
            <a href="{{ route('admin.asset-groups.index') }}" class="btn btn-info">
                Back to list
            </a>
        </div>
    </div>
</div>
@endsection

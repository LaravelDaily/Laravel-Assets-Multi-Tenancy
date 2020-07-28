@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        Image #{{ $image->id }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tr>
                <th>
                    ID
                </th>
                <td>
                    {{ $image->id }}
                </td>
            </tr>
            <tr>
                <th>
                    Asset
                </th>
                <td>
                    {{ $image->asset ? $image->asset->name : '' }}
                </td>
            </tr>
            <tr>
                <th>
                    Image
                </th>
                <td>
                    @if ($image->image)
                        <a href="{{ $image->image->url }}" target="_blank">
                            <img src="{{ $image->image->thumbnail }}" width="50px" height="50px">
                        </a>
                    @endif
                </td>
            </tr>
            <tr>
                <th>
                    Description
                </th>
                <td>
                    {{ $image->description ?? '' }}
                </td>
            </tr>
        </table>
        <div class="form-group">
            <a href="{{ route('admin.images.index') }}" class="btn btn-info">
                Back to list
            </a>
        </div>
    </div>
</div>
@endsection

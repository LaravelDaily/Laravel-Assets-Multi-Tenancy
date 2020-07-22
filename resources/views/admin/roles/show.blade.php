@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        Role {{ $role->title }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tr>
                <th>
                    ID
                </th>
                <td>
                    {{ $role->id }}
                </td>
            </tr>
            <tr>
                <th>
                    Title
                </th>
                <td>
                    {{ $role->title ?? '' }}
                </td>
            </tr>
            <tr>
                <th>
                    Permissions
                </th>
                <td>
                    @foreach($role->permissions as $key => $item)
                        <span class="badge badge-info">{{ $item->title }}</span>
                    @endforeach
                </td>
            </tr>
        </table>
        <div class="form-group">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-info">
                Back to list
            </a>
        </div>
    </div>
</div>
@endsection

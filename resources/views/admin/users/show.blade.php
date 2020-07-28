@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        User {{ $user->name }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tr>
                <th>
                    ID
                </th>
                <td>
                    {{ $user->id }}
                </td>
            </tr>
            <tr>
                <th>
                    Name
                </th>
                <td>
                    {{ $user->name ?? '' }}
                </td>
            </tr>
            <tr>
                <th>
                    Email
                </th>
                <td>
                    {{ $user->email ?? '' }}
                </td>
            </tr>
        </table>
        <div class="form-group">
            <a href="{{ route('admin.users.index') }}" class="btn btn-info">
                Back to list
            </a>
        </div>
    </div>
</div>
@endsection

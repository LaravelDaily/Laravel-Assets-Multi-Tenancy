@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        Tenant {{ $tenant->name }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tr>
                <th>
                    ID
                </th>
                <td>
                    {{ $tenant->id }}
                </td>
            </tr>
            <tr>
                <th>
                    Name
                </th>
                <td>
                    {{ $tenant->name ?? '' }}
                </td>
            </tr>
            <tr>
                <th>
                    Email
                </th>
                <td>
                    {{ $tenant->email ?? '' }}
                </td>
            </tr>
            <tr>
                <th>
                    Domain
                </th>
                <td>
                    {!! $tenant->domain ? '<a href="' . route('tenant.show', $tenant) . '">' . route('tenant.show', $tenant) . '</a>' : '' !!}
                </td>
            </tr>
        </table>
        <div class="form-group">
            <a href="{{ route('admin.tenants.index') }}" class="btn btn-info">
                Back to list
            </a>
        </div>
    </div>
</div>
@endsection

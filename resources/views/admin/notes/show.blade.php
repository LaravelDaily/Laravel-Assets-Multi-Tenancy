@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        Note #{{ $note->id }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tr>
                <th>
                    ID
                </th>
                <td>
                    {{ $note->id }}
                </td>
            </tr>
            <tr>
                <th>
                    Asset
                </th>
                <td>
                    {{ $note->asset ? $note->asset->name : '' }}
                </td>
            </tr>
            <tr>
                <th>
                    Note Text
                </th>
                <td>
                    {{ $note->text ?? '' }}
                </td>
            </tr>
        </table>
        <div class="form-group">
            <a href="{{ route('admin.notes.index') }}" class="btn btn-info">
                Back to list
            </a>
        </div>
    </div>
</div>
@endsection

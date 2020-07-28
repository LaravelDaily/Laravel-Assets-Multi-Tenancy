@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        Document #{{ $document->id }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tr>
                <th>
                    ID
                </th>
                <td>
                    {{ $document->id }}
                </td>
            </tr>
            <tr>
                <th>
                    Asset
                </th>
                <td>
                    {{ $document->asset ? $document->asset->name : '' }}
                </td>
            </tr>
            <tr>
                <th>
                    Document
                </th>
                <td>
                    @if ($document->document)
                        <a href="{{ $document->document->getUrl() }}" target="_blank">
                            Download File
                        </a>
                    @endif
                </td>
            </tr>
            <tr>
                <th>
                    Description
                </th>
                <td>
                    {{ $document->description ?? '' }}
                </td>
            </tr>
        </table>
        <div class="form-group">
            <a href="{{ route('admin.documents.index') }}" class="btn btn-info">
                Back to list
            </a>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        Edit note
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.notes.update", $note) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="text">Note Text</label>
                <textarea class="form-control {{ $errors->has('text') ? 'is-invalid' : '' }}" name="text" id="text">{{ old('text', $note->text) }}</textarea>
                @if($errors->has('text'))
                    <div class="invalid-feedback">
                        {{ $errors->first('text') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="asset_id">Asset</label>
                <select class="form-control select2 {{ $errors->has('asset_id') ? 'is-invalid' : '' }}" id="asset_id" name="asset_id">
                    @foreach($assets as $id => $asset)
                        <option value="{{ $id }}" {{ old('asset_id', $note->asset_id) == $id ? 'selected' : '' }}>{{ $asset }}</option>
                    @endforeach
                </select>
                @if($errors->has('asset_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('asset_id') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <button class="btn btn-success" type="submit">
                    Edit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

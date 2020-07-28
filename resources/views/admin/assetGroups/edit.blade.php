@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        Edit asset group
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.asset-groups.update", $assetGroup) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $assetGroup->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="parent_id">Parent group</label>
                <select class="form-control select2 {{ $errors->has('parent_id') ? 'is-invalid' : '' }}" name="parent_id" id="parent_id">
                    <option value="">---</option>
                    @foreach($parentGroups as $id => $parentGroup)
                        <option value="{{ $id }}" {{ old('parent_id', $assetGroup->parent_id ?? '') == $id ? 'selected' : '' }}>{{ $parentGroup }}</option>
                    @endforeach
                </select>
                @if($errors->has('parent_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('parent_id') }}
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

@section('scripts')
<script>
    $('.select2').select2()
</script>
@endsection

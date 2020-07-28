@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        Edit asset
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.assets.update", $asset) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $asset->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description', $asset->description) }}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="serial_number">Serial Number</label>
                <input class="form-control {{ $errors->has('serial_number') ? 'is-invalid' : '' }}"
                       type="text"
                       name="serial_number"
                       id="serial_number"
                       value="{{ old('serial_number', $asset->serial_number) }}">
                @if($errors->has('serial_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('serial_number') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number" name="price" id="price" step="0.01" value="{{ old('price', $asset->price) }}">
                @if($errors->has('price'))
                    <div class="invalid-feedback">
                        {{ $errors->first('price') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="warranty_expiry_date">Warranty Expiry Date</label>
                <input class="form-control date {{ $errors->has('price') ? 'is-invalid' : '' }}"
                       type="text"
                       name="warranty_expiry_date"
                       id="warranty_expiry_date"
                       value="{{ old('warranty_expiry_date', $asset->warranty_expiry_date) }}">
                @if($errors->has('warranty_expiry_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('warranty_expiry_date') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="group_id">Group</label>
                <select class="form-control select2 {{ $errors->has('parent_id') ? 'is-invalid' : '' }}" id="group_id" name="group_id">
                    @foreach($parentGroups as $id => $parentGroup)
                        <option value="{{ $id }}" {{ old('group_id', optional($asset->subGroup)->parent_id) == $id ? 'selected' : '' }}>{{ $parentGroup }}</option>
                    @endforeach
                </select>
                @if($errors->has('parent_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('parent_id') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="sub_group_id">Sub-group</label>
                <select class="form-control select2 {{ $errors->has('sub_group_id') ? 'is-invalid' : '' }}" id="sub_group_id" name="sub_group_id" required>
                    @if(old('sub_group_id', $asset->sub_group_id) && isset($subGroups[old('group_id', optional($asset->subGroup)->parent_id)]))
                        @foreach($subGroups[old('group_id', optional($asset->subGroup)->parent_id)] as $id => $group)
                            <option value="{{ $id }}" {{ old('sub_group_id', $asset->sub_group_id) == $id ? 'selected' : '' }}>{{ $group }}</option>
                        @endforeach
                    @endif
                </select>
                @if($errors->has('sub_group_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sub_group_id') }}
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
    let subGroups = @json($subGroups)

    $(function() {
        let changeSubGroups = function () {
            let data = []
            let index = $('#group_id').val()
            if (subGroups[index]) {
                for (const [id, text] of Object.entries(subGroups[index])) {
                    data.push({
                        id,
                        text
                    })
                }
            }

            $("#sub_group_id").empty()
            $('#sub_group_id').select2({
                data
            })
        }

        @if (!old('group_id', optional($asset->subGroup)->parent_id) || !old('sub_group_id', $asset->sub_group_id))
            changeSubGroups()
        @endif
        $('#group_id').change(changeSubGroups)
    })
</script>
@endsection

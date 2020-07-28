@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        Add image
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.images.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="image">Image</label>
                <div class="needsclick dropzone {{ $errors->has('image') ? 'is-invalid' : '' }}" id="image-dropzone">
                </div>
                @if($errors->has('image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('image') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description', '') }}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
            </div>
            @if(request()->input('asset_id'))
                <input type="hidden" name="asset_id" value="{{ request()->input('asset_id') }}">
            @else
                <div class="form-group">
                    <label for="asset_id">Asset</label>
                    <select class="form-control select2 {{ $errors->has('asset_id') ? 'is-invalid' : '' }}" id="asset_id" name="asset_id">
                        @foreach($assets as $id => $asset)
                            <option value="{{ $id }}" {{ old('asset_id', '') == $id ? 'selected' : '' }}>{{ $asset }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('asset_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('asset_id') }}
                        </div>
                    @endif
                </div>
            @endif
            <div class="form-group">
                <button class="btn btn-success" type="submit">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    Dropzone.options.imageDropzone = {
        url: '{{ route('admin.images.storeMedia') }}',
        maxFilesize: 2, // MB
        acceptedFiles: '.jpeg,.jpg,.png,.gif',
        maxFiles: 1,
        addRemoveLinks: true,
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        params: {
            size: 2,
            width: 4096,
            height: 4096
        },
        success: function (file, response) {
            $('form').find('input[name="image"]').remove()
            $('form').append('<input type="hidden" name="image" value="' + response.name + '">')
        },
        removedfile: function (file) {
            file.previewElement.remove()
            if (file.status !== 'error') {
                $('form').find('input[name="image"]').remove()
                this.options.maxFiles = this.options.maxFiles + 1
            }
        },
        init: function () {
                @if(isset($image) && $image->image)
            var file = {!! json_encode($image->image) !!}
                    this.options.addedfile.call(this, file)
            this.options.thumbnail.call(this, file, file.preview)
            file.previewElement.classList.add('dz-complete')
            $('form').append('<input type="hidden" name="image" value="' + file.file_name + '">')
            this.options.maxFiles = this.options.maxFiles - 1
            @endif
        },
        error: function (file, response) {
            if ($.type(response) === 'string') {
                var message = response //dropzone sends it's own error messages in string
            } else {
                var message = response.errors.file
            }
            file.previewElement.classList.add('dz-error')
            _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
            _results = []
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                node = _ref[_i]
                _results.push(node.textContent = message)
            }

            return _results
        }
    }
</script>
@endsection

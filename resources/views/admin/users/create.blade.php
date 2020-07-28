@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        Create user
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.users.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="text" name="email" id="email" value="{{ old('email', '') }}" required>
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="role_id">Role</label>
                <select class="form-control select2 {{ $errors->has('role_id') ? 'is-invalid' : '' }}" name="role_id" id="role_id" required>
                    @foreach($roles as $id => $roles)
                        <option value="{{ $id }}" {{ old('role_id', 3) == $id ? 'selected' : '' }}>{{ $roles }}</option>
                    @endforeach
                </select>
                @if($errors->has('role_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('role_id') }}
                    </div>
                @endif
            </div>
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
    $('.select2').select2()
</script>
@endsection

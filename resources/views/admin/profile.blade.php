@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        My profile
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.profile.update") }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="text" id="email" value="{{ $user->email }}" disabled>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password" value="">
                @if($errors->has('password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" type="password" name="password_confirmation" id="password_confirmation" value="">
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

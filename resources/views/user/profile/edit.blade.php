@extends('layouts.app')
@extends('layouts.sidebarProfile')

@section('content2')

<!-- Profile Info -->
<div class="col-md-5 border-right">
    <div class="p-3 py-5 shadow-sm p-3 mb-5 bg-white rounded">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="text-right">{{ __('Profile Info') }}</h5>
        </div>
        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
            <div class="row mt-3">
                <div class="col-md-12">
                    <label for="avatar">{{ __('Avatar') }}</label>
                    <input type="file" id="avatar" name="avatar" class="form-control">
                    <input type="hidden" id="avatar" name="avatarExist" value="{{ $user->avatar }}" class="form-control">
                    @error('image')
                        <div class="alert-danger"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <label class="labels">{{ __('Name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="text-danger"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <label class="labels">{{ __('Address') }}</label>
                    <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address">{{ $user->address}}</textarea>
                    @error('address')
                        <div class="text-danger"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mt-5 text-center">
                <button class="btn btn-primary profile-button" type="submit">{{ __('Save Change') }}</button>
            </div>
        </form>
    </div>
</div>
<!-- Security Settings -->
<div class="col-md-4 border-right bg-light">
    <div class="p-3 py-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="text-right">{{ __('Security Settings') }}</h5>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <label class="labels">{{ __('Email') }}</label>
                <p class="mt-2 text-secondary">
                    <i class="fa fa-envelope-o mr-1" aria-hidden="true"></i>
                    {{ $user->email }}
                </p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <label class="labels">{{ __('Phone') }}</label>
                <p class="mt-2 text-secondary">
                    <i class="fa fa-phone mr-1" aria-hidden="true"></i>
                    {{ $user->phone }}
                </p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-9">
                <label class="labels">{{ __('Password') }}</label>
                <p class="mt-2 text-secondary">
                    <i class="fa fa-lock mr-1" aria-hidden="true"></i>
                    {{ __('Change Password') }}
                </p>
            </div>
            <div class="col-md-3">
                <a href="{{ route('password.edit') }}" class="btn btn-outline-primary profile-button mt-4">{{ __('Change') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection

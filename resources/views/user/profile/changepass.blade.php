@extends('layouts.app')
@extends('layouts.sidebarProfile')

@section('content2')

<!-- Change Password -->
<div class="col-md-5 border-right ms-3 shadow p-3 mb-5 bg-white rounded">
    <div class="p-3 py-5">
        <div class="align-items-center mb-3">
            <h5 class="text-center">{{ __('Change Password') }}</h5>
        </div>
        <form method="post" action="{{ route('password.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row mb-3">
                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Current Password') }}</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="current_password">
                    @error('current_password')
                        <span class="text-danger"><small>{{ $message }}</small></span>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('New Password') }}</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="new_password">
                    @error('new_password')
                        <span class="text-danger"><small>{{ $message }}</small></span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                <div class="col-md-6">
                    <input id="password-confirm" type="password" class="form-control" name="confirm_password">
                    @error('confirm_password')
                        <span class="text-danger"><small>{{ $message }}</small></span>
                    @enderror
                </div>
            </div>
            <div class="mt-5 text-center">
                <button class="btn btn-primary profile-button" type="submit">{{ __('Save Change') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

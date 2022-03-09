@extends('layouts.admin.app')

@section('content')
<!-- Main content -->
<section class="content-wrapper">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Add User') }}</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">{{ __('Name') }}</label> <span class="text-danger">*</span>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <span class="error invalid-feedback"> {{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">{{ __('Email') }}</label> <span class="text-danger">*</span>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                                @error('email')
                                    <span class="error invalid-feedback"> {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="phone">{{ __('Phone number') }}</label> <span class="text-danger">*</span>
                                <input type="phone" id="phone" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" required>
                                @error('phone')
                                    <span class="error invalid-feedback"> {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">{{ __('Password') }}</label> <span class="text-danger">*</span>
                                <input type="password" id="password" name="password" value="{{ old('password') }}" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <span class="error invalid-feedback"> {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password-confirm">{{ __('Confirm Password') }}</label> <span class="text-danger">*</span>
                                <input type="password" id="password-confirm" name="password_confirmation" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="role">{{ __('Role') }}</label> <span class="text-danger">*</span>
                                <select id="role" name="role" class="form-control custom-select @error('role') is-invalid @enderror" required>
                                    <option value="{{ App\Enums\UserRole::ADMIN }}">{{ __('Admin') }}</option>
                                    <option value="{{ App\Enums\UserRole::USER }}">{{ __('User') }}</option>
                                </select>
                                @error('role')
                                    <span class="error invalid-feedback"> {{ $message }}</span>
                                @enderror
                            </div>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                            <input type="submit" value="{{ __('Create') }}" class="btn btn-success float-right">
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection

@extends('layouts.admin.app')

@section('content')
<!-- Main content -->
<section class="content-wrapper">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Edit User') }}</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <label for="name">{{ __('Name') }}</label> <span class="text-danger">*</span>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <span class="error invalid-feedback"> {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="phone">{{ __('Phone number') }}</label> <span class="text-danger">*</span>
                                <input type="phone" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control @error('phone') is-invalid @enderror" required>
                                @error('phone')
                                    <span class="error invalid-feedback"> {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">{{ __('Password') }}</label>
                                <span class="text-info"> ({{ __('Leave blank if you do not want to change it') }})</span>
                                <input type="text" id="password" name="password" value="{{ old('password') }}" class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <span class="error invalid-feedback"> {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="address">{{ __('Address') }}</label>
                                <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}" class="form-control @error('address') is-invalid @enderror">
                                @error('address')
                                    <span class="error invalid-feedback"> {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="role">{{ __('Role') }}</label> <span class="text-danger">*</span>
                                <select id="role" name="role" class="form-control custom-select @error('role') is-invalid @enderror" required>
                                    <option value="{{ App\Enums\UserRole::ADMIN }}" @if ($user->role == App\Enums\UserRole::ADMIN) selected @endif>{{ __('Admin') }}</option>
                                    <option value="{{ App\Enums\UserRole::USER }}" @if ($user->role == App\Enums\UserRole::USER) selected @endif>{{ __('User') }}</option>
                                </select>
                                @error('role')
                                    <span class="error invalid-feedback"> {{ $message }}</span>
                                @enderror
                            </div>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                            <input type="submit" value="{{ __('Edit') }}" class="btn btn-success float-right">
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

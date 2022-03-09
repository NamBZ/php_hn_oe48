@extends('layouts.admin.app')

@section('content')
<section class="content-wrapper">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="nav-icon fas fa-users"></i>
                            {{ __('User Management') }}
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.users.create')}}" class="btn btn-sm btn-success"><i class="fas fa-plus-circle"></i> {{ __('Create') }}</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('#') }}</th>
                                    <th>{{ __('Avatar') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Phone number') }}</th>
                                    <th>{{ __('Role') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($users->isNotEmpty())
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td class="text-center">
                                                @if (!empty($user->avatar))
                                                    <img class="style-image rounded-circle" width="50" src="{{ $user->avatar }}" alt="{{ $user->name }}">
                                                @else
                                                    <i class="fas fa-user"></i>
                                                @endif
                                            </td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td>
                                                @switch($user->role)
                                                    @case(App\Enums\UserRole::ADMIN)
                                                        <span class="badge badge-primary">{{ __('Admin') }}</span>
                                                        @break

                                                    @case(App\Enums\UserRole::USER)
                                                        <span class="badge badge-info">{{ __('User') }}</span>
                                                        @break

                                                    @default
                                                        <span class="badge badge-secondary">{{ __('Unknown') }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @switch($user->status)
                                                    @case(App\Enums\UserStatus::ACTIVE)
                                                        <span class="badge badge-success">{{ __('Active') }}</span>
                                                        @break

                                                    @case(App\Enums\UserStatus::BAN)
                                                        <span class="badge badge-secondary">{{ __('Banned') }}</span>
                                                        @break

                                                    @default
                                                        <span class="badge badge-secondary">{{ __('Unknown') }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.users.edit', $user->id)}}" class="btn btn-xs btn-primary"><i class="fas fa-user-edit"></i> {{ __('Edit') }}</a>
                                                <form class="list-inline-item" action="{{ route('admin.users.destroy', $user->id)}}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-xs btn-danger" type="submit" onclick="return confirm('{{ __('Comfirm Deleted') }}');"><i class="fas fa-trash-alt"></i> {{ __('Delete') }}</button>
                                                </form>
                                                @if ($user->role != App\Enums\UserRole::ADMIN)
                                                    @if ($user->status == App\Enums\UserStatus::ACTIVE)
                                                        <form class="list-inline-item" action="{{ route('admin.users.block', $user->id)}}" method="post">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button class="btn btn-xs btn-warning" type="submit" onclick="return confirm('{{ __('Comfirm Block') }}');"><i class="fas fa-lock"></i> {{ __('Block') }}</button>
                                                        </form>
                                                    @else
                                                        <form class="list-inline-item" action="{{ route('admin.users.unblock', $user->id)}}" method="post">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button class="btn btn-xs btn-info" type="submit" onclick="return confirm('{{ __('Comfirm UnBlock') }}');"><i class="fas fa-unlock"></i> {{ __('UnBlock') }}</button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                        <div class="alert alert-primary" role="alert">{{ __('Empty') }}</div>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-footer -->
                    <div class="card-footer clearfix d-flex justify-content-center">
                        {{ $users->links() }}
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

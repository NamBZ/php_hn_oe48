@extends('layouts.admin.app')

@section('content')
<section class="content-wrapper">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title col-md-12">{{ __('List Category') }}</h3>
                        <div class="col-md-12">
                            <a href="{{ route('admin.categories.create')}}" class="btn btn-success my-2">{{ __('Create') }}</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Slug') }}</th>
                                    <th colspan="2" style="width: 100px;">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($categories->isNotEmpty())
                                    @foreach ($categories as $key => $category)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                @if ($category->parent_id == 0)
                                                    <span style="color:red;">{{ __('Root Category') }}</span>
                                                @else
                                                    <span style="color:green;">{{$category->parent->name}}</span>  
                                                @endif
                                            </td>
                                            <td>{{ $category->slug }}</td>
                                            <td><a href="{{ route('admin.categories.edit', $category->id)}}" class="btn btn-primary">{{ __('Edit') }}</a></td>
                                            <td>
                                                <form action="{{ route('admin.categories.destroy', $category->id)}}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger" type="submit" onclick="return confirm('{{ __('Comfirm Deleted Category') }}');">{{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                        <div class="alert alert-primary" role="alert">{{ __('Empty category') }}</div>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        {{ $categories->links() }}
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

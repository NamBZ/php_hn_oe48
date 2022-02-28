@extends('layouts.admin.app')

@section('content')
<!-- Main content -->
<section class="content-wrapper">
    <div class="row justify-content-center">
        <div class="col-md-9">
            @if ($errors->has('name'))
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->get('name') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Edit Category') }}</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.categories.update', $category->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="name">{{ __('Title') }}</label>
                            <input type="text" id="name" name="name" value="{{ $category->name }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="parent">{{ __('Type') }}</label>
                            <select id="parent" name="parent_id" class="form-control custom-select">
                                <option value='0'>---Default---</option>
                                @foreach ($categories as $key => $sub_cate) {
                                    @if ($sub_cate->parent_id == null) {
                                        <option value="{{ $sub_cate->id }}" @if ($category->parent_id == $sub_cate->id) selected @endif>{{ $sub_cate->name }}</option>
                                    }
                                    @endif
                                }
                                @endforeach
                            </select>
                        </div>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                        <input type="submit" value="{{ __('Update') }}" class="btn btn-success float-right">
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</section>
<!-- /.content -->
@endsection

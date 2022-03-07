@extends('layouts.admin.app')

@section('content')
<!-- Main content -->
<section class="content-wrapper">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ __('New Product') }}</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="title">{{ __('Title') }}</label>
                            <input type="text" id="title" name="title" value="{{ $product->title }}" class="form-control">
                            @error('title')
                                <div class="alert-danger"> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="quantity">{{ __('Quantity') }}</label>
                            <input type="text" id="quantity" name="quantity" value="{{ $product->quantity }}" class="form-control">
                            @error('quantity')
                                <div class="alert-danger"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="retail_price">{{ __('Retail Price') }}</label>
                            <input type="text" id="retail_price" name="retail_price" value="{{ $product->retail_price }}" class="form-control price_format">
                            @error('retail_price')
                                <div class="alert-danger"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="original_price">{{ __('Original Price') }}</label>
                            <input type="text" id="original_price" name="original_price" value="{{ $product->original_price }}" class="form-control price_format">
                            @error('original_price')
                                <div class="alert-danger"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="editor1_content">{{ __('Content') }}</label>
                            <textarea name="content" id="editor_content" class="form-control">
                                {{ $product->content }}
                            </textarea>
                            @error('content')
                                <div class="alert-danger"> {{ $message }}</div>
                            @enderror
                            <script>
                                CKEDITOR.replace('editor_content');
                            </script>
                        </div>
                        <div class="form-group">
                            <label for="editor1_des">{{ __('Description') }}</label>
                            <textarea name="description" id="editor_desc" class="form-control">
                                {{ $product->description }}
                            </textarea>
                            @error('description')
                                <div class="alert-danger"> {{ $message }}</div>
                            @enderror
                            <script>
                                CKEDITOR.replace('editor_desc');
                            </script>
                        </div>
                        <div class="form-group">
                            <label for="image">{{ __('Image') }}</label>
                            <img  class="style-image" src="{{ $product->image }}">
                            <input type="file" id="image" name="image" class="form-control">
                            <input type="hidden" id="image" name="imageExist" value="{{ $product->image }}" class="form-control">
                            @error('image')
                                <div class="alert-danger"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="parent">{{ __('Category') }}</label>
                            <select id="parent" name="category_id" class="form-control custom-select">
                                @foreach ($categories as $key => $category) {
                                    @if ($category->parent_id == 0) {
                                        <option value="{{ $category->id }}" @if ($category->id == $product->category_id) selected @endif>
                                            {{ $category->name }}
                                        </option>
                                    }
                                    @else {
                                        <option value="{{ $category->id }}" @if ($category->id == $product->category_id) selected @endif>
                                            --{{ $category->name }}
                                        </option>
                                    }
                                    @endif
                                }
                                @endforeach
                            </select>
                        </div>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
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

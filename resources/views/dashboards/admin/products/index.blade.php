@extends('layouts.admin.app')

@section('content')
<section class="content-wrapper">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title col-md-12">{{ __('List Product') }}</h3>
                        <div class="col-md-12">
                            <a href="{{ route('admin.products.create')}}" class="btn btn-success my-2">{{ __('Create') }}</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="col-md-1">{{ __('Image') }}</th>
                                    <th class="col-md-2">{{ __('Title') }}</th>
                                    <th class="col-md-1">{{ __('Category') }}</th>
                                    <th class="col-md-2">{{ __('Slug') }}</th>
                                    <th class="col-md-1">{{ __('Quantity') }}</th>
                                    <th class="col-md-1">{{ __('Sold') }}</th>
                                    <th class="col-md-1">{{ __('Retail Price') }}</th>
                                    <th class="col-md-1">{{ __('Original Price') }}</th>
                                    <th class="col-md-1">{{ __('Average Rate') }}</th>
                                    <th colspan="2" class="btn-action">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($products->isNotEmpty())
                                    @foreach ($products as $key => $product)
                                        <tr>
                                            <td><img class="style-image" src="{{ $product->image }}" alt=""></td>
                                            <td>{{ $product->title }}</td>
                                            <td>{{ $product->category->name }}</td>
                                            <td>{{ $product->slug }}</td>
                                            <td>{{ $product->quantity }}</td>
                                            <td>{{ $product->sold }}</td>
                                            <td>{{ $product->retail_price }}</td>
                                            <td>{{ $product->original_price }}</td>
                                            <td>{{ $product->avg_rate }}</td>
                                            <td><a href="{{ route('admin.products.edit', $product->id)}}" class="btn btn-primary">{{ __('Edit') }}</a></td>
                                            <td>
                                                <form action="{{ route('admin.products.destroy', $product->id)}}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger" type="submit" onclick="return confirm('{{ __('Comfirm Deleted') }}');">{{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                        <div class="alert alert-primary" role="alert">{{ __('Empty product') }}</div>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        {{ $products->links() }}
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

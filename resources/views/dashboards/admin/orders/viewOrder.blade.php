@extends('layouts.admin.app')

@section('content')
<section class="content-wrapper">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg bg-info">
                        <h3 class="card-title col-md-12 text-center">{{ __('Customer Info') }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Customer Name') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Email') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($getCustomer->isNotEmpty())
                                    @foreach ($getCustomer as $key => $customer)
                                    <tr>
                                        <td>{{ ++$key}}</td>
                                        <td>{{ $customer->user->name }}</td>
                                        <td>{{ $customer->user->phone }}</td>
                                        <td>{{ $customer->user->email }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                        <div class="alert alert-primary" role="alert">{{ __('Empty user') }}</div>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg bg-info">
                        <h3 class="card-title col-md-12 text-center">{{ __('Shipping Info') }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Address') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Note') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($getShipping->isNotEmpty())
                                    @foreach ($getShipping as $key => $shipping)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $shipping->name }}</td>
                                            <td>{{ $shipping->address }}</td>
                                            <td>{{ $shipping->phone }}</td>
                                            <td>{{ $shipping->note }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                        <div class="alert alert-primary" role="alert">{{ __('Empty list order') }}</div>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg bg-info">
                        <h3 class="card-title col-md-12 text-center">{{ __('List Order Detail') }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Product') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Original Price') }}</th>
                                    <th>{{ __('Retail Price') }}</th>
                                    <th>{{ __('Price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($orderDetails->isNotEmpty())
                                    @foreach ($orderDetails as $key => $order)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td><img class="style-image" src="{{ $order->product->image }}" alt=""></td>
                                            <td>{{ $order->product->title }}</td>
                                            <td>{{ $order->quantity }}</td>
                                            <td>{{ @number_format($order->product->original_price, 0, '', ',') }} đ</td>
                                            <td>{{ @number_format($order->product->retail_price, 0, '', ',') }} đ</td>
                                            <td>{{ @number_format($order->price * $order->quantity, 0, '', ',') }} đ</td>
                                        </tr>
                                    @endforeach
                                @else
                                        <div class="alert alert-primary" role="alert">{{ __('Empty list order') }}</div>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix row">
                        <div class="col-md-6">
                            
                            <form action="{{ route('admin.orders.update', $order->id) }}" method="post">
                                @csrf
                                <div class="form-row align-items-center">
                                    <label class="sr-only" for="inlineFormInputGroupUsername">{{ __('Order status') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">{{ __('Order status') }}</div>
                                        </div>
                                        <select class="form-control" name="status" onchange="this.form.submit()">
                                            <option value=""> Choose Action </option>
                                            @foreach($orderStatusArray as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            
                        </div>
                        <div class="col-md-6 text-right">
                            @foreach ($getOrder as $key => $order)
                                <p>
                                    <span id="taxrate-0" class="col-6">{{ __('Total Price') }}</span>:
                                    {{ @number_format(($order->total_price /1.1), 0, '', ',') }} đ
                                </p>
                                <p>
                                    <span id="taxrate-0" class="col-6">VAT <small>(10%)</small></span>: 
                                    {{ number_format(intval($order->total_price * 0.1)) }} đ
                                </p>
                                <p>
                                    {{ __('Grand Total') }}: 
                                    
                                        {{ @number_format($order->total_price, 0, '', ',') }} đ
                                </p>
                            @endforeach
                        </div>
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

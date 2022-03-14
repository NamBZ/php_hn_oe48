@extends('layouts.admin.app')

@section('content')
<section class="content-wrapper">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <!-- Order Items -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg bg-info">
                        <h3 class="card-title col-md-12 text-center">{{ __('Order Items') }}</h3>
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
                                @if ($orderItems->isNotEmpty())
                                    @foreach ($orderItems as $key => $order)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td><img class="profile-user-img" src="{{ $order->product->image }}" alt=""></td>
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
                            
                            <form action="{{ route('admin.orders.update', $orders->id) }}" method="post">
                                @csrf
                                <div class="form-row align-items-center">
                                    <label class="sr-only" for="inlineFormInputGroupUsername">{{ __('Order status') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">{{ __('Order status') }}</div>
                                        </div>
                                        <select class="form-control" name="status" id="dbType">
                                            @foreach($orderStatus as $key => $status)
                                                @foreach($orderInfo as $ord => $order)
                                                    <option id="status" value="{{ $key }}" @if ($order->status == $key) selected @endif>{{ $status }}</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                        <div class="col-auto" id="form-canceled" style="display: none;">
                                            <input class="form-control" type="text" name="reason_canceled" placeholder="{{ __('Reason Canceled') }}">
                                        </div>
                                    </div>
                                    <button class="btn btn-success mt-2" type="submit" data-cf="{{ __('sure update') }}">
                                    {{ __('Submit') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                        <script>
                            $(document).ready(function()
                            {
                                $('#dbType').click(function(){
                                    var e = document.getElementById("dbType");
                                    var status = e.value;
                                    if(status == {{ App\Enums\OrderStatus::CANCELED }}) {
                                        $('#form-canceled').show();
                                    } else {
                                        $('#form-canceled').hide();
                                    }
                                });
                            });
                        </script>
                        <div class="col-md-6 text-right">
                            @foreach ($orderInfo as $key => $order)
                                <p class="text-uppercase">
                                    <span id="taxrate-0" class="col-6">{{ __('Total Price') }}</span>:
                                    {{ @number_format(($order->total_price /1.1), 0, '', ',') }} đ
                                </p>
                                <p class="text-uppercase">
                                    <span id="taxrate-0" class="col-6">VAT <small>(10%)</small></span>: 
                                    {{ number_format(intval($order->total_price * 0.1)) }} đ
                                </p>
                                <p class="text-uppercase h5 font-weight-bold">
                                    {{ __('Grand Total') }}: 
                                    
                                        {{ @number_format($order->total_price, 0, '', ',') }} đ
                                </p>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- /.card -->

            </div>
            <!-- /.Order Items-->

            <!-- Customer Infor -->
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
                                    @foreach ($getCustomer as $key => $order)
                                    <tr>
                                        <td>{{ ++$key}}</td>
                                        <td>{{ $order->name }}</td>
                                        <td>{{ $order->phone }}</td>
                                        <td>{{ $order->email }}</td>
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
            <!-- /.Customer Infor -->

            <!-- Shipping Infor -->
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
                                    @foreach ($getShipping as $key => $order)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $order->name }}</td>
                                            <td>{{ $order->address }}</td>
                                            <td>{{ $order->phone }}</td>
                                            <td>{{ $order->note }}</td>
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
            <!-- /.Shipping Infor -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

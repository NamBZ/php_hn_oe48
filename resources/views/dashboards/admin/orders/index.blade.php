@extends('layouts.admin.app')

@section('content')
<section class="content-wrapper">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg bg-info">
                        <h3 class="card-title col-md-12 text-center">{{ __('List Order') }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Order Code') }}</th>
                                    <th>{{ __('Created at') }}</th>
                                    <th>{{ __('Updated at') }}</th>
                                    <th>{{ __('Order Status') }}</th>
                                    <th>{{ __('Reason Canceled') }}</th>
                                    <th>{{ __('Total Price') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($orders->isNotEmpty())
                                    @foreach ($orders as $key => $order)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $order->order_code }}</td>
                                            <td>{{ $order->created_at }}</td>
                                            <td>{{ $order->updated_at }}</td>
                                            <td>
                                                @if ($order->status == App\Enums\OrderStatus::NEW_ORDER)
                                                    <span class="text text-primary">{{ __('New Order') }}</span>
                                                @elseif ($order->status == App\Enums\OrderStatus::IN_PROCCESS)
                                                    <span class="text text-primary">{{ __('In Proccess') }}</span>
                                                @elseif ($order->status == App\Enums\OrderStatus::IN_SHIPPING)
                                                    <span class="text text-primary">{{ __('In Shipping') }}</span>
                                                @elseif ($order->status == App\Enums\OrderStatus::COMPLETED)
                                                    <span class="text text-success">{{ __('Delivery Completed') }}</span>
                                                @elseif ($order->status == App\Enums\OrderStatus::CANCELED)
                                                    <span class="text text-danger">{{ __('Order Canceled') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($order->status==4)
                                                    {{ $order->reason_canceled }}
                                                @endif
                                            </td>
                                            <td>{{ @number_format($order->total_price, 0, '', ',') }} đ</td>
                                            <td><a href="{{ route('admin.orders.viewOrder', $order->id) }}" class="btn btn-primary">{{ __('View') }}</a></td>
                                        </tr>
                                    @endforeach
                                @else
                                        <div class="alert alert-primary" role="alert">{{ __('Empty order') }}</div>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        {{ $orders->links() }}
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

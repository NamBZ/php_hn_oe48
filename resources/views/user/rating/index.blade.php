@extends('layouts.app')
@extends('layouts.sidebarProfile')

@section('content2')
<!-- list successful order -->
<div class="col-md-9">
    <div class="card">
        <div class="card-header bg bg-info">
            <h3 class="card-title col-md-12 text-center">{{ __('Successful Order') }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @if ($orders->isNotEmpty())
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Order Code') }}</th>
                            <th>{{ __('Created at') }}</th>
                            <th>{{ __('Order Status') }}</th>
                            <th>{{ __('Total Price') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $key => $order)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $order->order_code }}</td>
                                <td>{{ $order->created_at }}</td>
                                <td>
                                    @if ($order->status == App\Enums\OrderStatus::COMPLETED)
                                        <span class="text text-success">{{ __('Delivery Completed') }}</span>
                                    @endif
                                </td>
                                <td>{{ @number_format($order->total_price, 0, '', ',') }} đ</td>
                                <td><a href="{{ route('user.rating.view', $order->id) }}" class="btn btn-primary">{{ __('View') }}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $orders->links() }}
            @else
                    <div class="alert alert-primary" role="alert">{{ __('Empty') }}</div>
            @endif
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
@endsection

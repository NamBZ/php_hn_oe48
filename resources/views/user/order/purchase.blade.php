@extends('layouts.app')
@extends('layouts.sidebarProfile')

@section('content2')

<!-- Profile Info -->
<div class="col-md-9">
    <div class="card">
        <h5 class="card-header">{{ __('My Purchase') }}</h5>
        <div class="card-body">
            @if ($orders->isNotEmpty())
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ __('Subtotal') }}</th>
                            <th scope="col">{{ __('Date') }}</th>
                            <th scope="col">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('user.purchase.details', $order->id) }}">#{{ $order->order_code }}</a>
                                </td>
                                <td><span class="text-warning">{{ number_format($order->total_price) }} <small>đ</small></span></td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if ($order->status == App\Enums\OrderStatus::NEW_ORDER)
                                        <span class="badge bg-secondary">{{ __('New Order') }}</span>
                                    @elseif ($order->status == App\Enums\OrderStatus::IN_PROCCESS)
                                        <span class="badge bg-warning">{{ __('In Proccess') }}</span>
                                    @elseif ($order->status == App\Enums\OrderStatus::IN_SHIPPING)
                                        <span class="badge bg-info">{{ __('In Shipping') }}</span>
                                    @elseif ($order->status == App\Enums\OrderStatus::COMPLETED)
                                        <span class="badge bg-success">{{ __('Delivery Completed') }}</span>
                                    @elseif ($order->status == App\Enums\OrderStatus::CANCELED)
                                        <span class="badge bg-danger">{{ __('Order Canceled') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $orders->links() }}
            @else
                <div class="alert alert-info"> {{ __('Empty') }}</div>
            @endif
        </div>
    </div>
</div>
@endsection

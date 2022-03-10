@extends('layouts.app')
@extends('layouts.sidebarProfile')

@section('content2')

<!-- Profile Info -->
<div class="col-md-9">
    <div class="card cart-details">
        <h5 class="card-header">{{ __('Order Details') }} #{{ $order->order_code }}</h5>
        <div class="card-body cart-content">
            <div class="row align-items-center justify-content-between">
                @if ($order->status == App\Enums\OrderStatus::NEW_ORDER ||
                    $order->status == App\Enums\OrderStatus::IN_PROCCESS)
                    <form class="col-auto row align-items-center" action="{{ route('user.purchase.cancel', $order->id)}}" method="post">
                        @csrf
                        <div class="col-auto">
                            <input class="form-control" type="text" name="reason_canceled" placeholder="{{ __('Reason cancel') }}">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('{{ __('Comfirm Cancel') }}');"><i class="fas fa-trash-alt"></i> {{ __('Cancel') }}</button>
                        </div>
                    </form>
                @endif

                <div class="col-auto">
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
                        @if (!empty($order->reason_canceled))
                            <span class="text-muted"> ({{ $order->reason_canceled }}) </span>
                        @endif
                    @endif
                </div>
            </div>
            <hr />
            @if ($order->orderItems->isNotEmpty())
                <div class="cart-item-list">
                    @foreach ($order->orderItems as $item)
                        @php
                            $product = $item->product;
                        @endphp
                        <div class="row col-12">
                            <a title="{{ $product->title }}" href="{{ route('products.show', $product->slug) }}" class="product-image-container col-2 text-decoration-none">
                                <img alt="{{ $product->title }}" src="{{ $product->image }}" onerror="this.src='https://i.imgur.com/tHUqrA7.jpg'" class="card-img-top">
                            </a>
                            <div class="product-details-content col-7 pr0">
                                <div class="row item-title no-margin">
                                    <a href="{{ route('products.show', $product->slug) }}" title="{{ $product->title }}" class="unset col-12 no-padding text-decoration-none">
                                        <span class="fs20 fw6 link-color">{{ $product->title }}</span>
                                    </a>
                                </div>
                                <div class="row col-12 no-padding no-margin">
                                    <div class="product-price">
                                        <span class="text-warning">{{ number_format($item->price) }}<small>đ</small></span>
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                        <span>{{ $item->quantity }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="product-price fs18 col-3">
                                <span class="card-current-price fw6 mr10 text-warning"> {{ number_format($item->price * $item->quantity) }}<small>đ</small> </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">{{ __('Order Empty') }}</div>
            @endif

            @if ($order->shipping)
                <div class="order-summary">
                    <h5 class="fw6">{{ __('Shipping Address') }}</h5>
                    <div class="row">
                        <span class="col-6">{{ __('Name') }}</span>
                        <span class="col-6 text-end">{{ $order->shipping->name }}</span>
                    </div>
                    <div class="row">
                        <span class="col-6">{{ __('Address') }}</span>
                        <span class="col-6 text-end">{{ $order->shipping->address }}</span>
                    </div>
                    <div class="row">
                        <span class="col-6">{{ __('Phone number') }}</span>
                        <span class="col-6 text-end">{{ $order->shipping->phone }}</span>
                    </div>
                    <div class="row">
                        <span class="col-6">{{ __('Note') }}</span>
                        <span class="col-6 text-end">{{ $order->shipping->note }}</span>
                    </div>
                    <div class="row">
                        <span class="col-6">{{ __('Order Total') }}</span>
                        <span class="col-6 text-end text-warning">{{ number_format($order->total_price) }}<small>đ</small></span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<section class="pb-5 cart-details">
    @php
        $total = 0;
    @endphp
    <div class="container">
        <div class="row col-12">
            <h2 class="pb-4 col-12">{{ __('Thank you for your order!') }}</h2>
            <div class="cart-details-header col-lg-7 col-md-12 bg-white shadow-lg p-3 rounded-3">
                <h3 class="pb-4 col-12">{{ __('Order Items') }}</h3>
                <div class="cart-content col-12">
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
                </div>
            </div>
            <div class="col-lg-4 col-md-12 offset-lg-1 order-summary-container">
                <div class="order-summary fs16 bg-white shadow-lg">
                    <h3 class="fw6">{{ __('Order Info') }}</h3>
                    <p class="col-12">{{ __('Your order has been successfully with Order Code') }}: <span class="badge bg-info">{{ $order->order_code }}</span></p>
                    <h3 class="fw6">{{ __('Shipping Address') }}</h3>
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
                    <div class="row">
                        <a href="{{ route('home') }}" class="btn btn-success text-decoration-none text-center">{{ __('Continue Shopping') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

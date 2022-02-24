@extends('layouts.app')

@section('content')
<section class="pb-5 cart-details">
    @php
        $total = 0;
    @endphp
    <div class="container">
        <div class="row col-12">
            <h2 class="fw6 col-12">{{ __('Shopping Cart') }}</h2>
            <div class="cart-details-header col-lg-7 col-md-12 bg-white shadow-lg p-3 rounded-3">
                <div class="row cart-header col-12 no-padding">
                    <span class="col-7 fw6 fs16 pr0"> {{ __('Items') }} </span>
                    <span class="col-3 fw6 fs16 no-padding"> {{ __('Qty') }} </span>
                    <span class="col-2 fw6 fs16 text-right pr0"> {{ __('Subtotal') }} </span>
                </div>
                <div class="cart-content col-12">
                    @if (Session::get('cart'))
                        <form method="POST" action="{{ route('cart.update') }}">
                            <div class="cart-item-list">
                                @csrf
                                @foreach (Session::get('cart') as $product)
                                    @php
                                        $total += $product->retail_price * $product->selected_quantity;
                                    @endphp
                                    <div class="row col-12">
                                        <a title="{{ $product->title }}" href="{{ route('products.show', $product->slug) }}" class="product-image-container col-2 text-decoration-none">
                                            <img alt="{{ $product->title }}" src="{{ $product->image }}" onerror="this.src='https://i.imgur.com/tHUqrA7.jpg'" class="card-img-top">
                                        </a>
                                        <div class="product-details-content col-5 pr0">
                                            <div class="row item-title no-margin">
                                                <a href="{{ route('products.show', $product->slug) }}" title="{{ $product->title }}" class="unset col-12 no-padding text-decoration-none">
                                                    <span class="fs20 fw6 link-color">{{ $product->title }}</span>
                                                </a>
                                            </div>
                                            <div class="row col-12 no-padding no-margin">
                                                <div class="product-price">
                                                    <span class="text-warning">{{ number_format($product->retail_price) }}<small>đ</small></span>
                                                </div>
                                            </div>
                                            <div class="no-padding col-12 cursor-pointer fs16">
                                                <div class="d-inline-block">
                                                    <a href="{{ route('cart.delete', $product->id) }}" class="unset text-decoration-none" onclick="confirm('{{ __('Are you want to delete this item?') }}')">
                                                        <span class="fa fa-trash-o"></span>
                                                        <span class="align-vertical-super">{{ __('Remove') }}</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-quantity col-3 no-padding">
                                            <div class="quantity control-group ">
                                                <input name="qty[{{ $product->id }}]" value="{{ $product->selected_quantity }}" type="number" min="1" max="{{ $product->quantity }}" id="quantity-changer" class="form-control text-center input-quantity">
                                                <!---->
                                            </div>
                                        </div>
                                        <div class="product-price fs18 col-2">
                                            <span class="card-current-price fw6 mr10 text-warning"> {{ number_format($product->retail_price * $product->selected_quantity) }}<small>đ</small> </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ route('home') }}" class="col-12 link-color text-decoration-none fs16 no-padding"> {{ __('Continue Shopping') }} </a>
                            <button type="submit" class="btn btn-primary float-end"> {{ __('Update Cart') }} </button>
                        </form>
                    @else
                        <div class="alert alert-info">{{ __('Cart Empty') }}</div>
                    @endif
                </div>
            </div>
            <div class="col-lg-4 col-md-12 offset-lg-1 order-summary-container">
                <div class="order-summary fs16 bg-white shadow-lg">
                    <h3 class="fw6">{{ __('Cart Summary') }}</h3>
                    <div class="row">
                        <span class="col-6">{{ __('Sub Total') }}</span>
                        <span class="col-6 text-end">{{ number_format($total) }}<small>đ</small></span>
                    </div>
                    <div class="row">
                        <span id="taxrate-0" class="col-6">VAT <small>(10%)</small></span>
                        <span id="basetaxamount-0" class="col-6 text-end">{{ number_format(intval($total * 0.1)) }}<small>đ</small></span>
                    </div>
                    <div id="grand-total-detail" class="payable-amount row">
                        <span class="col-6">{{ __('Grand Total') }}</span>
                        <span id="grand-total-amount-detail" class="col-6 text-end fw6"> {{ number_format($total + intval($total * 0.1)) }}<small>đ</small> </span>
                    </div>
                    @if (Session::get('cart'))
                        <div class="row">
                            <a href="" class="btn btn-success text-uppercase col-12 text-decoration-none fw6 text-center">{{ __('Checkout') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@extends('layouts.app')

@section('content')
<section class="pb-5 cart-details">
    @php
        $total = 0;
    @endphp
    <div class="container">
        <div class="row col-12">
            <h2 class="fw6 col-12">{{ __('Checkout') }}</h2>
            <div class="col-lg-7 col-md-12 bg-white shadow-lg p-3 rounded-3">
                <div class="accordion" id="accordionCheckout">
                    <div class="accordion-item">
                        <h4 class="accordion-header" id="headingItems">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseItems" aria-expanded="true" aria-controls="collapseItems">
                            {{ __('Order Items') }}
                            </button>
                        </h4>
                        <div id="collapseItems" class="accordion-collapse collapse" aria-labelledby="headingItems">
                            <div class="accordion-body cart-details-header">
                                <div class="cart-content col-12">
                                    @if (Session::get('cart'))
                                        <div class="cart-item-list">
                                            @foreach (Session::get('cart') as $product)
                                                @php
                                                    $total += $product->retail_price * $product->selected_quantity;
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
                                                                <span class="text-warning">{{ number_format($product->retail_price) }}<small>đ</small></span>
                                                                <i class="fa fa-times" aria-hidden="true"></i>
                                                                <span>{{ $product->selected_quantity }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="product-price fs18 col-3">
                                                        <span class="card-current-price fw6 mr10 text-warning"> {{ number_format($product->retail_price * $product->selected_quantity) }}<small>đ</small> </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-info">{{ __('Cart Empty') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingShipping">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseShipping" aria-expanded="true" aria-controls="collapseShipping"> {{ __('Shipping Address') }} </button>
                        </h2>
                        <div id="collapseShipping" class="accordion-collapse collapse show" aria-labelledby="headingShipping">
                            <div class="accordion-body">
                                <form method="POST" action="{{ route('cart.order') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="name" class="form-label">{{ __('Name') }}</label> <span class="text-danger">*</span>
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $user->name }}" required>
                                        @error('name')
                                            <div class="form-text invalid-feedback" role="alert">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="form-label">{{ __('Address') }}</label> <span class="text-danger">*</span>
                                        <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address" required>{{ old('address') ?? $user->address}}</textarea>
                                        @error('address')
                                            <div class="form-text invalid-feedback" role="alert">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">{{ __('Phone number') }}</label> <span class="text-danger">*</span>
                                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') ?? $user->phone}}" required>
                                        @error('phone')
                                            <div class="form-text invalid-feedback" role="alert">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="note" class="form-label">{{ __('Note') }}</label>
                                        <textarea id="note" class="form-control @error('note') is-invalid @enderror" name="note">{{ old('note') }}</textarea>
                                        @error('note')
                                            <div class="form-text invalid-feedback" role="alert">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">{{ __('Place Order') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
                        @php
                            Session::put('grandTotal', $total + intval($total * 0.1));
                        @endphp
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

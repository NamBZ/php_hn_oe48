@extends('layouts.app')

@section('content')

@php
    $parameters = request()->input();
    unset($parameters['page']);
@endphp

<section class="list-books pb-5" id="newest-book">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-tittle text-center mb-5">
                    <h2>{{ __('Category') }}: {{ $category->name }}</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="bg-white shadow-lg p-3 rounded-3">
                        <!-- Search -->
                        <form class="w-100 p-2 me-3 input-group" action="{{ route('categories.show', $category->slug) }}" method="get" style="max-width: 500px;">
                            <input type="search" name="search" class="form-control" value="{{ request()->query('search') }}" placeholder="Search..." aria-label="Search">
                        </form>
                        <!-- Category -->
                        @if ($category->children->isNotEmpty())
                            <h4 class="d-block h6 my-2 pb-2 border-bottom fw-bold">{{ __('Product Category') }}</h4>
                            @foreach ($category->children as $child_cate)
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('categories.show', $child_cate->slug) }}">{{ $child_cate->name }}</a>
                                    </li>
                                </ul>
                            @endforeach
                        @endif

                        <!-- Filter by rating -->
                        @php
                            $rating = isset($parameters['rating']) ? $parameters['rating'] : 0;
                        @endphp
                        <h4 class="d-block h6 my-2 pt-2 pb-2 border-bottom fw-bold">{{ __('Rating') }}</h4>
                        <div class="rating-list p-2">
                            <ul class="list-unstyled">
                                <li @if ($rating == 5) class="bg-light rounded-3" @endif>
                                    @php $parameters['rating'] = 5 @endphp
                                    <a href="{{ route('categories.show', array_merge(['slug' => $category->slug], $parameters)) }}" class="rating text-decoration-none">
                                        <span class="text-warning">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                        </span>
                                    </a>
                                </li>
                                <li @if ($rating == 4) class="bg-light rounded-3" @endif>
                                    @php $parameters['rating'] = 4 @endphp
                                    <a href="{{ route('categories.show', array_merge(['slug' => $category->slug], $parameters)) }}" class="rating text-decoration-none">
                                        <span class="text-warning">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-o"></i>
                                        </span>
                                        <span class="text-black">{{ __('& Up') }}</span>
                                    </a>
                                </li>
                                <li @if ($rating == 3) class="bg-light rounded-3" @endif>
                                    @php $parameters['rating'] = 3 @endphp
                                    <a href="{{ route('categories.show', array_merge(['slug' => $category->slug], $parameters)) }}" class="rating text-decoration-none">
                                        <span class="text-warning">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        </span>
                                        <span class="text-black">{{ __('& Up') }}</span>
                                    </a>
                                </li>
                                <li @if ($rating == 2) class="bg-light rounded-3" @endif>
                                    @php $parameters['rating'] = 2 @endphp
                                    <a href="{{ route('categories.show', array_merge(['slug' => $category->slug], $parameters)) }}" class="rating text-decoration-none">
                                        <span class="text-warning">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        </span>
                                        <span class="text-black">{{ __('& Up') }}</span>
                                    </a>
                                </li>
                                <li @if ($rating == 1) class="bg-light rounded-3" @endif>
                                    @php $parameters['rating'] = 1 @endphp
                                    <a href="{{ route('categories.show', array_merge(['slug' => $category->slug], $parameters)) }}" class="rating text-decoration-none">
                                        <span class="text-warning">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        </span>
                                        <span class="text-black">{{ __('& Up') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        @php
                            if ($rating == 0) {
                                unset($parameters['rating']);
                            }
                        @endphp

                        <!-- Filter by price -->
                        @php
                            $minPrice = isset($parameters['minPrice']) ? $parameters['minPrice'] : 0;
                            $maxPrice = isset($parameters['maxPrice']) ? $parameters['maxPrice'] : 0;
                        @endphp
                        <h4 class="d-block h6 my-2 pt-2 pb-2 border-bottom fw-bold">{{ __('Price Range') }}</h4>
                        <form class="p-2" action="{{ route('categories.show', $category->slug) }}" method="get">
                            <div class="input-group pb-2">
                                <input pattern="[0-9]*" class="form-control" name="minPrice" placeholder="{{ __('From') }}" value="{{ $minPrice }}">
                                <span class="input-group-text">-</span>
                                <input pattern="[0-9]*" class="form-control" name="maxPrice" placeholder="{{ __('To') }}" value="{{ $maxPrice }}">
                            </div>
                            <button type="submit" class="btn btn-outline-primary">{{ __('Apply') }}</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-9 row">
                    @if (isset($parameters['search']))
                        <h3 class="d-block h4 my-2 m-3 fw-bold">{{ __('Search results for') }} `{{ $parameters['search']}}`</h3>
                    @endif
                    @if ($list_products->isNotEmpty())
                        <div class="col-12 p-3">
                            @php
                                $sort = isset($parameters['sort']) ? $parameters['sort'] : '';
                            @endphp
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    @php $parameters['sort'] = '' @endphp
                                    <a class="nav-link @if ($sort == '') active @endif" href="{{ route('categories.show', array_merge(['slug' => $category->slug], $parameters)) }}">{{ __('Popular') }}</a>
                                </li>
                                <li class="nav-item">
                                    @php $parameters['sort'] = 'newest' @endphp
                                    <a class="nav-link @if ($sort == 'newest') active @endif" href="{{ route('categories.show', array_merge(['slug' => $category->slug], $parameters)) }}">{{ __('Newest') }}</a>
                                </li>
                                <li class="nav-item">
                                    @php $parameters['sort'] = 'top_seller' @endphp
                                    <a class="nav-link @if ($sort == 'top_seller') active @endif" href="{{ route('categories.show', array_merge(['slug' => $category->slug], $parameters)) }}">{{ __('Top Seller') }}</a>
                                </li>
                                <li class="nav-item">
                                    @php $parameters['sort'] = 'price_asc' @endphp
                                    <a class="nav-link @if ($sort == 'price_asc') active @endif" href="{{ route('categories.show', array_merge(['slug' => $category->slug], $parameters)) }}">{{ __('Low Price') }}</a>
                                </li>
                                <li class="nav-item">
                                    @php $parameters['sort'] = 'price_desc' @endphp
                                    <a class="nav-link @if ($sort == 'price_desc') active @endif" href="{{ route('categories.show', array_merge(['slug' => $category->slug], $parameters)) }}">{{ __('High Price') }}</a>
                                </li>
                                <li class="nav-item justify-content-end ms-auto">
                                    <a class="nav-link disabled"><span class="text-warning">{{ $list_products->count() }}</span>/{{ $list_products->total() }}</a>
                                </li>
                            </ul>
                        </div>
                        @foreach ($list_products as $product)
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 pb-4">
                                <div class="properties pb-30">
                                    <div class="properties-card">
                                        <div class="properties-img">
                                            <a href="{{ route('products.show', $product->slug) }}"><img src="{{ $product->image }}" alt=""></a>
                                        </div>
                                        <div class="properties-caption properties-caption2">
                                            <h3 class="properties-title"><a href="{{ route('products.show', $product->slug) }}">{{ $product->title }}</a></h3>
                                            <div class="properties-footer d-flex justify-content-between align-items-center">
                                                <div class="review">
                                                    <div class="rating">
                                                        @foreach (range(1,5) as $rate)
                                                            @if ($product->avg_rate >= $rate)
                                                                <i class="fa fa-star"></i>
                                                            @elseif ($product->avg_rate == $rate - 0.5)
                                                                <i class="fa fa-star-half-o"></i>
                                                            @else
                                                                <i class="fa fa-star-o"></i>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <p>(<span>{{ $product->sold }}</span> {{ __('sold') }})</p>
                                                </div>
                                                <div class="price">
                                                    <span>{{ @number_format($product->retail_price, 0, '', ',') }}đ</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="d-flex justify-content-center">
                            {{ $list_products->links() }}
                        </div>
                    @else
                        <div class="alert alert-primary" role="alert">{{ __('Empty product') }}</div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@extends('layouts.app')

@section('content')
<section class="list-books pb-5" id="newest-book">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="section-tittle text-center mb-55">
                    <h2>{{ __('Newest Book') }}</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 pb-4">
                    <div class="bg-white shadow-lg p-3 rounded-3">
                        @if ($categories->isNotEmpty())
                            <h4 class="d-block h6 my-2 pb-2 border-bottom fw-bold">{{ __('Product Category') }}</h4>
                            <div class="list-category">
                                <nav id="categories">
                                    <ul class="nav flex-column">
                                        @foreach ($categories as $category)
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('categories.show', $category->slug) }}">{{ $category->name }}</a>
                                                @if ($category->children->isNotEmpty())
                                                    <ul class="nav flex-column">
                                                    @foreach ($category->children as $child_cate)
                                                            <li class="nav-item">
                                                                <a class="nav-link" href="{{ route('categories.show', $child_cate->slug) }}">{{ $child_cate->name }}</a>
                                                            </li>
                                                    @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </nav>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-9 row">
                    @if ($list_products->isNotEmpty())
                        @foreach ($list_products as $product)
                            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 pb-4">
                                <div class="properties pb-30">
                                    <div class="properties-card">
                                        <div class="properties-img">
                                            <a href="{{ route('products.show', $product->slug) }}"><img src="{{ $product->image }}" alt=""></a>
                                        </div>
                                        <div class="properties-caption properties-caption2">
                                            <h3 class="properties-title"><a href="{{ route('products.show', $product->slug) }}">{{ $product->title }}</a></h3>
                                            <p class="properties-description">{{ $product->category->name }}</p>
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

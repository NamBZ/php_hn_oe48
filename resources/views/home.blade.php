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

            @if ($list_products->isNotEmpty())
                @foreach ($list_products as $product)
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 pb-4">
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
                                                    @elseif ($product->avg_rate == $rate + 0.5)
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
                {{-- Pagination --}}
                <div class="d-flex justify-content-center">
                    {{ $list_products->links() }}
                </div>
            @else
                <div class="alert alert-primary" role="alert">{{ __('Empty product') }}</div>
            @endif

        </div>
    </div>
</section>
@endsection

@extends('layouts.app')

@section('content')
<section class="view-books pb-5" id="book-details">
    <div class="container">
        <div class="bg-white shadow-lg rounded-3 px-4 py-3 mb-5">
            <div class="px-lg-3">
                <div class="row">
                    <!-- Product gallery-->
                    <div class="col-lg-7 p-4">
                        <div class="product-gallery">
                            <div class="product-gallery-preview order-sm-2">
                                <div class="product-gallery-preview-item active" id="first">
                                    <img class="image-zoom" src="{{ $product->image }}" alt="{{ $product->title }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Product details-->
                    <div class="col-lg-5 pt-4">
                        <div class="product-details ms-auto pb-3">
                            <!-- Product category-->
                            <a class="text-decoration-none text-black" href="{{ route('categories.show', $product->category->slug) }}">{{ $product->category->name }}</a>
                            <!-- Product title-->
                            <h2 class="product-title text-primary">
                                {{ $product->title }}
                            </h2>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <a class="text-decoration-none" href="#reviews" data-scroll="">
                                    <span class="star-rating text-warning">
                                        @foreach (range(1, 5) as $rate)
                                            @if ($product->avg_rate >= $rate)
                                                <i class="fa fa-star"></i>
                                            @elseif ($product->avg_rate == $rate + 0.5)
                                                <i class="fa fa-star-half-o"></i>
                                            @else
                                                <i class="fa fa-star-o"></i>
                                            @endif
                                        @endforeach
                                    </span>
                                    <span class="d-inline-block fs-sm text-body align-middle mt-1 ms-1">{{ $product->ratings->count() }} {{ __('Reviews') }}</span>
                                </a>
                            </div>
                            <div class="mb-3">
                                <span class="h3 fw-normal text-accent me-1 text-danger">{{ @number_format($product->retail_price, 0, '', ',') }}<small>đ</small></span>
                            </div>
                            <form class="mb-grid-gutter" action="{{ route('cart.add')}}" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <div class="mb-3 d-flex align-items-center">
                                    <input type="number" class="form-control me-3 text-center input-quantity" name="quantity" min="1" max="{{ $product->quantity }}" value="1">
                                    <button class="btn btn-success btn-shadow d-block w-100" type="submit"><i class="fa fa-cart-plus"></i> {{ __('Add to Cart') }}</button>
                                </div>
                            </form>
                            <!-- Product panels-->
                            <div class="accordion mb-4" id="productPanels">
                                <div class="accordion-item">
                                    <h3 class="accordion-header"><a class="accordion-button collapsed" href="#productInfo" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="productInfo"><i class="ci-announcement text-muted fs-lg align-middle mt-n1 me-2"></i>{{ __('Short Descriptions') }}</a></h3>
                                    <div class="accordion-collapse collapse" id="productInfo" data-bs-parent="#productPanels" style="">
                                        <div class="accordion-body">
                                            {!! $product->description !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-2 text-center">
                                <!-- Sharing-->
                                <a class="btn-share btn-twitter me-2 my-2 text-decoration-none" href="https://twitter.com/intent/tweet/?text={{ $product->title }}&url={{ route('products.show', $product->slug) }}&via=Crunchify"><i class="fa fa-twitter"></i> Twitter</a>
                                <a class="btn-share btn-instagram me-2 my-2 text-decoration-none" href="https://www.instagram.com/?url={{ route('products.show', $product->slug) }}"><i class="fa fa-instagram"></i> Instagram</a>
                                <a class="btn-share btn-facebook my-2 text-decoration-none" href="https://www.facebook.com/sharer/sharer.php?u={{ route('products.show', $product->slug) }}"><i class="fa fa-facebook"></i> Facebook</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="h3 pb-2">{{ __('Content') }}</h2>
                {!! $product->content !!}
            </div>
        </div>
        <hr class="mb-5">

        <!-- Review -->
        <div class="my-lg-3 py-5">
            <div class="container pt-md-2" id="reviews">
                <div class="row pb-3">
                    <div class="col-lg-4 col-md-5">
                        <h2 class="h3 mb-4">{{ $product->ratings->count() }} {{ __('Reviews') }}</h2>
                        <div class="star-rating text-warning me-2">
                            @foreach (range(1, 5) as $rate)
                                @if ($product->avg_rate >= $rate)
                                    <i class="fa fa-star"></i>
                                @elseif ($product->avg_rate == $rate + 0.5)
                                    <i class="fa fa-star-half-o"></i>
                                @else
                                    <i class="fa fa-star-o"></i>
                                @endif
                            @endforeach
                        </div>
                        <span class="d-inline-block align-middle">{{ $product->ratings->count() }} {{ __('Overall rating') }}</span>
                    </div>
                    <div class="col-lg-8 col-md-7">
                        @php
                            $rate_count = $product->ratings->count();
                            $progressbar_color = ['#f34770', '#fea569', '#ffda75', '#a7e453', '#42d697'];
                        @endphp
                        @foreach (range(5, 1) as $star)
                            @php
                                $rate_one_count = $product->ratings->where('rate', $star)->count();
                                $percent = 0;
                                if ($product->ratings->count() > 0)
                                    $percent = $rate_one_count / $rate_count * 100;
                            @endphp
                            <div class="d-flex align-items-center mb-2">
                                <div class="text-nowrap me-3"><span class="d-inline-block align-middle text-muted">{{ $star }}</span><i class="fa fa-star fs-xs ms-1 text-warning"></i></div>
                                <div class="w-100">
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%; background-color: {{ $progressbar_color[$star-1] }};" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div><span class="text-muted ms-3">{{ $rate_one_count }}</span>
                            </div>
                        @endforeach

                    </div>
                </div>
                <hr class="mt-4 mb-3">
                <div class="row pt-4">
                    <!-- Reviews list-->
                    <div class="col-md-7">
                        <!-- Review-->
                        @if ($ratings->isNotEmpty())
                            @foreach ($ratings as $rating)
                                <div class="product-review pb-4 mb-4 border-bottom">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center me-4 pe-2">
                                            @isset ($rating->order->user->avatar)
                                                <img class="rounded-circle" src="{{ $rating->order->user->avatar }}" width="50" alt="Rafael Marquez">
                                            @else
                                                <i class="fa fa-user-circle-o fa-3x" aria-hidden="true"></i>
                                            @endisset
                                            <div class="ps-3">
                                                <h6 class="fs-sm mb-0">{{ $rating->order->user->name }}</h6>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="star-rating text-warning">
                                                @foreach (range(1, 5) as $rate)
                                                    @if ($rating->rate >= $rate)
                                                        <i class="fa fa-star"></i>
                                                    @elseif ($rating->rate == $rate + 0.5)
                                                        <i class="fa fa-star-half-o"></i>
                                                    @else
                                                        <i class="fa fa-star-o"></i>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <p class="fs-md mb-2">{{ $rating->comment }}</p>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-info" role="alert">{{ __('Empty Rating') }}</div>
                        @endif

                        <div class="text-center">
                            {{ $ratings->links() }}
                        </div>
                    </div>
                    <!-- Leave review form-->
                    <div class="col-md-5 mt-2 pt-4 mt-md-0 pt-md-0">
                        <div class="bg-white shadow-lg p-3 rounded-3">
                            <h3 class="h4 pb-2">{{ __('Write a review') }}</h3>
                            <form class="needs-validation" method="post">
                                <div class="mb-3">
                                    <label class="form-label" for="rate">{{ __('Rating') }}<span class="text-danger">*</span></label>
                                    <div class="px-4">
                                        @foreach (range(5, 1) as $rate)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="rate"{{ $rate == 5 ? 'checked' : '' }} value="{{ $rate }}" id="review-rating-{{ $rate }}">
                                                <label class="form-check-label text-warning" for="review-rating-{{ $rate }}">
                                                    @foreach (range(1, 5) as $star)
                                                        @if ($rate >= $star)
                                                            <i class="fa fa-star"></i>
                                                        @elseif ($rate == $rate + 0.5)
                                                            <i class="fa fa-star-half-o"></i>
                                                        @else
                                                            <i class="fa fa-star-o"></i>
                                                        @endif
                                                    @endforeach
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="comment">{{ __('Comment') }}<span class="text-danger">*</span></label>
                                    <textarea class="form-control" rows="4" required="" value="comment" id="comment"></textarea>
                                </div>
                                <button class="btn btn-primary btn-shadow d-block w-100" type="submit">{{ __('Submit a Rating') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="mt-4 mb-3">

        <!-- Also like -->
        <div class="container list-books pt-lg-2 pb-5 mb-md-3">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="section-tittle text-center mb-55">
                        <h2 class="pb-4">{{ __('Related') }}</h2>
                    </div>
                </div>

                @if ($related_products->isNotEmpty())
                    @foreach ($related_products as $product)
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
                @else
                    <div class="alert alert-primary" role="alert">{{ __('Empty product') }}</div>
                @endif

            </div>
        </div>
    </div>
</section>
@endsection

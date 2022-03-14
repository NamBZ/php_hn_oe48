@extends('layouts.app')
@extends('layouts.sidebarProfile')

@section('content2')

<div class="col-md-9 pb-5">
    <div class="card">
        <div class="card-header bg bg-info">
            <h3 class="card-title col-md-12 text-center">{{ __('List Review') }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @if ($orderItems->isNotEmpty())
                @foreach ($orderItems as $item)
                    <div>
                        <div class="row align-items-center justify-content-between">
                            <div class="col-md-3">
                                <img class="profile-user-img" src="{{ $item->product->image }}" alt="">
                            </div>
                            <div class="col-md-5">
                                <a href="{{ route('products.show', $item->product->slug) }}" title="{{ $item->product->title }}">{{ $item->product->title }}</a>
                            </div>
                            <div class="col-md-2">{{ $item->quantity }}</div>
                            <div class="col-md-2">{{ @number_format($item->price * $item->quantity, 0, '', ',') }} đ</div>
                        </div>
                        <hr>
                        @if ($item->rstatus == App\Enums\RatingStatus::ALLOW)
                            <div class="col-md-12 mt-2 pt-4 mt-md-0 pt-md-0">
                                <div class="p-3">
                                    <form class="needs-validation" method="post" action="{{ route('user.rating.send', $item->id ) }}">
                                    @csrf
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
                                                                @elseif ($rate == $rate - 0.5)
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
                                            <textarea class="form-control" rows="4" name="comment" value="comment" id="comment"></textarea>
                                            @error('comment')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                            @enderror
                                        </div>
                                        <button class="btn btn-primary btn-shadow d-block w-100" type="submit">{{ __('Submit a Rating') }}</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <!-- show review -->
                            @if ($item->rating)
                                <div class="d-flex align-items-center justify-content-between ms-3 mb-3 mt-3">
                                    <div>{{ $item->rating->comment }}</div>
                                    <div>
                                        <div class="star-rating text-warning">
                                            @foreach (range(1, 5) as $rate)
                                                @if ($item->rating->rate >= $rate)
                                                    <i class="fa fa-star"></i>
                                                @elseif ($item->rating->rate == $rate - 0.5)
                                                    <i class="fa fa-star-half-o"></i>
                                                @else
                                                    <i class="fa fa-star-o"></i>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                @endforeach
            @else
                    <div class="alert alert-primary" role="alert">{{ __('Empty list order') }}</div>
            @endif
        </div>
        <div class="card-footer">
            <!-- grand price -->
            <div class="row p-2">
                <div class="col-md-6 offset-md-6 row justify-content-between">
                        <div class="col-6">{{ __('Total Price') }}:</div>
                        <div class="col-6 text-end text-danger">{{ @number_format($orderInfo->total_price, 0, '', ',') }} đ</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

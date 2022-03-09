@section('content')
<div class="container rounded bg-white mt-2 mb-2">
    <div class="row">
        <div class="col-md-3 border-right bg-light">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                <img class="rounded-circle" width="50px" src="{{ $user->avatar }}">
                <span class="font-weight-bold">{{ $user->name }}</span>
                <span> </span>
            </div>
            <div class="row mt-3 ps-2 h-100">
                <div class="list-group list-group-flush">
                    <a href="{{ route('profile') }}" class="list-group-item list-group-item-action">{{ __('My Account') }}</a>
                    <a href="#" class="list-group-item list-group-item-action">{{ __('History Order') }}</a>
                    <a href="#" class="list-group-item list-group-item-action">{{ __('My Review') }}</a>
                    <a href="#" class="list-group-item list-group-item-action">{{ __('My Wishlist') }}</a>
                </div>
                </div>
        </div>
        @yield('content2')
    </div>
</div>
@endsection

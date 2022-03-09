@section('content')
<div class="container rounded bg-white mt-2 mb-2">
    <div class="row">
        <div class="col-md-3">
            <div class="shadow-lg rounded-3 p-2">
                <div class="d-flex flex-column align-items-center text-center py-3">
                    @if (empty($user->avatar))
                        <i class="fa fa-user-circle-o fa-3x" aria-hidden="true"></i>
                    @else
                        <img class="rounded-circle" width="50px" src="{{ $user->avatar }}">
                    @endif
                    <span class="font-weight-bold">{{ $user->name }}</span>
                    <span> </span>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('profile') }}" class="list-group-item list-group-item-action">{{ __('My Account') }}</a>
                    <a href="{{ route('user.purchase') }}" class="list-group-item list-group-item-action">{{ __('History Order') }}</a>
                    <a href="#" class="list-group-item list-group-item-action">{{ __('My Review') }}</a>
                    <a href="#" class="list-group-item list-group-item-action">{{ __('My Wishlist') }}</a>
                </div>
            </div>
        </div>
        @yield('content2')
    </div>
</div>
@endsection

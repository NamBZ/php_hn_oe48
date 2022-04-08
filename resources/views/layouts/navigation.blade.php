<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <a class="navbar-brand" href="{{ route('home') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <!-- Search -->
            <form class="w-100 p-2 me-3 input-group" action="{{ route('search') }}" method="get" style="max-width: 500px;">
                <input type="search" name="query" class="form-control" value="{{ request()->query('query') }}" placeholder="Search..." aria-label="Search">
            </form>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Language -->
                @if (Route::has('language'))
                    <li class="nav-item dropdown">
                        <a id="languageDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            @if (App::isLocale('vi'))
                                {{ __('Vietnamese') }}
                            @else
                                {{ __('English') }}
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('language', ['en']) }}"> {{ __('English') }}</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('language', ['vi']) }}"> {{ __('Vietnamese') }}</a>
                            </li>
                        </ul>
                    </li>
                @endif

                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    @if (Auth()->user()->role == App\Enums\UserRole::ADMIN && Route::has('dashboard'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}"> {{ __('Dashboard') }}</a>
                        </li>
                    @endif
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item border-bottom" href="{{ route('profile') }}">
                                {{ __('Profile') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest

            </ul>
        </div>
        <ul class="nav ms-auto">

            <!-- Cart -->
            <li class="nav-item">
                <a class="nav-link navbar-text position-relative" href="{{ route('cart') }}">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                    @if (Session::get('cart'))
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ count(Session::get('cart')) }}</span>
                    @endif
                </a>
            </li>
            @auth
                <!-- Notification -->
                <li class="nav-item bell">
                    <a class="nav-link navbar-text position-relative">
                        <i class="fa fa-bell-o" aria-hidden="true"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger pending">{{ Auth::user()->unreadNotifications->count() }}</span>
                    </a>
                    <ul class="notifications m-0 p-0" id="notifications">
                        @foreach (Auth::user()->notifications as $notification)
                            <li class="notifications-item {{ $notification->unread() ? 'unread' : '' }}">
                                <a class="text-decoration-none" href="{{ $notification->data['link'] }}?read={{ $notification->id }}">
                                    <i class="fa {{ $notification->unread() ? 'fa-dot-circle-o text-danger' : 'fa-check-circle-o text-success' }}" aria-hidden="true"></i>
                                    <div class="text">
                                        <h6 class="m-0 p-0">{{ $notification->data['title'] }}</h6>
                                        <p class="m-0 p-0">{{ $notification->data['message'] }}</p>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endauth
        </ul>
    </div>
</nav>


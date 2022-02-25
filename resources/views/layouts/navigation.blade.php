<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Search -->
            <form class="w-100 p-2 me-3" style="max-width: 500px;">
                <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
            </form>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Language -->
                @if (Route::has('language'))
                    @if (App::isLocale('vi'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('language', ['en']) }}"> {{ __('English') }}</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('language', ['vi']) }}"> {{ __('Vietnamese') }}</a>
                        </li>
                    @endif
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

                <!-- Cart -->
                <li class="nav-item">
                    <a class="nav-link position-relative" href="{{ route('cart') }}">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        @if (Session::get('cart'))
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ count(Session::get('cart')) }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

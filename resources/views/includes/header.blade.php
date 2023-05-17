<nav class="bg-primary-purple navbar">
    <div class="inner">
        <div class="left flex-grow-1">
            <button class="sidebar-menu-btn">
                <img src="{{ asset('/images/HealthMate.svg') }}" alt="menu" width="30" height="30">
            </button>
            <div class="logo">
                <a href="{{ route('admin.dashboard') }}">HealthMate</a>
            </div>
        </div>
        <div class="right">
            <!-- @if (Route::has('login'))
            <div class="top-right links">
                @auth
                <a href="{{ url('/') }}" class="route-btn">Home</a>
                @else
                <a href="{{ route('login') }}" class="route-btn">Login</a>

                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="route-btn">Register</a>
                @endif
                @endauth
            </div>
            @endif -->
            <div class="user-dropdown-wrapper">
                <div class="user-dropdown-btn">
                    <img src="{{ asset('images/user-white.svg') }}" alt="" width="30" height="30" />
                </div>
                <div class="user-dropdown">
                    <ul>
                        <li class="user-dropdown-item">
                            <a href="{{ route('index') }}">
                                <img src="{{ asset('images/user.svg') }}" alt="" width="20" height="20" />
                                <span>Profile</span>
                            </a>
                        </li>
                        <li class="user-dropdown-item">
                            <a href="{{ route('logout') }}">
                                <img src="{{ asset('images/logout.svg') }}" alt="" width="20" height="20" />
                                <span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</nav>
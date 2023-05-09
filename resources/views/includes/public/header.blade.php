<nav class="{{ $bgColor }} navbar">
    <div class="inner">
        <div class="left flex-grow-1">
            logo
        </div>
        <div class="right">
            @if (Route::has('login'))
            <div class="top-right links">
                @auth
                <!-- <a href="{{ url('/') }}" class="route-btn">Home</a> -->
                @else
                <a href="{{ route('login') }}" class="route-btn">Login</a>
                <!-- @if (Route::has('register'))
                <a href="{{ route('register') }}" class="route-btn">Register</a>
                @endif -->
                @endauth
            </div>
            @endif
        </div>
    </div>
</nav>
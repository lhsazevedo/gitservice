<nav class="navbar">
    <div class="brand">GitService</div>

    <div class="navbar-nav">
        <a class="navbar-link" href="{{ route('dashboard') }}">
            {{ __('Dashboard') }}
        </a>
    </div>

    <div class="navbar-nav ms-auto">
        <span>Logged in as {{ Auth::user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf

            <a class="navbar-link" href="{{ route('logout')}}" onclick="event.preventDefault(); this.closest('form').submit();">
                {{ __('Log Out') }}
            </a>
        </form>
    </div>

    
</nav>

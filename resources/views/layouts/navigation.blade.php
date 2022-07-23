<nav class="navbar">
    <div class="brand">GitService</div>

    <div>
        <a href="{{ route('dashboard') }}">
            {{ __('Dashboard') }}
        </a>
    </div>

    <span>Logged in as {{ Auth::user()->name }}</span>
    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
        @csrf

        <a href="{{ route('logout')}}" onclick="event.preventDefault(); this.closest('form').submit();">
            {{ __('Log Out') }}
        </a>
    </form>
</nav>

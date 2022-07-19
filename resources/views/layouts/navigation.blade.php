<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div>
        <div style="font-size: 2em; margin-bottom: 8px;">
            <strong>GitService</strong>
        </div>
        <div>
            <span>Logged in as {{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf

                <a href="{{ route('logout')}}" onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __('Log Out') }}
                </a>
            </form>
        </div>
    </div>

    <br>

    <div>
        <a href="{{ route('dashboard') }}">
            {{ __('Dashboard') }}
        </a>
    </div>
</nav>

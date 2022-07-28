<nav class="navbar navbar-expand bg-dark navbar-dark">
  <div class="container-fluid">
    <div class="navbar-brand">GitService</div>

    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard') }}">
            {{ __('Dashboard') }}
        </a>
      </li>
    </ul>

    <ul class="navbar-nav ms-auto">
      <span class="navbar-text">Logged in as {{ Auth::user()->name }}</span>
      <li class="nav-item">
        <form class="d-inline" method="POST" action="{{ route('logout') }}">
            @csrf
            <a class="nav-link" href="{{ route('logout')}}" onclick="event.preventDefault(); this.closest('form').submit();">
                {{ __('Log Out') }}
            </a>
        </form>
      </li>
    </ul>
  </div>
    
</nav>

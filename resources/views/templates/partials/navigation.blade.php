<nav class="navbar navbar-default" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button 
                type="button" 
                class="navbar-toggle collapsed" 
                data-toggle="collapse"
                data-target="#navbar-collapse" 
                aria-expanded="false"
            >
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('home') }}">Chatty</a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            @if (Auth::check())
                <ul class="nav navbar-nav">
                    <li><a href="{{ route('home') }}">Timeline</a></li>
                    <li><a href="{{ route('friend.index') }}">Friends</a></li>
                </ul>
                <form class="navbar-form navbar-left" role="search" action="{{ route('search.results') }}">
                    <div class="form-group">
                        <input type="text" name="query" class="form-control" placeholder="Find people">
                    </div>
                    <button type="submit" class="btn btn-default">Search</button>
                </form>
            @endif
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::check())
                    <li><a href="{{ route('profile.index', ['username' => Auth::user()->name]) }}">
                        {{ Auth::user()->getNameOrUsername() }}
                    </a></li>
                    <li><a href="{{ route('profile.edit') }}">Update profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('logout') }}"
		               onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();">
		                {{ __('Logout') }}
		            </a></li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @else
                    <li><a href="{{ route('register') }}">Sign up</a></li>
                    <li><a href="{{ route('login') }}">Sign in</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>

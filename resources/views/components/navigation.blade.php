@if (isGL())

@else

    <a class="navbar-item" href="{{ iawUrl() }}">
        <i class="fas fa-mobile-alt"></i> &nbsp; Apps
    </a>

    <a class="navbar-item" href="{{ fpUrl() }}">
        <i class="fas fa-users"></i> &nbsp; Developers
    </a>

    <a class="navbar-item" href="{{ fxUrl() }}">
        <i class="fas fa-laptop"></i> &nbsp; Resources
    </a>

    <!--
    <a class="navbar-item" href="{{ feUrl() }}">
        <i class="fas fa-calendar"></i> &nbsp; Events
    </a>
    -->

    <a class="navbar-item" href="{{ fsUrl() }}">
        <i class="fas fa-video"></i> &nbsp; Streams
    </a>

    <!--
    @if (isIAW())
        @if (auth()->check())
            <a class="navbar-item" href="{{ url('logout') }}">
                <i class="fas fa-user-alt"></i> &nbsp; Logout
            </a>
        @else
            <a class="navbar-item" href="{{ url(auth()->check() ? 'submit' : 'auth/google') }}">
                <i class="fas fa-bell"></i> &nbsp; Monthly Stats
            </a>
        @endif
    @endif
    -->

    @if (false && isIAW())
        <a class="navbar-item" href="{{ iawUrl() }}/about">
            <i class="fas fa-info-circle"></i> &nbsp; About
        </a>
    @endif

    <br/>
@endif

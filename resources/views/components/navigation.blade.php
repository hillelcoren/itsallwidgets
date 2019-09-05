@php
    if (isFX())
        $url = 'docs';
    elseif (isFE() || isFP())
        $url = 'community';
    else
        $url = 'showcase'
@endphp

@if (isGL())

@else

    <!--
    @if (! isFP())
        <a class="navbar-item" href="{{ fpUrl() }}">
            <i class="fas fa-users"></i> &nbsp; Developers
        </a>
    @endif
    -->

    @if (! isFX())
        <a class="navbar-item" href="{{ fxUrl() }}">
            <i class="fas fa-laptop"></i> &nbsp; Resources
        </a>
    @endif

    @if (! isFE())
        <a class="navbar-item" href="{{ feUrl() }}">
            <i class="fas fa-calendar"></i> &nbsp; Events
        </a>
    @endif

    <a class="navbar-item" href="https://flutter.dev/{{ $url }}" target="_blank">
        <i class="fas fa-globe"></i> &nbsp; {{ ucwords($url) }}
    </a>

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

    @if (false && isIAW())
        <a class="navbar-item" href="{{ iawUrl() }}/about">
            <i class="fas fa-info-circle"></i> &nbsp; About
        </a>
    @endif
@endif

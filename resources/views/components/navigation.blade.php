@php
    if (isFX())
        $url = 'docs';
    elseif (isFE())
        $url = 'community';
    else
        $url = 'showcase'
@endphp

<a class="navbar-item" href="https://flutter.dev/{{ $url }}" target="_blank">
    <i class="fas fa-globe"></i> &nbsp; {{ ucwords($url) }}
</a>

<a class="navbar-item" href="{{ iawUrl() }}/about">
    <i class="fas fa-info-circle"></i> &nbsp; About
</a>

@if (! isFE() && ! isIAW())
    <a class="navbar-item" href="{{ feUrl() }}">
        <i class="fas fa-calendar"></i> &nbsp; Events
    </a>
@endif

@if (false && ! isFX())
    <a class="navbar-item" href="{{ fxUrl() }}">
        <i class="fas fa-book"></i> &nbsp; Resources
    </a>
@endif

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

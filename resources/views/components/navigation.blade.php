@php
    $isIAW = isIAW();
    $url = "https://flutter.dev/" . (isIAW() ? "showcase" : "community");
    $title = $isIAW ? "Showcase" : "Community";
@endphp
<a class="navbar-item" href="{{$url}}" target="_blank">
    <i class="fas fa-globe"></i> &nbsp; {{$title}}
</a>

<a class="navbar-item" href="{{ iawUrl() }}/about">
    <i class="fas fa-info-circle"></i> &nbsp; About
</a>

@if ($isIAW)
    <a class="navbar-item" href="{{ feUrl() }}">
        <i class="fas fa-calendar"></i> &nbsp; Events
    </a>

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

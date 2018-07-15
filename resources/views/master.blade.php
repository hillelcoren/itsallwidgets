<!doctype html>
<html lang="en">
<head>
    @if (config('services.analytics.tracking_id'))
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-122229484-1"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ config('services.analytics.tracking_id') }}', { 'anonymize_ip': true });

        </script>
    @endif

    <title>@yield('title') | {{ config('app.name') }}</title>
    <meta name="description" content="@yield('description')">

    <meta property="og:title" content="@yield('title') | {{ config('app.name') }}">
    <meta property="og:description" content="@yield('description')">
    <meta property="og:image" content="@yield('image_url')">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:site_name" content="It's All Widgets!">

    <meta name="twitter:title" content="@yield('title') | {{ config('app.name') }}">
    <meta name="twitter:description" content="@yield('description')">
    <meta name="twitter:image" content="@yield('image_url')">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image:alt" content="@yield('title') | {{ config('app.name') }}">

    <meta charset="utf-8">
    <meta id="token" name="token" value="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('/images/favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('css/bulma.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bulma-extensions.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
    <script src="{{ asset('js/vue.min.js') }}"></script>
    <script defer src="{{ asset('js/fontawesome.js') }}"></script>

    <style>

    .is-elevated {
        filter: drop-shadow(0px 16px 16px #CCC);
    }

    .is-hover-elevated:hover {
        filter: drop-shadow(0px 16px 16px #CCC);
        -webkit-transition : -webkit-filter 200ms
    }

    .is-slightly-elevated {
        filter: drop-shadow(0px 4px 2px #CCC);
    }

    .no-wrap {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    </style>
</head>

<body>

    <section class="hero is-info is-mediumx">
        <div class="hero-head">
            <nav class="navbar">
                <div class="container">
                    <div class="navbar-brand">
                        <a class="navbar-item" href="{{ url('/') }}">
                            <font style="font-family:impact;font-size:42px;">IT'S ALL WIDGETS!</font>
                        </a>
                    </div>
                    <div id="navbarMenuHeroA" class="navbar-menu">
                        <div class="navbar-end">
                            <span class="navbar-item">
                                <a class="navbar-item" href="https://github.com/hillelcoren/itsallwidgets" target="_blank">
                                    <i class="fab fa-github"></i> &nbsp; GitHub
                                </a>
                                @if (auth()->check())
                                    <a class="navbar-item" href="{{ url('logout') }}">
                                        <span class="icon">
                                            <i class="fas fa-user-alt"></i>
                                        </span> &nbsp;
                                        <span>Log Out</span>
                                    </a>
                                @else
                                    <a class="navbar-item" href="{{ url('auth/google') }}">
                                        <span class="icon">
                                            <i class="fas fa-user-alt"></i>
                                        </span> &nbsp;
                                        <span>Sign In</span>
                                    </a>
                                @endif

                                &nbsp;&nbsp;
                                &nbsp;&nbsp;

                                <a class="button is-info is-inverted" href="{{ url(auth()->check() ? 'flutter-apps/submit' : 'auth/google') }}">
                                    <span class="icon">
                                        <i class="fas fa-upload"></i>
                                    </span> &nbsp;
                                    <span>Submit App</span>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <div class="hero-body">
            <div class="container has-text-centered">
                <div class="title">
                    An open list of apps built with Google Flutter
                </div>
                <div class="subtitle">
                    Feel free to add an app in progress and update it when it goes live
                </div>
            </div>
        </div>
    </section>

    <div class="container">

        @if (session('status'))
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <div class="notification is-success">
                {{ session('status') }}
            </div>
        @endif

    </div>

    @yield('content')

    <p>&nbsp;</p>
    <p>&nbsp;</p>

    <footer class="footer">
        <div class="content has-text-centered">
            <p>
                Made with <i style="color:red" class="fas fa-heart"></i> by the <a href="https://medium.com/flutter-community" target="_blank">Flutter Community</a>
            </p>
            <p>
                Thank you to the <a href="https://flutter.io/" target="_blank">Flutter</a> and <a href="https://www.dartlang.org/" target="_blank">Dart</a> teams for this amazing platform!
            </p>
            <p>
                <a href="https://twitter.com/hillelcoren" target="_blank">@hillelcoren</a> •
                <a href="https://twitter.com/ThomasBurkhartB" target="_blank">@ThomasBurkhartB</a> •
                <a href="https://twitter.com/devangelslondon" target="_blank">@devangelslondon</a> •
                <a href="https://twitter.com/scottstoll2017" target="_blank">@scottstoll2017</a>

                <!--
                <a href="https://hillelcoren.com" target="_blank">Hillel Coren</a>
                <a href="https://twitter.com/hillelcoren" target="_blank"><i class="fab fa-twitter-square"></i></a> •
                <a href="https://www.burkharts.net/apps/blog/" target="_blank">Thomas Burkhart</a>
                <a href="https://twitter.com/ThomasBurkhartB" target="_blank"><i class="fab fa-twitter-square"></i></a> •
                Simon Lightfoot
                <a href="https://twitter.com/devangelslondon" target="_blank"><i class="fab fa-twitter-square"></i></a> •
                Scott Stoll
                <a href="https://twitter.com/scottstoll2017" target="_blank"><i class="fab fa-twitter-square"></i></a>
            -->
        </p>
    </div>
</footer>

</body>
</html>

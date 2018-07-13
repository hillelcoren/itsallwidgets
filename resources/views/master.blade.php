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

    <meta charset="utf-8">
    <meta id="token" name="token" value="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="An open list of apps built with Google Flutter">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('/images/favicon.png') }}">

    <title>@yield('title') | {{ config('app.name') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.1/css/bulma.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js"></script>

    <style>

    .is-elevated {
        filter: drop-shadow(0px 16px 16px #CCC);
    }

    .is-hover-elevated:hover {
        filter: drop-shadow(0px 16px 16px #CCC);
        -webkit-transition : -webkit-filter 320ms
    }

    .is-slightly-elevated {
        filter: drop-shadow(0px 4px 2px #CCC);
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
                                <a class="button is-info is-inverted" href="{{ url(auth()->check() ? 'submit-app' : 'auth/google') }}">
                                    <span class="icon">
                                        <i class="fas fa-upload"></i>
                                    </span>
                                    <span>Submit Application</span>
                                </a>
                                @if (auth()->check())
                                    <a class="navbar-item" href="{{ url('logout') }}">
                                        Log Out
                                    </a>
                                @else
                                    <a class="navbar-item" href="{{ url('auth/google') }}">
                                        Sign In
                                    </a>
                                @endif
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

    <p>&nbsp;</p>
    <p>&nbsp;</p>

    <div class="container">

        @if (session('status'))
            <div class="notification is-success">
                {{ session('status') }}
            </div>
            <p>&nbsp;</p>
        @endif

        @yield('content')

    </div>

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

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

        function trackEvent(category, action) {
            ga('send', 'event', category, action, this.src);
        }

        </script>
    @endif

    <title>@yield('title') | {{ config('app.name') }}</title>
    <meta name="description" content="@yield('description')">

    @include('feed::links')

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
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script defer src="{{ asset('js/fontawesome.js') }}"></script>

    <style>

    .is-elevated {
        -moz-filter: drop-shadow(0px 16px 16px #CCC);
        -webkit-filter: drop-shadow(0px 16px 16px #CCC);
        -o-filter: drop-shadow(0px 16px 16px #CCC);
        filter: drop-shadow(0px 16px 16px #CCC);
    }

    .is-hover-elevated:hover {
        -moz-filter: filter: drop-shadow(0px 16px 16px #CCC);
        -webkit-filter: filter: drop-shadow(0px 16px 16px #CCC);
        -o-filter: filter: drop-shadow(0px 16px 16px #CCC);
        filter: drop-shadow(0px 16px 16px #CCC);
        -webkit-transition : -webkit-filter 320ms;
        -moz-transition : -moz-filter 320ms;
        -o-transition : -o-filter 320ms;
        transition : filter 320ms;
    }

    .is-slightly-elevated {
        -moz-filter: drop-shadow(0px 4px 2px #CCC);
        -webkit-filter: drop-shadow(0px 4px 2px #CCC);
        -o-filter: drop-shadow(0px 4px 2px #CCC);
        filter: drop-shadow(0px 4px 2px #CCC);
    }

    .has-text-centered {
        justify-content: center;
        align-items: center;
    }

    .no-wrap {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .wrap {
        white-space: pre-wrap;
        word-wrap: break-word;
        font-family: inherit;
    }

    span.navbar-item,
    div.navbar-animate,
    .hero-body .title,
    .hero-body .subtitle {
        visibility: hidden;
    }

    </style>

    <script>

    window.onerror = function (errorMsg, url, lineNumber, column, error) {
        try {
            $.ajax({
                type: 'GET',
                url: '{{ URL::to('log_error') }}',
                data: 'error=' + encodeURIComponent(errorMsg + ' | URL: ' + url + ' | Line: ' + lineNumber + ' | Column: '+ column)
                + '&url=' + encodeURIComponent(window.location)
            });
        } catch (exception) {
            // do nothing
        }

        return false;
    }

    $(function() {
        $('div.navbar-animate').addClass('animated tada').css('visibility', 'visible');
        if (document.body.clientWidth > 1000) {
            $('span.navbar-item').addClass('animated slideInDown').css('visibility', 'visible');
            $('.hero-body .title, .hero-body .subtitle').addClass('animated fadeIn').css('visibility', 'visible');
        } else {
            $('span.navbar-item').css('visibility', 'visible');
            $('.hero-body .title, .hero-body .subtitle').css('visibility', 'visible');
        }

        // Check for click events on the navbar burger icon
        $(".navbar-burger").click(function() {

            // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
            $(".navbar-burger").toggleClass("is-active");
            $(".navbar-menu").toggleClass("is-active");

        });
    })

    </script>

</head>

<body>

    <section class="hero is-info is-mediumx">
        <div class="hero-head">
            <nav class="navbar">
                <div class="container">
                    <div class="navbar-brand">
                        <div class="navbar-animate">
                            <a class="navbar-item" href="{{ url('/') }}">
                                <font class="title" style="font-family:impact,arial;font-size:42px;">IT'S ALL WIDGETS!</font>
                            </a>
                        </div>
                        <span class="navbar-burger burger" data-target="navMenu">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </div>
                    <div id="navMenu" class="navbar-menu">
                        <div class="navbar-end">
                            <span class="navbar-item has-text-centered">
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

                                <a class="navbar-item" href="{{ url('feed') }}" target="_blank">
                                    <i class="fas fa-rss"></i> &nbsp; RSS
                                </a>

                                <a class="navbar-item" href="https://github.com/hillelcoren/itsallwidgets" target="_blank">
                                    <i class="fab fa-github"></i> &nbsp; GitHub
                                </a>

                                <div class="navbar-item">
                                    &nbsp;
                                </div>

                                @if (! auth()->check())
                                    <a class="button is-dark" href="{{ url(auth()->check() ? 'flutter-apps/submit' : 'auth/google') }}">
                                        <span class="icon">
                                            <i class="fas fa-bell"></i>
                                        </span> &nbsp;
                                        <span>Get Updates</span>
                                    </a> &nbsp;&nbsp;
                                @endif

                                <a class="button is-success" href="{{ url(auth()->check() ? 'flutter-apps/submit' : 'auth/google') }}">
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
            </p>
        </div>
    </footer>


</body>
</html>

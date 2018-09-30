<!doctype html>
<html lang="en">
<head>

    @if ($tracking_id)
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
    @else
        <script>
        function gtag(){}
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

    <link href="https://fonts.googleapis.com/css?family=Overpass" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bulma.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bulma-extensions.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
    <script src="{{ asset('js/vue.min.js') }}"></script>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script defer src="{{ asset('js/fontawesome.js') }}"></script>

    <style>

    .has-bg-img {
        background: url('/images/header-bg.jpg')center center; background-size:cover;
    }


    .footer {
        padding: 7rem 1.5rem 7rem;
    }

    .is-head-font {
        font-family: 'Overpass', sans-serif;
        font-weight: 800;
        letter-spacing: 1px;
        vertical-align: bottom;
    }

    .is-body-font {
        font-family: 'Overpass', sans-serif;
    }

    .is-elevated {
        -moz-filter: drop-shadow(0px 16px 16px #CCC);
        -webkit-filter: drop-shadow(0px 16px 16px #CCC);
        -o-filter: drop-shadow(0px 16px 16px #CCC);
        filter: drop-shadow(0px 16px 16px #CCC);
    }

    .is-elevated-dark {
        -moz-filter: drop-shadow(0px 3px 2px #777);
        -webkit-filter: drop-shadow(0px 3px 2px #777);
        -o-filter: drop-shadow(0px 3px 2px #777);
        filter: drop-shadow(0px 3px 2px #777);
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

    .is-vertical-center {
        display: flex;
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

    $.fn.extend({
        animateCss: function(animationName, callback) {
            var animationEnd = (function(el) {
                var animations = {
                    animation: 'animationend',
                    OAnimation: 'oAnimationEnd',
                    MozAnimation: 'mozAnimationEnd',
                    WebkitAnimation: 'webkitAnimationEnd',
                };

                for (var t in animations) {
                    if (el.style[t] !== undefined) {
                        return animations[t];
                    }
                }
            })(document.createElement('div'));

            this.addClass('animated ' + animationName).one(animationEnd, function() {
                $(this).removeClass('animated ' + animationName);

                if (typeof callback === 'function') callback();
            });

            return this;
        },
    });

    $(function() {
        $('div.navbar-animate').addClass('animated tada').css('visibility', 'visible');
        $('.hero-body .title, .hero-body .subtitle').addClass('animated fadeIn').css('visibility', 'visible');
        if (document.body.clientWidth > 1000) {
            $('span.navbar-item').addClass('animated slideInDown').css('visibility', 'visible');
        } else {
            $('span.navbar-item').css('visibility', 'visible');
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

    <section class="hero is-info is-head-font has-bg-img">
        <div class="hero-head">
            <nav class="navbar">
                <div class="container">
                    <div class="navbar-brand">
                        <div class="navbar-animate">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('images/logo.png') }}" width="240" style="padding-top: 12px; padding-left: 12px;"/>
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

                                <a class="navbar-item" href="{{ url('about') }}">
                                    <i class="fas fa-info-circle"></i> &nbsp; About
                                </a>

                                <a class="navbar-item" href="{{ url('feed') }}" target="_blank">
                                    <i class="fas fa-rss"></i> &nbsp; RSS
                                </a>

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
                                @endif

                                <div class="navbar-item">
                                    &nbsp;
                                </div>

                                <a class="button is-elevated-dark" style="color:white; background-color:#366cb6; border-color:#366cb6"
                                    href="https://twitter.com/itsallwidgets" target="_blank">
                                    <span class="icon">
                                        <i class="fab fa-twitter"></i>
                                    </span> &nbsp;
                                    <span>Twitter Feed</span>
                                </a> &nbsp;&nbsp;

                                @if (! auth()->check())
                                    <a class="button is-elevated-dark" style="color:white; background-color:#54b5eb; border-color:#54b5eb"
                                    href="{{ url(auth()->check() ? 'submit' : 'auth/google') }}">
                                        <span class="icon">
                                            <i class="fas fa-bell"></i>
                                        </span> &nbsp;
                                        <span>Monthly Stats</span>
                                    </a> &nbsp;&nbsp;
                                @endif

                                <a class="button is-elevated-dark" style="color:white; background-color:#5e60af; border-color:#5e60af"
                                    href="{{ url(auth()->check() ? 'submit' : 'auth/google') }}">
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

        <div class="hero-body" style="padding-top:34px">
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

    @if (session('warning'))
        <section class="hero is-light is-small">
            <div class="hero-body">
                <div class="container">
                    <div class="notification is-warning">
                        {{ session('warning') }}
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if (session('status'))
        <section class="hero hero-status is-light is-small">
            <div class="hero-body">
                <div class="container">
                    <div class="notification is-success">
                        {{ session('status') }}
                    </div>
                </div>
            </div>
        </section>

        <script>
        $(function() {
            setTimeout(function() {
                $('section.hero-status .notification').animateCss('animated fadeOut', function() {
                    $('section.hero-status').hide();
                });
            }, 3000);
        });
        </script>
    @endif

    @yield('content')

    <p>&nbsp;</p>
    <p>&nbsp;</p>

    <footer class="footer is-body-font">
        <div class="content has-text-centered">
            <p>
                Made with &nbsp;<i style="color:red" class="fas fa-heart"></i>&nbsp; by the <a href="https://medium.com/flutter-community" target="_blank">Flutter Community</a>
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

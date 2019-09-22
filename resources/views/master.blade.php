<!doctype html>
<html lang="en">
<head>

    @if ($tracking_id)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $tracking_id }}"></script>
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

    <title>@yield('title') | {{ appName() }}</title>
    <meta name="description" content="@yield('description')">

    @include('feed::links')

    <meta property="og:title" content="@yield('title') | {{ appName() }}">
    <meta property="og:description" content="@yield('description')">
    <meta property="og:image" content="@yield('image_url')?clear_cache=1">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:site_name" content="{{ appName() }}">

    <meta name="twitter:title" content="@yield('title') | {{ appName() }}">
    <meta name="twitter:description" content="@yield('description')">
    <meta name="twitter:image" content="@yield('image_url')?clear_cache=2">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image:alt" content="@yield('title') | {{ appName() }}">

    <meta charset="utf-8">
    <meta id="token" name="token" value="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    @if (isGL())

    @else
        <link rel="shortcut icon" href="{{ asset('/images/favicon.png') }}">
    @endif

    <link href="https://fonts.googleapis.com/css?family=Overpass:200,400,800" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bulma.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bulma-extensions.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
    <script src="{{ asset('js/vue.min.js') }}"></script>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script defer src="{{ asset('js/fontawesome.js') }}"></script>

    <style>

    [v-cloak] {
      display: none;
    }

    a {
        color: #368cd5;
    }

    .has-bg-img {
        background: url('/images/header_bg.jpg') center center; background-size:cover;
    }

    .has-bg-podcast-img {
        background: url('/images/header_podcast_bg.jpg') center center; background-size:cover;
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

    @if (request()->is('podcast*') || isset($useBlackHeader))
        a.navbar-item:hover {
            background-color: #000 !important;
        }
    @endif

    .button.is-elevated-dark {
        color: white;
        @if (request()->is('podcast*') || isset($useBlackHeader))
            background-color:#000;
            border-color:#000;
        @else
            background-color:#5e60af;
            border-color:#5e60af;
        @endif
    }

    .button.is-elevated-dark:hover {
        @if (request()->is('podcast*') || isset($useBlackHeader))
            background-color:#060606;
            border-color:#060606;
        @else
            background-color:#6062b1;
            border-color:#5e60af;
        @endif
        -moz-filter: drop-shadow(0px 2px 4px #888);
        -webkit-filter: drop-shadow(0px 2px 4px #888);
        -o-filter: drop-shadow(0px 2px 4px #888);
        filter: drop-shadow(0px 2px 4px #888);
    }

    .is-elevated-dark {
        -moz-filter: drop-shadow(0px 1px 2px #777);
        -webkit-filter: drop-shadow(0px 1px 2px #777);
        -o-filter: drop-shadow(0px 1px 2px #777);
        filter: drop-shadow(0px 1px 2px #777);
    }

    .is-hover-elevated {
        -moz-filter: filter: drop-shadow(0px 16px 16px #CCC);
        -webkit-filter: filter: drop-shadow(0px 16px 16px #CCC);
        -o-filter: filter: drop-shadow(0px 16px 16px #CCC);
        filter: drop-shadow(0px 16px 16px #CCC);
        -webkit-transition : -webkit-filter 320ms;
        -moz-transition : -moz-filter 320ms;
        -o-transition : -o-filter 320ms;
        transition : filter 320ms;
    }

    .is-hover-elevated:hover {
        -moz-filter: filter: drop-shadow(0px 4px 6px #CCC);
        -webkit-filter: filter: drop-shadow(0px 4px 6px #CCC);
        -o-filter: filter: drop-shadow(0px 4px 6px #CCC);
        filter: drop-shadow(0px 4px 6px #CCC);
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
    .hero-body-animate .title,
    .hero-body-animate .subtitle,
    .hero-body-animate .button {
        visibility: hidden;
    }

    .hero-body .subtitle a:hover {
        border-bottom: 1px white dashed;
    }

    .footer .column a div {
        font-size: 15px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        word-break: break-all;
    }

    /* https://stackoverflow.com/a/22603610/497368 */
    .strike {
        display: block;
        text-align: center;
        overflow: hidden;
        white-space: nowrap;
    }

    .strike > span {
        position: relative;
        display: inline-block;
    }

    .strike > span:before,
    .strike > span:after {
        content: "";
        position: absolute;
        top: 50%;
        width: 230px;
        height: 1px;
        background: #dddddd;
    }

    .strike > span:before {
        right: 100%;
        margin-right: 15px;
    }

    .strike > span:after {
        left: 100%;
        margin-left: 15px;
    }

    .hero-body {
         padding: 5rem 1.5rem 5rem 1.5rem;
    }

    .navigation-button {
        position: absolute;
        top: 50%;
    }

    .next-navigation-button {
        right: 30px;
    }

    .prev-navigation-button {
        left: 30px;
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
        $('div.navbar-animate').addClass(
            @if (isFP())
                'animated pulse'
            @elseif (isFX())
                'animated zoomIn'
            @elseif (isFE())
                'animated fadeInDown'
            @else
                'animated tada'
            @endif
        ).css('visibility', 'visible');

        $('.hero-body-animate .title, .hero-body-animate .subtitle, .hero-body-animate .button').addClass('animated fadeIn').css('visibility', 'visible');
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

    function trackBannerClick(eventSlug, isTwitter) {
        $.ajax({
            type: 'GET',
            url: '{{ url('/flutter-event-click') }}/' + encodeURIComponent(eventSlug) + '/' + (isTwitter ? 'twitter' : 'event'),
        });
    }

    </script>

    @yield('head')



</head>

<body>
    <section class="hero is-info is-head-font {{ request()->is('podcast*') || isset($useBlackHeader) ? 'has-bg-podcast-img' : 'has-bg-img' }}" style="background-color: {{ request()->is('podcast*') || isset($useBlackHeader) ? '#222' : '#3389d7' }};">
        <div class="hero-head">
            <nav class="navbar">
                <div class="container">
                    <div class="navbar-brand">
                        <div class="navbar-animate">
                            @if (isGL())

                            @else
                                @if (isFP())
                                    <a href="{{ fpUrl() }}">
                                        <div style="display:inline-block;vertical-align:top;">
                                            <img src="{{ asset('images/logo_blank.png') }}" width="80" style="padding-top: 12px; padding-left: 12px;"></img>
                                        </div>
                                        <div style="display:inline-block; font-size: 32px; padding-top:26px; padding-left:8px; font-weight: bold">
                                            Flutter Pro
                                        </div>
                                    </a>
                                @elseif (isFE())
                                    <a href="{{ feUrl() }}">
                                        <div style="display:inline-block;vertical-align:top;">
                                            <img src="{{ asset('images/logo_blank.png') }}" width="80" style="padding-top: 12px; padding-left: 12px;"/>
                                        </div>
                                        <div style="display:inline-block; font-size: 32px; padding-top:26px; padding-left:8px; font-weight: bold">
                                            Flutter Events
                                        </div>
                                    </a>
                                @elseif (isFX())
                                    <a href="{{ fxUrl() }}">
                                        <div style="display:inline-block;vertical-align:top;">
                                            <img src="{{ asset('images/logo_blank.png') }}" width="80" style="padding-top: 12px; padding-left: 12px;"/>
                                        </div>
                                        <div style="display:inline-block; font-size: 32px; padding-top:26px; padding-left:8px; font-weight: bold">
                                            FlutterX
                                        </div>
                                    </a>
                                @else
                                    <a href="{{ iawUrl() }}">
                                        <img src="{{ asset('images/logo.png') }}" width="240" style="padding-top: 12px; padding-left: 12px;"/>
                                    </a>
                                @endif
                            @endif
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

                                @navigation()
                                @endnavigation

                                &nbsp;&nbsp;&nbsp;

                                @channels(['isPodcast' => request()->is('podcast*') || isset($useBlackHeader)])
                                @endchannels
                            </span>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <div class="hero-body hero-body-animate" style="padding-top:34px">
            <div class="container has-text-centered">
                <div class="subtitle" style="font-weight:200; font-size:18px;">
                    @if (isGL())
                        MADE WITH &nbsp;<i class="fas fa-heart" style="font-size:16px"></i>&nbsp; TO BRING GEULA!
                    @else
                        MADE WITH &nbsp;<i class="fas fa-heart" style="font-size:16px"></i>&nbsp; BY THE <a href="https://medium.com/flutter-community" target="_blank">FLUTTER COMMUNITY</a>
                    @endif
                </div>
                <div class="title" style="font-size:38px; padding-top:8px;">
                    @yield('header_title', 'An open list of apps built with Flutter')
                </div>
                <div class="subtitle" style="font-size:18px; padding-bottom:6px;">
                    @if (isGL())
                        The global revelation of a higher reality
                    @else
                        @yield('header_subtitle', 'Feel free to add an app in progress and update it when it goes live')
                    @endif
                </div>
                @if (!isFX() && !isGL() && (!request()->is('podcast*') || (auth()->check() && auth()->user()->is_admin)))
                    <a class="button is-elevated-dark" style="padding: 20px 32px 18px 32px"
                        href="@yield('header_button_url', url(auth()->check() ? 'submit' : 'auth/google?intended_url=submit'))">
                        <span class="icon">
                            <i class="@yield('header_button_icon', 'fas fa-cloud-upload-alt')"></i>
                        </span> &nbsp;
                        <span>@yield('header_button_label', 'SUBMIT APP')</span>
                    </a>
                @endif
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

            <img src="{{ asset('images/thank_you.png') }}" width="330"/>

            <p>
                @if (isGL())
                    <div style="font-size:16px; letter-spacing:2px; padding-bottom:6px; font-weight:600">
                        To the Gate Of Unity Community
                    </div>
                @else
                    <div style="font-size:16px; letter-spacing:2px; padding-bottom:6px; font-weight:600">
                        TO THE <a href="https://flutter.dev" target="_blank">FLUTTER</a> & <a href="https://www.dartlang.org/" target="_blank">DART</a> TEAMS
                    </div>
                    for this amazing platform!
                @endif
            </p>

            <p style="padding-top:16px;">
                <div class="strike">
                   <span>FROM</span>
                </div>
            <p>

            <div class="columns is-gapless is-centered" style="padding-top:12px;">
                @if (isGL())
                    <div class="column is-offset-4 is-1">
                        <a href="https://twitter.com/charlottecoren" target="_blank">
                            <img src="{{ asset('images/img_charlotte.png') }}" width="72"/><br/>
                            <div>Chana Vered Coren</div>
                        </a>
                    </div><br/>
                @else
                    <div class="column is-offset-4 is-1">
                        <a href="https://twitter.com/hillelcoren" target="_blank">
                            <img src="{{ asset('images/img_hillel.png') }}" width="72"/><br/>
                            <div>Hillel Coren</div>
                        </a>
                    </div><br/>
                    @if (isFE())
                        <div class="column is-1">
                            <a href="https://twitter.com/Nash0x7E2" target="_blank">
                                <img src="{{ asset('images/img_nash.png') }}" width="72"/><br/>
                                <div>Nash Ramdial</div>
                            </a>
                        </div><br/>
                        <div class="column is-1">
                            <a href="https://twitter.com/MendyMarcus" target="_blank">
                                <img src="{{ asset('images/img_mendy.png') }}" width="72"/><br/>
                                <div>Mendy Marcus</div>
                            </a>
                        </div><br/>
                        <div class="column is-1">
                            <a href="https://twitter.com/pblead26" target="_blank">
                                <img src="{{ asset('images/img_pooja.png') }}" width="72"/><br/>
                                <div>Pooja Bhaumik</div>
                            </a>
                        </div><br/>
                    @elseif (isFX())
                        <div class="column is-1">
                            <a href="https://twitter.com/efthemess" target="_blank">
                                <img src="{{ asset('images/img_efthymios.png') }}" width="72"/><br/>
                                <div>Efthymis Sarmpanis</div>
                            </a>
                        </div><br/>
                        <div class="column is-1">
                            <a href="https://twitter.com/__nawalhmw" target="_blank">
                                <img src="{{ asset('images/img_nawal.png') }}" width="72"/><br/>
                                <div>Nawal Alhamwi</div>
                            </a>
                        </div><br/>
                        <div class="column is-1">
                            <a href="https://twitter.com/SergiAndReplace" target="_blank">
                                <img src="{{ asset('images/img_sergi.png') }}" width="72"/><br/>
                                <div>Sergi Martínez</div>
                            </a>
                        </div><br/>
                    @elseif (isFP())
                        <div class="column is-1">
                            <a href="https://twitter.com/lariki" target="_blank">
                                <img src="{{ asset('images/img_lara.png') }}" width="72"/><br/>
                                <div>Lara Martín</div>
                            </a>
                        </div><br/>
                        <div class="column is-1">
                            <a href="https://twitter.com/imthepk" target="_blank">
                                <img src="{{ asset('images/img_pawan.png') }}" width="72"/><br/>
                                <div>Pawan Kumar</div>
                            </a>
                        </div><br/>
                        <div class="column is-1">
                            <a href="https://twitter.com/flschweiger" target="_blank">
                                <img src="{{ asset('images/img_frederik.png') }}" width="72"/><br/>
                                <div>Frederik Schweiger</div>
                            </a>
                        </div><br/>
                    @else
                        <div class="column is-1">
                            <a href="https://twitter.com/ThomasBurkhartB" target="_blank">
                                <img src="{{ asset('images/img_thomas.png') }}" width="72"/><br/>
                                <div>Thomas Burkhart</div>
                            </a>
                        </div><br/>
                        <div class="column is-1">
                            <a href="https://twitter.com/devangelslondon" target="_blank">
                                <img src="{{ asset('images/img_simon.png') }}" width="72"/><br/>
                                <div>Simon Lightfoot</div>
                            </a>
                        </div><br/>
                        <div class="column is-1">
                            <a href="https://twitter.com/scottstoll2017" target="_blank">
                                <img src="{{ asset('images/img_scott.png') }}" width="72"/><br/>
                                <div>Scott Stoll</div>
                            </a>
                        </div><br/>
                    @endif
                @endif
            </div>

            <br/> &nbsp; <br/>

            @if (!isFX() && !isGL() && (!request()->is('podcast*') || (auth()->check() && auth()->user()->is_admin)))
                <a class="button is-elevated-dark" style="padding: 20px 32px 18px 32px"
                    href="@yield('header_button_url', url(auth()->check() ? 'submit' : 'auth/google?intended_url=submit'))">
                    <span class="icon">
                        <i class="@yield('header_button_icon', 'fas fa-cloud-upload-alt')"></i>
                    </span> &nbsp;
                    <span>@yield('header_button_label', 'SUBMIT APP')</span>
                </a>
            @endif
        </div>
    </footer>

    <section class="hero is-dark is-small is-body-font"
            style="text-align:center; letter-spacing: 4px; font-size: 16px; font-weight: 200">
        <div class="hero-body">
            <div class="container">
                <a href="{{ iawUrl() }}" title="An open list of apps built with Flutter">IT'S&nbsp;ALL&nbsp;WIDGETS!</a> &nbsp;&nbsp; • &nbsp;&nbsp;
                <a href="{{ feUrl() }}" title="An Open List of Flutter Events">FLUTTER&nbsp;EVENTS</a> &nbsp;&nbsp; • &nbsp;&nbsp;
                <a href="{{ fxUrl() }}" title="A Searchable List of Flutter Resources">FLUTTERX</a> &nbsp;&nbsp; • &nbsp;&nbsp;
                <a href="{{ fpUrl() }}" title="A Showcase for Passionate Flutter Developers">FLUTTER&nbsp;PRO</a>
            </div>
        </div>
    </section>

</body>
</html>

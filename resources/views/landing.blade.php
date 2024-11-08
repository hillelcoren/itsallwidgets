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
    @endif

    <title>@yield('title')</title>
    <meta name="description" content="@yield('description')">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.99em%22 font-size=%2275%22>🔘</text></svg>">

    <meta property="og:title" content="@yield('title')">
    <meta property="og:description" content="@yield('description')">
    <meta property="og:image" content="@yield('image_url')">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:site_name" content="{{ appName() }}">

    <meta name="twitter:title" content="@yield('title')">
    <meta name="twitter:description" content="@yield('description')">
    <meta name="twitter:image" content="@yield('image_url')">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image:alt" content="@yield('title')">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/bulma.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bulma-extensions.min.css') }}">
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/fontawesome.js') }}"></script>

    <style>
        .is-elevated {
            -moz-filter: drop-shadow(0px 0px 20px #000);
            -webkit-filter: drop-shadow(0px 0px 20px #000);
            -o-filter: drop-shadow(0px 0px 20px #000);
            filter: drop-shadow(0px 0px 20px #000);            
        }

        .app-footer a:hover {
            border-bottom: 1px white dashed;
        }

        /* https://stackoverflow.com/a/38270745/497368 */
        .video-wrapper {
            position: relative;
            padding-bottom: 56.25%;
            padding-top: 25px;
            height: 0;
        }

        .video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .center {
            text-align: center;
        }

    </style>


    @yield('head')
</head>
<body>
    @yield('body')

    <section class="app-footer hero is-dark is-small is-body-font" style="text-align:center; letter-spacing: 2px; font-size: 16px; font-weight: 200;">
        <div class="hero-body">
            <div class="container">
                See more <a href="https://flutter.dev" title="Build apps for any screen" target="_blank">Flutter</a>
                apps on <a href="{{ iawUrl() }}" title="An open list of apps built with Flutter" target="_blank">It's&nbsp;All&nbsp;Widgets!</a> 
                 💙
                Supported by <a href="https://invoiceninja.com" title="Leading small-business platform to manage invoices, expenses & tasks" target="_blank">Invoice&nbsp;Ninja</a>
                & <a href="https://eventschedule.com" title="The simple and free way to share your event schedule" target="_blank">Event&nbsp;Schedule</a>
            </div>
        </div>
    </section>

</body>
</html>
<!doctype html>
<html lang="en">
<head>
    <meta name="google" content="notranslate">


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

    @yield('head')
</head>
<body>
    @yield('body')
</body>
</html>
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

    <title>{{ $user->name }} | {{ appName() }}</title>
    <meta name="description" content="{{ $user->bio }}">

    <meta property="og:title" content="{{ $user->name }} | {{ appName() }}">
    <meta property="og:description" content="{{ $user->bio }}">
    <meta property="og:image" content="{{ $user->imageUrl() }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:site_name" content="{{ appName() }}">

    <meta name="twitter:title" content="{{ $user->name }} | {{ appName() }}">
    <meta name="twitter:description" content="{{ $user->bio }}">
    <meta name="twitter:image" content="{{ $user->imageUrl() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image:alt" content="{{ $user->name }} | {{ appName() }}">

    <meta charset="utf-8">
    <meta id="token" name="token" value="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <link rel="shortcut icon" href="{{ asset('/images/favicon.png') }}">

    <style>
        body {
            margin: 0px;
        }

        iframe {
            position: fixed;
            background: #FFF;
            border: none;
            top: 0; right: 0;
            bottom: 0; left: 0;
            width: 100%;
            height: 100%;
        }
    </style>

</head>

<body>
    <iframe sandboxx="allow-scripts allow-same-origin allow-top-navigation allow-popups" src="{{ $user->profileUrl() }}"
        allowTransparency="true" width="100%" height="100%" frameBorder="0">
    </iframe>
</body>
</html>

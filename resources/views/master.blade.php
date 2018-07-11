<!doctype html>
<html lang="en">
<head>
    @if (config('services.analytics.tracking_id'))
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-122229484-1"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ config('services.analytics.tracking_id') }}');
        </script>
    @endif

    <meta charset="utf-8">
    <meta id="token" name="token" value="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="An open list of apps built with Google Flutter">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title>@yield('title') | {{ config('app.name') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.1/css/bulma.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js"></script>
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
                                <a class="button is-info is-inverted" href="{{ url('submit-app') }}">
                                    <span class="icon">
                                        <i class="fas fa-upload"></i>
                                    </span>
                                    <span>Submit Application</span>
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
                Developed by
                <a href="https://hillelcoren.com" target="_blank">Hillel Coren</a>
                <a href="https://twitter.com/hillelcoren" target="_blank"><i class="fab fa-twitter-square"></i></a>
                and
                <a href="https://www.burkharts.net/apps/blog/" target="_blank">Thomas Burkhart</a>
                <a href="https://twitter.com/ThomasBurkhartB" target="_blank"><i class="fab fa-twitter-square"></i></a>
            </p>
        </div>
    </footer>

</body>
</html>

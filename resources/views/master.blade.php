<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta id="token" name="token" value="{{ csrf_token() }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="An open list of apps built with Google Flutter">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

  <title>@yield('title') | {{ config('app.name') }}</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.1/css/bulma.min.css">
  <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js"></script>

  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', {{ config('services.analytics.tracking_id') }}, 'auto');
    ga('send', 'pageview');
  </script>

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
                <h1 class="title">
                    An open list of apps built with Google Flutter
                </h1>
            </div>
        </div>
    </section>

    <p>&nbsp;</p>
    <p>&nbsp;</p>

    @if (session('status'))
        <div class="notification is-success">
            {{ session('status') }}
        </div>
        <p>&nbsp;</p>
    @endif

    @yield('content')

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

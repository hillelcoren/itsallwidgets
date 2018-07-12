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

    <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.1/css/bulma.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js"></script>

</head>

<body>

    <div class="container" style="padding-top:300px">

        <div class="columns">
            <div class="column is-3">
            </div>
            <div class="column">
            <form  method="POST">
                {{ csrf_field() }}

                <div class="field">
                    <label class="label" for="twitter_url">
                        Secret
                    </label>
                    <div class="control" style="max-width:300px">
                        <input
                        class="input {{ $errors->has('twitter_url') ? ' is-danger' : '' }}"
                        name="secret"
                        autofocus="true"
                        required>
                    </div>
                </div>

                <div class="block">
                    <button class="button is-info is-medium">Submit</button>
                </div>

                <div class="block">
                    Thanks for checking out the site!<br/>
                    Please don't share the link publicly while we get it ready.
                </div>

            </form>
            </div>

        </div>

    </div>


</body>
</html>

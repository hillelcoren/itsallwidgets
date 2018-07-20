@extends('master')

@section('title', 'About')
@section('description', 'An open list of apps built with Google Flutter')
@section('image_url', asset('images/background.png'))

@section('content')

    <section class="hero is-dark is-small">
        <div class="hero-body">
            <div class="container">
                <div class="title">
                    About Us
                </div>
                <div class="subtitle">
                    Our mission is to provide value to Flutter and Dart developers
                </div>
            </div>
        </div>
    </section><br/>

    <section class="section">
        <div class="container">

            <div class="columns">
                <div class="column has-text-centered">
                    <i class="fas fa-bell" style="font-size:70px"></i>
                </div>
                <div class="column is-four-fifths">
                    <div class="title is-4">
                        Keep track of new Flutter apps
                    </div>
                    <div class="block is-size-4">
                        The Flutter community continues to grow at an exponential rate, the site provides a single place to track all new apps being developed and released.
                    </div><br/>
                </div>
            </div>

            <div class="columns">
                <div class="column has-text-centered">
                    <i class="fas fa-user-circle" style="font-size:70px"></i>
                </div>
                <div class="column is-four-fifths">
                    <div class="title is-4">
                        Create a free profile page
                    </div>
                    <div class="block is-size-4">
                        If you submit your app we'll provide you with a permalink for your custom page. It has links to both app stores, is optimized for social sharing and can be updated at any time.
                    </div><br/>
                </div>
            </div>

            <div class="columns">
                <div class="column has-text-centered">
                    <i class="fas fa-image" style="font-size:70px"></i>
                </div>
                <div class="column is-four-fifths">
                    <div class="title is-4">
                        Validate your screenshot
                    </div>
                    <div class="block is-size-4">
                        One of the first things we noticed after launching was that some screenshots would have a faint yellow error border. The site automatically checks the screenshot and warns you if it's found.
                    </div><br/>
                </div>
            </div>

            <div class="columns">
                <div class="column has-text-centered">
                    <i class="fab fa-twitter" style="font-size:70px"></i>
                </div>
                <div class="column is-four-fifths">
                    <div class="title is-4">
                        Promote your application
                    </div>
                    <div class="block is-size-4">
                        On submission we'll automatically tweet your app to our <a href="https://twitter.com/itsallwidgets" target="_blank">Twitter account</a> notifying all of our followers. If you set a Twitter link in the profile we'll include your handle in the tweet.
                    </div><br/>                    
                </div>
            </div>


            <div class="columns">
                <div class="column has-text-centered">
                    <i class="fas fa-chart-line" style="font-size:70px"></i>
                </div>
                <div class="column is-four-fifths">
                    <div class="title is-4">
                        Provide monthly metrics
                    </div>
                    <div class="block is-size-4">
                        Once we've accumulated enough data to generate meaningful insights we plan to share the results in a monthly email.
                        @if (! auth()->check())
                            <a href="{{ url('auth/google') }}">Click here</a> to subscribe.
                        @endif
                    </div><br/>
                </div>
            </div>


        </div>
    </section>

</div>


@stop

@extends('master')

@section('title', 'About')
@section('description', 'An open list of apps built with Flutter')
@section('image_url', asset('images/background.jpg'))

@section('content')

    <script>
    $(function() {

        $('.header').addClass('animated fadeIn').css('visibility', 'visible');
        setTimeout(function() {
            $('.column-1').addClass('animated fadeInDown').css('visibility', 'visible');
        }, 100);
        setTimeout(function() {
            $('.column-2').addClass('animated fadeInDown').css('visibility', 'visible');
        }, 200);
        setTimeout(function() {
            $('.column-3').addClass('animated fadeInDown').css('visibility', 'visible');
        }, 300);
        setTimeout(function() {
            $('.column-4').addClass('animated fadeInDown').css('visibility', 'visible');
        }, 400);
        setTimeout(function() {
            $('.column-5').addClass('animated fadeInDown').css('visibility', 'visible');
        }, 500);
    })

    </script>

    <section class="header section is-body-font has-text-centered" style="visibility: hidden;">
        <div style="color:#368cd5; font-weight:600; letter-spacing: 2px;">
            ABOUT US
        </div>
        <div style="font-size: 26px;">
            Our mission is to provide value</br>
            to developers using
        </div>
        <div class="is-vertical-center has-text-centered">
            <a href="https://flutter.io/" target="_blank">
                <img src="{{ asset('images/flutter_logo.png') }}" width="210"/>
            </a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="font-size:28px; color:#888888">+</span>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="https://www.dartlang.org/" target="_blank" style="padding-top:4px">
                <img src="{{ asset('images/dart_logo.png') }}" width="200"/>
            </a>
        </div>
    </section>

    <p>&nbsp;</p>

    <section class="section is-body-font">
        <div class="container">

            <div class="columns column-1" style="visibility:hidden">
                <div class="column is-6 is-offset-3">
                    <div class="subtitle is-4" style="font-weight:600">
                        Learn about new apps
                    </div>
                    <div class="block is-size-5">
                        The Flutter community continues to grow at an exponential rate, the site provides a place to learn about new apps being developed and released.
                    </div><br/>
                </div>
            </div>

            <div class="columns column-2" style="visibility:hidden">
                <div class="column is-6 is-offset-3">
                    <div class="subtitle is-4" style="font-weight:600">
                        Create a free profile page
                    </div>
                    <div class="block is-size-5">
                        If you submit your app we'll provide you with a permalink for your custom page. It has links to both app stores, is optimized for social sharing and can be updated at any time.
                    </div><br/>
                </div>
            </div>

            <div class="columns column-3" style="visibility:hidden">
                <div class="column is-6 is-offset-3">
                    <div class="subtitle is-4" style="font-weight:600">
                        Validate your screenshot
                    </div>
                    <div class="block is-size-5">
                        One of the first things we noticed after launching was that some screenshots would have a faint yellow error border. The site automatically checks the screenshot and warns you if it's found.
                    </div><br/>
                </div>
            </div>

            <div class="columns column-4" style="visibility:hidden">
                <div class="column is-6 is-offset-3">
                    <div class="subtitle is-4" style="font-weight:600">
                        Promote your application
                    </div>
                    <div class="block is-size-5">
                        On submission we'll automatically tweet your app to our <a href="https://twitter.com/itsallwidgets" target="_blank">Twitter account</a> notifying all of our followers. If you set a Twitter link in the profile we'll include your handle in the tweet.
                    </div><br/>
                </div>
            </div>


            <div class="columns column-5" style="visibility:hidden">
                <div class="column is-6 is-offset-3">
                    <div class="subtitle is-4" style="font-weight:600">
                        Provide monthly metrics
                    </div>
                    <div class="block is-size-5">
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

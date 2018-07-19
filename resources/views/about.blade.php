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
                    <i class="fas fa-user-circle" style="font-size:80px"></i>
                </div>
                <div class="column is-four-fifths">
                    <div class="title is-4">
                        Create a free profile page
                    </div>
                    <div class="block is-size-4">
                        Once your app is submitted we'll provide you with a perma-link for your custom page. It has links to both app stores, is optimized for social sharing and can be updated at any time.
                    </div><br/>
                </div>
            </div>

            <div class="columns">
                <div class="column has-text-centered">
                    <i class="fas fa-bullhorn" style="font-size:80px"></i>
                </div>
                <div class="column is-four-fifths">
                    <div class="title is-4">
                        Promote your application
                    </div>
                    <div class="block is-size-4">
                        On submission we'll automatically tweet your app to our <a href="https://twitter.com/itsallwidgets" target="_blank">Twitter account</a> notifying all of our followers. If you set a Twitter URL in the profile we'll include the handle in the tweet.
                    </div><br/>
                </div>
            </div>

            <div class="columns">
                <div class="column has-text-centered">
                    <i class="fas fa-exclamation-triangle" style="font-size:80px"></i>
                </div>
                <div class="column is-four-fifths">
                    <div class="title is-4">
                        Validate screenshot
                    </div>
                    <div class="block is-size-4">
                        One of the first things we noticed after launch the site was that sometimes the uploaded screenshot would have a faint yellow error border. 
                    </div><br/>
                </div>
            </div>

            <div class="columns">
                <div class="column has-text-centered">
                    <i class="fas fa-user-circle" style="font-size:80px"></i>
                </div>
                <div class="column is-four-fifths">
                    <div class="title is-4">

                    </div>
                    <div class="block is-size-4">

                    </div><br/>
                </div>
            </div>

            <div class="columns">
                <div class="column has-text-centered">
                    <i class="fas fa-user-circle" style="font-size:80px"></i>
                </div>
                <div class="column is-four-fifths">
                    <div class="title is-4">

                    </div>
                    <div class="block is-size-4">

                    </div><br/>
                </div>
            </div>


        </div>
    </section>

</div>


@stop

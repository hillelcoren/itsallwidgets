@extends('master')

@section('title', 'Flutter Podcast')
@section('description', 'An open podcast for Flutter developers')
@section('image_url', asset('images/background.jpg'))

@section('header_title', 'An open podcast for Flutter developers')
@section('header_subtitle', 'Share your Flutter story with the community')
@section('header_button_url', url(auth()->check() ? 'podcast/submit' : 'auth/google?intended_url=podcast/submit'))
@section('header_button_label', 'REQUEST INTERVIEW')
@section('header_button_icon', 'fas fa-microphone')

@section('content')

    <style>

    .short-description {
        line-height: 1.5em;
        height: 4.5em;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        text-overflow: ellipsis;
    }

    .podcast-episode {
        background-color: white;
        border-radius: 8px;
    }

    .podcast-episode a {
        color: #259bee;
    }

    .column {
        padding: 1rem 1rem 6rem 1rem;
    }

    @media screen and (max-width: 788px) {
        .slider-control {
            display: none;
        }
    }

    @media screen and (max-width: 769px) {
        .store-buttons img {
            max-width: 200px;
        }


        /*
        .is-hover-elevated {
            -moz-filter: drop-shadow(0px 16px 16px #CCC);
            -webkit-filter: drop-shadow(0px 16px 16px #CCC);
            -o-filter: drop-shadow(0px 16px 16px #CCC);
            filter: drop-shadow(0px 16px 16px #CCC);
        }
        */
    }

    </style>

    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>

    <div class="container">

    <div class="columns is-multiline is-8 is-variable">
        <div class="column is-one-third">
            <img src="{{ asset('images/favicon.png') }}"/>
        </div>
        <div class="column is-two-third">

            <h2 class="title">
                <div class="is-vertical-center">
                    It's All Widgets! Flutter Podcast
                </div>
                <div style="border-bottom: 2px #259bee solid; width: 50px; padding-top:12px;"/>
            </h2>
            <div class="subtitle" style="padding-top:8px; max-width:600px">
                Hosted by
                <a href="https://twitter.com/hillelcoren" target="_blank">Hillel Coren</a>,
                <a href="https://twitter.com/ThomasBurkhartB" target="_blank">Thomas Burkhart</a>,
                <a href="https://twitter.com/devangelslondon" target="_blank">Simon Lightfoot</a> and
                <a href="https://twitter.com/scottstoll2017" target="_blank">Scott Stoll</a>
            </div>

            <div style="max-width:600px" style="padding-tojp:6px">
                An ongoing series featuring some of the amazing developers from the Flutter community. In each episode we discuss the developer's background, what got them into Flutter and their thoughts on the platform in general.
            </div>
            <p>&nbsp;</p>
            <div style="max-width:600px">
                If you'd like to be featured in an episode please click "Request Interview" above. We all have a story to tell, make your voice heard!
            </div>
            <p>&nbsp;</p>
            <div>
                Music by <a href="https://www.facebook.com/ScottHolmesMusic/" target="_blank" rel="nofollow">Scott Holmes</a>
            </div>
            <p>&nbsp;</p>

            <!--
            <a class="button is-light is-slightly-elevated" href="" target="_blank">
                <i style="font-size: 20px" class="fab fa-reddit"></i> &nbsp;
                Feedback
            </a> &nbsp;
            -->

            <a class="button is-light is-slightly-elevated" href="{{ url('podcast/feed') }}" target="_blank">
                <i style="font-size: 20px" class="fas fa-rss"></i> &nbsp;
                RSS Feed
            </a> &nbsp;


        </div>
    </div>

	<div class="columns is-multiline is-5 is-variable">
		@foreach ($episodes as $episode)
			<div class="column is-one-third"
                @if ($episode->is_uploaded)
                    onclick="location.href = '{{ $episode->url() }}'" style="cursor: pointer;"
                @endif
            >
				<div class="podcast-episode is-hover-elevated">
                    <header style="padding: 16px">
                        <div class="is-pulled-right">
                            <img src="{{ $episode->avatar_url }}" style="border-radius: 50%; width: 50px;"/>
                        </div>
                        <p class="no-wrap" style="font-size:22px; padding-bottom:10px;">
                            {{ $episode->title }}
                        </p>
                        <div style="border-bottom: 2px #259bee solid; width: 50px"/>
                    </header>

					<div class="content" style="padding:16px;padding-bottom:16px;">
						<div class="short-description">
							{{ $episode->listDescription() }}
						</div>

                        <div style="padding-top:20px" class="is-size-6 has-text-grey">
                            @if ($episode->published_at)
                                Published: {{ \Carbon\Carbon::parse($episode->published_at)->format('M jS, Y') }}
                            @endif&nbsp;
                        </div>

                        @if (auth()->check() && auth()->user()->is_admin)
                            <br/>
                            <a class="button is-info is-slightly-elevated" href="{{ $episode->adminUrl() }}">
    							<i style="font-size: 20px" class="fas fa-edit"></i> &nbsp;
    							Edit Episode
    						</a>
                        @endif

					</div>

				</div>
                <p>&nbsp;</p>

			</div>
		@endforeach
	</div>
    </div>

	<p>&nbsp;</p>


@stop

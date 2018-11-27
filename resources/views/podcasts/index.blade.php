@extends('master')

@section('title', 'Flutter Podcast')
@section('description', 'An open podcast for Flutter developers')

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

    .podcast-episode .is-hover-visible {
        display: none;
    }

    .podcast-episode:hover .is-hover-visible {
        display: flex;
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

    <div class="container">

    <div class="columns is-multiline is-5 is-variable">
        <div class="column is-one-third">
        </div>
        <div class="column is-two-third">
            <div class="is-size-3">It's All Widgets! Flutter Podcast</div>
            <div class="is-size-5">
                Hosted by <a href="https://twitter.com/hillelcoren" target="_blank">Hillel Coren</a>
            </div>
            <p>&nbsp;</p>
            <div style="max-width:600px" style="padding-top:6px">
                A regular podcast featuring many of the amazing developers from the Flutter community. In each episode we review the developer's background, what got them into Flutter and their thoughts in general.
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
            <a class="button is-light is-slightly-elevated" href="{{ url('podcast/feed') }}" target="_blank">
                <i style="font-size: 20px" class="fas fa-rss"></i> &nbsp;
                RSS Feed
            </a>

        </div>
    </div>

    <p>&nbsp;</p>
    <p>&nbsp;</p>

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
                        <p class="no-wrap" v-bind:title="app.title" style="font-size:22px; padding-bottom:10px;">
                            {{ $episode->title }}
                        </p>
                        <div style="border-bottom: 2px #259bee solid; width: 50px"/>
                    </header>

					<div class="content" style="padding:16px;padding-bottom:16px;">
						<div class="short-description" title="{{ $episode->listDescription() }}">
							{{ $episode->listDescription() }}
						</div>

                        <p>&nbsp;</p>
                        @if (auth()->check() && auth()->user()->is_admin)
                            <a class="button is-info is-small is-slightly-elevated" href="{{ $episode->adminUrl() }}">
    							<i style="font-size: 20px" class="fas fa-edit"></i> &nbsp;
    							Edit Podcast
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

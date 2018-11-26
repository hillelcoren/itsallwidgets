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
        height: 3em;
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
		@foreach ($episodes as $episode)
			<div class="column is-one-third"
                @if ($episode->is_uploaded)
                    onclick="location.href = '{{ $episode->url() }}'"
                @endif
            >
				<div class="podcast-episode is-hover-elevated">
                    <header style="padding: 16px">
                        <p class="no-wrap" v-bind:title="app.title" style="font-size:22px; padding-bottom:10px;">
                            {{ $episode->title }}
                        </p>
                        <div style="border-bottom: 2px #259bee solid; width: 50px"/>
                    </header>

					<div class="content" style="padding:16px;padding-bottom:22px;">
						<div class="subtitle is-6" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $episode->listDescription() }}">
							{{ $episode->listDescription() }}
						</div>
					</div>

				</div>
                <p>&nbsp;</p>

			</div>
		@endforeach
	</div>
    </div>

	<p>&nbsp;</p>


@stop

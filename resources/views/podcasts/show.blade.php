@extends('master')

@section('title', $episode->title)
@section('description', $episode->short_description)
@section('image_url', asset('images/podcast.jpg'))

@section('header_title', 'An open podcast for Flutter developers')
@section('header_subtitle', 'Share your Flutter story with the community')
@section('header_button_url', url(auth()->check() ? 'podcast/submit' : 'auth/google?intended_url=podcast/submit'))
@section('header_button_label', 'REQUEST INTERVIEW')
@section('header_button_icon', 'fas fa-microphone')

@section('content')

	<section class="section is-body-font">
		<div class="container">

			<div class="columns is-multiline is-8 is-variable">
				<div class="column is-one-third">
		            <img src="{{ asset('images/podcast.jpg') }}"/>
		        </div>
				<div class="column is-two-third">
					<nav class="breadcrumb" aria-label="breadcrumbs">
						<ul>
							<li><a href="{{ url('/podcast') }}">All Episodes</a></li>
							<li class="is-active"><a href="#" aria-current="page">Episode #{{ $episode->episode }}</a></li>
						</ul>
					</nav>

					<h2 class="title">
						<div class="is-vertical-center">
							{{ $episode->title }}
							<img src="{{ $episode->avatar_url }}" style="border-radius: 50%; width: 50px; margin-left: 80px"/>
						</div>
						<div style="border-bottom: 2px #368cd5 solid; width: 50px; padding-top:8px;"/>
					</h2>

					<div style="padding-bottom:16px;">
						<audio controls preload="none" style="min-height:54px">
							<source src="{{ $episode->downloadUrl() }}" type="audio/mpeg" />
							Your browser does not support the audio element.
						</audio>
					</div>

					<a class="button is-slightly-elevated" href="{{ $episode->downloadUrl() }}">
						<i style="font-size: 20px" class="fas fa-download"></i> &nbsp; Download
					</a> &nbsp;

					<div class="dropdown is-hoverable">
		                <div class="dropdown-trigger is-slightly-elevated">
		                    <button class="button" aria-haspopup="true" aria-controls="dropdown-menu4">
		                        <span>
		                            <i style="font-size: 20px" class="fa fa-share"></i> &nbsp;
		                            Share Episode
		                        </span>
		                        <span class="icon is-small">
		                            <i class="fas fa-angle-down" aria-hidden="true"></i>
		                        </span>
		                    </button>
		                </div>
		                <div class="dropdown-menu" role="menu">
		                    <a href="https://www.facebook.com/sharer/sharer.php?u=#url" target="_blank" rel="nofollow">
		                        <div class="dropdown-content">
		                            <div class="dropdown-item">
		                                <i style="font-size: 20px" class="fab fa-facebook"></i> &nbsp; Facebook
		                            </div>
		                        </div>
		                    </a>
		                    <a href="https://twitter.com/share?text={{ urlencode("It's All Widgets! Flutter Podcast: " . $episode->title) }}&amp;url={{ urlencode($episode->url()) }}" target="_blank" rel="nofollow">
		                        <div class="dropdown-content">
		                            <div class="dropdown-item">
		                                <i style="font-size: 20px" class="fab fa-twitter"></i> &nbsp; Twitter
		                            </div>
		                        </div>
		                    </a>
		                </div>
		            </div>

					<p>&nbsp;</p>


					<div class="subtitle" style="padding-top:16px; padding-bottom:8px; max-width:600px">
						{{ $episode->short_description }}
					</div>

					@if ($episode->website_url)
						<div class="content" style="padding-bottom: 8px;">
							<a href="{{ $episode->website_url }}" target="_blank" rel="nofollow">{{ $episode->website_url }}</a>
						</div>
					@endif

					<a class="button is-medium is-slightly-elevated" href="{{ $episode->twitter_url }}" target="_blank" rel="nofollow">
						<i style="font-size: 20px" class="fab fa-twitter"></i> &nbsp; Twitter
					</a> &nbsp;

					@if ($episode->github_url)
						<a class="button is-medium is-slightly-elevated" href="{{ $episode->github_url }}" target="_blank" rel="nofollow">
							<i style="font-size: 20px" class="fab fa-github"></i> &nbsp; GitHub
						</a> &nbsp;
					@endif

					@if ($episode->app_url)
						<a class="button is-medium is-slightly-elevated" href="{{ $episode->app_url }}" target="_blank">
							<i style="font-size: 20px" class="fas fa-star"></i> &nbsp; View App
						</a> &nbsp;
					@endif

					@if ($long_description)
						<p>&nbsp;</p>
						<p>&nbsp;</p>

						<div style="max-width:600px" class="block">
							{!! $long_description !!}
						</div>
					@endif

					<p>&nbsp;</p>
					<p>&nbsp;</p>

					<div>
						@if ($episode->published_at)
							Published {{ \Carbon\Carbon::parse($episode->published_at)->format('M jS, Y') }} •
						@endif

						Music by <a href="https://www.facebook.com/ScottHolmesMusic/" target="_blank" rel="nofollow">Scott Holmes</a>
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


		</div>
	</section>

@stop

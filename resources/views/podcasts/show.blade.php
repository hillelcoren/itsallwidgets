@extends('master')

@section('title', $episode->title)
@section('description', $episode->short_description)

@section('header_title', 'An open podcast for Flutter developers')
@section('header_subtitle', 'Share your Flutter story with the community')
@section('header_button_url', url(auth()->check() ? 'podcast/submit' : 'auth/google?intended_url=podcast/submit'))
@section('header_button_label', 'REQUEST INTERVIEW')
@section('header_button_icon', 'fas fa-microphone')

@section('content')

	<section class="section is-body-font">
		<div class="container">

			<div class="columns is-multiline is-5 is-variable">
				<div class="column is-one-third">
		            <img src="{{ asset('images/favicon.png') }}"/>
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
						<div style="border-bottom: 2px #259bee solid; width: 50px; padding-top:8px;"/>
					</h2>
					<div class="subtitle" style="padding-top:16px; padding-bottom:8px; max-width:600px">
						{{ $episode->short_description }}
					</div>

					@if ($episode->website_url)
						<div class="content" style="padding-bottom: 8px;">
							<a href="{{ $episode->website_url }}" target="_blank">{{ $episode->website_url }}</a>
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

					<a class="button is-medium is-slightly-elevated" href="{{ $episode->downloadUrl() }}">
						<i style="font-size: 20px" class="fas fa-download"></i> &nbsp; Download
					</a> &nbsp;

				</div>
			</div>


		</div>
	</section>

@stop

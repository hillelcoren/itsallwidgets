@extends('master')

@section('title', 'Flutter Podcast')
@section('description', 'An open podcast for Flutter devlopers')

@section('header_title', 'An open podcast for Flutter devlopers')
@section('header_subtitle', 'Share your Flutter story with the community')
@section('header_button_url', url(auth()->check() ? 'flutter-podcast/create' : 'auth/google?intended_url=flutter-podcast/create')))
@section('header_button_label', 'REQUEST INTERVIEW')
@section('header_button_icon', 'fas fa-microphone')

@section('content')

    <div class="container">
	<div class="columns is-multiline is-5 is-variable">
		@foreach ($episodes as $episode)
			<div class="column is-one-quarter">
				<a href="{{ $episode->url() }}">
					<div class="card is-hover-elevated">
						<header class="card-header">
							<p class="card-header-title is-2">

							</p>
							@if ($app->twitter_url)
								<a href="{{ $app->twitter_url }}" class="card-header-icon" target="_blank">
									<i style="font-size: 20px; color: #888" class="fab fa-twitter"></i>
								</a>
							@endif
						</header>
						<div class="card-content">
							<div class="content">
								<div class="subtitle is-6" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $app->short_description }}">
									{{ $app->short_description }}
								</div>
							</div>
						</div>

					</div>
				</a>
				<p>&nbsp;</p>
			</div>
		@endforeach
	</div>

	<p>&nbsp;</p>


@stop

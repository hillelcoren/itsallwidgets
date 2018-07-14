@extends('master')

@section('title', $app->title)
@section('description', $app->short_description)
@section('image_url', $app->screenshot1_url)

@section('content')

	<div class="container">
		<p>&nbsp;</p>
		<p>&nbsp;</p>

		<div class="columns">
			<div class="column is-4 is-elevated">
				<img src="{{ $app->screenshot1_url }}" width="1080" height="1920"/>
			</div>
			<div class="column is-8">
				<nav class="breadcrumb" aria-label="breadcrumbs">
					<ul>
						<li><a href="{{ url('/flutter-apps') }}">All Applications</a></li>
						<li class="is-active"><a href="#" aria-current="page">{{ $app->title }}</a></li>
					</ul>
				</nav>

				@if (auth()->check() && auth()->user()->id == $app->user_id)
					<a class="button is-info is-slightly-elevated" href="{{ url('flutter-app/' . $app->slug . '/edit') }}">
						<i style="font-size: 20px" class="fas fa-edit"></i> &nbsp;
						Edit Application
					</a>
					<p>&nbsp;</p>
				@endif

				<div class="content">
					<h2 class="title">
						{{ $app->title }}
					</h2>
					<div class="subtitle">
						{{ $app->short_description }}
					</div>

					@if ($app->google_url || $app->apple_url)
						<div class="block">
							@if ($app->google_url)
								<a href="{{ $app->google_url }}" target="_blank" class="is-slightly-elevated">
									<img src="{{ asset('images/google.png') }}" style="width:180px"/>
								</a>
							@endif
							@if ($app->apple_url)
								<a href="{{ $app->apple_url }}" target="_blank" class="is-slightly-elevated">
									<img src="{{ asset('images/apple.png') }}" style="width:180px"/>
								</a>
							@endif
						</div>
					@endif

					@if ($app->website_url || $app->repo_url)
						<div class="content">
							@if ($app->website_url)
								<a href="{{ url($app->website_url) }}" target="_blank">{{ url($app->website_url) }}</a></br>
							@endif

							@if ($app->repo_url)
								<a href="{{ url($app->repo_url) }}" target="_blank">{{ url($app->repo_url) }}</a><br/>
							@endif
						</div>
						<br/>
					@endif

					<div class="content">
						@if ($app->facebook_url)
							<a class="button is-slightly-elevated" href="{{ $app->facebook_url }}" target="_blank">
								<i style="font-size: 20px" class="fab fa-facebook"></i> &nbsp; Facebook
							</a>
						@endif
						@if ($app->twitter_url)
							<a class="button is-slightly-elevated" 	href="{{ $app->twitter_url }}" target="_blank">
								<i style="font-size: 20px" class="fab fa-twitter"></i> &nbsp; Twitter
							</a>
						@endif
					</div>

					<div class="block">
						{!! nl2br(e($app->long_description)) !!}
					</div>

					@if ($app->youtube_url)
						<iframe width="560" height="315" src="{{ $app->youtube_url }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					@endif

				</div>
			</div>

		</div>
	</div>
@stop

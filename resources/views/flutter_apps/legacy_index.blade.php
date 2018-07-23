@extends('master')

@section('title', 'Flutter Apps')
@section('description', 'An open list of apps built with Google Flutter')
@section('image_url', asset('images/background.png'))

@section('content')

	<script>
		function openStoreUrl(url) {
			window.open(url, '_blank');

			// https://stackoverflow.com/a/2385180/497368
			if (!e) var e = window.event;
   			e.cancelBubble = true;
   			if (e.stopPropagation) e.stopPropagation();
		}
	</script>

    <p>&nbsp;</p>
    <p>&nbsp;</p>

    <div class="container">
	<div class="columns is-multiline is-5 is-variable">
		@foreach ($apps as $app)
			<div class="column is-one-quarter">
				<a href="{{ url('flutter-app/'. $app->slug) }}">
					<div class="card is-hover-elevated">
						<header class="card-header">
							<p class="card-header-title is-2">
								{{ $app->title }}
							</p>
							@if ($app->facebook_url)
								<a href="{{ $app->facebook_url }}" class="card-header-icon" target="_blank">
									<i style="font-size: 20px; color: #888" class="fab fa-facebook"></i>
								</a>
							@endif
							@if ($app->twitter_url)
								<a href="{{ $app->twitter_url }}" class="card-header-icon" target="_blank">
									<i style="font-size: 20px; color: #888" class="fab fa-twitter"></i>
								</a>
							@endif
							@if ($app->repo_url)
								<a href="{{ $app->repo_url }}" class="card-header-icon" target="_blank">
									<i style="font-size: 20px; color: #888" class="fab fa-github"></i>
								</a>
							@endif
						</header>
						<div class="card-content">
							<div class="content">
								<div class="subtitle is-6" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $app->short_description }}">
									{{ $app->short_description }}
								</div>
								<div class="columns">
									<div class="column is-one-half">
										@if ($app->google_url)
											<div onclick="openStoreUrl('{{ $app->google_url }}')" target="_blank" style="visibility:{{ $app->google_url ? 'visible' : 'hidden' }}">
												<div class="card-image is-slightly-elevated">
													<img src="{{ asset('images/google.png') }}"/>
												</div>
											</div>
										@else
											<div class="card-image is-slightly-elevated">
												<img src="{{ asset('images/google.png') }}" style="opacity: 0.1; filter: grayscale(100%);"/>
											</div>
										@endif
									</div>
									<div class="column is-one-half">
										@if ($app->apple_url)
											<div onclick="openStoreUrl('{{ $app->apple_url }}')" target="_blank" style="visibility:{{ $app->apple_url ? 'visible' : 'hidden' }}">
												<div class="card-image is-slightly-elevated">
													<img src="{{ asset('images/apple.png') }}"/>
												</div>
											</div>
										@else
											<div class="card-image is-slightly-elevated">
												<img src="{{ asset('images/apple.png') }}" style="opacity: 0.1; filter: grayscale(100%);"/>
											</div>
										@endif
									</div>
								</div>
							</div>
						</div>

						<div class="card-image">
							<img src="{{ $app->screenshotUrl() }}" width="1080" height="1920"/>
						</div>

						<!-- <div style="background-image: url('{{ $app->screenshotUrl() }}');height:500px;background-size: cover;"/> -->
					</div>
				</a>
				<p>&nbsp;</p>
			</div>
		@endforeach
	</div>

	<p>&nbsp;</p>
</div>

@stop

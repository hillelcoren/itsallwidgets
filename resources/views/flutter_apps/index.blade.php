@extends('master')

@section('title', 'Flutter Apps')

@section('content')

	<div class="columns is-multiline">
		@foreach ($apps as $app)
			<div class="column is-one-third">
				<div onclick="location.href = '{{ url('flutter-app/'. $app->slug) }}';" style="cursor:pointer">
					<div class="card is-elevated">
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
								<div class="subtitle is-6" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
									{{ $app->short_description }}
								</div>
								<div class="columns">
									<div class="column is-one-half">
										<a href="{{ $app->google_url }}" target="_blank" style="visibility:{{ $app->google_url ? 'visible' : 'hidden' }}">
											<div class="card-image">
												<img src="{{ asset('images/google.png') }}"/>
											</div>
										</a>
									</div>
									<div class="column is-one-half">
										<a href="{{ $app->apple_url }}" target="_blank" style="visibility:{{ $app->google_url ? 'visible' : 'hidden' }}">
											<div class="card-image">
												<img src="{{ asset('images/apple.png') }}"/>
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>

						<div class="card-image">
							<img src="{{ $app->screenshot1_url }}" width="1080" height="1920"/>
						</div>

						<!-- <div style="background-image: url('{{ $app->screenshot1_url }}');height:500px;background-size: cover;"/> -->
					</div>
				</div>
			</div>
		@endforeach
	</div>

	<p>&nbsp;</p>

@stop

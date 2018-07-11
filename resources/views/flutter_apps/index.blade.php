@extends('master')

@section('title', 'Flutter Apps')

@section('content')

	<div class="columns is-multiline">
		@foreach ($apps as $app)
			<div class="column is-one-third">
				<div onclick="location.href = '{{ url('flutter-app/'. $app->slug) }}';" style="cursor:pointer">
					<div class="card">
						<header class="card-header">
							<p class="card-header-title is-2">
								{{ $app->title }}
							</p>
							@if ($app->facebook_url)
								<a href="{{ $app->facebook_url }}" class="card-header-icon">
									<i style="font-size: 20px; color: #888" class="fab fa-facebook"></i>
								</a>
							@endif
							@if ($app->twitter_url)
								<a href="{{ $app->twitter_url }}" class="card-header-icon">
									<i style="font-size: 20px; color: #888" class="fab fa-twitter"></i>
								</a>
							@endif
						</header>
						<div class="card-content">
							<div class="content">
								<div class="subtitle is-6">
									{{ $app->short }}
								</div>
								@if ($app->google_url || $app->apple_url)
									<br/>
									<div class="columns">
										<div class="column is-one-half">
											<a href="{{ $app->google_url }}" target="_blank">
												<div class="card-image" style="visibility:{{ $app->google_url ? 'visible' : 'hidden' }}">
													<img src="{{ asset('images/google.png') }}"/>
												</div>
											</a>
										</div>
										<div class="column is-one-half">
											<a href="{{ $app->apple_url }}" target="_blank">
												<div class="card-image" style="visibility:{{ $app->google_url ? 'visible' : 'hidden' }}">
													<img src="{{ asset('images/apple.png') }}"/>
												</div>
											</a>
										</div>
									</div>
								@endif
							</div>
						</div>

						<div class="card-image">
							<figure class="image is-1080x1920">
								<img src="{{ $app->screenshot1_url }}"/>
							</figure>
						</div>
					</div>
				</div>
			</div>
		@endforeach
	</div>

	<p>&nbsp;</p>

@stop

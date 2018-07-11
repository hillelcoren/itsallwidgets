@extends('layouts.app')

@section('title')
	{{ $app->title }}
@stop

@section('content')

	<div class="container">

		<div class="columns">
			<div class="column is-4">
				<figure class="image is-1080x1920">
					<img src="{{ $app->screenshot1_url }}"/>
				</figure>
			</div>
			<div class="column is-8">

				@if (session('status'))
				    <div class="notification is-success">
				        {{ session('status') }}
				    </div>
				@endif


				<nav class="breadcrumb" aria-label="breadcrumbs">
					<ul>
						<li><a href="{{ url('/flutter-apps') }}">All Applications</a></li>
						<li class="is-active"><a href="#" aria-current="page">{{ $app->title }}</a></li>
					</ul>
				</nav>

				<div class="content">
					<h2 class="title">
						{{ $app->title }}
					</h2>
					<div class="subtitle">
						{{ $app->short }}
					</div>


					@if ($app->google_url || $app->apple_url)
						<div class="columns" style="width:300px">
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

					@if ($app->website_url || $app->repo_url)
						<div class="content">
						@if ($app->website_url)
							<a href="{{ url($app->website_url) }}" target="_blank">{{ url($app->website_url) }}</a></br>
						@endif

						@if ($app->repo_url)
							<a href="{{ url($app->repo_url) }}" target="_blank">{{ url($app->repo_url) }}</a><br/>
						@endif
						</content>
						<br/>
					@endif

					<div class="content">
						@if ($app->facebook_url)
							<a class="button" href="{{ $app->facebook_url }}" target="_blank">
								<i style="font-size: 20px" class="fab fa-facebook"></i> &nbsp; Facebook
							</a>
						@endif
						@if ($app->twitter_url)
							<a class="button" 	href="{{ $app->twitter_url }}" target="_blank">
								<i style="font-size: 20px" class="fab fa-twitter"></i> &nbsp; Twitter
							</a>
						@endif
					</div>

					<div class="block">
						{!! nl2br(e($app->description)) !!}
					</div>

				</div>
			</div>

		</div>
	</div>

@stop

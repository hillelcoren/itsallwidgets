@extends('layouts.app')

@section('title')
	{{ $project->title }}
@stop

@section('content')

	<div class="container">

		<div class="columns">
			<div class="column is-4">
				<figure class="image is-1080x1920">
					<img src="{{ $project->screenshot1_url }}"/>
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
						<li class="is-active"><a href="#" aria-current="page">{{ $project->title }}</a></li>
					</ul>
				</nav>

				<div class="content">
					<h2 class="title">
						{{ $project->title }}
					</h2>
					<div class="subtitle">
						{{ $project->short }}
					</div>


					@if ($project->google_url || $project->apple_url)
						<div class="columns" style="width:300px">
							<div class="column is-one-half">
								<a href="{{ $project->google_url }}" target="_blank">
									<div class="card-image" style="visibility:{{ $project->google_url ? 'visible' : 'hidden' }}">
										<img src="{{ asset('images/google.png') }}"/>
									</div>
								</a>
							</div>
							<div class="column is-one-half">
								<a href="{{ $project->apple_url }}" target="_blank">
									<div class="card-image" style="visibility:{{ $project->google_url ? 'visible' : 'hidden' }}">
										<img src="{{ asset('images/apple.png') }}"/>
									</div>
								</a>
							</div>
						</div>
					@endif

					@if ($project->website_url || $project->repo_url)
						<div class="content">
						@if ($project->website_url)
							<a href="{{ url($project->website_url) }}" target="_blank">{{ url($project->website_url) }}</a></br>
						@endif

						@if ($project->repo_url)
							<a href="{{ url($project->repo_url) }}" target="_blank">{{ url($project->repo_url) }}</a><br/>
						@endif
						</content>
						<br/>
					@endif

					<div class="content">
						@if ($project->facebook_url)
							<a class="button" href="{{ $project->facebook_url }}" target="_blank">
								<i style="font-size: 20px" class="fab fa-facebook"></i> &nbsp; Facebook
							</a>
						@endif
						@if ($project->twitter_url)
							<a class="button" 	href="{{ $project->twitter_url }}" target="_blank">
								<i style="font-size: 20px" class="fab fa-twitter"></i> &nbsp; Twitter
							</a>
						@endif
					</div>

					<div class="block">
						{!! nl2br(e($project->description)) !!}
					</div>

				</div>
			</div>

		</div>
	</div>

@stop

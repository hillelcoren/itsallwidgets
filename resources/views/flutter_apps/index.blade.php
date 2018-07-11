@extends('layouts.app')

@section('title', 'Flutter Apps')

@section('content')

	<div class="container">
		<div class="columns is-multiline">
			@foreach ($projects as $project)
				<div class="column is-one-third">
					<div onclick="location.href = '{{ url('flutter-app/'. $project->slug) }}';" style="cursor:pointer">
						<div class="card">
							<header class="card-header">
								<p class="card-header-title is-2">
									{{ $project->title }}
								</p>
								@if ($project->facebook_url)
									<a href="{{ $project->facebook_url }}" class="card-header-icon">
										<i style="font-size: 20px; color: #888" class="fab fa-facebook"></i>
									</a>
								@endif
								@if ($project->twitter_url)
									<a href="{{ $project->twitter_url }}" class="card-header-icon">
										<i style="font-size: 20px; color: #888" class="fab fa-twitter"></i>
									</a>
								@endif
							</header>
							<div class="card-content">
								<div class="content">
									<div class="subtitle is-6">
										{{ $project->short }}
									</div>
									@if ($project->google_url || $project->apple_url)
										<br/>
										<div class="columns">
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
								</div>
							</div>

							<div class="card-image">
								<figure class="image is-1080x1920">
									<img src="{{ $project->screenshot1_url }}"/>
								</figure>
							</div>
						</div>
					</div>
				</div>
			@endforeach
		</div>
	</div>

	<p>&nbsp;</p>

@stop

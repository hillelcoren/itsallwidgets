@extends("landing")

@section("title", $app->title)
@section("description", $app->short_description)
@section("image_url", $app->is_desktop ? $app->desktopScreenshotUrl() : $app->screenshotUrl())

@section("head")
    <style>
		body {
			min-height: 100%;
			background-image: linear-gradient({{ $app->background_rotation }}deg, {{ $app->background_colors }});
			background-attachment: fixed;
			color: {{ $app->font_color }};
		}

		.app-title {
			font-size: 5rem;
			font-weight: 600;						
		}

		.app-subtitle {
			font-size: 2rem;
		}

		.social-buttons > .button {
			margin-bottom: 12px;
		}

		.website-content {
			font-size: 1.5rem;
		}

		.website-content a:hover {
            border-bottom: 1px {{ $app->font_color }} dashed;
        }

		@media only screen and (min-width: 768px) {
			.app-container {
				padding: 70px 50px 50px 50px;
			}

			.is-two-thirds {
				padding-right: 50px;
			}
		}

		@media only screen and (max-width: 768px) {
			.app-container {
				padding: 20px;
			}
		}
	</style>
@stop

@section("body")
	
	@if ($app->repo_url)
		<a href="{{ $app->repo_url }}" target="_blank">
			<svg width="80" height="80" viewBox="0 0 250 250" style="fill:#151513;color:#fff;position:absolute;top:0;border:0;right:0" aria-hidden="true"><path d="M0,0 L115,115 L130,115 L142,142 L250,250 L250,0 Z"></path><path d="M128.3,109.0 C113.8,99.7 119.0,89.6 119.0,89.6 C122.0,82.7 120.5,78.6 120.5,78.6 C119.2,72.0 123.4,76.3 123.4,76.3 C127.3,80.9 125.5,87.3 125.5,87.3 C122.9,97.6 130.6,101.9 134.4,103.2" fill="currentColor" style="transform-origin:130px 106px" class="octo-arm"></path><path d="M115.0,115.0 C114.9,115.1 118.7,116.5 119.8,115.4 L133.7,101.6 C136.9,99.2 139.9,98.4 142.2,98.6 C133.8,88.0 127.5,74.4 143.8,58.0 C148.5,53.4 154.0,51.2 159.7,51.0 C160.3,49.4 163.2,43.6 171.4,40.1 C171.4,40.1 176.1,42.5 178.8,56.2 C183.1,58.6 187.2,61.8 190.9,65.4 C194.5,69.0 197.7,73.2 200.1,77.6 C213.8,80.2 216.3,84.9 216.3,84.9 C212.7,93.1 206.9,96.0 205.4,96.6 C205.1,102.4 203.0,107.8 198.3,112.5 C181.9,128.9 168.3,122.5 157.7,114.1 C157.9,116.9 156.7,120.9 152.7,124.9 L141.0,136.5 C139.8,137.7 141.6,141.9 141.8,141.8 Z" fill="currentColor" class="octo-body"></path></svg>
		</a>
	@endif

	<div class="app-container container">
    	<div class="columns">
			<div class="column is-two-thirds">
				<div class="app-title">{{ $app->title }}</div>
				<div class="app-subtitle">{{ $app->short_description }}</div>
				@if ($app->google_url || $app->apple_url)
					<div style="padding-top: 32px; padding-bottom: 22px;">
						@if ($app->google_url)
							<a href="{{ $app->google_url }}" target="_blank" rel="nofollow">
								<img src="{{ asset('images/google.png') }}" style="width:200px"/>
							</a>
						@endif
						@if ($app->apple_url)
							@if ($app->google_url)
								&nbsp;
							@endif
							<a href="{{ $app->apple_url }}?platform=iphone" target="_blank" rel="nofollow">
								<img src="{{ asset('images/apple.png') }}" style="width:200px"/>
							</a>
						@endif
					</div>
				@endif

				<div class="block">
					{!! nl2br(e($app->long_description)) !!}
				</div>

				@if ($app->website_url)
					<div class="content website-content">
						<a href="{{ url($app->website_url) }}" style="color: {{ $app->font_color }}" target="_blank" 
							rel="nofollow">{{ str_replace("https://", "", $app->website_url) }}</a></br>
					</div>
				@endif


				<div class="content social-buttons">
					@if ($app->facebook_url)
						<a class="button" href="{{ $app->facebook_url }}" target="_blank" rel="nofollow">
							<i style="font-size: 20px" class="fab fa-facebook"></i> &nbsp; Facebook
						</a> &nbsp;
					@endif
					@if ($app->twitter_url)
						<a class="button" href="{{ $app->twitter_url }}" target="_blank" rel="nofollow">
							<i style="font-size: 20px" class="fab fa-twitter"></i> &nbsp; Twitter
						</a> &nbsp;
					@endif
					@if ($app->instagram_url)
						<a class="button" href="{{ $app->instagram_url }}" target="_blank" rel="nofollow">
							<i style="font-size: 20px" class="fab fa-instagram"></i> &nbsp; Instagram
						</a> &nbsp;
					@endif

					<div class="dropdown is-hoverable">
						<div class="dropdown-trigger">
							<button class="button" aria-haspopup="true" aria-controls="dropdown-menu4">
								<span>
									<i style="font-size: 20px;" class="fa fa-share"></i> &nbsp;
									Share App
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
							<a href="https://twitter.com/share?text={{ urlencode($app->title) }}&amp;url={{ urlencode(url('/flutter-app/' . $app->slug)) }}" target="_blank" rel="nofollow">
								<div class="dropdown-content">
									<div class="dropdown-item">
										<i style="font-size: 20px" class="fab fa-twitter"></i> &nbsp; Twitter
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>

			</div>
			<div class="column">	
				@if ($app->is_mobile)
					<img src="{{ $app->has_gif ? $app->gifUrl() : $app->screenshotUrl() }}" class="is-elevated"/>
				@endif
			</div>
		</div>

		@if (auth()->check() && auth()->user()->owns($app))
			<div style="padding-bottom: 20px">
				<a class="button is-info" href="{{ url('flutter-app/' . $app->slug . '/edit') }}">
					<i style="font-size: 20px" class="fas fa-edit"></i> &nbsp;
					Edit Application
				</a>
				&nbsp;&nbsp;
				<a class="button is-danger" onclick="if (confirm('Are you sure?')) $('#delete-form').submit()";>
					<i style="font-size: 20px" class="fas fa-trash"></i> &nbsp;
					Delete Application
				</a>
				<form style="display: none" action="{{ url('flutter-app/' . $app->slug . '/delete') }}" method="POST" id="delete-form">
					<input type="hidden" name="_token" value="{{ csrf_token() }}"/>	
				</form>
			</div>
		@endif


		@if ($app->is_desktop)
			<div style="margin-top: 30px">
				@if ($app->has_desktop_gif)
					<img src="{{ $app->desktopGifUrl() }}"/>
				@else
					<img src="{{ $app->desktopScreenshotUrl() }}"/>
				@endif

				<div class="center" style="margin-top: 30px">
					@if ($app->microsoft_url)							
						<a href="{{ $app->microsoft_url }}" target="_blank" rel="nofollow">
							<img src="{{ asset('images/microsoft.png') }}" width="200px"/>
						</a>&nbsp;
					@endif											
					@if ($app->apple_url)
						<a href="{{ $app->apple_url }}?platform=mac" target="_blank" rel="nofollow">
							<img src="{{ asset('images/apple.png') }}" width="200px"/>
						</a>&nbsp;
					@endif						
					@if ($app->snapcraft_url)
						<a href="{{ $app->snapcraft_url }}" target="_blank" rel="nofollow">
							<img src="{{ asset('images/linux.png') }}" width="200px"/>
						</a>
					@endif						
				</div>
			</div>
		@endif

		@if ($app->is_web && $app->flutter_web_url)
			<iframe sandbox="allow-scripts allow-same-origin allow-top-navigation allow-popups" 
				src="{{ $app->flutter_web_url }}" allowTransparency="true"
				width="100%" height="600px" frameBorder="0" style="border:none; overflow:hidden; margin-top: 30px;"></iframe>
		@endif



		@if ($app->youtube_url)		
			<div class="videoWrapper" style="margin-top: 50px;">
				<iframe src="{{ $app->youtube_url }}" frameborder="0" allowfullscreen></iframe>  				
			</div>
		@endif

	</div>
@stop
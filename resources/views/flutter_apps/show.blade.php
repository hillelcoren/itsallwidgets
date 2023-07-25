@extends("landing")

@section("title", $app->title)
@section("description", $app->short_description)
@section("image_url", $app->is_desktop ? $app->desktopScreenshotUrl() : $app->screenshotUrl())

@section("head")
    <style>
		html {
			height: 100%;
		}

		body {
			height: 100%;
			background-image: linear-gradient(135deg, #7468E6, #C44B85);			
			background-attachment: fixed;
			color: #FFFFFF;
		}

		.app-title {
			font-size: 5rem;
			font-weight: 600;						
		}

		.app-subtitle {
			font-size: 2rem;
		}

		@media only screen and (min-width: 768px) {
			.container {
				padding: 70px 50px;
			}
		}

		@media only screen and (max-width: 768px) {
			.container {
				padding: 20px;
			}
		}
	</style>
@stop

@section("body")
	<div class="container">
    	<div class="columns">
			<div class="column is-two-thirds">
				<div class="app-title">{{ $app->title }}</div>
				<div class="app-subtitle">{{ $app->short_description }}</div>
				@if ($app->google_url || $app->apple_url)
					<div style="padding-top: 30px">
						@if ($app->google_url)
							<a href="{{ $app->google_url }}" target="_blank" class="is-slightly-elevated" rel="nofollow">
								<img src="{{ asset('images/google.png') }}" style="width:200px"/>
							</a>
						@endif
						@if ($app->apple_url)
							@if ($app->google_url)
								&nbsp;
							@endif
							<a href="{{ $app->apple_url }}?platform=iphone" target="_blank" class="is-slightly-elevated" rel="nofollow">
								<img src="{{ asset('images/apple.png') }}" style="width:200px"/>
							</a>
						@endif
					</div>
				@endif

				
			</div>
			<div class="column">	
				@if ($app->is_mobile)			
					<img src="{{ $app->screenshotUrl() }}"/>					
				@endif
			</div>
		</div>
	</div>
@stop
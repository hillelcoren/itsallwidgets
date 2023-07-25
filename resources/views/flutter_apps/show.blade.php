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
	</style>
@stop

@section("body")
	<div class="container pt-2">
    	<div class="columns">
			<div class="column is-two-thirds">
				<div class="app-title">{{ $app->title }}</div>
				<div class="app-subtitle">{{ $app->short_description }}</div>
			</div>
			<div class="column">	
				@if ($app->is_mobile)			
					<img src="{{ $app->screenshotUrl() }}"/>					
				@endif
			</div>
		</div>
	</div>
@stop
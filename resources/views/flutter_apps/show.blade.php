@extends("landing")

@section("title", $app->title)
@section("description", $app->short_description)
@section("image_url", $app->is_desktop ? $app->desktopScreenshotUrl() : $app->screenshotUrl())

@section("head")
    <style>
		body {
			background-image: linear-gradient(45deg, #2E3192, #1BFFFF);
			background-attachment: fixed;
			color: #FFFFFF;
		}		
	</style>
@stop

@section("body")
    <h1>Hello!</h1>
@stop
@extends('master')

@section('title', 'FlutterX')
@section('description', 'A Searchable List of Flutter Resources')
@section('image_url', asset('images/flutterx_twitter.png'))
@section('header_title', 'A Searchable List of Flutter Resources')
@section('header_button_url', false)

@section('header_subtitle')
    Sourced from the <a href="https://flutterweekly.net" target="_blank">Flutter Weekly Newsletter</a>
@endsection

@section('content')

    <section class="section is-body-font">
		<div class="container">

			<div class="columns">
				<div class="column is-4 is-elevated">
					<img id="appImage" src="{{ $artifact->image_url }}" width="1080" height="1920"/>
				</div>
				<div class="column is-8">
					<nav class="breadcrumb" aria-label="breadcrumbs">
						<ul>
							<li><a href="{{ fxUrl() }}">All Resources</a></li>
							<li class="is-active"><a href="#" aria-current="page">{{ $artifact->title }}</a></li>
						</ul>
					</nav>

					<div class="content">
						<h2 class="title">
							{{ $artifact->title }}

							@if ($artifact->category)
								&nbsp;&nbsp;
								<a class="tag is-info is-medium" style="text-decoration: none;"
									href="{{ url('?search=' . strtolower($artifact->category)) }}">
									{{ $artifact->category }}
								</a>
							@endif

							<div style="border-bottom: 2px #368cd5 solid; width: 50px; padding-top:12px;"/>
						</h2>
						<div class="subtitle" style="padding-top:16px;">
							{{ $artifact->short_description }}
						</div>

						@if ($artifact->google_url || $artifact->apple_url)
							<div class="block">
								@if ($artifact->google_url)
									<a href="{{ $artifact->google_url }}" target="_blank" class="is-slightly-elevated" rel="nofollow">
										<img src="{{ asset('images/google.png') }}" style="width:180px"/>
									</a>
								@endif
								@if ($artifact->apple_url)
									<a href="{{ $artifact->apple_url }}" target="_blank" class="is-slightly-elevated" rel="nofollow">
										<img src="{{ asset('images/apple.png') }}" style="width:180px"/>
									</a>
								@endif
							</div>
						@endif

						@if ($artifact->website_url || $artifact->repo_url)
							<div class="content">
								@if ($artifact->website_url)
									<a href="{{ url($artifact->website_url) }}" target="_blank" rel="nofollow">{{ url($artifact->website_url) }}</a></br>
								@endif

								@if ($artifact->repo_url)
									<a href="{{ url($artifact->repo_url) }}" target="_blank" rel="nofollow">{{ url($artifact->repo_url) }}</a><br/>
								@endif
							</div>
						@endif

						<div class="content">
							@if ($artifact->facebook_url)
								<a class="button is-slightly-elevated" href="{{ $artifact->facebook_url }}" target="_blank" rel="nofollow">
									<i style="font-size: 20px" class="fab fa-facebook"></i> &nbsp; Facebook
								</a> &nbsp;
							@endif
							@if ($artifact->twitter_url)
								<a class="button is-slightly-elevated" 	href="{{ $artifact->twitter_url }}" target="_blank" rel="nofollow">
									<i style="font-size: 20px" class="fab fa-twitter"></i> &nbsp; Twitter
								</a> &nbsp;
							@endif
							@if ($artifact->instagram_url)
								<a class="button is-slightly-elevated" 	href="{{ $artifact->instagram_url }}" target="_blank" rel="nofollow">
									<i style="font-size: 20px" class="fab fa-instagram"></i> &nbsp; Instagram
								</a> &nbsp;
							@endif

							<div class="dropdown is-hoverable">
								<div class="dropdown-trigger is-slightly-elevated">
									<button class="button" aria-haspopup="true" aria-controls="dropdown-menu4">
										<span>
											<i style="font-size: 20px" class="fa fa-share"></i> &nbsp;
											Share Resource
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
									<a href="https://twitter.com/share?text={{ urlencode($artifact->title) }}&amp;url={{ urlencode(url('/flutter-app/' . $artifact->slug)) }}" target="_blank" rel="nofollow">
										<div class="dropdown-content">
											<div class="dropdown-item">
												<i style="font-size: 20px" class="fab fa-twitter"></i> &nbsp; Twitter
											</div>
										</div>
									</a>
								</div>
							</div>

							<br/><br/>

							<div class="block">
								{!! nl2br(e($artifact->long_description)) !!}
							</div>

							@if ($artifact->has_gif || $artifact->has_screenshot_1 || $artifact->has_screenshot_2 || $artifact->has_screenshot_3)
								<div class="columns is-multiline is-2 is-variable">
									@if ($artifact->has_gif)
										<div class="column is-one-fifth">
											<img src="{{ $artifact->gifUrl() }}" class="is-slightly-elevated is-hover-elevated" style="cursor:pointer" onclick="selectImage('gif')"/>
			                            </div>
									@endif
		                            <div class="column is-one-fifth">
										<img src="{{ $artifact->screenshotUrl() }}" class="is-slightly-elevated is-hover-elevated" style="cursor:pointer" onclick="selectImage('png')"/>
		                            </div>
									@if ($artifact->has_screenshot_1)
										<div class="column is-one-fifth">
											<img src="{{ $artifact->screenshotUrl(1) }}" class="is-slightly-elevated is-hover-elevated" style="cursor:pointer" onclick="selectImage('png1')"/>
			                            </div>
									@endif
									@if ($artifact->has_screenshot_2)
										<div class="column is-one-fifth">
											<img src="{{ $artifact->screenshotUrl(2) }}" class="is-slightly-elevated is-hover-elevated" style="cursor:pointer" onclick="selectImage('png2')"/>
			                            </div>
									@endif
									@if ($artifact->has_screenshot_3)
										<div class="column is-one-fifth">
											<img src="{{ $artifact->screenshotUrl(3) }}" class="is-slightly-elevated is-hover-elevated" style="cursor:pointer" onclick="selectImage('png3')"/>
			                            </div>
									@endif
		                        </div>
							@endif

							@if ($artifact->youtube_url)
								<iframe width="560" height="315" src="{{ $artifact->youtube_url }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
							@endif

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

@endsection

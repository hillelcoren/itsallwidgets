@extends('master')

@section('title', $app->title)
@section('description', $app->short_description)
@section('image_url', $app->is_desktop ? $app->desktopScreenshotUrl() : ($app->is_mobile ? $app->screenshotUrl() : asset('images/background.jpg')))

@section('content')

	<script>

		function selectImage(type) {
			if (type == 'png') {
				$('#appImage').attr('src', '{{ $app->screenshotUrl() }}');
			} else if (type == 'png1') {
				$('#appImage').attr('src', '{{ $app->screenshotUrl(1) }}');
			} else if (type == 'png2') {
				$('#appImage').attr('src', '{{ $app->screenshotUrl(2) }}');
			} else if (type == 'png3') {
				$('#appImage').attr('src', '{{ $app->screenshotUrl(3) }}');
			} else {
				$('#appImage').attr('src', '{{ $app->gifUrl() }}');
			}
		}

	</script>

	<section class="section is-body-font">
		<div class="container">

			<div class="columns">
				@if ($app->is_mobile)
					<div class="column is-4 is-elevated">
						@if ($app->is_mobile)
							<img id="appImage" src="{{ $app->screenshotUrl() }}" width="1080" height="1920"/>
						@endif
					</div>
				@endif
				<div class="column is-{{ $app->is_mobile ? '8' : '12' }}">

					@if (auth()->check() && auth()->user()->owns($app) && $app->campaign_id)
						<!--
						<div class="notification is-info">
							<a href="{{ url('/badge') }}" target="_blank">Click here</a> to create your #30DaysOfFlutter badge!
						</div>
						<p>&nbsp;</p>
						-->
					@endif

					<nav class="breadcrumb" aria-label="breadcrumbs">
						<ul>
							<li><a href="{{ url('/') }}">All Applications</a></li>
							<li class="is-active"><a href="#" aria-current="page">{{ $app->title }}</a></li>
						</ul>
					</nav>

					@if (auth()->check() && auth()->user()->is_admin)
						@if (! $app->is_approved)
							<a class="button is-success is-medium is-slightly-elevated" href="{{ url('flutter-app/' . $app->slug . '/approve') }}">
								<i style="font-size: 20px" class="fas fa-check"></i> &nbsp;
								Approve
							</a>
							@if (! $app->is_template)
								<a class="button is-success is-medium is-slightly-elevated" href="{{ url('flutter-app/' . $app->slug . '/approve?is_template=true') }}">
									<i style="font-size: 20px" class="fas fa-check"></i> &nbsp;
									Approve as Template
								</a>
							@endif
							<a class="button is-danger is-medium is-slightly-elevated" href="{{ url('flutter-app/' . $app->slug . '/reject') }}">
								<i style="font-size: 20px" class="fas fa-trash"></i> &nbsp;
								Reject
							</a>
							<p>&nbsp;</p>
						@endif

						<b>Developer:</b><br/>
						{{ $app->user->name }}</br>
						{{ $app->user->email}}
						<p>&nbsp;</p>
					@endif

					@if (auth()->check() && auth()->user()->is_editor && ! $app->featured)
						<a class="button is-warning is-slightly-elevated" href="{{ url('flutter-app/' . $app->slug . '/feature') }}">
							<i style="font-size: 20px" class="fas fa-star"></i> &nbsp;
							Feature Application
						</a>
						<p>&nbsp;</p>
					@endif

					@if (auth()->check() && auth()->user()->owns($app))
					<a class="button is-info is-slightly-elevated" href="{{ url('flutter-app/' . $app->slug . '/edit') }}">
							<i style="font-size: 20px" class="fas fa-edit"></i> &nbsp;
							Edit Application
						</a>
						&nbsp;&nbsp;
						<a class="button is-danger is-slightly-elevated" onclick="if (confirm('Are you sure?')) $('#delete-form').submit()";>
							<i style="font-size: 20px" class="fas fa-trash"></i> &nbsp;
							Delete Application
						</a>
						<form style="display: none" action="{{ url('flutter-app/' . $app->slug . '/delete') }}" method="POST" id="delete-form">
							<input type="hidden" name="_token" value="{{ csrf_token() }}"/>	
						</form>
						<p>&nbsp;</p>
					@endif

					<div class="content">
						<h2 class="title">
							{{ $app->title }}

							@if ($app->category)
								&nbsp;&nbsp;
								<a class="tag is-info is-medium" style="text-decoration: none;"
									href="{{ url('?search=' . strtolower($app->readableCategory())) }}">
									{{ $app->readableCategory() }}
								</a>
							@elseif ($app->is_template)
								&nbsp;&nbsp;
								<span class="tag is-info is-medium">Template</span>
							@endif

							<div style="border-bottom: 2px #368cd5 solid; width: 50px; padding-top:12px;"/>
						</h2>
						<div class="subtitle" style="padding-top:16px;">
							{{ $app->short_description }}
						</div>

						@if ($app->google_url || $app->apple_url)
							<div class="block">
								@if ($app->google_url)
									<a href="{{ $app->google_url }}" target="_blank" class="is-slightly-elevated" rel="nofollow">
										<img src="{{ asset('images/google.png') }}" style="width:180px"/>
									</a>
								@endif
								@if ($app->apple_url)
									<a href="{{ $app->apple_url }}?platform=iphone" target="_blank" class="is-slightly-elevated" rel="nofollow">
										<img src="{{ asset('images/apple.png') }}" style="width:180px"/>
									</a>
								@endif
							</div>
						@endif

						@if ($app->is_desktop)
							<div class="block">
							@if ($app->microsoft_url)
								<a href="{{ $app->microsoft_url }}" target="_blank" class="is-slightly-elevated" rel="nofollow">
									<i class="fab fa-microsoft" style="font-size:50px"></i>
								</a>
							@endif						
							&nbsp;&nbsp;&nbsp;
							@if ($app->apple_url)
								<a href="{{ $app->apple_url }}?platform=mac" target="_blank" class="is-slightly-elevated" rel="nofollow">
									<i class="fab fa-apple" style="font-size:50px"></i>
								</a>
							@endif						
							&nbsp;&nbsp;&nbsp;
							@if ($app->snapcraft_url)
								<a href="{{ $app->snapcraft_url }}" target="_blank" class="is-slightly-elevated" rel="nofollow">
									<i class="fab fa-linux" style="font-size:50px"></i>
								</a>
							@endif						
							</div>
						@endif

						@if ($app->website_url || $app->repo_url)
							<div class="content">
								@if ($app->website_url)
									<a href="{{ url($app->website_url) }}" target="_blank" rel="nofollow">{{ url($app->website_url) }}</a></br>
								@endif

								@if ($app->repo_url)
									<a href="{{ url($app->repo_url) }}" target="_blank" rel="nofollow">{{ url($app->repo_url) }}</a><br/>
								@endif
							</div>
						@endif

						<div class="content">
							@if ($app->facebook_url)
								<a class="button is-slightly-elevated" href="{{ $app->facebook_url }}" target="_blank" rel="nofollow">
									<i style="font-size: 20px" class="fab fa-facebook"></i> &nbsp; Facebook
								</a> &nbsp;
							@endif
							@if ($app->twitter_url)
								<a class="button is-slightly-elevated" 	href="{{ $app->twitter_url }}" target="_blank" rel="nofollow">
									<i style="font-size: 20px" class="fab fa-twitter"></i> &nbsp; Twitter
								</a> &nbsp;
							@endif
							@if ($app->instagram_url)
								<a class="button is-slightly-elevated" 	href="{{ $app->instagram_url }}" target="_blank" rel="nofollow">
									<i style="font-size: 20px" class="fab fa-instagram"></i> &nbsp; Instagram
								</a> &nbsp;
							@endif

							<div class="dropdown is-hoverable">
								<div class="dropdown-trigger is-slightly-elevated">
									<button class="button" aria-haspopup="true" aria-controls="dropdown-menu4">
										<span>
											<i style="font-size: 20px" class="fa fa-share"></i> &nbsp;
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

							<br/><br/>

							<div class="block">
								{!! nl2br(e($app->long_description)) !!}
							</div>

							@if ($app->is_desktop)
								<br/>&nbsp;<br/>
								@if ($app->has_desktop_gif)
									<img src="{{ $app->desktopGifUrl() }}" class="is-slightly-elevated is-hover-elevated"/>
								@else
									<img src="{{ $app->desktopScreenshotUrl() }}" class="is-slightly-elevated is-hover-elevated"/>
								@endif
							@endif

							@if ($app->is_web && $app->flutter_web_url)
								<br/>&nbsp;<br/>							
								<iframe sandbox="allow-scripts allow-same-origin allow-top-navigation allow-popups" src="{{ $app->flutter_web_url }}" allowTransparency="true"
			                        width="100%" height="800px" frameBorder="0" style="border:none; overflow:hidden;"></iframe>
							@endif

							@if ($app->has_gif || $app->has_screenshot_1 || $app->has_screenshot_2 || $app->has_screenshot_3)
								<div class="columns is-multiline is-2 is-variable">
									@if ($app->has_gif)
										<div class="column is-one-fifth">
											<img src="{{ $app->gifUrl() }}" class="is-slightly-elevated is-hover-elevated" style="cursor:pointer" onclick="selectImage('gif')"/>
			                            </div>
									@endif
									@if ($app->is_mobile)
										<div class="column is-one-fifth">
											<img src="{{ $app->screenshotUrl() }}" class="is-slightly-elevated is-hover-elevated" style="cursor:pointer" onclick="selectImage('png')"/>
										</div>
									@endif
									@if ($app->has_screenshot_1)
										<div class="column is-one-fifth">
											<img src="{{ $app->screenshotUrl(1) }}" class="is-slightly-elevated is-hover-elevated" style="cursor:pointer" onclick="selectImage('png1')"/>
			                            </div>
									@endif
									@if ($app->has_screenshot_2)
										<div class="column is-one-fifth">
											<img src="{{ $app->screenshotUrl(2) }}" class="is-slightly-elevated is-hover-elevated" style="cursor:pointer" onclick="selectImage('png2')"/>
			                            </div>
									@endif
									@if ($app->has_screenshot_3)
										<div class="column is-one-fifth">
											<img src="{{ $app->screenshotUrl(3) }}" class="is-slightly-elevated is-hover-elevated" style="cursor:pointer" onclick="selectImage('png3')"/>
			                            </div>
									@endif
		                        </div>
							@endif

							@if ($app->youtube_url)
								<iframe width="560" height="315" src="{{ $app->youtube_url }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
							@endif

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@stop

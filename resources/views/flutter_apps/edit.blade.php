@extends('master')

@section('title', 'Submit Application')
@section('description', 'Submit a Flutter application to be added to the list')
@section('image_url', asset('images/background.jpg'))

@section('content')

	<style>

		#preview {
			border: 1px solid #dbdbdb;
			border-radius: 4px;
			height: 150px;
			width: 100%;
			text-align: center;
			vertical-align: middle;
			display: flex;
			align-items:center;
        	justify-content:center;
			margin: auto;
			font-size: 3rem;
		}

	</style>

	<script>
		function onFormSubmit() {
			$('#saveButton').addClass('is-loading').prop('disabled', true);
		}

		function updatePlatforms() {
			$('#mobileScreenshotInput').toggle($('input[name=is_mobile]').is(':checked'));
			$('#desktopScreenshotInput').toggle($('input[name=is_desktop]').is(':checked'));
			$('#desktopGifInput').toggle($('input[name=is_desktop]').is(':checked'));
			$('#webUrlInput').toggle($('input[name=is_web]').is(':checked'));
		}

		function updatePreview() {
			var backgroundColors = $('#background_colors').val();
			var backgroundRotation = $('#background_rotation').val();
			var fontColor = $('#font_color').val();
			var appName = $('#title').val();

			$('#custom_colors').toggle(backgroundColors == '');
			if (backgroundColors == '') {
				var customColor1 = $('#custom_color1').val();
				var customColor2 = $('#custom_color2').val();
				backgroundColors = customColor1 + ', ' + customColor2;
			}

			var gradient = 'linear-gradient(' + backgroundRotation + 'deg, ' + backgroundColors + ')';

			$('#preview')
				.css('background-image', gradient)
				.css('color', fontColor)
				.text(appName);
		}

		$(function() {
			updatePlatforms();
			updatePreview();
			bulmaSlider.attach();
		});
	</script>

	<p>&nbsp;</p>
	<p>&nbsp;</p>

	<div class="container is-body-font">
		<div class="column is-8 is-offset-2">

			<h2 class="title">Submit Application</h2>
			<p>&nbsp;</p>

			@if (isset($errors) && $errors->count())
				<div class="notification is-warning">
					There was a problem with your submission, please correct the errors and try again.
				</div>
				<p>&nbsp;</p>
			@endif

			<div class="subtitle">Required Fields</div>

			{{ Form::open(['url' => $url, 'method' => $method, 'files' => true, 'onsubmit' => 'onFormSubmit()']) }}

			<article class="message is-dark is-elevated">
				<div class="message-body">

					<div class="field">
						<label class="label" for="title">
							Application Name <span class="required">*</span>
						</label>
						<div class="control">
							{{ Form::text('title', $app->title, ['class' => 'input', 'required' => true, 'id' => 'title', 'oninput' => 'updatePreview()']) }}

							@if ($errors->has('title'))
								<span class="help is-danger">
									{{ $errors->first('title') }}
								</span>
							@endif
							@if ($errors->has('slug'))
								<span class="help is-danger">
									{{ $errors->first('slug') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<div class="field">
							<label class="label">
								Platforms <span class="required">*</span>
							</label>
							<div class="control prevent-select">
								<label>
									{{ Form::hidden('is_mobile', 0) }}
									<input name="is_mobile" type="checkbox" onchange="updatePlatforms()"
										value="1" {{ (old('is_mobile') !== null ? intval(old('is_mobile')) : $app->is_mobile) ? 'CHECKED' : '' }}/> Mobile
								</label>
								&nbsp;&nbsp;
								<label>
									{{ Form::hidden('is_desktop', 0) }}
									<input name="is_desktop" type="checkbox" onchange="updatePlatforms()"
										value="1" {{ (old('is_desktop') !== null ? intval(old('is_desktop')) : $app->is_desktop) ? 'CHECKED' : '' }}/> Desktop
								</label>
								&nbsp;&nbsp;
								<label>
									{{ Form::hidden('is_web', 0) }}
									<input name="is_web" type="checkbox" onchange="updatePlatforms()"
										value="1" {{ (old('is_web') !== null ? intval(old('is_web')) : $app->is_web) ? 'CHECKED' : '' }}/> Web
								</label>
							</div>
						</div>
					</div>

					<div class="field" id="mobileScreenshotInput" style="display:{{ $app->is_mobile ? 'block' : 'none' }}">
						<label class="label" for="screenshot">
							Mobile PNG Screenshot • 1080px by 1920px <span class="required">*</span>
						</label>
						<div class="control">

							{{ Form::file('screenshot') }}

							@if ($errors->has('screenshot'))
								<span class="help is-danger">
									{{ $errors->first('screenshot') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field" id="desktopScreenshotInput" style="display:{{ $app->is_desktop ? 'block' : 'none' }}">
						<label class="label" for="screenshot_desktop">
							Desktop PNG Screenshot • 1280px x 800px <span class="required">*</span>
						</label>
						<div class="control">

							{{ Form::file('screenshot_desktop') }}

							@if ($errors->has('screenshot_desktop'))
								<span class="help is-danger">
									{{ $errors->first('screenshot_desktop') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field" id="webUrlInput" style="display:{{ $app->is_web ? 'block' : 'none' }}">
						<label class="label" for="flutter_web_url">
							Flutter Web URL <span class="required">*</span>
						</label>
						<div class="control">
							{{ Form::url('flutter_web_url', $app->flutter_web_url, ['class' => 'input', 'maxlength' => 250, 'placeholder' => 'https://example.com']) }}

							@if ($errors->has('flutter_web_url'))
								<span class="help is-danger">
									{{ $errors->first('flutter_web_url') }}
								</span>
							@endif
						</div>
					</div>


					<div class="field">
						<label class="label" for="short_description">
							Short Description <span class="required">*</span>
						</label>
						<div class="control">

							{{ Form::text('short_description', $app->short_description, ['class' => 'input', 'required' => true, 'maxlength' => 250]) }}

							@if ($errors->has('short_description'))
								<span class="help is-danger">
									{{ $errors->first('short_description') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="long_description">
							Long Description <span class="required">*</span>
						</label>
						<div class="control">

							{{ Form::textarea('long_description', $app->long_description, ['class' => 'textarea', 'required' => true, 'rows' => 6]) }}

							@if ($errors->has('long_description'))
								<span class="help is-danger">
									{{ $errors->first('long_description') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<div class="field">
							<label class="label">
								Template
							</label>
							<div class="control">
								<label>
									{{ Form::hidden('is_template', 0) }}
									<input name="is_template" type="checkbox"
										value="1" {{ (old('is_template') !== null ? intval(old('is_template')) : $app->is_template) ? 'CHECKED' : '' }}/> Please check this if your app is a template or UI kit
								</lable>
							</div>
						</div>
					</div>

				</div>

			</article>

			<p>&nbsp;</p>
			<p>&nbsp;</p>

			@if ($campaign == '30days')

				<input type="text" name="campaign_id" value="1" style="display:none"/>

				<div class="subtitle">30 Days of Flutter</div>

				<div class="subtitle is-6">Thank you for participating in the event!! Feel free to share your app even if it isn't yet complete. Your Flutter journey is just beginning... hopefully you'll keep improving the app over time, you can update the details here any time.</div>
				<article class="message is-info is-elevated">
					<div class="message-body">

						<div class="field">
							<label class="label" for="campaign_is_first_app">
								Was this your first Flutter app?
							</label>
							<div class="control">
							  <label class="radio" style="color:black">
							    <input type="radio" name="campaign_is_first_app" value="1" {{ old('campaign_is_first_app') == "1" ? "CHECKED" : "" }}>
								Yes
							</label>
							  <label class="radio" style="color:black">
							    <input type="radio" name="campaign_is_first_app" value="0" {{ old('campaign_is_first_app') == "0" ? "CHECKED" : "" }}>
							    No
							  </label>
							</div>
						</div>

						<div class="field">
							<label class="label" for="campaign_content_score">
								On a scale of 1-5, how useful did you find the content shared during #30DaysOfFlutter?
							</label>
							<div class="control">
								<select name="campaign_content_score">
									<option value=""></option>
									@for ($i=1; $i<=5; $i++)
										<option value="{{ $i }}" {{ old('campaign_content_score') == $i ? "SELECTED" : ""}}>
											@if ($i == 1)
												1 - Poor
											@elseif ($i == 5)
												5 - Loved it!
											@else
												{{ $i }}
											@endif
										</option>
									@endfor
								</select>
							</div>
						</div>

						<div class="field">
							<label class="label" for="campaign_video_score">
								On a scale of 1-5, how useful did you find the live sessions?
							</label>
							<div class="control">
								<select name="campaign_video_score">
									<option value=""></option>
									@for ($i=1; $i<=5; $i++)
										<option value="{{ $i }}" {{ old('campaign_video_score') == $i ? "SELECTED" : ""}}>
											@if ($i == 1)
												1 - Poor
											@elseif ($i == 5)
												5 - Enjoyed a lot!
											@else
												{{ $i }}
											@endif
										</option>
									@endfor
								</select>
							</div>
						</div>

						<div class="field">
							<label class="label" for="campaign_support_score">
								On a scale of 1-5, how satisfied are you with the support provided by the community during #30DaysOfFlutter campaign?
							</label>
							<div class="control">
								<select name="campaign_support_score">
									<option value=""></option>
									@for ($i=1; $i<=5; $i++)
										<option value="{{ $i }}" {{ old('campaign_support_score') == $i ? "SELECTED" : ""}}>
											@if ($i == 1)
												1 - Poor
											@elseif ($i == 5)
												5 - It was great!
											@else
												{{ $i }}
											@endif
										</option>
									@endfor
								</select>
							</div>
						</div>

						<div class="field">
							<label class="label" for="campaign_subscribe">
								Will you like to be a part of more such campaigns in the future?
							</label>
							<div class="control">
							  <label class="radio" style="color:black">
							    <input type="radio" name="campaign_subscribe" value="1" {{ old('campaign_subscribe') == "1" ? "CHECKED" : "" }}>
								Yes
							</label>
							  <label class="radio" style="color:black">
							    <input type="radio" name="campaign_subscribe" value="0" {{ old('campaign_subscribe') == "0" ? "CHECKED" : "" }}>
							    No
							  </label>
							</div>
						</div>

						<div class="field">
							<label class="label" for="campaign_comments">
								Comments (if any)
							</label>
							<div class="control">

								{{ Form::textarea('campaign_comments', '', ['class' => 'textarea', 'rows' => 4]) }}

							</div>
						</div>

					</div>
				</article>

				<p>&nbsp;</p>
				<p>&nbsp;</p>

			@endif

			<div class="subtitle">Optional GIFs/Images</div>

			<article class="message is-elevated">
				<div class="message-body">

					<div class="field" id="desktopGifInput">
						<label class="label" for="gif_desktop">
							Desktop GIF • 1280px by 800px
						</label>
						<div class="control">

							{{ Form::file('gif_desktop') }}

							@if ($errors->has('gif_desktop'))
								<span class="help is-danger">
									{{ $errors->first('gif_desktop') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="gif">
							Mobile GIF • 1080px by 1920px
						</label>
						<div class="control">

							{{ Form::file('gif') }}

							@if ($errors->has('gif'))
								<span class="help is-danger">
									{{ $errors->first('gif') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="screenshot_1">
							Mobile PNG • 1080px by 1920px
						</label>
						<div class="control">

							{{ Form::file('screenshot_1') }}

							@if ($errors->has('screenshot_1'))
								<span class="help is-danger">
									{{ $errors->first('screenshot_1') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="screenshot_2">
							Mobile PNG • 1080px by 1920px
						</label>
						<div class="control">

							{{ Form::file('screenshot_2') }}

							@if ($errors->has('screenshot_2'))
								<span class="help is-danger">
									{{ $errors->first('screenshot_2') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="screenshot_3">
							Mobile PNG • 1080px by 1920px
						</label>
						<div class="control">

							{{ Form::file('screenshot_3') }}

							@if ($errors->has('screenshot_3'))
								<span class="help is-danger">
									{{ $errors->first('screenshot_3') }}
								</span>
							@endif
						</div>
					</div>

				</div>
			</article>

			<p>&nbsp;</p>
			<p>&nbsp;</p>

			<div class="subtitle">App Store Links</div>

			<article class="message is-elevated">
				<div class="message-body">

				<div class="field">
					<label class="label" for="google_url">
						Android • Google Play Store
					</label>
					<div class="control has-icons-left">

						{{ Form::url('google_url', $app->google_url, ['class' => 'input', 'placeholder' => 'https://play.google.com/...']) }}

						<span class="icon is-small is-left">
							<i class="fab fa-google"></i>
						</span>

						@if ($errors->has('google_url'))
							<span class="help is-danger">
								{{ $errors->first('google_url') }}
							</span>
						@endif
					</div>
				</div>

				<div class="field">
					<label class="label" for="apple_url">
						iOS/macOS • Apple App Store
					</label>
					<div class="control has-icons-left">

						{{ Form::url('apple_url', $app->apple_url, ['class' => 'input', 'placeholder' => 'https://apps.apple.com/...']) }}

						<span class="icon is-small is-left">
							<i class="fab fa-apple"></i>
						</span>

						@if ($errors->has('apple_url'))
							<span class="help is-danger">
								{{ $errors->first('apple_url') }}
							</span>
						@endif
					</div>
				</div>
													
				<div class="field">
					<label class="label" for="apple_url">
						Windows • Microsoft Store
					</label>
					<div class="control has-icons-left">

						{{ Form::url('microsoft_url', $app->microsoft_url, ['class' => 'input', 'placeholder' => 'https://microsoft.com/...']) }}

						<span class="icon is-small is-left">
							<i class="fab fa-microsoft"></i>
						</span>

						@if ($errors->has('microsoft_url'))
							<span class="help is-danger">
								{{ $errors->first('microsoft_url') }}
							</span>
						@endif
					</div>
				</div>	

				<div class="field">
					<label class="label" for="snapcraft_url">
						Linux • Snapcraft
					</label>
					<div class="control has-icons-left">

						{{ Form::url('snapcraft_url', $app->snapcraft_url, ['class' => 'input', 'placeholder' => 'https://snapcraft.io/...']) }}

						<span class="icon is-small is-left">
							<i class="fab fa-linux"></i>
						</span>

						@if ($errors->has('snapcraft_url'))
							<span class="help is-danger">
								{{ $errors->first('snapcraft_url') }}
							</span>
						@endif
					</div>
				</div>
	
			</div>
			</article>
			<p>&nbsp;</p>
			<p>&nbsp;</p>

			<div class="subtitle">Page Style</div>

			<article class="message is-elevated">
				<div class="message-body">

				<div class="field">
					<label class="label" for="background_colors">
						Background Colors
					</label>
					<div class="control has-icons-left">

						<div class="select">
							{{ Form::select('background_colors', $gradients, $selectedGradient, ['oninput' => 'updatePreview()', 'id' => 'background_colors']) }}
						</div>

						<span class="help">
							<a href="https://uigradients.com" target="_blank" rel="nofollow">Gradients from uiGradients</a>
						</span>

						<span class="icon is-small is-left">
							<i class="fas fa-palette"></i>
						</span>

						<div id="custom_colors" style="padding-top: 10px;display: none;">
							{{ Form::color('custom_color1', explode(', ', $app->background_colors)[0], ['id' => 'custom_color1', 'oninput' => 'updatePreview()']) }} &nbsp;
							{{ Form::color('custom_color2', explode(', ', $app->background_colors)[1], ['id' => 'custom_color2', 'oninput' => 'updatePreview()']) }}
						</div>

						@if ($errors->has('background_colors'))
							<span class="help is-danger">
								{{ $errors->first('background_colors') }}
							</span>
						@endif
					</div>
				</div>

				<div class="field">
					<label class="label" for="background_rotation">
						Background Rotation
					</label>
					<div class="control">

						{{ Form::range('background_rotation', $app->background_rotation, ['class' => 'slider has-output is-fullwidth', 'step' => '1', 'min' => '0', 'max' => '360', 'id' => 'background_rotation', 'oninput' => 'updatePreview()']) }}
						<output for="background_rotation" style="margin-top:22px;">{{ $app->background_rotation }}</output>

						@if ($errors->has('background_rotation'))
							<span class="help is-danger">
								{{ $errors->first('background_rotation') }}
							</span>
						@endif
					</div>
				</div>

				<div class="field">
					<label class="label" for="font_color">
						Font Color
					</label>
					<div class="control has-icons-left">

						{{ Form::color('font_color', $app->font_color, ['class' => 'input', 'id' => 'font_color', 'oninput' => 'updatePreview()']) }}

						<span class="icon is-small is-left">
							<i class="fas fa-font"></i>
						</span>

						@if ($errors->has('font_color'))
							<span class="help is-danger">
								{{ $errors->first('font_color') }}
							</span>
						@endif
					</div>
				</div>

	
				<div class="field">
					<label class="label" for="preview">
						Preview
					</label>
					<div id="preview"/>
				</div>

	
			</div>
			</article>

			<p>&nbsp;</p>
			<p>&nbsp;</p>

			<div class="subtitle">Optional Links</div>

			<div class="subtitle is-6">If a Twitter link is set we'll include the handle in the promotional tweet</div>

			<article class="message is-elevated">
				<div class="message-body">


					<div class="field">
						<label class="label" for="repo_url">
							GitHub
						</label>
						<div class="control has-icons-left">

							{{ Form::url('repo_url', $app->repo_url, ['class' => 'input', 'placeholder' => 'https://github.com/...']) }}

							<span class="icon is-small is-left">
								<i class="fab fa-github"></i>
							</span>

							@if ($errors->has('repo_url'))
								<span class="help is-danger">
									{{ $errors->first('repo_url') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="website_url">
							Website
						</label>
						<div class="control has-icons-left">

							{{ Form::url('website_url', $app->website_url, ['class' => 'input', 'placeholder' => 'https://example.com']) }}

							<span class="icon is-small is-left">
								<i class="fas fa-globe"></i>
							</span>

							@if ($errors->has('website_url'))
								<span class="help is-danger">
									{{ $errors->first('website_url') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="youtube_url">
							YouTube
						</label>
						<div class="control has-icons-left">

							{{ Form::url('youtube_url', $app->youtube_url, ['class' => 'input', 'placeholder' => 'https://www.youtube.com/embed/...']) }}

							<span class="icon is-small is-left">
								<i class="fab fa-youtube"></i>
							</span>

							@if ($errors->has('youtube_url'))
								<span class="help is-danger">
									{{ $errors->first('youtube_url') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="facebook_url">
							Facebook
						</label>
						<div class="control has-icons-left">

							{{ Form::url('facebook_url', $app->facebook_url, ['class' => 'input', 'placeholder' => 'https://www.facebook.com/...']) }}

							<span class="icon is-small is-left">
								<i class="fab fa-facebook"></i>
							</span>

							@if ($errors->has('facebook_url'))
								<span class="help is-danger">
									{{ $errors->first('facebook_url') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="twitter_url">
							Twitter
						</label>
						<div class="control has-icons-left">

							{{ Form::url('twitter_url', $app->twitter_url, ['class' => 'input', 'placeholder' => 'https://twitter.com/...']) }}

							<span class="icon is-small is-left">
								<i class="fab fa-twitter"></i>
							</span>

							@if ($errors->has('twitter_url'))
								<span class="help is-danger">
									{{ $errors->first('twitter_url') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="instagram_url">
							Instagram
						</label>
						<div class="control has-icons-left">

							{{ Form::url('instagram_url', $app->instagram_url, ['class' => 'input', 'placeholder' => 'https://www.instagram.com/...']) }}

							<span class="icon is-small is-left">
								<i class="fab fa-instagram"></i>
							</span>

							@if ($errors->has('instagram_url'))
								<span class="help is-danger">
									{{ $errors->first('instagram_url') }}
								</span>
							@endif
						</div>
					</div>



				</div>
			</article>

			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>

			@if (auth()->user()->is_pro && auth()->user()->is_pro_iaw)
				<div class="has-text-centered">
					Your developer profile will also be listed on <a href="{{ fpUrl() }}" target="_blank">flutterpro.dev</a>,
					to change your settings <a href="/profile/edit" target="_blank">click here</a>
				</div>

				<p>&nbsp;</p>
			@endif

			@if (! $app->exists)
				<div class="has-text-centered">
					<input name="terms" id="terms" type="checkbox" value="1" required>
					<label for="terms">
						&nbsp; I accept the It's All Widgets! {{ link_to('terms', 'Terms of Service', ['target' => '_blank']) }}
					</label>
					<br/>

					@if ($errors->has('terms'))
						<span class="help is-danger">
							{{ $errors->first('terms') }}
						</span>
					@endif

				</div>

				<p>&nbsp;</p>
			@endif

			<p>&nbsp;</p>
			<p>&nbsp;</p>

			<div class="columns is-centered is-mobile">

				<div class="control">
					<a href="{{ $app->exists ? url('/flutter-app/' . $app->slug) : url('/') }}" class="button is-medium is-outlined is-slightly-elevated">
						<i style="font-size: 20px" class="fa fa-times-circle"></i> &nbsp; Cancel
					</a> &nbsp;
					<button id="saveButton" class="button is-info is-medium is-slightly-elevated">
						<i style="font-size: 20px" class="fas fa-cloud-upload-alt"></i> &nbsp; {{ $app->exists ? 'Save' : 'Submit' }}
					</button>
				</div>
			</div>

			<p>&nbsp;</p>

			{{ Form::close() }}

		</div>
	</div>
@stop

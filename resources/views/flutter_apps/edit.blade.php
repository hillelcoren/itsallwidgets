@extends('master')

@section('title', 'Submit Application')
@section('description', 'Submit a Flutter application to be added to the list')
@section('image_url', asset('images/background.jpg'))

@section('content')

	<script>
		function onFormSubmit() {
			$('#saveButton').addClass('is-loading').prop('disabled', true);
		}

		function updatePlatforms() {
			$('#webRequiredField').toggle($('input[name=is_web]').is(':checked'));
			$('#mobileRequiredField').toggle($('input[name=is_mobile]').is(':checked'));
		}
		$(function() {
			updatePlatforms();
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
							{{ Form::text('title', $app->title, ['class' => 'input', 'required' => true]) }}

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
							<div class="control">
								<label>
									{{ Form::hidden('is_mobile', 0) }}
									<input name="is_mobile" type="checkbox" onchange="updatePlatforms()"
										value="1" {{ (old('is_mobile') !== null ? intval(old('is_mobile')) : $app->is_mobile) ? 'CHECKED' : '' }}/> Mobile
								</lable>
								&nbsp;&nbsp;
								<label>
									{{ Form::hidden('is_web', 0) }}
									<input name="is_web" type="checkbox" onchange="updatePlatforms()"
										value="1" {{ (old('is_web') !== null ? intval(old('is_web')) : $app->is_web) ? 'CHECKED' : '' }}/> Web
								</label>
							</div>
						</div>
					</div>

					<div class="field" id="mobileRequiredField" style="display:{{ $app->is_mobile ? 'block' : 'none' }}">
						<label class="label" for="screenshot">
							PNG Screenshot • 1080px by 1920px <span class="required">*</span>
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


					<div class="field" id="webRequiredField" style="display:{{ $app->is_web ? 'block' : 'none' }}">
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
										value="1" {{ (old('is_template') !== null ? intval(old('is_template')) : $app->is_template) ? 'CHECKED' : '' }}/> Please check this if your app is a template
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

				<article class="message is-info is-elevated">
					<div class="message-body">

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
												5 - Enjoyed it a lot!
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

			<div class="subtitle">Optional Mobile Images</div>

			<article class="message is-elevated">
				<div class="message-body">

					<div class="field">
						<label class="label" for="gif">
							GIF Video • 1080px by 1920px
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
							PNG Screenshot • 1080px by 1920px
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
							PNG Screenshot • 1080px by 1920px
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
							PNG Screenshot • 1080px by 1920px
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

			<div class="subtitle">Optional Links</div>

			<div class="subtitle is-6">If a Twitter link is set we'll include the handle in the promotional tweet</div>

			<article class="message is-elevated">
				<div class="message-body">

					<div class="field">
						<label class="label" for="google_url">
							Google Play Store
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
							Apple App Store
						</label>
						<div class="control has-icons-left">

							{{ Form::url('apple_url', $app->apple_url, ['class' => 'input', 'placeholder' => 'https://itunes.apple.com/...']) }}

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
						<label class="label" for="repo_url">
							Source Code
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
					Your profile will also be listed on <a href="{{ fpUrl() }}" target="_blank">flutterpro.dev</a>,
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

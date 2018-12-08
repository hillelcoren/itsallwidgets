@extends('master')

@section('title', 'Submit Application')
@section('description', 'Submit a Flutter application to be added to the list')
@section('image_url', asset('images/background.jpg'))

@section('content')

	<script>
		function onFormSubmit() {
			$('#saveButton').addClass('is-loading').prop('disabled', true);
		}
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
						<label class="label" for="screenshot">
							PNG Screenshot • 1080px by 1920px <span class="required">*</span>
						</label>
						<div class="control">

							{{ Form::file('screenshot', ['required' => $app->exists ? false : true]) }}

							@if ($errors->has('screenshot'))
								<span class="help is-danger">
									{{ $errors->first('screenshot') }}
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

							{{ Form::textarea('long_description', $app->long_description, ['class' => 'textarea', 'required' => true]) }}

							@if ($errors->has('long_description'))
								<span class="help is-danger">
									{{ $errors->first('long_description') }}
								</span>
							@endif
						</div>
					</div>

				</div>

			</article>

			<p>&nbsp;</p>
			<p>&nbsp;</p>

			<div class="subtitle">Optional Images</div>

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

			<div class="subtitle is-6">If a Twitter link is set we'll include your handle in the promotional tweet</div>

			<article class="message is-elevated">
				<div class="message-body">

					<div class="field">
						<label class="label" for="google_url">
							Google Play Store
						</label>
						<div class="control has-icons-left">

							{{ Form::text('google_url', $app->google_url, ['class' => 'input', 'type' => 'url', 'placeholder' => 'https://play.google.com/...']) }}

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

							{{ Form::text('apple_url', $app->apple_url, ['class' => 'input', 'type' => 'url', 'placeholder' => 'https://itunes.apple.com/...']) }}

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

							{{ Form::text('repo_url', $app->repo_url, ['class' => 'input', 'type' => 'url', 'placeholder' => 'https://github.com/...']) }}

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

							{{ Form::text('website_url', $app->website_url, ['class' => 'input', 'type' => 'url', 'placeholder' => 'https://example.com']) }}

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

							{{ Form::text('youtube_url', $app->youtube_url, ['class' => 'input', 'type' => 'url', 'placeholder' => 'https://www.youtube.com/embed/...']) }}

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

							{{ Form::text('facebook_url', $app->facebook_url, ['class' => 'input', 'type' => 'url', 'placeholder' => 'https://www.facebook.com/...']) }}

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

							{{ Form::text('twitter_url', $app->twitter_url, ['class' => 'input', 'type' => 'url', 'placeholder' => 'https://twitter.com/...']) }}

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

							{{ Form::text('instagram_url', $app->instagram_url, ['class' => 'input', 'type' => 'url', 'placeholder' => 'https://www.instagram.com/...']) }}

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
				<p>&nbsp;</p>
			@endif

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

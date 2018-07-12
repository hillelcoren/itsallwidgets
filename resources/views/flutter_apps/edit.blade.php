@extends('master')

@section('title', 'Submit Application')

@section('content')

	<div class="column is-8 is-offset-2">

		<h2 class="title">Submit Application</h2>
		<p>&nbsp;</p>

		@foreach ($errors->all() as $error)
	       <div class="block">{{ $error }}</div>
	   	@endforeach

		<div class="subtitle">Required Fields</div>

		{{ Form::open(array('url' => $url, 'method' => $method)) }}

		@if ($app->exists())
			{{ Form::hidden('id', $app->id) }}
		@endif

		<article class="message is-dark">
			<div class="message-body">

				<label class="label" for="title">
					Application Name <span class="required">*</span>
				</label>
				<div class="control">
					{{ Form::text('title', $app->title, ['class' => 'input', 'required' => 'true']) }}

					@if ($errors->has('title'))
						<span class="help is-danger">
							{{ $errors->first('title') }}
						</span>
					@endif
				</p>

				<label class="label" for="screenshot1_url">
					Screenshot URL <span class="required">*</span>
				</label>
				<div class="control">

					{{ Form::text('screenshot1_url', $app->screenshot1_url, ['class' => 'input', 'required' => 'true', 'type' => 'url']) }}

					@if ($errors->has('screenshot1_url'))
						<span class="help is-danger">
							{{ $errors->first('screenshot1_url') }}
						</span>
					@endif
				</p>

				<div class="field">
					<label class="label" for="short_description">
						Short Description <span class="required">*</span>
					</label>
					<div class="control">

						{{ Form::text('short_description', $app->short_description, ['class' => 'input', 'required' => 'true']) }}

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

						{{ Form::textarea('long_description', $app->long_description, ['class' => 'textarea', 'required' => 'true']) }}

						@if ($errors->has('long_description'))
							<span class="help is-danger">
								{{ $errors->first('long_description') }}
							</span>
						@endif
					</div>
				</div>

			</div>

		</div>
	</article>

	<p>&nbsp;</p>


	<div class="subtitle">Optional Links</div>

	<article class="message">
		<div class="message-body">


			<div class="field">
				<label class="label" for="apple_url">
					Apple App Store
				</label>
				<div class="control has-icons-left">

					{{ Form::text('apple_url', $app->apple_url, ['class' => 'input', 'type' => 'url', 'placeholder' => 'https://itunes.apple.com/app/...']) }}

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
				<label class="label" for="google_url">
					Google Play Store
				</label>
				<div class="control has-icons-left">

					{{ Form::text('google_url', $app->google_url, ['class' => 'input', 'type' => 'url', 'placeholder' => 'https://play.google.com/store/apps/...']) }}

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

					{{ Form::text('youtube_url', $app->youtube_url, ['class' => 'input', 'type' => 'url', 'placeholder' => 'https://youtube.com/...']) }}

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

					{{ Form::text('facebook_url', $app->facebook_url, ['class' => 'input', 'type' => 'url', 'placeholder' => 'https://facebook.com/...']) }}

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



		</div>
	</div>

	<p>&nbsp;</p>
	<p>&nbsp;</p>

	<div class="columns is-centered">
		<div class="control">
			<a href="{{ url($app->exists() ? '/flutter-app/' . $app->slug : '/') }}" class="button is-medium is-outlined">Cancel</a> &nbsp;
			<button class="button is-info is-medium">{{ $app->exists() ? 'Save' : 'Submit' }}</button>
		</div>
	</div>

	<p>&nbsp;</p>
	<p>&nbsp;</p>

	{{ Form::close() }}

</div>
@stop

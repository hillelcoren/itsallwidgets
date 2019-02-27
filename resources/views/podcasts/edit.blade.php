@extends('master')

@section('title', 'Submit Podcast')
@section('description', 'An open podcast for Flutter developers')
@section('image_url', asset('images/background.jpg'))

@section('header_title', 'The podcast for Flutter developers')
@section('header_subtitle', 'Stories from Flutter contributors and practitioners')
@section('header_button_url', url(auth()->check() ? 'podcast/submit' : 'auth/google?intended_url=podcast/submit'))
@section('header_button_label', 'SUGGEST INTERVIEW')
@section('header_button_icon', 'fas fa-microphone')

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
							Name <span class="required">*</span>
						</label>
						<div class="control">
							{{ Form::text('title', $episode->exists ? $episode->title : (auth()->check() ? auth()->user()->name : ''), ['class' => 'input', 'required' => true]) }}

							@if ($errors->has('title'))
								<span class="help is-danger">
									{{ $errors->first('title') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="email">
							Email <span class="required">*</span>
						</label>
						<div class="control">
							{{ Form::text('email', $episode->exists ? $episode->email : (auth()->check() ? auth()->user()->email : ''), ['class' => 'input', 'required' => true]) }}

							@if ($errors->has('email'))
								<span class="help is-danger">
									{{ $errors->first('email') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="avatar_url">
							Avatar URL <span class="required">*</span>
						</label>
						<div class="control">
							{{ Form::text('avatar_url', $episode->exists ? $episode->avatar_url : (auth()->check() ? auth()->user()->avatar_url : ''), ['class' => 'input', 'required' => true]) }}

							@if ($errors->has('avatar_url'))
								<span class="help is-danger">
									{{ $errors->first('avatar_url') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="twitter_url">
							Twitter <span class="required">*</span>
						</label>
						<div class="control has-icons-left">

							{{ Form::text('twitter_url', $episode->twitter_url, ['class' => 'input', 'type' => 'url', 'placeholder' => 'https://twitter.com/...']) }}

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
						<label class="label" for="short_description">
							Bio <span class="required">*</span>
						</label>
						<div class="control">
							{{ Form::textarea('short_description', $episode->short_description, ['class' => 'input', 'required' => true, 'style' => 'height:100px']) }}

							@if ($errors->has('short_description'))
								<span class="help is-danger">
									{{ $errors->first('short_description') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="private_notes">
							Date/Times UTC Available <span class="required">*</span>
						</label>
						<div class="control">
							{{ Form::text('private_notes', $episode->private_notes, ['class' => 'input', 'required' => true]) }}

							@if ($errors->has('private_notes'))
								<span class="help is-danger">
									{{ $errors->first('private_notes') }}
								</span>
							@endif
						</div>
					</div>


				</div>

			</article>

			<p>&nbsp;</p>
			<p>&nbsp;</p>

			<div class="subtitle">Optional Links</div>

			<article class="message is-elevated">
				<div class="message-body">

					<div class="field">
						<label class="label" for="github_url">
							GitHub
						</label>
						<div class="control has-icons-left">

							{{ Form::text('github_url', $episode->github_url, ['class' => 'input', 'type' => 'url', 'placeholder' => 'https://github.com/...']) }}

							<span class="icon is-small is-left">
								<i class="fab fa-github"></i>
							</span>

							@if ($errors->has('github_url'))
								<span class="help is-danger">
									{{ $errors->first('github_url') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="website_url">
							Website
						</label>
						<div class="control has-icons-left">

							{{ Form::text('website_url', $episode->website_url, ['class' => 'input', 'type' => 'url', 'placeholder' => 'https://example.com']) }}

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
						<label class="label" for="twitter_url">
							App on It's All Widgets!
						</label>
						<div class="control has-icons-left">

							{{ Form::text('app_url', $episode->app_url, ['class' => 'input', 'type' => 'url', 'placeholder' => 'https://itsallwidgets.com/...']) }}

							<span class="icon is-small is-left">
								<i class="fas fa-star"></i>
							</span>

							@if ($errors->has('app_url'))
								<span class="help is-danger">
									{{ $errors->first('app_url') }}
								</span>
							@endif
						</div>
					</div>

				</div>
			</article>

			<p>&nbsp;</p>
			<p>&nbsp;</p>

			@if (auth()->check() && auth()->user()->is_admin)
				<div class="subtitle">Admin Section</div>

				<article class="message is-elevated">
					<div class="message-body">

						<div class="field">
							<label class="label" for="long_description">
								Long Description
							</label>
							<div class="control">
								{{ Form::textarea('long_description', $episode->long_description, ['class' => 'input', 'required' => false, 'style' => 'height:100px']) }}

								@if ($errors->has('long_description'))
									<span class="help is-danger">
										{{ $errors->first('long_description') }}
									</span>
								@endif
							</div>
						</div>

						<div class="field">
							<label class="label" for="episode">
								Episode
							</label>
							<div class="control">
								{{ Form::text('episode', $episode->episode, ['class' => 'input', 'required' => false]) }}

								@if ($errors->has('episode'))
									<span class="help is-danger">
										{{ $errors->first('episode') }}
									</span>
								@endif
							</div>
						</div>

						<div class="field">
							<label class="label" for="file_duration">
								Duration
							</label>
							<div class="control">
								{{ Form::text('file_duration', $episode->file_duration ?: '0', ['class' => 'input', 'required' => false]) }}

								@if ($errors->has('file_duration'))
									<span class="help is-danger">
										{{ $errors->first('file_duration') }}
									</span>
								@endif
							</div>
						</div>

						<!--
						<div class="field">
							<label class="label" for="mp3">
								MP3 File
							</label>
							<div class="control">

								{{ Form::file('mp3') }}

								@if ($errors->has('mp3'))
									<span class="help is-danger">
										{{ $errors->first('mp3') }}
									</span>
								@endif
							</div>
						</div>
						-->

						<div class="field">
							<div class="field">
								<label class="label" for="is_visible">
									Is Visible
								</label>
								<div class="control">
									{{ Form::hidden('is_visible', 0) }}
									<input name="is_visible" type="checkbox" value="1" {{ $episode->is_visible ? 'CHECKED' : '' }}>
								</div>
							</div>
						</div>

						<div class="field">
							<div class="field">
								<label class="label" for="is_uploaded">
									Is Uploaded
								</label>
								<div class="control">
									{{ Form::hidden('is_uploaded', 0) }}
									<input name="is_uploaded" type="checkbox" value="1" {{ $episode->is_uploaded ? 'CHECKED' : '' }}>
								</div>
							</div>
						</div>

					</div>
				</article>
			@endif


			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>

			<div class="columns is-centered is-mobile">
				<div class="control">
					<a href="{{ $episode->exists ? $episode->url() : url('/podcast') }}" class="button is-medium is-outlined is-slightly-elevated">
						<i style="font-size: 20px" class="fa fa-times-circle"></i> &nbsp; Cancel
					</a> &nbsp;
					<button id="saveButton" class="button is-info is-medium is-slightly-elevated">
						<i style="font-size: 20px" class="fas fa-cloud-upload-alt"></i> &nbsp; {{ $episode->exists ? 'Save' : 'Submit' }}
					</button>
				</div>
			</div>

			<p>&nbsp;</p>

			{{ Form::close() }}

		</div>
	</div>
@stop

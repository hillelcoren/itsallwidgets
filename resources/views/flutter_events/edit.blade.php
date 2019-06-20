@extends('master')

@section('title', 'Submit Event')
@section('description', 'Submit a Flutter event')
@section('image_url', asset('images/background.jpg'))

@section('content')

	<script>
		function onFormSubmit() {
			$('#saveButton').addClass('is-loading').prop('disabled', true);
		}

		function updatePreview() {
			var banner = $('textarea[name=banner]').val();
			var name = $('input[name=event_name]').val() || 'EVENT';
			var url = $('input[name=event_url]').val() || '/#';
			var str = banner;

			str = str.replace(/@(\S+)/g, '<b><a href="https://twitter.com/$1" target="blank">@$1</a></b>')
					.replace(/#(\S+)/g, '<b><a href="https://twitter.com/hashtag/$1" target="blank">#$1</a></b>')
					.replace('$event', '<b><a href="' + url + '" target="_blank">' + name + '</a></b>');							

			$('#bannerPreview').html(str);
		}

		$(function() {
			updatePreview();
		});

	</script>

	<p>&nbsp;</p>
	<p>&nbsp;</p>

	<div class="container is-body-font">
		<div class="column is-8 is-offset-2">

			@if (!$event->exists)
				<h2 class="title">Submit a Flutter Event</h2>
			@else
				<nav class="breadcrumb" aria-label="breadcrumbs">
					<ul>
						<li><a href="{{ url('/flutter-events') }}">All Events</a></li>
						<li class="is-active"><a href="#" aria-current="page">{{ $event->event_name }}</a></li>
					</ul>
				</nav>

				<h2 class="title">Edit Flutter Event</h2>
			@endif

			<div class="subtitle" style="padding-top:6px">
				@if ($event->is_approved)
					Your banner will be shown to people who are near the event
				@else
					Once approved your banner will be shown to people who are near the event
				@endif
				<!--
				Your banner will be shown to people who are near the event and you'll be able to tweet the event from the
				<a href="https://twitter.com/itsallwidgets" target="_blank">@itsallwidgets</a> Twitter account.</div>
				-->
			</div>
			<p>&nbsp;</p>

			@if (isset($errors) && $errors->count())
				<div class="notification is-warning">
					There was a problem with your submission, please correct the errors and try again.
				</div>
				<p>&nbsp;</p>
			@endif

			{{ Form::open(['url' => $url, 'method' => $method, 'files' => true, 'onsubmit' => 'onFormSubmit()', 'onkeyup' => 'updatePreview()']) }}

			<article class="message is-dark is-elevated">
				<div class="message-body">

					<div class="field">
						<label class="label" for="event_name">
							Event Name <span class="required">*</span>
						</label>
						<div class="control has-icons-left">
							{{ Form::text('event_name', $event->event_name, ['class' => 'input', 'required' => true]) }}

							<span class="icon is-small is-left">
								<i class="fas fa-globe"></i>
							</span>

							<div class="help">The event name must be unique</div>

							@if ($errors->has('event_name'))
								<span class="help is-danger">
									{{ $errors->first('event_name') }}
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
						<label class="label" for="event_url">
							Event Link <span class="required">*</span>
						</label>
						<div class="control has-icons-left">
							{{ Form::url('event_url', $event->event_url, ['class' => 'input', 'required' => true]) }}

							<span class="icon is-small is-left">
								<i class="fas fa-link"></i>
							</span>

							@if ($errors->has('event_url'))
								<span class="help is-danger">
									{{ $errors->first('event_url') }}
								</span>
							@endif
						</div>
					</div>
					<div class="field">
						<label class="label" for="address">
							Event Address <span class="required">*</span>
						</label>
						<div class="control has-icons-left">
							{{ Form::text('address', $event->address, ['class' => 'input', 'required' => true]) }}

							<span class="icon is-small is-left">
								<i class="fas fa-map-marked"></i>
							</span>

							@if ($errors->has('address'))
								<span class="help is-danger">
									{{ $errors->first('address') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="event_date">
							Event Date <span class="required">*</span>
						</label>
						<div class="control has-icons-left">
							{{ Form::date('event_date', $event->event_date, ['class' => 'input', 'required' => true]) }}


							<span class="icon is-small is-left">
								<i class="fas fa-calendar"></i>
							</span>

							@if ($errors->has('event_date'))
								<span class="help is-danger">
									{{ $errors->first('event_date') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="banner">
							Message <span class="required">*</span>
						</label>
						<div class="control">

							{{ Form::textarea('banner', $event->banner ?: $event->defaultBanner(), ['class' => 'textarea', 'required' => true, 'rows' => 4]) }}

							<ul class="help">
								<li>• Use $event for the event link</li>
								<li>• Use @ and # to create twitter links</li>
								<li>• HTML is not supported but emoji are 😊</li>
							</ul>

							@if ($errors->has('banner'))
								<span class="help is-danger">
									{{ $errors->first('banner') }}
								</span>
							@endif
						</div>
					</div>
				</div>
			</article>


			<br/>
			<h3 class="subtitle">Banner Preview</h3>
			<div id="bannerPreview" class="notification is-info"></div>

			<p>&nbsp;</p>
			<p>&nbsp;</p>

			@if (! $event->exists)
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
					<a href="{{ $event->exists ? url('/flutter-events') : url('/') }}" class="button is-medium is-outlined is-slightly-elevated">
						<i style="font-size: 20px" class="fa fa-times-circle"></i> &nbsp; Cancel
					</a> &nbsp;
					<button id="saveButton" class="button is-info is-medium is-slightly-elevated">
						<i style="font-size: 20px" class="fas fa-cloud-upload-alt"></i> &nbsp; {{ $event->exists ? 'Save' : 'Submit' }}
					</button>
				</div>
			</div>

			<p>&nbsp;</p>

			{{ Form::close() }}

		</div>
	</div>
@stop

@extends('master')

@section('title', 'Edit Podcast')

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

			<h2 class="title">Submit Podcast</h2>
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
							Title <span class="required">*</span>
						</label>
						<div class="control">
							{{ Form::text('title', $episode->title, ['class' => 'input', 'required' => true]) }}

							@if ($errors->has('title'))
								<span class="help is-danger">
									{{ $errors->first('title') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="short_description">
							Short Description <span class="required">*</span>
						</label>
						<div class="control">
							{{ Form::text('short_description', $episode->short_description, ['class' => 'input', 'required' => true]) }}

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
							{{ Form::textarea('long_description', $episode->short_description, ['class' => 'input', 'required' => true]) }}

							@if ($errors->has('long_description'))
								<span class="help is-danger">
									{{ $errors->first('long_description') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="episode">
							Episode <span class="required">*</span>
						</label>
						<div class="control">
							{{ Form::text('episode', $episode->episode, ['class' => 'input', 'required' => true]) }}

							@if ($errors->has('episode'))
								<span class="help is-danger">
									{{ $errors->first('episode') }}
								</span>
							@endif
						</div>
					</div>

				</div>

			</article>

			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>

			<div class="columns is-centered is-mobile">
				<div class="control">
					<a href="{{ $episode->exists ? url('/flutter-app/' . $episode->slug) : url('/') }}" class="button is-medium is-outlined is-slightly-elevated">
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

@extends('master')

@section('title', 'Profile | It\'s All Widget!')
@section('description', 'Profile | It\'s All Widget!')
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

			<h2 class="title">Developer Profile</h2>
			<p>&nbsp;</p>

			@if (isset($errors) && $errors->count())
				<div class="notification is-warning">
					Please correct the errors and try again.
				</div>
				<p>&nbsp;</p>
			@endif

			<div class="subtitle">Required Fields</div>

			{{ Form::open(['url' => url('/profile'), 'method' => 'PUT', 'files' => true, 'onsubmit' => 'onFormSubmit()']) }}

			<article class="message is-dark is-elevated">
				<div class="message-body">

                    <div class="field">
						<label class="label" for="title">
							Name <span class="required">*</span>
						</label>
						<div class="control">
							{{ Form::text('name', $user->name, ['class' => 'input', 'required' => true]) }}

							@if ($errors->has('name'))
								<span class="help is-danger">
									{{ $errors->first('name') }}
								</span>
							@endif
						</div>
					</div>

                    <div class="field">
						<label class="label" for="title">
							Email <span class="required">*</span>
						</label>
						<div class="control">
							{{ Form::text('name', $user->email, ['class' => 'input', 'required' => true]) }}

							@if ($errors->has('email'))
								<span class="help is-danger">
									{{ $errors->first('email') }}
								</span>
							@endif
						</div>
					</div>

				</div>

			</article>

			<p>&nbsp;</p>
			<p>&nbsp;</p>

			<div class="columns is-centered is-mobile">

				<div class="control">
					<a href="{{ url('/') }}" class="button is-medium is-outlined is-slightly-elevated">
						<i style="font-size: 20px" class="fa fa-times-circle"></i> &nbsp; Cancel
					</a> &nbsp;
					<button id="saveButton" class="button is-info is-medium is-slightly-elevated">
						<i style="font-size: 20px" class="fas fa-cloud-upload-alt"></i> &nbsp; Save
					</button>
				</div>
			</div>

			<p>&nbsp;</p>

			{{ Form::close() }}

		</div>
	</div>
@stop

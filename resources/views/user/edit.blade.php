@extends('master')

@section('title', 'A Showcase for Passionate Flutter Developers')
@section('description', 'Profiles are sourced from FlutterX, Flutter Events and It\'s All Widgets!')
@section('image_url', asset('images/background.jpg'))

@section('header_title', 'A Showcase for Passionate Flutter Developers')
@section('header_button_url', iawUrl() . '/auth/google?intended_url=profile/edit')
@section('header_button_label', 'MANAGE PROFILE')
@section('header_button_icon', 'fas fa-user')

@section('header_subtitle')
    Profiles are sourced from
        <a href="https://flutterweekly.net" target="_blank">Flutter Weekly</a>,
        <a href="https://www.meetup.com/find/?allMeetups=false&keywords=flutter&radius=Infinity" target="_blank">Meetup.com</a> and
        <a href="{{ iawUrl() }}">It's All Widgets!</a>
@endsection

@section('head')
    <link rel="stylesheet" href="/css/countrySelect.min.css"/>
    <script src="/js/countrySelect.min.js"></script>
@endsection

@section('content')

	<script>
        $(function() {
            $("#country").countrySelect({
                defaultCountry: "{{ $user->country_code }}",
            });
        })

    	function onFormSubmit() {
    		$('#saveButton').addClass('is-loading').prop('disabled', true);
    	}
	</script>

    <section class="hero is-light is-small is-body-font">
        <div class="hero-body">
            <div class="container">
                <div class="column is-8 is-offset-2">
                    <h2 class="title">Developer Profile</h2>
                </div>
            </div>
        </div>
    </section>

    <p>&nbsp;</p>
	<p>&nbsp;</p>

	<div class="container is-body-font">
		<div class="column is-8 is-offset-2">

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
						<label class="label" for="name">
							Name
						</label>
						<div class="control has-icons-left">
							{{ Form::text('name', $user->name, ['class' => 'input', 'required' => true]) }}

							<span class="icon is-small is-left">
								<i class="fas fa-user"></i>
							</span>

							@if ($errors->has('name'))
								<span class="help is-danger">
									{{ $errors->first('name') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="handle">
							Handle
						</label>
						<div class="control has-icons-left">
							{{ Form::text('handle', $user->handle, ['class' => 'input', 'required' => true]) }}

							<span class="icon is-small is-left">
								<i class="fas fa-at"></i>
							</span>

                            <!--
                            <span class="help">
                                Profile Link <a href="{{ $user->url() }}" target="_blank">flutterpro.dev/{{ $user->handle }}</a>
                            </span>
                            -->

							@if ($errors->has('handle'))
								<span class="help is-danger">
									{{ $errors->first('handle') }}
								</span>
							@endif
						</div>
					</div>

                    <!--
					<div class="field">
						<label class="label" for="title">
							Sources
						</label>
						<div class="control">
                            @if ($user->is_pro)
    							<div style="padding-bottom:14px;padding-top:4px;">
                                    <label class="checkbox">
                                        {{ Form::hidden('is_pro_iaw', 0) }}
        								<input name="is_pro_iaw" type="checkbox" value="1" {{ $user->is_pro_iaw ? 'CHECKED' : '' }}> Applications
                                    </label>
                                    <div class="help">
                                        Sourced from <a href="https://itsallwidgets.com" target="_blank">itsallwidgets.com</a>
                                    </div>
    							</div>

    							<div style="padding-bottom:14px">
                                    <label class="checkbox">
                                        {{ Form::hidden('is_pro_fx', 0) }}
        								<input name="is_pro_fx" type="checkbox" value="1" {{ $user->is_pro_fx ? 'CHECKED' : '' }}> Resources
                                    </label>
                                    <div class="help">
                                        Sourced from <a href="https://flutterx.com" target="_blank">flutterx.com</a>
                                    </div>
    							</div>

    							<div>
                                    <label class="checkbox">
                                        {{ Form::hidden('is_pro_fe', 0) }}
        								<input name="is_pro_fe" type="checkbox" value="1" {{ $user->is_pro_fe ? 'CHECKED' : '' }}> Talks
                                    </label>
                                    <div class="help">
                                        Sourced from <a href="https://flutterevents.com" target="_blank">flutterevents.com</a>
                                    </div>
    							</div>
                            @else
                                <span style="color:#888">Enable Flutter Pro to configure sources</span>
                            @endif
						</div>
					</div>
                    -->
				</div>

			</article>

            <p>&nbsp;</p>
			<p>&nbsp;</p>

            @if (request()->flutter_cards)
                <div class="subtitle">Flutter Cards</div>

                <article class="message is-dark is-elevated">
                    <div class="message-body">

                        <div class="field">
                            <label class="label" for="youtube_channel_id">
                                Widget
                            </label>
                            <div class="control has-icons-left">
                                @if ($user->widget)
                                    {{ $user->widget }}
                                @else
                                    <div class="select">
                                      <select name="widget">
                                        @foreach ($widgets as $widget)
                                            <option value="{{ $widget }}">{{ $widget }}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="field">
    						<label class="label" for="widget_youtube_url">
    							Widget of the Week URL
    						</label>
    						<div class="control has-icons-left">

    							{{ Form::url('widget_youtube_url', $user->widget_youtube_url, ['class' => 'input', 'placeholder' => 'https://youtube.com']) }}

    							<span class="icon is-small is-left">
    								<i class="fab fa-youtube"></i>
    							</span>

    							@if ($errors->has('widget_youtube_url'))
    								<span class="help is-danger">
    									{{ $errors->first('widget_youtube_url') }}
    								</span>
    							@endif
    						</div>
    					</div>


                    </div>
                </article>

                <p>&nbsp;</p>
                <p>&nbsp;</p>
            @endif


            <div class="subtitle">Live Stream</div>

			<article class="message is-dark is-elevated">
				<div class="message-body">

                    <div class="field">
						<label class="label" for="youtube_channel_id">
							YouTube Channel ID
						</label>
						<div class="control has-icons-left">
							{{ Form::text('youtube_channel_id', $user->channel ? $user->channel->channel_id : '', [
                                    'class' => 'input',
                                    'placeholder' => 'UC...',
                                    'minlength' => 24,
                                    'maxlength' => 24,
                                    ]) }}

							<span class="icon is-small is-left">
								<i class="fab fa-youtube"></i>
							</span>

                            @if ($user->channel && $user->channel->name)
                                <div class="help">
                                    Channel: {{ $user->channel->name }}
                                </div>
                            @endif

							@if ($errors->has('youtube_channel_id'))
								<span class="help is-danger">
									{{ $errors->first('youtube_channel_id') }}
								</span>
							@endif
						</div>
					</div>

                    <div class="field" style="padding-top: 14px;">
                        <label class="label">
							Include
						</label>
                        <div class="control">
                          <label class="radio">
                            <input type="radio" name="match_videos" value="all" {{ !$user->channel || $user->channel->match_all_videos ? 'CHECKED' : '' }}>
                            All videos
                          </label>
                          <label class="radio">
                            <input type="radio" name="match_videos" value="flutter" {{ $user->channel && !$user->channel->match_all_videos ? 'CHECKED' : '' }}>
                            Videos with 'Flutter' in the title
                          </label>
                        </div>
					</div>

                    <div class="field" style="padding-top: 8px;">
                        <label class="label">
							Language
						</label>
                        <div class="control">

                        <div class="select">
                          <select name="language_id">
                            @foreach ($languages as $language)
                                <option value="{{ $language->id}}"
                                    {{ ($user->channel && $language->id == $user->channel->language_id) || (! $user->channel && $language->id == 1) ? ' SELECTED' : '' }}
                                >{{ $language->name}}</option>
                            @endforeach
                          </select>
                        </div>
					</div>

                    <div style="padding-top: 25px;">
                        For guidance producing a live stream check out this
                        <a href="https://medium.com/flutter/best-practices-for-hosting-a-live-streaming-coding-session-18d28b3a8d93" target="_blank">article</a>
                        and
                        <a href="https://www.youtube.com/watch?v=mGkuOQK3kAM" target="_blank">video</a>
                    </div>

                </div>
            </article>


			<p>&nbsp;</p>
			<p>&nbsp;</p>

			<div class="subtitle">Profile Details</div>

			<article class="message is-dark is-elevated">
				<div class="message-body">

                    <div class="field">
						<label class="label" for="website_url">
							Website
						</label>
						<div class="control has-icons-left">

							{{ Form::url('website_url', $user->website_url, ['class' => 'input', 'placeholder' => 'https://example.com']) }}

							<span class="icon is-small is-left">
								<i class="fas fa-globe"></i>
							</span>

                            <!--
                            <span class="help">
                                JSON Feed <a href="{{ $user->jsonUrl() }}" target="_blank">flutterpro.dev/{{ $user->handle }}/json</a>
                            </span>
                            -->

							@if ($errors->has('website_url'))
								<span class="help is-danger">
									{{ $errors->first('website_url') }}
								</span>
							@endif
						</div>
					</div>

                    <!--
                    <div class="field">
						<label class="label" for="profile_url">
							Flutter Web Portfolio
						</label>
						<div class="control has-icons-left">

							{{ Form::url('profile_url', $user->profile_url, ['class' => 'input', 'placeholder' => 'https://example.com']) }}

							<span class="icon is-small is-left">
								<i class="fas fa-globe"></i>
							</span>

                            <span class="help">
                                JSON Feed <a href="{{ $user->jsonUrl() }}" target="_blank">flutterpro.dev/{{ $user->handle }}/json</a>
                            </span>

							@if ($errors->has('profile_url'))
								<span class="help is-danger">
									{{ $errors->first('profile_url') }}
								</span>
							@endif
						</div>
					</div>
                    -->

                    <div class="field">
						<label class="label" for="avatar">
							Image
						</label>
						<div class="control">

							{{ Form::file('avatar') }}

                            <div class="help">The image can be any size but needs to be a sqaure</div>

							@if ($errors->has('avatar'))
								<span class="help is-danger">
									{{ $errors->first('avatar') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="bio">
							Bio
						</label>
						<div class="control">

							{{ Form::textarea('bio', $user->bio, ['class' => 'textarea', 'rows' => 3]) }}

							@if ($errors->has('bio'))
								<span class="help is-danger">
									{{ $errors->first('bio') }}
								</span>
							@endif
						</div>
					</div>

                    <div class="field">
						<label class="label" for="country_code">
							Country
						</label>
						<div class="control has-icons-left">

							{{ Form::text('country', '', ['class' => 'input', 'id' => 'country']) }}

                            <span style="display:none">
                                {{ Form::text('country_code', $user->country_code, ['class' => 'input', 'id' => 'country_code']) }}
                            </span>

							@if ($errors->has('country_code'))
								<span class="help is-danger">
									{{ $errors->first('country_code') }}
								</span>
							@endif
						</div>
					</div>

					<div class="field">
						<label class="label" for="github_url">
							GitHub
						</label>
						<div class="control has-icons-left">

							{{ Form::url('github_url', $user->github_url, ['class' => 'input', 'placeholder' => 'https://github.com']) }}

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
						<label class="label" for="medium_url">
							Medium
						</label>
						<div class="control has-icons-left">

							{{ Form::url('medium_url', $user->medium_url, ['class' => 'input', 'placeholder' => 'https://medium.com']) }}

							<span class="icon is-small is-left">
								<i class="fab fa-medium"></i>
							</span>

							@if ($errors->has('medium_url'))
								<span class="help is-danger">
									{{ $errors->first('medium_url') }}
								</span>
							@endif
						</div>
					</div>


					<div class="field">
						<label class="label" for="twitter_url">
							Twitter
						</label>
						<div class="control has-icons-left">

							{{ Form::url('twitter_url', $user->twitter_url, ['class' => 'input', 'placeholder' => 'https://twitter.com']) }}

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
						<label class="label" for="youtube_url">
							YouTube
						</label>
						<div class="control has-icons-left">

							{{ Form::url('youtube_url', $user->youtube_url, ['class' => 'input', 'placeholder' => 'https://youtube.com']) }}

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
						<label class="label" for="linkedin_url">
							LinkedIn
						</label>
						<div class="control has-icons-left">

							{{ Form::url('linkedin_url', $user->linkedin_url, ['class' => 'input', 'placeholder' => 'https://linkedin.com']) }}

							<span class="icon is-small is-left">
								<i class="fab fa-linkedin"></i>
							</span>

							@if ($errors->has('linkedin_url'))
								<span class="help is-danger">
									{{ $errors->first('linkedin_url') }}
								</span>
							@endif
						</div>
					</div>

                    <div class="field">
						<label class="label" for="instagram_url">
							Instagram
						</label>
						<div class="control has-icons-left">

							{{ Form::url('instagram_url', $user->instagram_url, ['class' => 'input', 'placeholder' => 'https://instagram.com']) }}

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


            <div class="subtitle">Account Settings</div>

			<article class="message is-dark is-elevated">
				<div class="message-body">

                    <div class="field" style="padding-bottom:12px">
						<label class="label" for="is_pro">
							Flutter Pro Profile
						</label>
						<div class="control">
                            <label class="checkbox">
                                {{ Form::hidden('is_pro', 0) }}
								<input name="is_pro" type="checkbox" value="1" {{ $user->is_pro ? 'CHECKED' : '' }}> Enable
                            </label>
                            <div class="help">
                                Show developer profile on <a href="https://flutterpro.dev" target="_blank">flutterpro.dev</a>
                            </div>
						</div>
					</div>

                    <div class="field" style="padding-bottom:12px">
						<label class="label" for="is_subscribed">
							It's All Widgets! Newsletter
						</label>
						<div class="control">
                            <label class="checkbox">
                                {{ Form::hidden('is_subscribed', 0) }}
								<input name="is_subscribed" type="checkbox" value="1" {{ $user->is_subscribed ? 'CHECKED' : '' }}> Subscribe
                            </label>
                            <div class="help">
                                In the future we plan to send emails sharing interesting statistics
                            </div>
						</div>
					</div>

                    <div class="field">
						<label class="label" for="is_for_hire">
							Available for Hire
						</label>
						<div class="control">
                            <label class="checkbox">
                                {{ Form::hidden('is_for_hire', 0) }}
								<input name="is_for_hire" type="checkbox" value="1" {{ $user->is_for_hire ? 'CHECKED' : '' }}> Yes
                            </label>
                            <div class="help">
                                Enable clients to see you're available to take on work
                            </div>
						</div>
					</div>

                    <div class="field">
						<label class="label" for="is_trainer">
							Available to Train
						</label>
						<div class="control">
                            <label class="checkbox">
                                {{ Form::hidden('is_trainer', 0) }}
								<input name="is_trainer" type="checkbox" value="1" {{ $user->is_trainer ? 'CHECKED' : '' }}> Yes
                            </label>
                            <div class="help">
                                Find new clients as a Flutter trainer
                            </div>
						</div>
					</div>

                    <div class="field">
						<label class="label" for="is_mentor">
							Available to Mentor
						</label>
						<div class="control">
                            <label class="checkbox">
                                {{ Form::hidden('is_mentor', 0) }}
								<input name="is_mentor" type="checkbox" value="1" {{ $user->is_mentor ? 'CHECKED' : '' }}> Yes
                            </label>
                            <div class="help">
                                Help other developers learn from your experience
                            </div>
						</div>
					</div>

				</div>
			</article>


			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>

			<div class="columns is-centered is-mobile">

				<div class="control">
					<a href="{{ fpUrl() }}" class="button is-medium is-outlined is-slightly-elevated">
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

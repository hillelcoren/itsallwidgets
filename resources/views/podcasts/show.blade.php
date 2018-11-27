@extends('master')

@section('title', $episode->title)
@section('description', $episode->short_description)

@section('header_title', 'An open podcast for Flutter developers')
@section('header_subtitle', 'Share your Flutter story with the community')
@section('header_button_url', url(auth()->check() ? 'podcast/submit' : 'auth/google?intended_url=podcast/submit'))
@section('header_button_label', 'REQUEST INTERVIEW')
@section('header_button_icon', 'fas fa-microphone')

@section('content')

	<section class="section is-body-font">
		<div class="container">

			<div class="columns is-multiline is-5 is-variable">
				<div class="column is-one-third">
				</div>
				<div class="column is-two-third">
					<nav class="breadcrumb" aria-label="breadcrumbs">
						<ul>
							<li><a href="{{ url('/podcast') }}">All Episodes</a></li>
							<li class="is-active"><a href="#" aria-current="page">Episode #{{ $episode->episode }}</a></li>
						</ul>
					</nav>

					<h2 class="title">
						{{ $episode->title }}
						<div style="border-bottom: 2px #259bee solid; width: 50px; padding-top:12px;"/>
					</h2>
					<div class="subtitle" style="padding-top:16px; max-width:600px">
						{{ $episode->short_description }}
					</div>


					<a class="button is-large is-slightly-elevated" href="{{ $episode->twitter_url }}" target="_blank" rel="nofollow">
						<i style="font-size: 20px" class="fab fa-twitter"></i> &nbsp; Twitter
					</a> &nbsp;

					<a class="button is-large is-slightly-elevated" href="{{ $episode->downloadUrl() }}">
						<i style="font-size: 20px" class="fas fa-download"></i> &nbsp; Download
					</a> &nbsp;

				</div>
			</div>


		</div>
	</section>

@stop

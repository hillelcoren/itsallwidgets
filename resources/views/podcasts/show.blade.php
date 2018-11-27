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
			<div class="is-title">
				{{ $episode->title }}
			</div>

			<a class="button is-large is-slightly-elevated" href="{{ $episode->twitter_url }}" target="_blank" rel="nofollow">
				<i style="font-size: 20px" class="fab fa-twitter"></i> &nbsp; Twitter
			</a> &nbsp;

			<a class="button is-large is-slightly-elevated" href="{{ $episode->downloadUrl() }}">
				<i style="font-size: 20px" class="fas fa-download"></i> &nbsp; Download
			</a> &nbsp;

		</div>
	</section>

@stop

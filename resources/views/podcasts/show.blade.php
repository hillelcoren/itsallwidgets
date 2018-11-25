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
		</div>
	</section>

@stop

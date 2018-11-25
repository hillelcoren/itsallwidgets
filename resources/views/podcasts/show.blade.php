@extends('master')

@section('title', $episode->guest)
@section('description', $episode->short_description)

@section('content')

	<section class="section is-body-font">
		<div class="container">
			<div class="is-title">
				{{ $episode->title }}
			</div>
		</div>
	</section>

@stop

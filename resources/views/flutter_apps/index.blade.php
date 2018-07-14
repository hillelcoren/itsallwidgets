@extends('master')

@section('title', 'Flutter Apps')
@section('description', 'An open list of apps built with Google Flutter')
@section('image_url', asset('images/logo.png'))

@section('content')

	<style>

	.filter-select {
		padding-left: 20px;
	}

	</style>

	<div id="app">

		<section class="hero is-light is-small">
			<div class="hero-body">
				<div class="container">

					<div class="field is-horizontal">
						<div class="field-body">
							<div class="field has-addons">
								<p class="control is-expanded has-icons-left">
									<input class="input is-medium" type="text" placeholder="Search">
									<span class="icon is-small is-left">
										<i class="fas fa-search"></i>
									</span>
									<div class="select is-medium filter-select">
										<select>
											<option>Open & closed source</option>
											<option>Open source</option>
											<option>Closed source</option>
										</select>
									</div>
									<div class="select is-medium filter-select">
										<select>
											<option>Three cards per row</option>
											<option>Sort oldest</option>
											<option>Sort released</option>
										</select>
									</div>
									<div class="select is-medium filter-select">
										<select>
											<option>Sort newest</option>
											<option>Sort oldest</option>
											<option>Sort released</option>
										</select>
									</div>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<p>&nbsp;</p>
		<p>&nbsp;</p>

		<div class="container">
			<div class="columns is-multiline is-4 is-variable">
				<div v-for="app in apps" class="column is-one-third">
					<div onclick="location.href = '@{{ url('flutter-app/'. $app->slug) }}';" style="cursor:pointer">
						<div class="card is-hover-elevated">
							<header class="card-header">
								<p class="card-header-title is-2">
									@{{ app.title }}
								</p>
								<a href="@{{ app.facebook_url }}" class="card-header-icon" target="_blank">
									<i style="font-size: 20px; color: #888" class="fab fa-facebook"></i>
								</a>
								<a href="@{{ app.twitter_url }}" class="card-header-icon" target="_blank">
									<i style="font-size: 20px; color: #888" class="fab fa-twitter"></i>
								</a>
								<a href="@{{ app.repo_url }}" class="card-header-icon" target="_blank">
									<i style="font-size: 20px; color: #888" class="fab fa-github"></i>
								</a>
							</header>

							<div class="card-content">
								<div class="content">
									<div class="subtitle is-6" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" v-bind:title="app.short_description">
										@{{ app.short_description }}
									</div>
									<div class="columns">
										<div class="column is-one-half">
											<div v-if="app.google_url" v-on:click="openStoreUrl(app.google_url)">
												<div class="card-image is-slightly-elevated">
													<img src="{{ asset('images/google.png') }}"/>
												</div>
											</div>
											<div v-if="! app.google_url" class="card-image is-slightly-elevated">
												<img src="{{ asset('images/google.png') }}" style="opacity: 0.1; filter: grayscale(100%);"/>
											</div>
										</div>
										<div class="column is-one-half">
											<div v-if="app.apple_url" v-on:click="openStoreUrl(app.apple_url)">
												<div class="card-image is-slightly-elevated">
													<img src="{{ asset('images/apple.png') }}"/>
												</div>
											</div>
											<div v-if="! app.apple_url" class="card-image is-slightly-elevated">
												<img src="{{ asset('images/apple.png') }}" style="opacity: 0.1; filter: grayscale(100%);"/>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="card-image">
								<img v-bind:src="app.screenshot1_url" width="1080" height="1920"/>
							</div>
						</div>
					</div>
				</div>

				<p>&nbsp;</p>

			</div>
		</div>
	</div>

	<script>

	var app = new Vue({
		el: '#app',
		methods: {
			openStoreUrl: function(url) {
				window.open(url, '_blank');
			}
		},
		data: {
			apps: {!! $apps !!}
		},
	});

	</script>


@stop

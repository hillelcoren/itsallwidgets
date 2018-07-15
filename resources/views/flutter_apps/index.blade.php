@extends('master')

@section('title', 'Flutter Apps')
@section('description', 'An open list of apps built with Google Flutter')
@section('image_url', asset('images/logo.png'))

@section('content')

<style>

.filter-control {
	padding-left: 32px;
}

.modal {
	-webkit-animation-duration: .5s;
	-moz-animation-duration: .5s;
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
								<input v-model="search" class="input is-medium" type="text" placeholder="Search" autofocus="true">
								<span class="icon is-small is-left">
									<i class="fas fa-search"></i>
								</span>
								<div class="is-medium filter-control" style="padding-top:10px;" v-on:click="toggleOpenSource()">
									<input type="checkbox" name="openSourceSwitch" class="switch is-info is-medium" v-model="filterOpenSource">
									<label for="openSourceSwitch">Open Source</label>
								</div>
								<div class="is-medium filter-control">
									<input class="slider is-fullwidth is-info" step="1" min="2" max="6" type="range" v-model="cardsPerRow">
								</div>
								<div class="select is-medium filter-control">
									<select v-model="sortBy">
										<option value="newest">Sort newest</option>
										<option value="oldest">Sort oldest</option>
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
			<div v-for="app in filteredApps" class="column" v-bind:class="columnClass">
				<div v-on:click="selectApp(app)" style="cursor:pointer">
					<div class="card is-hover-elevated">
						<header class="card-header">
							<p class="card-header-title is-2 no-wrap" v-bind:title="app.title">
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
								<div class="subtitle is-6 no-wrap" v-bind:title="app.short_description">
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

				<p>&nbsp;</p>

			</div>

		</div>
	</div>

	<div class="modal animated fadeIn" v-bind:class="modalClass">
		<div class="modal-background" v-on:click="selectApp()"></div>
		<div class="modal-card">
			<header class="modal-card-head">
				<p class="modal-card-title">Modal title</p>
				<button class="delete" aria-label="close" v-on:click="selectApp()"></button>
			</header>
			<section class="modal-card-body" @click.stop>

			</section>
		</div>
	</div>


</div>

<script>

var app = new Vue({
	el: '#app',

	methods: {
		openStoreUrl: function(url) {
			window.open(url, '_blank');
		},

		toggleOpenSource: function() {
			this.filterOpenSource = ! this.filterOpenSource;
		},

		selectApp: function(app) {
			this.selectedApp = app;
		},
	},

	data: {
		apps: {!! $apps !!},
		search: '',
		filterOpenSource: false,
		cardsPerRow: 5,
		sortBy: 'newest',
		selectedApp: false,
	},

	computed: {
		modalClass() {
			if (this.selectedApp) {
				return {'is-active': true};
			} else {
				return {};
			}
		},

		columnClass() {
			switch(+this.cardsPerRow) {
				case 6:
				return {'is-6': true};
				case 5:
				return {'is-one-third': true};
				case 4:
				return {'is-one-fourth': true};
				case 3:
				return {'is-one-fifth': true};
				case 2:
				return {'is-2': true};
			}
		},

		filteredApps() {
			var apps = this.apps;
			var search = this.search.toLowerCase().trim();
			var filterOpenSource = this.filterOpenSource;
			var sortBy = this.sortBy;

			if (search) {
				apps = apps.filter(function(item) {
					if (item.title.toLowerCase().indexOf(search) >= 0) {
						return true;
					}

					if (item.short_description.toLowerCase().indexOf(search) >= 0) {
						return true;
					}

					return false;
				});
			}

			if (filterOpenSource) {
				apps = apps.filter(function(item) {
					return item.repo_url;
				});
			}

			apps.sort(function(itemA, itemB) {
				var timeA = new Date(itemA.created_at).getTime();
				var timeB = new Date(itemB.created_at).getTime();
				if (sortBy == 'oldest') {
					return timeA - timeB;
				} else {
					return timeB - timeA;
				}
			});

			return apps;
		},
	}

});


</script>


@stop

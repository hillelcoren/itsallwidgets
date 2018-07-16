@extends('master')

@section('title', 'Flutter Apps')
@section('description', 'An open list of apps built with Google Flutter')
@section('image_url', asset('images/logo.png'))

@section('content')

<style>

body {
    -moz-transition: width 1s ease-in-out, left 1.5s ease-in-out;
    -webkit-transition: width 1s ease-in-out, left 1.5s ease-in-out;
    -moz-transition: width 1s ease-in-out, left 1.5s ease-in-out;
    -o-transition: width 1s ease-in-out, left 1.5s ease-in-out;
    transition: width 1s ease-in-out, left 1.5s ease-in-out;
}

.filter-control {
    padding-left: 16px;
}

.filter-label {
    padding-left: 36px;
}

.modal {
    -webkit-animation-duration: .5s;
    -moz-animation-duration: .5s;
}

.is-vertical-center {
    display: flex;
    align-items: center;
}

.modal-card {
    width: 80%;
}

[v-cloak] {
    display: none;
}

@media screen and (max-width: 769px) {
    .slider-control {
        display: none;
    }

    .store-buttons img {
        max-width: 200px;
    }

    .is-hover-elevated {
        -moz-filter: drop-shadow(0px 16px 16px #CCC);
        -webkit-filter: drop-shadow(0px 16px 16px #CCC);
        -o-filter: drop-shadow(0px 16px 16px #CCC);
        filter: drop-shadow(0px 16px 16px #CCC);
    }
}

</style>

<div id="app">

    <section class="hero is-light is-small">
        <div class="hero-body">
            <div class="container">
                <div class="field is-grouped is-grouped-multiline is-vertical-center">
                    <p class="control is-expanded has-icons-left">
                        <input v-model="search" class="input is-medium" type="text" v-bind:placeholder="'Search ' + filteredApps.length + ' apps and counting...'" autofocus="true" style="margin-top: 10px" v-bind:style="{ backgroundColor: searchBackgroundColor()}">
                        <span class="icon is-small is-left" style="margin-top: 10px">
                            <i class="fas fa-search"></i>
                        </span>
                        <div class="is-medium" v-on:click="toggleOpenSource()" style="padding-left: 26px;">
                            <input type="checkbox" name="openSourceSwitch"
                            class="switch is-info is-medium" v-model="filter_open_source">
                            <label for="openSourceSwitch">Open Source &nbsp;</label>
                        </div>
                        <div class="is-medium filter-label slider-control">
                            <label class="label is-medium" style="font-weight: normal;">Zoom</label>
                        </div>
                        <div class="is-medium filter-control slider-control">
                            <input class="slider is-fullwidth is-medium is-info"
                            step="1" min="2" max="6" type="range" v-model="cards_per_row">
                        </div>
                        <div class="is-medium filter-label">
                            <label class="label is-medium" style="font-weight: normal;">Sort</label>
                        </div>
                        <div class="select is-medium filter-control">
                            <select v-model="sort_by">
                                <option value="newest">Newest</option>
                                <option value="oldest">Oldest</option>
                            </select>
                        </div>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container" v-cloak>
            <div v-if="filteredApps.length == 0" class="is-wide has-text-centered is-vertical-center"
            style="height:400px; text-align:center; font-size: 32px; color: #AAA">
            No apps found
        </div>
        <div class="columns is-multiline is-4 is-variable">
            <div v-for="app in filteredApps" class="column" v-bind:class="columnClass">
                <div v-on:click="selectApp(app)" style="cursor:pointer">
                    <div class="card is-hover-elevated">
                        <header class="card-header">
                            <p class="card-header-title is-2 no-wrap" v-bind:title="app.title">
                                @{{ app.title }}
                            </p>
                            <a v-if="app.facebook_url && cards_per_row > 3" v-bind:href="app.facebook_url" class="card-header-icon" target="_blank">
                                <i style="font-size: 20px; color: #888" class="fab fa-facebook"></i>
                            </a>
                            <a v-if="app.twitter_url && cards_per_row > 3" v-bind:href="app.twitter_url" class="card-header-icon" target="_blank">
                                <i style="font-size: 20px; color: #888" class="fab fa-twitter"></i>
                            </a>
                            <a v-if="app.repo_url" v-bind:href="app.repo_url" class="card-header-icon" target="_blank">
                                <i style="font-size: 20px; color: #888" class="fab fa-github"></i>
                            </a>
                        </header>

                        <div class="card-content">
                            <div class="content">
                                <div class="subtitle is-6 no-wrap" v-bind:title="app.short_description">
                                    @{{ app.short_description }}
                                </div>
                                <div class="columns is-1 is-variable is-mobile">
                                    <div class="column is-one-half">
                                        <div v-if="app.google_url" v-on:click="openStoreUrl(app.google_url)" v-on:click.stop>
                                            <div class="card-image is-slightly-elevated">
                                                <img src="{{ asset('images/google.png') }}"/>
                                            </div>
                                        </div>
                                        <div v-if="! app.google_url" class="card-image is-slightly-elevated">
                                            <img src="{{ asset('images/google.png') }}" style="opacity: 0.1; filter: grayscale(100%);"/>
                                        </div>
                                    </div>
                                    <div class="column is-one-half">
                                        <div v-if="app.apple_url" v-on:click="openStoreUrl(app.apple_url)" v-on:click.stop>
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
</section>

<div class="modal animated fadeIn" v-bind:class="modalClass" v-if="selected_app">
    <div class="modal-background" v-on:click="selectApp()"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">
                @{{ selected_app.title }}
            </p>
            <button class="delete" aria-label="close" v-on:click="selectApp()"></button>
        </header>
        <section class="modal-card-body" @click.stop>

            <div class="columns">
                <div class="column is-4 is-elevated">
                    <img v-bind:src="selected_app.screenshot1_url" width="1080" height="1920"/>
                </div>
                <div class="column is-8">

                    @if (auth()->check())
                        <div v-if="selected_app.user_id == {{ auth()->user()->id }}">
                            <a class="button is-info is-slightly-elevated" v-bind:href="'/flutter-app/' + selected_app.slug + '/edit'">
                                <i style="font-size: 20px" class="fas fa-edit"></i> &nbsp;
                                Edit Application
                            </a>
                            <p>&nbsp;</p>
                        </div>
                    @endif

                    <div class="content">
                        <div class="subtitle">
                            @{{ selected_app.short_description }}
                        </div>

                        <div class="columns is-2 is-variable" v-if="selected_app.google_url || selected_app.apple_url">
                            <div class="column is-2">
                                <div v-if="selected_app.google_url" v-on:click="openStoreUrl(selected_app.google_url)">
                                    <div class="card-image is-slightly-elevated">
                                        <img src="{{ asset('images/google.png') }}"/>
                                    </div>
                                </div>
                                <div v-if="! selected_app.google_url" class="card-image is-slightly-elevated">
                                    <img src="{{ asset('images/google.png') }}" style="opacity: 0.1; filter: grayscale(100%);"/>
                                </div>
                            </div>
                            <div class="column is-2">
                                <div v-if="selected_app.apple_url" v-on:click="openStoreUrl(selected_app.apple_url)">
                                    <div class="card-image is-slightly-elevated">
                                        <img src="{{ asset('images/apple.png') }}"/>
                                    </div>
                                </div>
                                <div v-if="! selected_app.apple_url" class="card-image is-slightly-elevated">
                                    <img src="{{ asset('images/apple.png') }}" style="opacity: 0.1; filter: grayscale(100%);"/>
                                </div>
                            </div>
                        </div>

                        <div class="content" v-if="selected_app.website_url || selected_app.repo_url">
                            <a v-if="selected_app.website_url" v-bind:href="selected_app.website_url" target="_blank">
                                @{{ selected_app.website_url }}
                            </a></br>
                            <a v-if="selected_app.repo_url" v-bind:href="selected_app.repo_url" target="_blank">
                                @{{ selected_app.repo_url }}
                            </a><br/>
                            <br/>
                        </div>

                        <div class="content">
                            <a v-if="selected_app.facebook_url" class="button is-slightly-elevated" v-bind:href="selected_app.facebook_url" target="_blank">
                                <i style="font-size: 20px" class="fab fa-facebook"></i> &nbsp; Facebook
                            </a>
                            <a v-if="selected_app.twitter_url" class="button is-slightly-elevated" v-bind:href="selected_app.twitter_url" target="_blank">
                                <i style="font-size: 20px" class="fab fa-twitter"></i> &nbsp; Twitter
                            </a>

                            <div class="dropdown is-hoverable">
                                <div class="dropdown-trigger is-slightly-elevated">
                                    <button class="button" aria-haspopup="true" aria-controls="dropdown-menu4">
                                        <span>
                                            <i style="font-size: 20px" class="fa fa-share"></i> &nbsp;
                                            Share App
                                        </span>
                                        <span class="icon is-small">
                                            <i class="fas fa-angle-down" aria-hidden="true"></i>
                                        </span>
                                    </button>
                                </div>
                                <div class="dropdown-menu" role="menu">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=#url" target="_blank">
                                        <div class="dropdown-content">
                                            <div class="dropdown-item">
                                                <i style="font-size: 20px" class="fab fa-facebook"></i> &nbsp; Facebook
                                            </div>
                                        </div>
                                    </a>
                                    <a v-bind:href="'https://twitter.com/share?text=' + encodeURIComponent(selected_app.title) + '&amp;url=' + encodeURIComponent('{{ url('/flutter-app') }}' + '/' + selected_app.slug)" target="_blank">
                                        <div class="dropdown-content">
                                            <div class="dropdown-item">
                                                <i style="font-size: 20px" class="fab fa-twitter"></i> &nbsp; Twitter
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                        </div>

                        <span class="block wrap">@{{ selected_app.long_description }}</span>

                    </div>

                    <iframe v-if="selected_app.youtube_url" width="560" height="315" v-bind:src="selected_app.youtube_url"
                    frameborder="0" allowfullscreen></iframe>
                </div>

            </div>

        </div>


    </section>
</div>
</div>


</div>

<script>


function isStorageSupported() {
    try {
        return 'localStorage' in window && window['localStorage'] !== null;
    } catch (e) {
        return false;
    }
};


var app = new Vue({
    el: '#app',

    watch: {
        sort_by: {
            handler() {
                app.saveFilters();
            },
        },
        cards_per_row: {
            handler() {
                app.saveFilters();
            },
        },
    },

    methods: {
        openStoreUrl: function(url) {
            window.open(url, '_blank');
        },

        toggleOpenSource: function() {
            this.filter_open_source = ! this.filter_open_source;
        },

        selectApp: function(app) {
            if (document.body.clientWidth < 1000) {
                window.location = '/flutter-app/' + app.slug;
            } else {
                this.selected_app = app;
                if (history.pushState) {
                    if (app) {
                        history.pushState(null, null, '/flutter-app/' + app.slug);
                    } else {
                        history.pushState(null, null, '/flutter-apps');
                    }
                }
            }
        },

        saveFilters: function() {
            if (! isStorageSupported()) {
                return false;
            }

            localStorage.setItem('cards_per_row', this.cards_per_row);
            localStorage.setItem('sort_by', this.sort_by);
        },

        searchBackgroundColor: function() {
            if (! this.search) {
                return '#FFFFFF';
            } else {
                if (this.filteredApps.length) {
                    return '#FFFFBB';
                } else {
                    return '#FFC9D9';
                }
            }
        }
    },

    mounted () {
        window.addEventListener('keyup', function(event) {
            if (event.keyCode == 27) {
                app.selectApp();
            }
        });
    },

    data: {
        apps: {!! $apps !!},
        search: '',
        filter_open_source: false,
        cards_per_row: (isStorageSupported() ? localStorage.getItem('cards_per_row') : false) || 5,
        sort_by: (isStorageSupported() ? localStorage.getItem('sort_by') : false) || 'newest',
        selected_app: false,
    },

    computed: {

        modalClass() {
            if (this.selected_app) {
                return {'is-active': true};
            } else {
                return {};
            }
        },

        columnClass() {
            switch(+this.cards_per_row) {
                case 6:
                return {'is-6': true};
                case 5:
                return {'is-one-third': true};
                case 4:
                return {'is-one-quarter': true};
                case 3:
                return {'is-one-fifth': true};
                case 2:
                return {'is-2': true};
            }
        },

        filteredApps() {
            var apps = this.apps;
            var search = this.search.toLowerCase().trim();
            var filter_open_source = this.filter_open_source;
            var sort_by = this.sort_by;

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

            if (filter_open_source) {
                apps = apps.filter(function(item) {
                    return item.repo_url;
                });
            }

            apps.sort(function(itemA, itemB) {
                var timeA = new Date(itemA.created_at).getTime();
                var timeB = new Date(itemB.created_at).getTime();
                if (sort_by == 'oldest') {
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

@extends('master')

@section('title', 'A Showcase for Passionate Flutter Developers')
@section('description', request()->has('for_hire') ? 'Hire some of the best Flutter developers on the planet' : 'Profiles are sourced from FlutterX, Flutter Events and It\'s All Widgets!')
@section('image_url', asset('images/flutterpro_twitter.png'))

@section('header_title', 'A Showcase for Passionate Flutter Developers')
@section('header_button_url', iawUrl() . '/auth/google?intended_url=profile/edit')
@section('header_button_label', 'MANAGE PROFILE')
@section('header_button_icon', 'fas fa-user')

@section('header_subtitle')
    @if (request()->has('for_hire'))
        Hire some of the best <a href="https://flutter.dev" target="_blank">Flutter</a> developers on the planet
    @else
        Profiles are sourced from
        <a href="{{ fxUrl() }}">FlutterX</a>,
        <a href="{{ feUrl() }}">Flutter Events</a> and
        <a href="{{ iawUrl() }}">It's All Widgets!</a>
    @endif
@endsection

@section('head')

<style>

.profile-panel {
    background-color: white;
    border-radius: 8px;
    height: 420px;
}

.short-description {
    padding-left: 16px;
    padding-right: 16px;
    line-height: 1.5em;
    height: 5.3em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    text-overflow: ellipsis;
}


.fa-github,
.fa-twitter,
.fa-medium,
.fa-linkedin,
.fa-instagram,
.fa-youtube {
    color: #888 !important;
    font-size: 22px !important;
}

.fa-github:hover,
.fa-twitter:hover,
.fa-medium:hover,
.fa-linkedin:hover,
.fa-instagram:hover,
.fa-youtube:hover {
    color: black !important;
}

/*
.column {
padding: 1rem 1rem 4rem 1rem;
}
*/

</style>


@endsection


@section('content')


    <div id="app">

        <section class="hero is-light is-small is-body-font">
            <div class="hero-body">
                <div class="container">
                    <div class="field is-grouped is-grouped-multiline is-vertical-center">
                        <p class="control is-expanded has-icons-left">

                            <input v-model="search" class="input is-medium" type="text" placeholder="SEARCH"
                            autofocus="true" style="margin-top: 10px" v-bind:style="{ backgroundColor: searchBackgroundColor()}">
                            <span class="icon is-small is-left" style="margin-top: 10px">
                                <i class="fas fa-search"></i>
                            </span>

                            <div class="is-medium" v-on:click="togglePortfolio()" style="padding-left: 20px;">
                                <input type="checkbox" name="portfolioSwitch"
                                class="switch is-info" v-model="filter_portfolio">
                                <label for="portfolioSwitch" style="padding-top:6px; font-size: 16px">PORTFOLIO &nbsp;</label>
                            </div>

                            <div class="is-medium" v-on:click="toggleForHire()" style="padding-left: 20px;">
                                <input type="checkbox" name="forHireSwitch"
                                class="switch is-info" v-model="filter_for_hire">
                                <label for="openSourceSwitch" style="padding-top:6px; font-size: 16px">FOR HIRE &nbsp;</label>
                            </div>

                            <!--
                            <div class="is-medium filter-label" style="padding-left: 26px;">
                                <label class="label is-medium" style="font-weight: normal; font-size: 16px">PLATFORM</label>
                            </div>
                            <div class="select is-medium filter-control" style="padding-left: 14px; font-size: 16px">
                                <select v-model="filter_platform" onchange="$(this).blur()">
                                    <option value="">ALL</option>
                                    <option value="github">GitHub</option>
                                    <option value="youtube">YouTube</option>
                                    <option value="twitter">Twitter</option>
                                    <option value="medium">Medium</option>
                                    <option value="linkedin">LinkedIn</option>
                                    <option value="instagram">Instagram</option>
                                </select>
                            </div>
                            -->

                            <div class="is-medium filter-label" style="padding-left: 26px;">
                                <label class="label is-medium" style="font-weight: normal; font-size: 16px">SORT</label>
                            </div>
                            <div class="select is-medium filter-control" style="padding-left: 14px; font-size: 16px">
                                <select v-model="sort_by" onchange="$(this).blur()">
                                    <option value="sort_activity">ACTIVITY</option>
                                    <option value="sort_featured">FEATURED</option>
                                    <option value="sort_newest">NEWEST</option>
                                    <option value="sort_apps">APPS</option>
                                    <option value="sort_artifacts">RESOURCES</option>
                                    <option value="sort_events">EVENTS</option>
                                </select>
                            </div>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section is-body-font" style="background-color:#fefefe">

            <div class="container" v-cloak>
                <div v-if="filteredProfiles.length == 0 || is_searching"
                class="is-wide has-text-centered is-vertical-center"
                style="text-align:center; font-size: 32px; color: #AAA; padding-top: 150px; padding-bottom: 150px;">
                <span v-if="is_searching">Loading...</span>
                <span v-if="! is_searching">No developers found</span>
            </div>

            <div class="columns is-multiline is-6 is-variable" v-if="! is_searching">
                <div v-for="profile in filteredProfiles" :key="profile.id" class="column is-one-third">
                    <div v-on:click="selectProfile(profile)" style="cursor:pointer">
                        <div class="profile-panel is-hover-elevated has-text-centered">

                            <header style="padding-top: 25px">
                                <div style="height: 100px">
                                    <span v-if="profile.image_url">
                                        <img v-bind:src="profile.image_url + '?clear_cache=' + profile.updated_at"
                                        style="border-radius: 50%; width: 120px;"/>
                                    </span>
                                    <span v-if="!profile.image_url">
                                        <img src="/images/flutter_logo.png" style="width: 94px;"/>
                                    </span>
                                </div><br/>

                                <p class="no-wrap" style="font-size:22px; padding-top:18px; padding-bottom:14px;">
                                    @{{ profile.name }}
                                </p>
                                <div style="border-bottom: 2px #368cd5 solid; margin-left:40%; margin-right: 40%;"></div>
                            </header>

                            <div class="content" style="padding-left:16px; padding-right:16px; padding-top: 2px;">

                                <div class="short-description" style="padding-top:16px;">
                                    <a v-bind:href="profile.activity_link_url" target="_blank" v-on:click.stop rel="nofollow">
                                        @{{ profile.activity_link_title }}</a>

                                        • @{{ profile.activity_message }}
                                    </div>
                                </div>

                                <div style="padding-bottom:20px;">
                                    &nbsp;
                                    <span v-if="profile.github_url">
                                        <span class="icon-bug-fix" style="padding:12px">
                                            <a v-if="profile.github_url" v-bind:href="profile.github_url" target="_blank" v-on:click.stop rel="nofollow">
                                                <i class="fab fa-github"></i>
                                            </a>
                                        </span>
                                    </span>
                                    <span v-if="profile.youtube_url">
                                        <span class="icon-bug-fix" style="padding:12px">
                                            <a v-bind:href="profile.youtube_url" target="_blank" v-on:click.stop rel="nofollow">
                                                <i class="fab fa-youtube"></i>
                                            </a>
                                        </span>
                                    </span>
                                    <span v-if="profile.twitter_url">
                                        <span class="icon-bug-fix" style="padding:12px">
                                            <a v-bind:href="profile.twitter_url" target="_blank" v-on:click.stop rel="nofollow">
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                        </span>
                                    </span>
                                    <span v-if="profile.medium_url">
                                        <span class="icon-bug-fix" style="padding:12px">
                                            <a v-bind:href="profile.medium_url" target="_blank" v-on:click.stop rel="nofollow">
                                                <i class="fab fa-medium"></i>
                                            </a>
                                        </span>
                                    </span>
                                    <span v-if="profile.linkedin_url">
                                        <span class="icon-bug-fix" style="padding:12px">
                                            <a v-bind:href="profile.linkedin_url" target="_blank" v-on:click.stop rel="nofollow">
                                                <i class="fab fa-linkedin"></i>
                                            </a>
                                        </span>
                                    </span>
                                    <span v-if="profile.instagram_url">
                                        <span class="icon-bug-fix" style="padding:12px">
                                            <a v-bind:href="profile.instagram_url" target="_blank" v-on:click.stop rel="nofollow">
                                                <i class="fab fa-instagram"></i>
                                            </a>
                                        </span>
                                    </span>
                                </div>

                                <div style="color:#888; font-size:15px;">
                                    @{{ profile.counts }}
                                </div>

                            </div>

                        </div>
                        <p>&nbsp;</p>
                    </div>
                </div>


            </section>

            <center>

                <a class="button is-info is-slightly-elevated" v-on:click="adjustPage(-1)" v-if="page_number > 1">
                    <span class="icon-bug-fix">
                        <i style="font-size: 18px" class="fas fa-chevron-circle-left"></i> &nbsp;&nbsp;
                    </span>
                    Previous Page
                </a> &nbsp;
                <a class="button is-info is-slightly-elevated" v-on:click="adjustPage(1)" v-if="filteredProfiles.length == 12">
                    Next Page &nbsp;&nbsp;
                    <span>
                        <i style="font-size: 18px" class="fas fa-chevron-circle-right"></i>
                    </span>
                </a>
            </center>

            <div class="modal animated fadeIn" v-bind:class="modalClass" v-if="selected_profile">
                <div class="modal-background" v-on:click="selectProfile()"></div>
                <div class="modal-card is-body-font" style="padding:0" @click.stop>
                    <!--
                    <header class="modal-card-head">
                        <p class="modal-card-title">
                            @{{ selected_profile.name }}
                        </p>
                        <button class="delete" aria-label="close" v-on:click="selectProfile()"></button>
                    </header>
                    -->
                    <iframe sandbox="allow-scripts allow-same-origin allow-top-navigation allow-popups" v-bind:src="selected_profile.profile_url" allowTransparency="true"
                        loading="lazy" width="100%" height="800" frameBorder="0" style="border:none; overflow:hidden; background-color: white;"></iframe>
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

        function getCachedSortBy() {
            return (isStorageSupported() ? localStorage.getItem('pro_sort_by') : false) || 'sort_activity';
        }

        var app = new Vue({
            el: '#app',

            watch: {
                search: {
                    handler() {
                        app.serverSearch();
                    },
                },
                filter_platform: {
                    handler() {
                        app.serverSearch();
                    },
                },
                filter_portfolio: {
                    handler() {
                        app.serverSearch();
                    },
                },
                filter_for_hire: {
                    handler() {
                        app.serverSearch();
                    },
                },
                page_number: {
                    handler() {
                        app.serverSearch();
                    },
                },
                sort_by: {
                    handler() {
                        app.serverSearch();
                        app.saveFilters();
                    },
                },
            },

            methods: {

                toggleForHire: function() {
                    this.filter_for_hire = ! this.filter_for_hire;
                },

                togglePortfolio: function() {
                    this.filter_portfolio = ! this.filter_portfolio;
                },

                adjustPage: function(change) {
                    this.page_number += change;
                    document.body.scrollTop = 0; // For Safari
                    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
                },

                setFilter: function(filter) {
                    filter = filter || '';
                    this.selectProfile();
                    this.search = filter.toLowerCase();
                },

                saveFilters: function() {
                    if (! isStorageSupported()) {
                        return false;
                    }

                    localStorage.setItem('pro_sort_by', this.sort_by);
                },

                searchBackgroundColor: function() {
                    if (! this.search) {
                        return '#FFFFFF';
                    } else {
                        if (this.is_searching) {
                            return '#FFFFBB';
                        } else if (this.filteredProfiles.length) {
                            return '#FFFFBB';
                        } else {
                            return '#FFC9D9';
                        }
                    }
                },

                selectProfile: function(profile) {

                    if (document.body.clientWidth < 1000) {
                        if (app) {
                            window.location = '/' + profile.handle;
                        }
                    } else {
                        this.selected_profile = profile;

                        if (history.pushState) {
                            if (app) {
                                var route = '/' + (profile ? profile.handle : '');
                                gtag('config', '{{ $tracking_id }}', {'page_path': route});
                                history.pushState(null, null, route);
                            } else {
                                history.pushState(null, null, '{{ isTest() ? '/profiles' : '/' }}' );
                            }
                        }
                    }
                },

                serverSearch: function() {
                    var app = app || this;
                    var searchStr = this.search;
                    var profiles = this.profiles;
                    var sortBy = this.sort_by;
                    var page = this.page_number;
                    var platform = this.filter_platform;
                    var forHire = this.filter_for_hire;
                    var portfolio = this.filter_portfolio;

                    app.$set(app, 'is_searching', true);
                    if (this.bounceTimeout) clearTimeout(this.bounceTimeout);

                    this.bounceTimeout = setTimeout(function() {
                        var url = '/search_pro?counts=true&search='
                        + encodeURIComponent(searchStr)
                        + '&sort_by=' + sortBy
                        + '&page=' + page
                        + '&platform=' + platform;

                        if (forHire) {
                            url += '&for_hire=true';
                        }

                        if (portfolio) {
                            url += '&portfolio=true';
                        }

                        $.get(url,
                        function (data) {
                            app.$set(app, 'profiles', data);
                            app.$set(app, 'is_searching', false);
                        });
                    }, 500);
                },

            },

            beforeMount(){
                this.serverSearch();
            },

            mounted () {
                window.addEventListener('keyup', function(event) {
                    if (event.keyCode == 27) {
                        app.selectProfile();
                    }
                });
            },

            data: {
                profiles: [],
                search: "{{ request()->search }}",
                filter_for_hire: {{ request()->has('for_hire') ? 'true' : 'false' }},
                filter_portfolio: {{ request()->has('portfolio') ? 'true' : 'false' }},
                sort_by: getCachedSortBy(),
                selected_profile: false,
                page_number: 1,
                filter_platform: '',
                is_searching: false,
            },

            computed: {

                modalClass() {
                    if (this.selected_profile) {
                        return {'is-active': true};
                    } else {
                        return {};
                    }
                },

                filteredProfiles() {

                    var profiles = this.profiles;
                    var search = this.search.toLowerCase().trim();
                    var sort_by = this.sort_by;

                    if (search) {
                        profiles = profiles.filter(function(item) {

                            return true;
                        });
                    }

                    profiles.sort(function(itemA, itemB) {
                        var timeA = false;//new Date(itemA.created_at).getTime();
                        var timeB = false;//new Date(itemB.created_at).getTime();

                        if (sort_by == 'sort_newest') {
                            return timeB - timeA;
                        } else {
                        }
                    });

                    return profiles;
                },
            }

        });

        </script>

    @endsection

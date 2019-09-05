@extends('master')

@section('title', 'Flutter Pro')
@section('description', 'A Showcase for Passionate Flutter Developers')
@section('image_url', asset('images/background.jpg'))

@section('header_title', 'A Showcase for Passionate Flutter Developers')
@section('header_button_url', iawUrl() . '/auth/google?intended_url=profile/edit')
@section('header_button_label', 'MANAGE PROFILE')
@section('header_button_icon', 'fas fa-user')

@section('header_subtitle')
    Profiles are sourced from
    <a href="{{ fxUrl() }}">FlutterX</a>,
    <a href="{{ feUrl() }}">Flutter Events</a> and
    <a href="{{ iawUrl() }}">It's All Widgets!</a>
@endsection

@section('head')

<style>

.profile-panel {
    background-color: white;
    border-radius: 8px;
    height: 360px;
}

.short-description {
    padding-left: 16px;
    padding-right: 16px;
    line-height: 1.5em;
    height: 5em;
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
.fa-youtube {
    color: #888 !important;
    font-size: 22px !important;
}

.fa-github:hover,
.fa-twitter:hover,
.fa-medium:hover,
.fa-linkedin:hover,
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

                            <div class="is-medium filter-label" style="padding-left: 26px;">
                                <label class="label is-medium" style="font-weight: normal; font-size: 16px">SORT</label>
                            </div>
                            <div class="select is-medium filter-control" style="padding-left: 14px; font-size: 16px">
                                <select v-model="sort_by" onchange="$(this).blur()">
                                    <option value="sort_featured">FEATURED</option>
                                    <option value="sort_newest">NEWEST</option>
                                </select>
                            </div>

                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section is-body-font" style="background-color:#fefefe">

            <div class="container" v-cloak>
                <div v-if="filteredProfiles.length == 0" class="is-wide has-text-centered is-vertical-center"
                style="height:400px; text-align:center; font-size: 32px; color: #AAA">
                <span v-if="is_searching">Loading...</span>
                <span v-if="! is_searching">No developers found</span>
            </div>

            <div class="columns is-multiline is-6 is-variable">
                <div v-for="profile in filteredProfiles" :key="profile.id" class="column is-one-third">
                    <div v-on:click="selectProfile(profile)" style="cursor:pointer">
                        <div class="profile-panel is-hover-elevated has-text-centered">

                            <header style="padding-top: 25px">
                                <div style="height: 100px">
                                    <img v-bind:src="'/avatars/' + profile.id + '.png'" style="border-radius: 50%; width: 120px;"
                                    onerror="this.onerror=null; this.src='/images/flutter_logo.png'; this.style['border-radius'] = 0; this.style.width = '96px'"/>
                                </div><br/>

                                <p class="no-wrap" style="font-size:22px; padding-top:20px; padding-bottom:8px;">
                                    @{{ profile.name }}
                                </p>
                                <div style="border-bottom: 2px #368cd5 solid; margin-left:40%; margin-right: 40%;"></div>
                            </header>

                            <div class="content" style="padding-left:16px; padding-right:16px; padding-top: 0px;">

                                <div class="short-description" style="padding-top:16px;">
                                    <a v-bind:href="profile.activity_link_url" target="_blank" v-on:click.stop rel="nofollow">
                                        @{{ profile.activity_link_title }}</a>

                                        • @{{ profile.activity_message }}
                                    </div>
                                </div>

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
                            </div>

                        </div>
                        <p>&nbsp;</p>
                    </div>
                </div>


            </section>

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
            return (isStorageSupported() ? localStorage.getItem('pro_sort_by') : false) || 'sort_featured';
        }

        var app = new Vue({
            el: '#app',

            watch: {
                search: {
                    handler() {
                        app.serverSearch();
                    },
                },
                sort_by: {
                    handler() {
                        app.saveFilters();
                    },
                },
            },

            methods: {

                adjustPage: function(change) {
                    this.page_number += change;
                    document.body.scrollTop = 0; // For Safari
                    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
                },

                setFilter: function(filter) {
                    filter = filter || '';
                    this.search = filter.toLowerCase();
                },

                saveFilters: function() {
                    if (! isStorageSupported()) {
                        return false;
                    }

                    localStorage.setItem('sort_by', this.sort_by);
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

                serverSearch: function() {
                    var app = app || this;
                    var searchStr = this.search;
                    var profiles = this.profiles;

                    app.$set(app, 'is_searching', true);
                    if (this.bounceTimeout) clearTimeout(this.bounceTimeout);

                    this.bounceTimeout = setTimeout(function() {
                        if (!profiles.length || (searchStr && searchStr.length >= 3)) {
                            $.get('/search_pro?search=' + encodeURIComponent(searchStr), function (data) {
                                console.log(data);
                                app.$set(app, 'profiles', data);
                                app.$set(app, 'is_searching', false);
                            });
                        } else {
                            app.$set(app, 'is_searching', false);
                            for (var i=0; i<profiles.length; i++) {
                                //app.$set(artifacts[i], 'contents', '');
                            }
                        }

                    }, 500);
                },

            },

            beforeMount(){
                this.serverSearch();
            },

            mounted () {
                window.addEventListener('keyup', function(event) {
                    if (event.keyCode == 27) {
                        //app.selectApp();
                    }
                });
            },

            data: {
                profiles: [],
                search: "{{ request()->search }}",
                sort_by: getCachedSortBy(),
                selected_profile: false,
                page_number: 1,
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

                unpaginatedFilteredProfiles() {

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

                filteredProfiles() {

                    profiles = this.unpaginatedFilteredProfiles;

                    var startIndex = (this.page_number - 1) * 40;
                    var endIndex = startIndex + 40;
                    profiles = profiles.slice(startIndex, endIndex);

                    return profiles;
                },
            }

        });

        </script>

    @endsection

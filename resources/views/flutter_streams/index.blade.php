@extends('master')

@section('title', 'An Open List of Flutter Live Streams')
@section('description', 'Live streams are sourced from YouTube')
@section('image_url', asset('images/flutterpro_twitter.png'))

@section('header_title', 'An Open List of Flutter Live Streams')
@section('header_button_url', 'https://twitter.com/FlutterStreams')
@section('header_button_label', 'FOLLOW US')
@section('header_button_icon', 'fab fa-twitter')
@section('header_subtitle', 'Add #FlutterStream to the title to add your stream')

@section('head')

<style>

.stream-panel {
    background-color: white;
    border-radius: 8px;
    height: 550px;
}

.stream-panel a {
    color: black;
}

</style>

@endsection

@section('content')

    <div id="app">
        <section class="hero is-light is-small is-body-font">
            <div class="hero-body">
                <div class="container">
                    <div class="field is-grouped is-grouped-multiline is-vertical-center">
                        <p class="control is-expanded has-icons-left">

                            <input v-model="search" class="input is-medium" type="text" placeholder="Search {{ $count }} live streams..."
                            autofocus="true" style="margin-top: 10px" v-bind:style="{ backgroundColor: searchBackgroundColor()}">
                            <span class="icon is-small is-left" style="margin-top: 10px">
                                <i class="fas fa-search"></i>
                            </span>


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

        @if ($banner)
            <br/>
            <div class="container" v-cloak>
                <div class="notification is-info">
                    {!! $banner !!}
                </div>
            </div>
        @endif

        <section class="section is-body-font" style="background-color:#fefefe">

            <div class="container" v-cloak>
                <div v-if="filteredStreams.length == 0 || is_searching"
                class="is-wide has-text-centered is-vertical-center"
                style="text-align:center; font-size: 32px; color: #AAA; padding-top: 150px; padding-bottom: 150px;">
                <span v-if="is_searching">Loading...</span>
                <span v-if="! is_searching">No streams found</span>
            </div>

            <div class="columns is-multiline is-6 is-variable" v-if="! is_searching">
                <div v-for="stream in filteredStreams" :key="stream.id" class="column is-one-third">
                    <div v-on:click="selectStream(stream)" style="cursor:pointer">
                        <div class="stream-panel is-hover-elevated">

                            <div>
                                <div v-if="stream.image_url">
                                    <img v-bind:src="stream.image_url + '?clear_cache=' + stream.updated_at"/>
                                </div>
                                <span v-if="!stream.image_url">
                                    <img src="/images/flutter_logo.png" style="width: 94px;"/>
                                </span>
                            </div><br/>

                            <table>
                                <tr>
                                    <td width="40">
                                        <div v-if="stream.channel_image_url">
                                            <img v-bind:src="stream.channel_image_url + '?clear_cache=' + stream.updated_at" style="width: 40px; height: 40px;"/>
                                        </div>
                                        <div v-if="!stream.image_url">
                                            <img src="/images/flutter_logo.png" style="width: 40px; height: 40px;"/>
                                        </div>

                                    </td>
                                    <td>
                                        <p style="font-size:22px; padding-bottom:14px; padding-left: 16px; padding-right: 16px;">
                                            @{{ stream.name }}
                                        </p>

                                        <div style="padding-left: 16px; padding-right: 16px;">
                                            <div style="border-bottom: 2px #368cd5 solid; width: 50px;"/></div>
                                        </div>

                                        <div class="content" style="padding-left:16px; padding-right:16px; padding-top: 2px;">

                                            <div style="padding-top:20px;">
                                                <a v-bind:href="'https://www.youtube.com/channel/' + stream.channel_id" target="_blank" v-on:click.stop>
                                                    @{{ stream.channel_name }}
                                                </a>
                                            </div>

                                            <div style="color:#888; font-size:15px;">
                                                @{{ stream.counts }}
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            </table>


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
                <a class="button is-info is-slightly-elevated" v-on:click="adjustPage(1)" v-if="filteredStreams.length == 12">
                    Next Page &nbsp;&nbsp;
                    <span>
                        <i style="font-size: 18px" class="fas fa-chevron-circle-right"></i>
                    </span>
                </a>
            </center>

            <div class="modal animated fadeIn" v-bind:class="modalClass" v-if="selected_stream">
                <div class="modal-background" v-on:click="selectStream()"></div>

                <div class="navigation-button prev-navigation-button" BAK-v-if="hasPrev">
                    <button class="button is-medium is-rounded" v-on:click="movePrev()">
                        <span class="icon">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    </button>
                </div>
                <div class="navigation-button next-navigation-button" BAK-v-if="hasNext">
                    <button class="button is-medium is-rounded" v-on:click="moveNext()">
                        <span class="icon">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </button>
                </div>

                <div class="modal-card" style="padding:0; width:900px" @click.stop>
                    <iframe width="1000" height="500" v-bind:src="selected_stream.embed_url" frameborder="0"
                        allow="accelerometer; autoplay; encrypted-media;" allowfullscreen></iframe>
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

                adjustPage: function(change) {
                    this.page_number += change;
                    document.body.scrollTop = 0; // For Safari
                    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
                },

                setFilter: function(filter) {
                    filter = filter || '';
                    this.selectStream();
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
                        } else if (this.filteredStreams.length) {
                            return '#FFFFBB';
                        } else {
                            return '#FFC9D9';
                        }
                    }
                },

                selectStream: function(stream) {
                    if (document.body.clientWidth < 1000) {
                        if (app) {
                            window.location = '/' + stream.handle;
                        }
                    } else {
                        this.selected_stream = stream;

                        if (history.pushState) {
                            if (app) {
                                var route = '/' + (stream ? stream.handle : '');
                                gtag('config', '{{ $tracking_id }}', {'page_path': route});
                                history.pushState(null, null, route);
                            } else {
                                history.pushState(null, null, '{{ isTest() ? '/streams' : '/' }}' );
                            }
                        }
                    }
                },

                serverSearch: function() {
                    var app = app || this;
                    var searchStr = this.search;
                    var streams = this.streams;
                    var sortBy = this.sort_by;
                    var page = this.page_number;
                    var platform = this.filter_platform;

                    app.$set(app, 'is_searching', true);
                    if (this.bounceTimeout) clearTimeout(this.bounceTimeout);

                    this.bounceTimeout = setTimeout(function() {
                        var url = '/search_streams?counts=true&search='
                        + encodeURIComponent(searchStr)
                        + '&sort_by=' + sortBy
                        + '&page=' + page
                        + '&platform=' + platform;

                        $.get(url,
                        function (data) {
                            app.$set(app, 'streams', data);
                            app.$set(app, 'is_searching', false);
                        });
                    }, 500);
                },


                moveNext() {
                    var streams = this.filteredStreams;
                    var index = streams.indexOf(this.selected_stream);
                    this.selectStream(streams[index + 1]);
                },

                movePrev() {
                    var streams = this.filteredStreams;
                    var index = streams.indexOf(this.selected_stream);
                    this.selectStream(streams[index - 1]);
                },

            },

            beforeMount(){
                this.serverSearch();
            },

            mounted () {
                window.addEventListener('keyup', function(event) {
                    if (event.keyCode == 27) {
                        app.selectStream();
                    } else if (event.keyCode == 39) {
                        app.moveNext();
                    } else if (event.keyCode == 37) {
                        app.movePrev();
                    }
                });
            },

            data: {
                streams: [],
                search: "{{ request()->search }}",
                sort_by: getCachedSortBy(),
                selected_stream: false,
                page_number: 1,
                filter_platform: '',
                is_searching: false,
            },

            computed: {

                modalClass() {
                    if (this.selected_stream) {
                        return {'is-active': true};
                    } else {
                        return {};
                    }
                },

                filteredStreams() {

                    var streams = this.streams;
                    var search = this.search.toLowerCase().trim();
                    var sort_by = this.sort_by;

                    if (search) {
                        streams = streams.filter(function(item) {

                            return true;
                        });
                    }

                    streams.sort(function(itemA, itemB) {
                        var timeA = false;//new Date(itemA.created_at).getTime();
                        var timeB = false;//new Date(itemB.created_at).getTime();

                        if (sort_by == 'sort_newest') {
                            return timeB - timeA;
                        } else {
                        }
                    });

                    return streams;
                },
            }

        });

        </script>

    @endsection

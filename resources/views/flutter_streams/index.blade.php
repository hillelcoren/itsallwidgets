@extends('master')

@section('title', 'An Open List of Flutter Live Streams')
@section('description', 'Live streams are sourced from YouTube')
@section('image_url', asset('images/flutterpro_twitter.png'))

@section('header_title', 'An Open List of Flutter Live Streams')
@section('header_button_url', 'https://twitter.com/FlutterStreams')
@section('header_button_label', 'FOLLOW US')
@section('header_button_icon', 'fab fa-twitter')
@section('header_subtitle', 'Streams are sourced from the YouTube API (Twitch coming soon)')

@section('head')

<style>

.stream-panel {
    background-color: white;
    border-radius: 8px;
    padding-bottom: 12px;
}

.stream-name {
    font-size: 18px;
    line-height: 1.5em;
    height: 3.2em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    text-overflow: ellipsis;
}

.channel-name {
    line-height: 1.5em;
    height: 2.0em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    text-overflow: ellipsis;
}

.stream-panel a {
    color: black;
}

.stream-panel .content a {
    color: #000; /* Fallback for older browsers */
    color: rgba(0, 0, 0, 0.6);
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

                            <div class="is-medium" v-on:click="toggleEnglish()" style="padding-left: 35px; padding-right: 10px;">
                                <input type="checkbox" name="englishSwitch"
                                class="switch is-info" v-model="filter_english">
                                <label for="englishSwitch" style="padding-top:6px; font-size: 16px">ENGLISH &nbsp;</label>
                            </div>

                            <div class="is-medium filter-label" style="padding-left: 26px;">
                                <label class="label is-medium" style="font-weight: normal; font-size: 16px">SORT</label>
                            </div>
                            <div class="select is-medium filter-control" style="padding-left: 14px; font-size: 16px">
                                <select v-model="sort_by" onchange="$(this).blur()">
                                    <option value="sort_newest">NEWEST</option>
                                    <option value="sort_views">VIEWS</option>
                                </select>
                            </div>

                            <!--
                            <div style="padding-left: 20px">
                                <a class="button is-white is-slightly-elevated" href="{{ feUrl() }}/feed" target="_blank">
                                    <i style="font-size: 20px" class="fas fa-rss"></i> &nbsp;
                                    SCHEDULE
                                </a>
                            </div>
                            -->

                            <div style="padding-left: 35px">
                                <a class="button is-white is-slightly-elevated" href="{{ fsUrl() }}/feed" target="_blank">
                                    <i style="font-size: 20px" class="fas fa-rss"></i> &nbsp; &nbsp;
                                    FEED
                                </a>
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
                                <img v-bind:src="'streams/stream-' + stream.id + '.jpg?clear_cache=' + stream.updated_at" style="width: 100%; object-fit: fill;"/>
                                <!--
                                <div v-if="stream.image_url">
                                    <img v-bind:src="stream.image_url + '?clear_cache=' + stream.updated_at" style="width: 100%; object-fit: fill;"/>
                                </div>
                                <span v-if="!stream.image_url">
                                    <img src="/images/flutter_logo.png" style="width: 94px;"/>
                                </span>
                                -->
                            </div>

                            <div style="padding-left: 16px; padding-right: 16px; padding-top: 8px;">
                            <table>
                                <tr>
                                    <td width="50">
                                        <div v-if="stream.channel_image_url">
                                            <a v-bind:href="'https://www.youtube.com/channel/' + stream.channel_id" target="_blank" v-on:click.stop>
                                                <img v-bind:src="stream.channel_image_url + '?clear_cache=' + stream.updated_at" style="width: 40px; height: 40px; border-radius: 50%;"/>
                                            </a>
                                        </div>
                                        <div v-if="!stream.image_url">
                                            <img src="/images/flutter_logo.png" style="width: 50px; height: 50px; border-radius: 50%;"/>
                                        </div>
                                    </td>
                                    <td width="100%">
                                        <div style="padding-left: 8px; padding-right: 8px">
                                            <p class="stream-name">
                                                @{{ stream.name }}
                                            </p>

                                            <div style="padding-top:8px; border-bottom: 2px #368cd5 solid; width: 50px;"/></div>

                                            <div class="content">
                                                <div style="padding-top:14px;" class="channel-name">
                                                    <a v-bind:href="'https://www.youtube.com/channel/' + stream.channel_id" target="_blank" v-on:click.stop>
                                                        @{{ stream.channel_name }}
                                                    </a>
                                                </div>

                                                <div style="color:#888; font-size:15px; padding-top: 10px;" class="channel-name">
                                                    @{{ stream.time_ago }}

                                                    <!--
                                                    <span v-if="!stream.is_upcoming">
                                                        • @{{ stream.view_count }} views
                                                    </span>
                                                    -->

                                                    <div v-if="stream.is_upcoming" class="is-pulled-right">
                                                        <span class="tag is-info">Upcoming</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
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
                <a class="button is-info is-slightly-elevated" v-on:click="adjustPage(1)" v-if="filteredStreams.length == 12 &amp;&amp; {{ $count }} > 12">
                    Next Page &nbsp;&nbsp;
                    <span>
                        <i style="font-size: 18px" class="fas fa-chevron-circle-right"></i>
                    </span>
                </a>
            </center>

            <div class="modal animated fadeIn" v-bind:class="modalClass" v-if="selected_stream">
                <div class="modal-background" v-on:click="selectStream()" style="z-index:0"></div>
                <div style="z-index:1">
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

                    <div @click.stop>
                        <iframe v-bind:src="selected_stream.embed_url" frameborder="0"
                            style="width: 1280px; height: 720px;"
                            allow="accelerometer; autoplay; encrypted-media;" allowfullscreen></iframe>
                    </div>
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
            return (isStorageSupported() ? localStorage.getItem('streams_sort_by') : false) || 'sort_newest';
        }

        function getCachedEnglish() {
            return (isStorageSupported() ? localStorage.getItem('streams_english') : false) || false;
        }

        var app = new Vue({
            el: '#app',

            watch: {
                search: {
                    handler() {
                        app.serverSearch();
                    },
                },
                filter_source: {
                    handler() {
                        app.serverSearch();
                    },
                },
                filter_english: {
                    handler() {
                        app.serverSearch();
                        app.saveFilters();
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

                toggleEnglish: function() {
                    this.filter_english = ! this.filter_english;
                },

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

                    localStorage.setItem('streams_sort_by', this.sort_by);
                    localStorage.setItem('streams_english', this.filter_english);
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
                            window.open(stream.video_url, '_blank');
                        }
                    } else {
                        this.selected_stream = stream;

                        if (history.pushState) {
                            if (app) {
                                var route = '/' + (stream ? stream.slug : '');
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
                    var source = this.filter_source;
                    var isEnglish = this.filter_english;

                    app.$set(app, 'is_searching', true);
                    if (this.bounceTimeout) clearTimeout(this.bounceTimeout);

                    this.bounceTimeout = setTimeout(function() {
                        var url = '/search_streams?counts=true&search='
                        + encodeURIComponent(searchStr)
                        + '&sort_by=' + sortBy
                        + '&page=' + page
                        + '&source=' + source
                        + '&is_english=' + (isEnglish ? '1' : '');

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
                filter_english: getCachedEnglish(),
                selected_stream: false,
                page_number: 1,
                filter_source: '',
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

                    /*
                    streams.sort(function(itemA, itemB) {
                        if (sort_by == 'sort_newest') {
                            return timeB - timeA;
                        } else {

                        }
                    });
                    */

                    return streams;
                },
            }

        });

        </script>

    @endsection

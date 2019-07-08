@extends('master')

@section('title', 'Flutter Events')
@section('description', 'An Open List of Flutter Events')
@section('image_url', asset('images/events_twitter.png'))
@section('header_title', 'An Open List of Flutter Events')
@section('header_button_url', iawUrl() . '/' . (auth()->check() ? 'flutter-event/submit' : 'auth/google?intended_url=flutter-event/submit'))
@section('header_button_label', 'SUBMIT EVENT')

@section('header_subtitle')
    Events are synced with <a href="https://www.meetup.com/find/?allMeetups=false&keywords=flutter&radius=Infinity" target="_blank">Meetup.com</a> or can be added manually
@endsection

@section('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"
       integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
       crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"
     integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og=="
     crossorigin=""></script>
    <script src="{{ asset('js/bouncemarker.js') }}"></script>

@endsection

@section('content')

    <style>

    #map { height: 300px; }

    div.event-links > a:hover {
        text-decoration: underline;
        color: #368cd5;
    }

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
        z-index: 999999;
        -webkit-animation-duration: .5s;
        -moz-animation-duration: .5s;
    }

    .modal-card {
        width: 80%;
    }

    [v-cloak] {
        display: none;
    }

    .short-address {
        line-height: 1.5em;
        height: 1.5em;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        text-overflow: ellipsis;
    }

    .short-description {
        line-height: 1.5em;
        height: 9em;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        text-overflow: ellipsis;
    }

    .is-owned {
        xbackground-color: #FFFFAA;
        background-color: #FFFFFF;
    }

    .flutter-event {
        background-color: white;
        border-radius: 8px;
    }

    .flutter-event .is-hover-visible {
        display: none;
    }

    .flutter-event:hover .is-hover-visible {
        display: flex;
    }

    .flutter-event a {
        color: #368cd5;
    }

    .columns.is-variable.is-6 {
        --columnGap: 1.2rem;
    }

    .column {
        padding: 1rem 1rem 2rem 1rem;
    }



    @media screen and (max-width: 788px) {
        .slider-control {
            display: none;
        }
    }

    @media screen and (max-width: 769px) {
        .store-buttons img {
            max-width: 200px;
        }


        /*
        .is-hover-elevated {
            -moz-filter: drop-shadow(0px 16px 16px #CCC);
            -webkit-filter: drop-shadow(0px 16px 16px #CCC);
            -o-filter: drop-shadow(0px 16px 16px #CCC);
            filter: drop-shadow(0px 16px 16px #CCC);
        }
        */
    }

    </style>


    <script>

    var markerList = [
        @foreach ($events as $event)
            {{ $event->id}},
        @endforeach
    ];
    var markerMap = {
        @foreach ($events as $event)
            {{ $event->id}}: ['{{ $event->eventLink() }}<br/>{{ substr(strip_tags($event->description), 0, 150) }}...', {{ $event->latitude }}, {{ $event->longitude }}, {{ $event->distance }}],
        @endforeach
    };

    $(function() {
        @if (false && $hasLocation)
            var map = L.map('map').setView([{{ cache(\Request::getClientIp() . '_latitude') ?: '26' }}, {{ cache(\Request::getClientIp() . '_longitude') ?: '0' }}], 2);
        @else
            var map = L.map('map').setView([26, 0], 2);
        @endif
        mapLink = '<a href="http://openstreetmap.org">OpenStreetMap</a>';

        window.layerGroup = L.layerGroup().addTo(map);

        window.tileLayer = L.tileLayer(
            'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; ' + mapLink + ' Contributors',
                maxZoom: 18,
            }).addTo(map);

            @if (! filter_var(request()->study_jams, FILTER_VALIDATE_BOOLEAN))
                setTimeout(function() {
                    var nearestMarker = false;
                    var nearestMarkerDistance = 0;
                    for (var i = 0; i < markerList.length; i++) {
                        var data = markerMap[markerList[i]];
                        marker = new L.marker([data[1],data[2]], {
                            bounceOnAdd: true,
                            bounceOnAddOptions: {
                                duration: 500 + Math.floor(Math.random() * 801),
                                height: 150 + Math.floor(Math.random() * 301),
                            },
                        })
                        .bindPopup(data[0])
                        .addTo(layerGroup);

                        if (data[3] < nearestMarkerDistance) {
                            nearestMarker = marker;
                            nearestMarkerDistance = data[3];
                        }
                    }

                    if (nearestMarker) {
                        nearestMarker.openPopup();
                    }
                }, 400);
            @endif
        })

        function updateMapMarkers(events) {
            window.layerGroup.clearLayers();
            var events = app.filteredEvents;

            for (var i = 0; i < events.length; i++) {
                var data = markerMap[events[i].id];
                marker = new L.marker([data[1],data[2]], {
                    bounceOnAdd: true,
                    bounceOnAddOptions: {
                        duration: 500 + Math.floor(Math.random() * 801),
                        height: 150 + Math.floor(Math.random() * 301),
                    },
                })
                .bindPopup(data[0])
                .addTo(layerGroup);
            }

        }

    </script>

<div id="event">

    <div id="map"></div>

    <section class="hero is-light is-small is-body-font">
        <div class="hero-body">
            <div class="container">
                <div class="field is-grouped is-grouped-multiline is-vertical-center">
                    <p class="control is-expanded has-icons-left">
                        <input v-model="search" class="input is-medium" type="text" v-bind:placeholder="'Search ' + unpaginatedFilteredEvents.length.toLocaleString() + ' upcoming events...'"
                            autofocus="true" style="margin-top: 10px" v-bind:style="{ backgroundColor: searchBackgroundColor()}">
                        <span class="icon is-small is-left" style="margin-top: 10px">
                            <i class="fas fa-search"></i>
                        </span>

                        <div class="is-medium" v-on:click="toggleStudyJams()" style="padding-left: 20px; padding-right: 20px;">
                            <input type="checkbox" name="openSourceSwitch"
                            class="switch is-info" v-model="filter_study_jams">
                            <label for="openSourceSwitch" style="padding-top:6px; font-size: 16px">STUDY JAMS &nbsp;</label>
                        </div>

                        @if ($hasLocation)
                            <div class="is-medium filter-label">
                                <label class="label is-medium" style="font-weight: normal; font-size: 16px"> DISTANCE </label>
                            </div>
                            <div class="select is-medium filter-control" style="font-size: 16px;">
                                <select v-model="filter_distance" onchange="$(this).blur()">
                                    <option value="filter_distance_any"></option>
                                    <option value="filter_distance_50">50 miles</option>
                                    <option value="filter_distance_100">100 miles</option>
                                    <option value="filter_distance_250">250 miles</option>
                                    <option value="filter_distance_500">500 miles</option>
                                </select>
                            </div>
                        @endif
                        <div class="is-medium filter-label">
                            <label class="label is-medium" style="font-weight: normal; font-size: 16px"> SORT </label>
                        </div>
                        <div class="select is-medium filter-control" style="font-size: 16px; margin-right:50px">
                            <select v-model="sort_by" onchange="$(this).blur()">
                                <option value="sort_date">DATE</option>
                                <option value="sort_attending">ATTENDING</option>
                                @if ($hasLocation)
                                    <option value="sort_distance">DISTANCE</option>
                                @endif
                            </select>
                        </div>
                        <div>
                            <a class="button is-white is-slightly-elevated" href="{{ feUrl() }}/feed" target="_blank">
                                <i style="font-size: 20px" class="fas fa-rss"></i> &nbsp;
                                EVENT FEED
                            </a>
                        </div>
                    </p>
                </div>
            </div>
        </div>
    </section>


<div class="zcontainer">
<section class="section is-body-font" style="background-color:#fefefe">
    <div class="container" v-cloak>
        <div v-if="filteredEvents.length == 0" class="is-wide has-text-centered is-vertical-center"
        style="height:400px; text-align:center; font-size: 32px; color: #AAA">
        No events found
    </div>
    <div class="columns is-multiline is-6 is-variable">
        <div v-for="event in filteredEvents" :key="event.id" class="column is-one-third">
            <div v-on:click="selectEvent(event)" style="cursor:pointer">
                <div class="flutter-event is-hover-elevated" v-bind:class="[event.user_id == {{ auth()->check() ? auth()->user()->id : '0' }} ? 'is-owned' : '']">

                    <header style="padding: 16px">

                        <p class="no-wrap" v-bind:title="event.title" style="font-size:22px; padding-bottom:10px;">

                            @{{ event.event_name }}

                        </p>
                        <div style="border-bottom: 2px #368cd5 solid; width: 50px"/>

                    </header>

                    <div class="content" style="padding-left:16px; padding-right:16px; padding-top: 4px;">
                        <div class="short-address" style="color: #666; font-size: 15px; padding-bottom: 36px;">
                            @{{ event.pretty_event_date }} • @{{ event.location }}
                        </div>

                        <div class="short-description" v-bind:title="event.short_description">
                            @{{ event.text_description }}
                        </div>

                        <div style="height:250px;padding-top:20px;">
                            <img v-if="event.image_url" v-bind:src="event.image_url + '?updated_at=' + event.updated_at" width="100%" style="max-height:250px"/>
                        </div>

                        <div style="color:#666; padding-top:30px">
                            @{{ event.rsvp_yes > 0 ? event.rsvp_yes + ' Attending' : '' }}
                            @{{ event.rsvp_yes > 0 && event.rsvp_limit > 0 ? '•' : '' }}
                            @{{ event.rsvp_limit > 0 ? event.rsvp_limit + ' Limit' : '' }}
                            @{{ event.rsvp_limit > 0 && event.rsvp_waitlist > 0 ? '•' : '' }}
                            @{{ event.rsvp_waitlist > 0 ? event.rsvp_waitlist + ' Waitlisted' : '' }}
                        </div>

                        <div class="event-links" style="font-size:13px; padding-top:16px; padding-bottom:16px">
                            <a v-bind:href="event.event_url" target="_blank" v-on:click.stop target="_blank" rel="nofollow">
                                VIEW EVENT
                            </a>
                            <span style="color:#CCCCCC">
                                &nbsp; | &nbsp;
                            </span>
                            <a v-bind:href="'https://www.google.com/maps/search/?api=1&query=' + event.latitude + ',' + event.longitude" target="_blank" v-on:click.stop target="_blank" rel="nofollow">
                                VIEW MAP
                            </a>

                            <span v-if="event.user_id == {{ auth()->check() ? auth()->user()->id : '0' }} || {{ auth()->check() && auth()->user()->is_admin ? 'true' : 'false' }}">
                                <span style="color:#CCCCCC">
                                    &nbsp; | &nbsp;
                                </span>
                                <a v-bind:href="'{{ iawUrl() }}/flutter-event/' + event.slug + '/edit'" target="_blank" v-on:click.stop target="_blank" rel="nofollow">
                                    EDIT EVENT
                                </a>
                            </span>
                        </div>

                    </div>
                </div>
            </div>
            <p>&nbsp;</p>
        </div>
    </div>
</div>
</section>
</div>


<div class="modal animated fadeIn" v-bind:class="modalClass" v-if="selected_event">
<div class="modal-background" v-on:click="selectEvent()"></div>
<div class="modal-card is-body-font">
    <header class="modal-card-head">
        <p class="modal-card-title"></p>
        <button class="delete" aria-label="close" v-on:click="selectEvent()"></button>
    </header>
    <section class="modal-card-body" @click.stop>

        <div class="columns">
            <div class="column is-8">

                @if (auth()->check())
                    <div v-if="selected_event.user_id == {{ auth()->user()->id }}">
                        <a class="button is-info is-slightly-elevated" v-bind:href="'/flutter-event/' + selected_event.slug + '/edit'">
                            <i style="font-size: 20px" class="fas fa-edit"></i> &nbsp;
                            Edit Event
                        </a>
                        <p>&nbsp;</p>
                    </div>
                @endif

                <div class="content">
                    <div style="font-size:24px; padding-bottom:10px;">
                        @{{ selected_event.event_name }}
                    </div>

                    <div style="border-bottom: 2px #368cd5 solid; width: 50px;"></div><br/>

                    <div class="subtitle">
                        @{{ selected_event.pretty_event_date }} • @{{ selected_event.location }}
                    </div>

                    <div class="content">
                        <a v-bind:href="selected_event.event_url" target="_blank" rel="nofollow">
                            @{{ selected_event.event_url }}
                        </a>
                    </div>

                    <div class="content">
                        <div class="dropdown is-hoverable">
                            <div class="dropdown-trigger is-slightly-elevated">
                                <button class="button" aria-haspopup="true" aria-controls="dropdown-menu4">
                                    <span>
                                        <i style="font-size: 20px" class="fa fa-share"></i> &nbsp;
                                        Share Event
                                    </span>
                                    <span class="icon is-small">
                                        <i class="fas fa-angle-down" aria-hidden="true"></i>
                                    </span>
                                </button>
                            </div>
                            <div class="dropdown-menu" role="menu">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=#url" target="_blank" rel="nofollow">
                                    <div class="dropdown-content">
                                        <div class="dropdown-item">
                                            <i style="font-size: 20px" class="fab fa-facebook"></i> &nbsp; Facebook
                                        </div>
                                    </div>
                                </a>
                                <a v-bind:href="'https://twitter.com/share?text=' + encodeURIComponent(selected_event.title) + '&amp;url=' + encodeURIComponent('{{ url('/flutter-event') }}' + '/' + selected_event.slug)" target="_blank" rel="nofollow">
                                    <div class="dropdown-content">
                                        <div class="dropdown-item">
                                            <i style="font-size: 20px" class="fab fa-twitter"></i> &nbsp; Twitter
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                    </div>

                    <span class="block wrap">@{{ selected_event.text_description }}</span>

                </div>
            </div>
        </div>

    </div>


</section>
</div>

<center>

<a class="button is-info is-slightly-elevated" v-on:click="adjustPage(-1)" v-if="page_number > 1">
    <span class="icon-bug-fix">
        <i style="font-size: 18px" class="fas fa-chevron-circle-left"></i> &nbsp;&nbsp;
    </span>
    Previous Page
</a> &nbsp;
<a class="button is-info is-slightly-elevated" v-on:click="adjustPage(1)" v-if="page_number < unpaginatedFilteredEvents.length / 39">
    Next Page &nbsp;&nbsp;
    <span>
        <i style="font-size: 18px" class="fas fa-chevron-circle-right"></i>
    </span>
</a>
</center>

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
    return (isStorageSupported() ? localStorage.getItem('flutterevents_sort_by') : false) || 'sort_date';
}

var app = new Vue({
el: '#event',

watch: {
    search: {
        handler() {
            app.updateMap();
        },
    },
    filter_distance: {
        handler() {
            app.updateMap();
        },
    },
    sort_by: {
        handler() {
            app.saveFilters();
        },
    },
},

methods: {
    toggleStudyJams: function() {
        this.filter_study_jams = ! this.filter_study_jams;
    },

    adjustPage: function(change) {
        this.page_number += change;
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    },

    selectEvent: function(event) {
        if (document.body.clientWidth < 1000) {
            if (event) {
                //window.location = '/' + event.slug;
            }
        } else {
            this.selected_event = event;
            if (history.pushState) {
                if (event) {
                    var route = '/' + event.slug;
                    gtag('config', '{{ $tracking_id }}', {'page_path': route});
                    //history.pushState(null, null, route);
                } else {
                    //history.pushState(null, null, '/');
                }
            }
        }
    },

    setFilter: function(filter) {
        filter = filter || '';
        this.selectEvent();
        this.search = filter.toLowerCase();
    },

    saveFilters: function() {
        if (! isStorageSupported()) {
            return false;
        }

        localStorage.setItem('flutterevents_sort_by', this.sort_by);
    },

    updateMap: function() {
        if (this.bounceTimeout) clearTimeout(this.bounceTimeout);
        this.bounceTimeout = setTimeout(function() {
            updateMapMarkers();
        }, 1000);
    },

    searchBackgroundColor: function() {
        if (! this.search) {
            return '#FFFFFF';
        } else {
            if (this.filteredEvents.length) {
                return '#FFFFBB';
            } else {
                return '#FFC9D9';
            }
        }
    }
},

beforeMount () {
    @if (filter_var(request()->study_jams, FILTER_VALIDATE_BOOLEAN))
        this.updateMap();
    @endif
},

mounted () {
    window.addEventListener('keyup', function(event) {
        if (event.keyCode == 27) {
            app.selectEvent();
        }
    });
},

data: {
    events: {!! $events !!},
    search: "{{ request()->search }}",
    sort_by: getCachedSortBy(),
    filter_study_jams: {{ filter_var(request()->study_jams, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false' }},
    selected_event: false,
    page_number: 1,
    filter_distance: 'filter_distance_any',
},

computed: {

    modalClass() {
        if (this.selected_event) {
            return {'is-active': true};
        } else {
            return {};
        }
    },

    unpaginatedFilteredEvents() {

        var events = this.events;
        var search = this.search.toLowerCase().trim();
        var sort_by = this.sort_by;
        var distance = this.filter_distance;
        var filter_study_jams = this.filter_study_jams;

        if (search || distance) {
            events = events.filter(function(item) {
                if (distance == 'filter_distance_50' && item.distance > 50) {
                    return false;
                } else if (distance == 'filter_distance_100' && item.distance > 100) {
                    return false;
                } else if (distance == 'filter_distance_250' && item.distance > 250) {
                    return false;
                } else if (distance == 'filter_distance_500' && item.distance > 500) {
                    return false;
                }

                if ((item.event_name || '').toLowerCase().indexOf(search) >= 0) {
                    return true;
                }

                if ((item.address || '').toLowerCase().indexOf(search) >= 0) {
                    return true;
                }

                if ((item.text_description || '').toLowerCase().indexOf(search) >= 0) {
                    return true;
                }

                return false;
            });
        }

        if (filter_study_jams) {
            events = events.filter(function(item) {
                return (item.event_name || '').toLowerCase().indexOf('study jam') >= 0;
            });
        }

        events.sort(function(itemA, itemB) {
            if (sort_by == 'sort_attending' && (itemA.rsvp_yes + itemA.rsvp_waitlist) != (itemB.rsvp_yes + itemB.rsvp_waitlist)) {
                return (itemB.rsvp_yes + itemB.rsvp_waitlist) - (itemA.rsvp_yes + itemA.rsvp_waitlist);
            } else if (sort_by == 'sort_distance' && itemA.distance != itemB.distance) {
                return itemA.distance - itemB.distance;
            } else {
                return (itemA.event_date || '').toLowerCase()
                    .localeCompare((itemB.event_date || '').toLowerCase());
            }
        });

        return events;
    },

    filteredEvents() {

        events = this.unpaginatedFilteredEvents;

        var startIndex = (this.page_number - 1) * 39;
        var endIndex = startIndex + 39;
        events = events.slice(startIndex, endIndex);

        return events;
    },
}

});

</script>


</div>

@stop

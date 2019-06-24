@extends('master')

@section('title', 'Flutter Events')
@section('description', 'An Open List of Flutter Events')
@section('image_url', asset('images/background.jpg'))
@section('header_title', 'An Open List of Flutter Events')
@section('header_subtitle', 'Events are synced with Meetup.com or can be added manually')

@section('header_button_url', url(auth()->check() ? 'flutter-event/submit' : 'auth/google?intended_url=flutter-event/submit'))
@section('header_button_label', 'SUBMIT EVENT')

@section('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"
       integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
       crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"
     integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og=="
     crossorigin=""></script>

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
        -webkit-animation-duration: .5s;
        -moz-animation-duration: .5s;
    }

    .modal-card {
        width: 80%;
    }

    [v-cloak] {
        display: none;
    }

    .short-description {
        line-height: 1.5em;
        height: 12em;
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
        --columnGap: 2rem;
    }

    .column {
        padding: 1rem 1rem 6rem 1rem;
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

    $(function() {
        var planes = [

            @foreach ($events as $event)
            ['{{ $event->eventLink() }}<br/>{{ substr(strip_tags($event->description), 0, 150) }}...', {{ $event->latitude }}, {{ $event->longitude }}],
            @endforeach
        ];

        var map = L.map('map').setView([26, 0], 2);
        mapLink = '<a href="http://openstreetmap.org">OpenStreetMap</a>';

        L.tileLayer(
            'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; ' + mapLink + ' Contributors',
                maxZoom: 18,
            }).addTo(map);

            for (var i = 0; i < planes.length; i++) {
                marker = new L.marker([planes[i][1],planes[i][2]])
                .bindPopup(planes[i][0])
                .addTo(map);
            }
        })

    </script>

<div id="event">

    <div id="map"></div>

    <section class="hero is-light is-small is-body-font">
        <div class="hero-body">
            <div class="container">
                <div class="field is-grouped is-grouped-multiline is-vertical-center">
                    <p class="control is-expanded has-icons-left">
                        <input v-model="search" class="input" type="text" placeholder="SEARCH" BAK-v-bind:placeholder="'Search ' + unpaginatedFilteredEvents.length + ' events and counting.."
                            autofocus="true" style="margin-top: 10px" v-bind:style="{ backgroundColor: searchBackgroundColor()}">
                        <span class="icon is-small is-left" style="margin-top: 10px">
                            <i class="fas fa-search"></i>
                        </span>
                        <div class="is-medium filter-label slider-control">
                            <label class="label is-medium" style="font-weight: normal; font-size: 16px"> &nbsp; ZOOM </label>
                        </div>
                        <div class="is-medium filter-control slider-control">
                            <input class="slider is-fullwidth is-medium is-info"
                            step="1" min="2" max="6" type="range" v-model="cards_per_row">
                        </div>
                        <div class="is-medium filter-label">
                            <label class="label is-medium" style="font-weight: normal; font-size: 16px"> &nbsp;&nbsp;&nbsp; SORT </label>
                        </div>
                        <div class="select is-medium filter-control" style="font-size: 16px">
                            <select v-model="sort_by" onchange="$(this).blur()">
                                <option value="sort_featured">FEATURED</option>
                                <option value="sort_newest">NEWEST</option>
                                <option value="sort_oldest">OLDEST</option>
                            </select>
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
        No apps found
    </div>
    <div class="columns is-multiline is-6 is-variable">
        <div v-for="event in filteredEvents" :key="event.id" class="column" v-bind:class="columnClass">
            <div v-on:click="selectEvent(event)" v-on:mouseenter="onMouseOver(event)" v-on:mouseleave="onMouseOut(event)" style="cursor:pointer">
                <div class="flutter-event is-hover-elevated" v-bind:class="[event.user_id == {{ auth()->check() ? auth()->user()->id : '0' }} ? 'is-owned' : '']">

                    <header style="padding: 16px">

                        <p class="no-wrap" v-bind:title="event.title" style="font-size:22px; padding-bottom:10px;">
                            <!--
                            <span v-if="event.featured > 0">
                                <i style="font-size: 18px" class="fas fa-star"></i> &nbsp;
                            </span>
                            -->

                            @{{ event.event_name }}

                        </p>
                        <div style="border-bottom: 2px #368cd5 solid; width: 50px"/>

                    </header>

                    <div class="content" style="padding-left:16px; padding-right:16px;">
                        <p class="help">
                            @{{ event.pretty_event_date }} • @{{ event.location }}
                        </p>

                        <div class="short-description" v-bind:title="event.short_description">
                            @{{ event.description }}
                        </div>

                        <div class="event-links" style="font-size:13px; padding-top:12px; padding-bottom:10px">
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
                                <a v-bind:href="'{{ url('flutter-event') }}/' + event.slug + '/edit'" target="_blank" v-on:click.stop target="_blank" rel="nofollow">
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
            <div class="column is-4 is-elevated">
                <img v-bind:src="imageSrc" width="1080" height="1920"/>
            </div>
            <div class="column is-8">

                @if (auth()->check())
                    @if(auth()->user()->is_editor)
                        <div v-if="selected_event.featured == 0">
                            <a class="button is-warning is-slightly-elevated" v-bind:href="'/flutter-event/' + selected_event.slug + '/feature'">
                                <i style="font-size: 20px" class="fas fa-star"></i> &nbsp;
                                Feature Eventlication
                            </a>
                            <p>&nbsp;</p>
                        </div>
                    @endif

                    <div v-if="selected_event.user_id == {{ auth()->user()->id }}">
                        <a class="button is-info is-slightly-elevated" v-bind:href="'/flutter-event/' + selected_event.slug + '/edit'">
                            <i style="font-size: 20px" class="fas fa-edit"></i> &nbsp;
                            Edit Eventlication
                        </a>
                        <p>&nbsp;</p>
                    </div>
                @endif

                <div class="content">
                    <div style="font-size:24px; padding-bottom:10px;">
                        @{{ selected_event.title }}

                        <span v-if="selected_event.category">
                            &nbsp;&nbsp;
                            <a class="tag is-info is-medium" v-on:click="setFilter(selected_event.category)"
                                href="#" style="text-decoration: none;">
                                @{{ selected_event.category }}
                            </a>
                        </span>
                    </div>

                    <div style="border-bottom: 2px #368cd5 solid; width: 50px;"></div><br/>

                    <div class="subtitle">
                        @{{ selected_event.short_description }}
                    </div>

                    <div v-if="selected_event.google_url || selected_event.eventle_url" class="buttons">
                        <a v-bind:href="selected_event.google_url" v-if="selected_event.google_url" target="_blank" v-on:click.stop target="_blank" rel="nofollow">
                            <div class="card-image is-slightly-elevated">
                                <img src="{{ asset('images/google.png') }}" width="160px"/>
                            </div>
                        </a>
                        <div v-if="! selected_event.google_url" class="card-image is-slightly-elevated">
                            <img src="{{ asset('images/google.png') }}" style="opacity: 0.1; filter: grayscale(100%);" width="160px"/>
                        </div> &nbsp;&nbsp;
                        <a v-bind:href="selected_event.eventle_url" v-if="selected_event.eventle_url" target="_blank" v-on:click.stop target="_blank" rel="nofollow">
                            <div class="card-image is-slightly-elevated">
                                <img src="{{ asset('images/eventle.png') }}" width="160px"/>
                            </div>
                        </a>
                        <div v-if="! selected_event.eventle_url" class="card-image is-slightly-elevated">
                            <img src="{{ asset('images/eventle.png') }}" style="opacity: 0.1; filter: grayscale(100%);" width="160px"/>
                        </div>
                    </div>

                    <div class="content" v-if="selected_event.website_url || selected_event.repo_url">
                        <div>
                            <a v-if="selected_event.website_url" v-bind:href="selected_event.website_url" target="_blank" rel="nofollow">
                                @{{ selected_event.website_url }}
                            </a>
                        </div>
                        <div>
                            <a v-if="selected_event.repo_url" v-bind:href="selected_event.repo_url" target="_blank" rel="nofollow">
                                @{{ selected_event.repo_url }}
                            </a>
                        </div>
                    </div>

                    <div class="content">
                        <a v-if="selected_event.facebook_url" class="button is-slightly-elevated" v-bind:href="selected_event.facebook_url" target="_blank" rel="nofollow">
                            <i style="font-size: 20px" class="fab fa-facebook"></i> &nbsp; Facebook
                        </a>
                        <a v-if="selected_event.twitter_url" class="button is-slightly-elevated" v-bind:href="selected_event.twitter_url" target="_blank" rel="nofollow">
                            <i style="font-size: 20px" class="fab fa-twitter"></i> &nbsp; Twitter
                        </a>
                        <a v-if="selected_event.instagram_url" class="button is-slightly-elevated" v-bind:href="selected_event.instagram_url" target="_blank" rel="nofollow">
                            <i style="font-size: 20px" class="fab fa-instagram"></i> &nbsp; Instagram
                        </a>

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

                    <span class="block wrap">@{{ selected_event.long_description }}</span>

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
<a class="button is-info is-slightly-elevated" v-on:click="adjustPage(1)" v-if="page_number < unpaginatedFilteredEvents.length / 40">
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
var sortBy = (isStorageSupported() ? localStorage.getItem('sort_by') : false) || 'sort_featured';
if (sortBy == 'oldest' || sortBy == 'newest') {
    sortBy = 'sort_featured';
}
return sortBy;
}

function getCachedCardsPerRow() {
return (isStorageSupported() ? localStorage.getItem('cards_per_row') : false) || 4;
}

var event = new Vue({
el: '#event',

watch: {
    sort_by: {
        handler() {
            event.saveFilters();
        },
    },
    cards_per_row: {
        handler() {
            event.saveFilters();
        },
    },
},

methods: {
    adjustPage: function(change) {
        this.page_number += change;
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    },

    onMouseOver: function(event) {
        $('#social-buttons-' + event.id)
            .removeClass('animated flipOutX')
            .addClass('animated flipInX')
            .css('display', 'flex')
            .css('visibility', 'visible');

        if (event.has_gif) {
            $('#' + event.slug + '-img').attr('src', '/gifs/event-' + event.id + '.gif?updated_at=' + event.updated_at);
            $('#' + event.slug + '-video').hide();
        }
    },

    onMouseOut: function(event, e) {
        $('#social-buttons-' + event.id)
            .animateCss('animated flipOutX', function() {
                $('#social-buttons-' + event.id).css('display', 'none')
            });

        if (event.has_gif) {
            $('#' + event.slug + '-img').attr('src', '/screenshots/event-' + event.id + '.png?updated_at=' + event.updated_at);
            $('#' + event.slug + '-video').show();
        }
    },

    selectImage: function(type) {
        this.image_type = type;
    },

    selectEvent: function(event) {
        this.image_type = '.png';

        if (document.body.clientWidth < 1000) {
            if (event) {
                window.location = '/flutter-event/' + event.slug;
            }
        } else {
            this.selected_event = event;
            if (history.pushState) {
                if (event) {
                    var route = '/flutter-event/' + event.slug;
                    gtag('config', '{{ $tracking_id }}', {'page_path': route});
                    history.pushState(null, null, route);
                } else {
                    history.pushState(null, null, '/');
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

        localStorage.setItem('cards_per_row', this.cards_per_row);
        localStorage.setItem('sort_by', this.sort_by);
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

mounted () {
    window.addEventListener('keyup', function(event) {
        if (event.keyCode == 27) {
            event.selectEvent();
        }
    });
},

data: {
    events: {!! $events !!},
    search: "{{ request()->search }}",
    cards_per_row: getCachedCardsPerRow(),
    sort_by: getCachedSortBy(),
    selected_event: false,
    image_type: '.png',
    page_number: 1,
},

computed: {

    modalClass() {
        if (this.selected_event) {
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

    unpaginatedFilteredEvents() {

        var events = this.events;
        var search = this.search.toLowerCase().trim();
        var sort_by = this.sort_by;

        if (search) {
            events = events.filter(function(item) {
                if (item.title.toLowerCase().indexOf(search) >= 0) {
                    return true;
                }

                if (item.short_description.toLowerCase().indexOf(search) >= 0) {
                    return true;
                }

                if (item.category.toLowerCase().indexOf(search) >= 0) {
                    return true;
                }

                return false;
            });
        }

        events.sort(function(itemA, itemB) {
            var timeA = new Date(itemA.created_at).getTime();
            var timeB = new Date(itemB.created_at).getTime();

            if (sort_by == 'sort_oldest') {
                return timeA - timeB;
            } else if (sort_by == 'sort_newest') {
                return timeB - timeA;
            } else {
                var itemARating = itemA.store_rating;
                if (itemA.store_download_count < 500) {
                    itemARating -= 1;
                } else if (itemA.store_download_count < 1000) {
                    itemARating -= .5;
                }

                var itemBRating = itemB.store_rating;
                if (itemB.store_download_count < 500) {
                    itemBRating -= 1;
                } else if (itemB.store_download_count < 1000) {
                    itemBRating -= .5;
                }

                if (itemA.featured != itemB.featured) {
                    return itemB.featured - itemA.featured;
                } else if (itemARating != itemBRating) {
                    return itemBRating - itemARating;
                } else if (itemA.store_review_count != itemB.store_review_count) {
                    return itemB.store_review_count - itemA.store_review_count;
                } else {
                    return timeB - timeA;
                }
            }
        });

        return events;
    },

    filteredEvents() {

        events = this.unpaginatedFilteredEvents;

        var startIndex = (this.page_number - 1) * 40;
        var endIndex = startIndex + 40;
        events = events.slice(startIndex, endIndex);

        return events;
    },
}

});

</script>



<div class="container">
<div class="columns is-multiline is-5 is-variable">
    @foreach ($events as $event)
        <div class="column is-one-third">
            <div class="flutter-event is-hover-elevated has-text-centered">

                <header style="padding: 16px">
                    <p class="no-wrap" style="font-size:22px; padding-bottom:10px;">
                        {{ $event->event_name }}
                    </p>
                    <div style="border-bottom: 2px #368cd5 solid; margin-left:40%; margin-right: 40%;"></div>
                </header>
                <div>{{ $event->prettyDate() }}</div>

                <div class="content" style="padding:16px;padding-bottom:16px;padding-top:20px;">

                    @if (auth()->check() && auth()->user()->owns($event))
                        <div style="font-weight:300">
                            <i class="fas fa-eye"></i> &nbsp; {{ $event->view_count ?: '0' }} views &nbsp;&nbsp;&nbsp;
                            <i class="fas fa-user"></i> &nbsp; {{ ($event->click_count + $event->twitter_click_count) ?: '0' }} clicks
                        </div><br/>
                    @endif

                    <div class="short-description">
                        @if (false && auth()->check() && auth()->user()->is_admin)
                            {!! $event->getBanner() !!}
                        @else
                            {{ preg_replace('/\s\s+/', ' ', strip_tags($event->description)) }}
                            <br/>
                        @endif
                    </div>

                    @if (auth()->check() && auth()->user()->is_admin)
                        <br/>
                        @if (! $event->is_approved)
                            <a class="button is-success is-medium is-slightly-elevated" href="{{ url('flutter-event/' . $event->slug . '/eventrove') }}">
    							<i style="font-size: 20px" class="fas fa-check"></i> &nbsp;
    							Eventrove
    						</a>
                            <!--
    						<a class="button is-danger is-medium is-slightly-elevated" href="{{ url('flutter-event/' . $event->slug . '/reject') }}">
    							<i style="font-size: 20px" class="fas fa-trash"></i> &nbsp;
    							Reject
    						</a>
                            -->
                        @endif

                    @else

                    @endif

                    <br/>
                    <div class="is-clearfix">
                        <!--
                        <div class="is-pulled-left" style="padding-left:20px;padding-top:10px;">
                            @if ($event->is_approved)
                                <div class="tag is-success">
                                    Eventroved
                                </div>
                            @else
                                <div class="tag is-warning">
                                    Pending
                                </div>
                            @endif
                        </div>
                        -->
                        <div classx="is-pulled-right">
                            <a href="{{ $event->event_url }}" class="button is-light is-small is-slightly-elevated" target="_blank">
                                <i class="fas fa-external-link-alt"></i> &nbsp; View
                            </a>
                            @if (auth()->check() && auth()->user()->owns($event))
                                &nbsp;
                                <a href="{{ $event->url() }}" class="button is-light is-small is-slightly-elevated">
                                    <i class="fas fa-edit"></i> &nbsp; Edit
                                </a>
                            @endif
                            @if ($event->is_approved)
                                &nbsp;
                                <a href="{{ $event->mapUrl() }}" target="_blank" class="button is-light is-small is-slightly-elevated">
                                    <i class="fas fa-map"></i> &nbsp; Map
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
</div>
</div>

@stop

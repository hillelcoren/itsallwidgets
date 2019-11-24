@extends('master')

@section('title', 'List of ' . (request()->open_source ? 'open-source ' : '') . 'Flutter ' . (request()->platform ? request()->platform . ' ' : '') . 'apps')
@section('description', 'An open list of example apps made with Flutter include many open source samples.')
@section('image_url', asset('images/background.jpg'))

@section('content')

<style>

div.app-stores > a:hover {
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
    height: 3em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    text-overflow: ellipsis;
}

.is-owned {
    background-color: #FFFFAA;
}

.flutter-app {
    background-color: white;
    border-radius: 8px;
}

.flutter-app .is-hover-visible {
    display: none;
}

.flutter-app:hover .is-hover-visible {
    display: flex;
}

.flutter-app a {
    color: #368cd5;
}

.columns.is-variable.is-6 {
    --columnGap: 2rem;
}

.column {
    padding: 1rem 1rem 3rem 1rem;
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

<div id="app">

    <section class="hero is-light is-small is-body-font">
        <div class="hero-body">
            <div class="container">
                <div class="field is-grouped is-grouped-multiline is-vertical-center">
                    <p class="control is-expanded has-icons-left">

                        <input v-model="search" class="input is-medium" type="text" v-bind:placeholder="searchPlaholder"
                            autofocus="true" style="margin-top: 10px" v-bind:style="{ backgroundColor: searchBackgroundColor()}">
                        <span class="icon is-small is-left" style="margin-top: 10px">
                            <i class="fas fa-search"></i>
                        </span>

                        <div class="is-medium" v-on:click="toggleOpenSource()" style="padding-left: 26px;">
                            <input type="checkbox" name="openSourceSwitch"
                            class="switch is-info" v-model="filter_open_source">
                            <label for="openSourceSwitch" style="padding-top:6px; font-size: 16px">OPEN SOURCE &nbsp;</label>
                        </div>

                        <div class="is-medium" v-on:click="toggleTemplate()" style="padding-left: 26px;">
                            <input type="checkbox" name="templateSwitch"
                            class="switch is-info" v-model="filter_template">
                            <label for="templateSwitch" style="padding-top:6px; font-size: 16px">TEMPLATE &nbsp;</label>
                        </div>

                        <div class="is-medium filter-label slider-control">
                            <label class="label is-medium" style="font-weight: normal; font-size: 16px">ZOOM</label>
                        </div>
                        <div class="is-medium filter-control slider-control">
                            <input class="slider is-fullwidth is-medium is-info"
                            step="1" min="2" max="6" type="range" v-model="cards_per_row">
                        </div>

                        <!--
                        <div class="is-medium filter-label">
                            <label class="label is-medium" style="font-weight: normal; font-size: 16px">PLATFORM</label>
                        </div>
                        <div class="select is-medium filter-control" style="font-size: 16px">
                            <select v-model="filter_platform" onchange="$(this).blur()">
                                <option value="platform_mobile">MOBILE</option>
                                <option value="platform_web">WEB</option>
                            </select>
                        </div>
                        -->

                        <div class="is-medium filter-label">
                            <label class="label is-medium" style="font-weight: normal; font-size: 16px">SORT</label>
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

    @if ($banner)
        <br/>
        <div class="container" v-cloak>
            <div class="notification is-info">
                {!! $banner !!}
            </div>
        </div>
    @endif

    <div class="container">
        <center>
            <div class="tabs is-centered is-boxed is-medium" style="widthx:50%;padding-top:30px">
                <ul>
                    <li v-bind:class="[filter_platform == 'platform_mobile' ? 'is-active' : '']">
                        <a href="#" v-on:click="setPlatform('platform_mobile')">
                            <div style="width:250px">
                                <span class="icon is-small">
                                    <i class="fas fa-mobile-alt" aria-hidden="true"></i>
                                </span>
                                <span>Mobile Apps</span>
                            </div>
                        </a>
                    </li>
                    <li v-bind:class="[filter_platform == 'platform_web' ? 'is-active' : '']">
                        <a href="#" v-on:click="setPlatform('platform_web')">
                            <div style="width:250px">
                                <span class="icon is-small">
                                    <i class="fas fa-desktop" aria-hidden="true"></i>
                                </span>
                                <span>Web Apps</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </center>
    </div>

    <section class="section is-body-font" style="background-color:#fefefe">
        <div class="container" v-cloak>
            <div v-if="filteredApps.length == 0" class="is-wide has-text-centered is-vertical-center"
            style="height:400px; text-align:center; font-size: 32px; color: #AAA">
            No apps found
        </div>
        <div class="columns is-multiline is-6 is-variable">
            <div v-for="app in filteredApps" :key="app.id" class="column" v-bind:class="columnClass">
                <div v-on:click="selectApp(app)" style="cursor:pointer">
                    <div class="flutter-app is-hover-elevated" v-bind:class="[app.user_id == {{ auth()->check() ? auth()->user()->id : '0' }} ? 'is-owned' : '']">
                        <div class="is-pulled-right field is-grouped is-grouped-multiline is-vertical-center"
                            style="padding-top:6px; padding-right:4px;">
                            <!--
                            <span v-if="app.facebook_url && cards_per_row > 3">
                                <a v-bind:href="app.facebook_url" class="card-header-icon" target="_blank" v-on:click.stop rel="nofollow">
                                    <i style="font-size: 20px; color: #888" class="fab fa-facebook"></i>
                                </a>
                            </span>
                            <span v-if="app.instagram_url && ! app.facebook_url && cards_per_row > 3">
                                <span class="icon-bug-fix">
                                    <a v-bind:href="app.instagram_url" class="card-header-icon" target="_blank" v-on:click.stop rel="nofollow">
                                        <i style="font-size: 20px; color: #888" class="fab fa-instagram"></i>
                                    </a>
                                </span>
                            </span>
                            <span v-if="app.twitter_url && cards_per_row > 3">
                                <span class="icon-bug-fix">
                                <span class="icon-bug-fix">
                                <a v-bind:href="app.twitter_url" class="card-header-icon" target="_blank" v-on:click.stop rel="nofollow">
                                    <i style="font-size: 20px; color: #888" class="fab fa-twitter"></i>
                                </a>
                                </span>
                                </span>
                            </span>
                            -->
                            <span v-if="app.repo_url">
                                <span class="icon-bug-fix">
                                <span class="icon-bug-fix">
                                <span class="icon-bug-fix">
                                <a v-bind:href="app.repo_url" class="card-header-icon" target="_blank" v-on:click.stop rel="nofollow">
                                    <i style="font-size: 20px; color: #888" class="fab fa-github"></i>
                                </a>
                                </span>
                                </span>
                                </span>
                            </span>
                        </div>

                        <header style="padding: 16px">

                            <p class="no-wrap" v-bind:title="app.title" style="font-size:22px; padding-bottom:10px;">
                                <!--
                                <span v-if="app.featured > 0">
                                    <i style="font-size: 18px" class="fas fa-star"></i> &nbsp;
                                </span>
                                -->

                                @{{ app.title }}

                            </p>
                            <div style="border-bottom: 2px #368cd5 solid; width: 50px"/>

                        </header>

                        <div class="content" style="padding-left:16px; padding-right:16px;">
                            <div class="short-description" v-bind:title="app.short_description">
                                @{{ app.short_description }}
                            </div>

                            @if (auth()->check() && auth()->user()->is_editor)
                                <div v-if="filter_platform == 'platform_mobile'">
                                    <br/>
                                    @{{ Math.round(app.store_rating * 100) / 100 }} (@{{ app.store_review_count }}) • @{{ app.store_download_count }}+
                                </div>
                            @endif

                            <div v-if="filter_platform == 'platform_mobile'" class="app-stores" style="font-size:13px; padding-top:12px;">
                                <a v-bind:href="app.google_url" v-if="app.google_url" target="_blank" v-on:click.stop target="_blank" rel="nofollow">
                                    GOOGLE PLAY
                                </a>
                                <span v-if="! app.google_url" style="color:#AAAAAA">
                                    GOOGLE PLAY
                                </span>
                                <span style="color:#CCCCCC">
                                    &nbsp; | &nbsp;
                                </span>
                                <a v-bind:href="app.apple_url" v-if="app.apple_url" target="_blank" v-on:click.stop target="_blank" rel="nofollow">
                                    APP STORE
                                </a>
                                <span v-if="! app.apple_url" style="color:#AAAAAA">
                                    APP STORE
                                </span>
                            </div>
                        </div>

                        <div v-if="filter_platform == 'platform_mobile'" class="card-image" style="line-height:0px">
                            <img v-if="app.has_gif" v-bind:src="'/gifs/app-' + app.id + '.gif?updated_at=' + app.updated_at" loading="lazy" width="1080" height="1920"/>
                            <img v-if="!app.has_gif" v-bind:src="'/screenshots/app-' + app.id + '.png?updated_at=' + app.updated_at" loading="lazy" width="1080" height="1920"/>
                        </div>
                        <div v-if="filter_platform == 'platform_web'" style="line-height:0px">
                            <iframe sandbox="allow-scripts allow-same-origin allow-top-navigation allow-popups" v-bind:src="app.flutter_web_url" allowTransparency="true"
                                loading="lazy" width="100%" v-bind:height="cards_per_row == 6 ? 900 : 700" frameBorder="0" style="border:none; overflow:hidden;"></iframe>
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

    <div class="modal-card is-body-font">
        <header class="modal-card-head">
            <p class="modal-card-title"></p>
            <button class="delete" aria-label="close" v-on:click="selectApp()"></button>
        </header>
        <section class="modal-card-body" @click.stop>

            <div class="columns">
                <div class="column is-4 is-elevated" v-if="selected_app.is_mobile">
                    <img v-bind:src="imageSrc" width="1080" height="1920"/>
                </div>
                <div v-bind:class="modalColumClass">

                    <div>
                        @if (auth()->check())
                            <span v-if="selected_app.user_id == {{ auth()->user()->id }}">
                                <a class="button is-info is-slightly-elevated" v-bind:href="'/flutter-app/' + selected_app.slug + '/edit'">
                                    <i style="font-size: 20px" class="fas fa-edit"></i> &nbsp;
                                    Edit Application
                                </a>
                                &nbsp;&nbsp;
                            </span>

                            @if (auth()->user()->is_editor)
                                <span v-if="selected_app.featured == 0">
                                    <a class="button is-warning is-slightly-elevated" v-bind:href="'/flutter-app/' + selected_app.slug + '/feature'">
                                        <i style="font-size: 20px" class="fas fa-star"></i> &nbsp;
                                        Feature Application
                                    </a>
                                    &nbsp;&nbsp;
                                </span>
                                <a class="button is-danger is-slightly-elevated" v-bind:href="'/flutter-app/' + selected_app.slug + '/hide'">
                                    <i style="font-size: 20px" class="fas fa-trash"></i> &nbsp;
                                    Hide Application
                                </a>
                            @endif
                        @endif
                    </div>
                    <p>&nbsp;</p>

                    <div class="content">
                        <div style="font-size:24px; padding-bottom:10px;">
                            @{{ selected_app.title }}

                            <span v-if="selected_app.category">
                                &nbsp;&nbsp;
                                <a class="tag is-info is-medium" v-on:click="setFilter(selected_app.category)"
                                    href="#" style="text-decoration: none;">
                                    @{{ selected_app.category }}
                                </a>
                            </span>
                        </div>

                        <div style="border-bottom: 2px #368cd5 solid; width: 50px;"></div><br/>

                        <div class="subtitle">
                            @{{ selected_app.short_description }}
                        </div>

                        <div v-if="selected_app.google_url || selected_app.apple_url" class="buttons">
                            <a v-bind:href="selected_app.google_url" v-if="selected_app.google_url" target="_blank" v-on:click.stop target="_blank" rel="nofollow">
                                <div class="card-image is-slightly-elevated">
                                    <img src="{{ asset('images/google.png') }}" width="160px"/>
                                </div>
                            </a>
                            <div v-if="! selected_app.google_url" class="card-image is-slightly-elevated">
                                <img src="{{ asset('images/google.png') }}" style="opacity: 0.1; filter: grayscale(100%);" width="160px"/>
                            </div> &nbsp;&nbsp;
                            <a v-bind:href="selected_app.apple_url" v-if="selected_app.apple_url" target="_blank" v-on:click.stop target="_blank" rel="nofollow">
                                <div class="card-image is-slightly-elevated">
                                    <img src="{{ asset('images/apple.png') }}" width="160px"/>
                                </div>
                            </a>
                            <div v-if="! selected_app.apple_url" class="card-image is-slightly-elevated">
                                <img src="{{ asset('images/apple.png') }}" style="opacity: 0.1; filter: grayscale(100%);" width="160px"/>
                            </div>
                        </div>

                        <div class="content" v-if="selected_app.website_url || selected_app.repo_url">
                            <div>
                                <a v-if="selected_app.website_url" v-bind:href="selected_app.website_url" target="_blank" rel="nofollow">
                                    @{{ selected_app.website_url }}
                                </a>
                            </div>
                            <div>
                                <a v-if="selected_app.repo_url" v-bind:href="selected_app.repo_url" target="_blank" rel="nofollow">
                                    @{{ selected_app.repo_url }}
                                </a>
                            </div>
                        </div>

                        <div class="content">
                            <a v-if="selected_app.facebook_url" class="button is-slightly-elevated" v-bind:href="selected_app.facebook_url" target="_blank" rel="nofollow">
                                <i style="font-size: 20px" class="fab fa-facebook"></i> &nbsp; Facebook
                            </a>
                            <a v-if="selected_app.twitter_url" class="button is-slightly-elevated" v-bind:href="selected_app.twitter_url" target="_blank" rel="nofollow">
                                <i style="font-size: 20px" class="fab fa-twitter"></i> &nbsp; Twitter
                            </a>
                            <a v-if="selected_app.instagram_url" class="button is-slightly-elevated" v-bind:href="selected_app.instagram_url" target="_blank" rel="nofollow">
                                <i style="font-size: 20px" class="fab fa-instagram"></i> &nbsp; Instagram
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
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=#url" target="_blank" rel="nofollow">
                                        <div class="dropdown-content">
                                            <div class="dropdown-item">
                                                <i style="font-size: 20px" class="fab fa-facebook"></i> &nbsp; Facebook
                                            </div>
                                        </div>
                                    </a>
                                    <a v-bind:href="'https://twitter.com/share?text=' + encodeURIComponent(selected_app.title) + '&amp;url=' + encodeURIComponent('{{ url('/flutter-app') }}' + '/' + selected_app.slug)" target="_blank" rel="nofollow">
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

                    <iframe v-if="selected_app.is_web && selected_app.flutter_web_url" sandbox="allow-scripts allow-same-origin" v-bind:src="selected_app.flutter_web_url" allowTransparency="true" scrolling="no"
                        width="100%" height="800px" frameborder="0" style="border:none; overflow:hidden;"></iframe>

                    <div v-if="selected_app.has_gif || selected_app.has_screenshot_1 || selected_app.has_screenshot_2 || selected_app.has_screenshot_3">
                        <div class="columns is-multiline is-3 is-variable">
                            <div class="column is-one-fifth" v-if="selected_app.has_gif">
                                <img v-on:click="selectImage('.gif')" v-bind:src="'/gifs/app-' + selected_app.id + '.gif?updated_at=' + selected_app.updated_at" class="is-slightly-elevated is-hover-elevated" style="cursor:pointer"/>
                            </div>
                            <div class="column is-one-fifth">
                                <img v-on:click="selectImage('.png')" v-bind:src="'/screenshots/app-' + selected_app.id + '.png?updated_at=' + selected_app.updated_at" class="is-slightly-elevated is-hover-elevated" style="cursor:pointer"/>
                            </div>
                            <div class="column is-one-fifth" v-if="selected_app.has_screenshot_1">
                                <img v-on:click="selectImage('-1.png')" v-bind:src="'/screenshots/app-' + selected_app.id + '-1.png?updated_at=' + selected_app.updated_at" class="is-slightly-elevated is-hover-elevated" style="cursor:pointer"/>
                            </div>
                            <div class="column is-one-fifth" v-if="selected_app.has_screenshot_2">
                                <img v-on:click="selectImage('-2.png')" v-bind:src="'/screenshots/app-' + selected_app.id + '-2.png?updated_at=' + selected_app.updated_at" class="is-slightly-elevated is-hover-elevated" style="cursor:pointer"/>
                            </div>
                            <div class="column is-one-fifth" v-if="selected_app.has_screenshot_3">
                                <img v-on:click="selectImage('-3.png')" v-bind:src="'/screenshots/app-' + selected_app.id + '-3.png?updated_at=' + selected_app.updated_at" class="is-slightly-elevated is-hover-elevated" style="cursor:pointer"/>
                            </div>
                        </div>
                    </div><br/>

                    <iframe v-if="selected_app.youtube_url" width="560" height="315" v-bind:src="selected_app.youtube_url"
                    frameborder="0" allowfullscreen></iframe><br/>

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
    <a class="button is-info is-slightly-elevated" v-on:click="adjustPage(1)" v-if="page_number < unpaginatedFilteredApps.length / 40">
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

function getCachedPlatform() {
    @if (request()->platform == 'web')
        return 'platform_web';
    @elseif (request()->platform == 'mobile')
        return 'platform_mobile';
    @endif

    return (isStorageSupported() ? localStorage.getItem('filter_platform') : false) || 'platform_mobile';
}

function getCachedCardsPerRow() {
    return (isStorageSupported() ? localStorage.getItem('cards_per_row') : false) || 4;
}

var app = new Vue({
    el: '#app',

    watch: {
        sort_by: {
            handler() {
                app.saveFilters();
            },
        },
        filter_platform: {
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
        toggleOpenSource: function() {
            this.filter_open_source = ! this.filter_open_source;
        },

        toggleTemplate: function() {
            this.filter_template = ! this.filter_template;
        },

        adjustPage: function(change) {
            this.page_number += change;
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        },

        /*
        onMouseOver: function(app) {
            console.log('over');
            $('#social-buttons-' + app.id)
                .removeClass('animated flipOutX')
                .addClass('animated flipInX')
                .css('display', 'flex')
                .css('visibility', 'visible');
        },

        onMouseOut: function(app, e) {
            console.log('out');
            $('#social-buttons-' + app.id)
                .animateCss('animated flipOutX', function() {
                    $('#social-buttons-' + app.id).css('display', 'none')
                });
        },
        */

        selectImage: function(type) {
            this.image_type = type;
        },

        selectApp: function(app) {
            this.image_type = '.png';

            if (document.body.clientWidth < 1000) {
                if (app) {
                    window.location = '/' + app.slug;
                }
            } else {
                this.selected_app = app;
                if (history.pushState) {
                    if (app) {
                        var route = '/' + app.slug;
                        gtag('config', '{{ $tracking_id }}', {'page_path': route});
                        history.pushState(null, null, route);
                    } else {
                        history.pushState(null, null, '/');
                    }
                }
            }
        },

        setPlatform(platform) {
            this.filter_platform = platform;
        },

        setFilter: function(filter) {
            filter = filter || '';
            this.selectApp();
            this.search = filter.toLowerCase();
        },

        saveFilters: function() {
            if (! isStorageSupported()) {
                return false;
            }

            localStorage.setItem('cards_per_row', this.cards_per_row);
            localStorage.setItem('sort_by', this.sort_by);
            localStorage.setItem('filter_platform', this.filter_platform);
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
        },

        moveNext() {
            var apps = this.filteredApps;
            var index = apps.indexOf(this.selected_app);
            this.selectApp(apps[index + 1]);
        },

        movePrev() {
            var apps = this.filteredApps;
            var index = apps.indexOf(this.selected_app);
            this.selectApp(apps[index - 1]);
        },

    },

    mounted () {
        window.addEventListener('keyup', function(event) {
            console.log('key code: %s', event.keyCode);
            if (event.keyCode == 27) {
                app.selectApp();
            } else if (event.keyCode == 39) {
                app.moveNext();
            } else if (event.keyCode == 37) {
                app.movePrev();
            }
        });
    },

    data: {
        apps: {!! $apps !!},
        search: "{{ request()->search }}",
        filter_open_source: {{ filter_var(request()->open_source, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false' }},
        filter_template: {{ filter_var(request()->templates, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false' }},
        cards_per_row: getCachedCardsPerRow(),
        sort_by: getCachedSortBy(),
        filter_platform: getCachedPlatform(),
        selected_app: false,
        image_type: '.png',
        page_number: 1,
    },

    computed: {

        /*
        hasNext() {
            var apps = this.filteredApps;
            var index = apps.indexOf(this.selected_app);
            return index < 40;
        },

        hasPrev() {
            var apps = this.filteredApps;
            var index = apps.indexOf(this.selected_app);
            return index > 0;
        },
        */

        modalClass() {
            if (this.selected_app) {
                return {'is-active': true};
            } else {
                return {};
            }
        },

        modalColumClass() {
            if (this.selected_app.is_mobile) {
                return 'column is-8';
            } else {
                return 'column is-12'
            }
        },

        imageSrc() {
            if (this.image_type == '.gif' && this.selected_app.has_gif) {
                return '/gifs/app-' + this.selected_app.id + '.gif?updated_at=' + this.selected_app.updated_at;
            } else {
                return '/screenshots/app-' + this.selected_app.id + this.image_type + '?updated_at=' + this.selected_app.updated_at;
            }
        },

        columnClass() {
            if (this.filter_platform == 'platform_mobile') {
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
            } else {
                switch(+this.cards_per_row) {
                    case 6:
                        return {'is-full': true};
                    case 5:
                        return {'is-full': true};
                    case 4:
                        return {'is-6': true};
                    case 3:
                        return {'is-one-third': true};
                    case 2:
                        return {'is-one-quarter': true};
                }
            }
        },

        unpaginatedFilteredApps() {

            var apps = this.apps;
            var search = this.search.toLowerCase().trim();
            var filter_open_source = this.filter_open_source;
            var filter_template = this.filter_template;
            var filter_platform = this.filter_platform;
            var sort_by = this.sort_by;

            if (search) {
                apps = apps.filter(function(item) {
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

            if (filter_open_source) {
                apps = apps.filter(function(item) {
                    return item.repo_url;
                });
            }

            if (filter_template) {
                apps = apps.filter(function(item) {
                    return item.is_template;
                });
            } else {
                apps = apps.filter(function(item) {
                    return !item.is_template;
                });
            }

            if (filter_platform == 'platform_mobile') {
                apps = apps.filter(function(item) {
                    return item.is_mobile;
                });
            } else {
                apps = apps.filter(function(item) {
                    return item.is_web;
                });
            }

            apps.sort(function(itemA, itemB) {
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

            return apps;
        },

        filteredApps() {

            apps = this.unpaginatedFilteredApps;

            var startIndex = (this.page_number - 1) * 40;
            var endIndex = startIndex + 40;
            apps = apps.slice(startIndex, endIndex);

            return apps;
        },

        searchPlaholder() {

            apps = this.unpaginatedFilteredApps;

            apps = apps.filter(function(item) {
                return !item.is_template;
            });

            if (this.filter_template) {
                return "Search " + apps.length + " templates...";
            } else {
                return "Search " + apps.length + " apps...";
            }
        },
    }

});

</script>

@stop

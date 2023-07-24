@extends('master')

@section('title', isGL() ? 'A directory of universal Torah resources' : 'A Searchable List of Flutter Resources')
@section('description', isGL() ? 'A directory of universal Torah resources' : 'A Searchable List of Flutter Resources')
@section('image_url', isGL() ? asset('images/geula_twitter.png') : asset('images/flutterx_twitter.png'))
@section('header_title', isGL() ? 'A directory of universal Torah resources' : 'A Searchable List of Flutter Resources')
@section('header_button_url', false)

@section('header_subtitle')
    Resources are sourced from <a href="https://flutterpro.dev" target="_blank">Flutter Pro</a>
@endsection

@section('content')

    <script src="{{ asset('js/vue-masonry.min.js') }}"></script>

    <style>

    div.artifact-links > a:hover {
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

    .item {
        margin: 1.6em 0em 1.6em 0em;
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

    .is-owned {
        xbackground-color: #FFFFAA;
        background-color: #FFFFFF;
    }

    .flutter-artifact {
        background-color: white;
        border-radius: 8px;
    }

    .flutter-artifact .is-hover-visible {
        display: none;
    }

    .flutter-artifact:hover .is-hover-visible {
        display: flex;
    }

    .flutter-artifact a {
        color: #368cd5;
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

<div id="artifact">

    <section class="hero is-light is-small is-body-font">
        <div class="hero-body">
            <div class="container">
                <div class="field is-grouped is-grouped-multiline is-vertical-center">
                    <p class="control is-expanded has-icons-left">
                        <input v-model="search" class="input is-medium" type="text" v-bind:placeholder="'Search {{ $artifact_count }} resources...'"
                            style="margin-top: 10px" v-bind:style="{ backgroundColor: searchBackgroundColor()}">
                        <span class="icon is-small is-left" style="margin-top: 10px">
                            <i class="fas fa-search"></i>
                        </span>

                        <div class="is-medium filter-label">
                            <label class="label is-medium" style="font-weight: normal; font-size: 16px"> TYPE </label>
                        </div>
                        <div class="select is-large filter-control" style="font-size: 16px;">
                            <select v-model="filter_type" onchange="$(this).blur()">
                                <option value="filter_type_all">ALL</option>
                                <option value="filter_type_articles">ARTICLES</option>
                                <option value="filter_type_videos">VIDEOS</option>
                                @if (!isGL())
                                    <option value="filter_type_libraries">LIBRARIES</option>
                                @endif
                            </select>
                        </div>

                        <div class="is-medium filter-label">
                            <label class="label is-medium" style="font-weight: normal; font-size: 16px"> SORT </label>
                        </div>
                        <div class="select is-large filter-control" style="font-size: 16px;">
                            <select v-model="sort_by" onchange="$(this).blur()">
                                <!-- <option value="sort_featured">FEATURED</option> -->
                                <option value="sort_newest">NEWEST</option>
                                <option value="sort_oldest">OLDEST</option>
                            </select>
                        </div>

                    </p>
                </div>
            </div>
        </div>
    </section>

    <!--
    <br/>
    <div class="container">
        <a href="http://goo.gle/30daysofflutter" target="_blank"><img src="{{ asset('images/30days.jpg') }}"/></a>
    </div>
    -->

    @if ($banner)
        <br/>
        <div class="container" v-cloak>
            <div class="notification is-info">
                {!! $banner !!}
            </div>
        </div>
    @endif


<section class="section is-body-font" style="background-color:#fefefe; padding-top:14px">
    <div class="container" v-cloak>
        <div v-if="filteredArtifacts.length == 0" class="is-wide has-text-centered is-vertical-center"
        style="height:400px; text-align:center; font-size: 32px; color: #AAA">
        <span v-if="is_searching">Searching...</span>
        <span v-if="! is_searching">No resources found</span>
    </div>
    <masonry :cols="{default: 4, 1000: 3, 800: 2, 600: 1}" :gutter="{default: '22px', 600: '15px'}">
        <div v-for="artifact in filteredArtifacts" :key="artifact.id + artifact.contents" class="item">
            <div v-on:click="selectArtifact(artifact)" style="cursor:pointer">
                <div class="flutter-artifact is-hover-elevated" v-bind:class="[artifact.user_id == {{ auth()->check() ? auth()->user()->id : '0' }} ? 'is-owned' : '']">

                    <header style="padding-left:16px;padding-top:16px;padding-right:16px;">

                        <a v-bind:href="artifact.repo_url" v-if="artifact.repo_url" v-on:click.stop rel="nofollow"
                            class="is-pulled-right" target="_blank" style="color:black; margin-top:5px; margin-left:5px;">
                            <i class="fab fa-github"></i>
                        </a>

                        <p v-bind:title="artifact.title" style="font-size:22px; padding-bottom:6px; word-break:break-word;">
                            @{{ artifact.title }}
                        </p>

                        <div style="font-size:13px; padding-bottom:14px">
                            <span v-if="artifact.meta_author_url" class="">
                                <a target="_blank" v-bind:href="artifact.meta_author_url" v-on:click.stop rel="nofollow">
                                    @{{ artifact.meta_author }}
                                </a>
                            </span>
                            <span v-if="!artifact.meta_author_url && artifact.meta_author_twitter" class="">
                                <a target="_blank" v-bind:href="'https://twitter.com/' + artifact.meta_author_twitter" v-on:click.stop rel="nofollow">
                                    @@{{ artifact.meta_author_twitter }}
                                </a>
                            </span>

                            <span v-if="(artifact.meta_author_url || artifact.meta_author_twitter) && (artifact.meta_publisher_twitter || artifact.meta_publisher || artifact.domain)" class="">
                                •
                            </span>

                            <span v-if="artifact.meta_publisher_twitter" class="">
                                <a target="_blank" v-bind:href="'https://twitter.com/' + artifact.meta_publisher_twitter" v-on:click.stop rel="nofollow">
                                    @@{{ artifact.meta_publisher_twitter }}
                                </a>
                            </span>
                            <span v-if="!artifact.meta_publisher_twitter && artifact.meta_publisher">
                                @{{ artifact.meta_publisher }}
                            </span>                    
                            <span v-if="!artifact.meta_publisher_twitter && !artifact.meta_publisher">
                                @{{ artifact.domain }}
                            </span>
                        </div>


                        <div style="border-bottom: 2px #368cd5 solid; width: 50px"/>

                    </header>

                    <div class="content" style="margin-top:0px">
                        <div style="padding-left:16px; padding-right:16px;">
                            <div class="help is-clearfix" style="padding-top:8px;padding-bottom:8px;">
                                <div class="is-pulled-right">
                                    <span v-bind:class="'button is-outlined is-small is-static is-' + artifact.type_class">
                                        @{{ artifact.pretty_type }}
                                    </span>
                                </div>
                                <div class="is-pulled-left">
                                    <span class="tag is-white" style="padding-left:0px; padding-top:6px; color:#777">
                                        @{{ artifact.pretty_published_date }}
                                    </span>
                                </div>
                            </div>

                            <div v-bind:title="artifact.short_description" style="word-break:break-word;">
                                <div v-if="artifact.contents">
                                    @{{ artifact.contents.substr(0, 200) }}
                                </div>
                                <div v-if="artifact.comment && !artifact.contents">
                                    @{{ artifact.comment }}
                                </div>
                                <div v-if="artifact.meta_description && !artifact.comment && !artifact.contents">
                                    @{{ artifact.meta_description.substr(0, 200).trim() }}...
                                </div>
                            </div><br/>


                            <center>
                                <div class="artifact-links" style="font-size:13px; padding-top:16px; padding-bottom:16px">
                                    <a v-bind:href="artifact.url" target="_blank" v-on:click.stop target="_blank" rel="nofollow">
                                        @{{ artifact.type_label }}
                                    </a> &nbsp;
                                    <a v-bind:href="artifact.url" target="_blank" v-on:click.stop target="_blank" rel="nofollow">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <span v-if="artifact.user_id == {{ auth()->check() ? auth()->user()->id : '0' }} || {{ auth()->check() && auth()->user()->is_admin ? 'true' : 'false' }}">
                                        <span style="color:#CCCCCC">
                                            &nbsp; | &nbsp;
                                        </span>
                                        <a v-bind:href="'{{ iawUrl() }}/flutter-artifact/' + artifact.slug + '/edit'" target="_blank" v-on:click.stop target="_blank" rel="nofollow">
                                            EDIT RESOURCE
                                        </a>
                                    </span>
                                </div>
                            </center>
                        </div>

                        @if (isGL())
                            <div v-if="artifact.image_url" class="card-image" style="max-height:250px; overflow: hidden">
                                <img v-bind:src="artifact.image_url + '?updated_at=' + artifact.updated_at" loading="lazy" width="100%"/>
                            </div>
                        @else
                            <div v-if="artifact.gif_url || artifact.image_url" class="card-image" style="max-height:220px; overflow: hidden; line-height:0px;">
                                <img v-bind:src="(artifact.gif_url || artifact.image_url) + '?updated_at=' + artifact.updated_at" loading="lazy" width="100%"/>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </masonry>
</div>
</section>

<div class="modal animated fadeIn" v-bind:class="modalClass" v-if="selected_artifact">
<div class="modal-background" v-on:click="selectArtifact()"></div>

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
        <button class="delete" aria-label="close" v-on:click="selectArtifact()"></button>
    </header>
    <section class="modal-card-body" @click.stop>

        <div class="columns">
            <div class="column is-4 is-elevated" v-if="selected_artifact.gif_url || selected_artifact.image_url">
                <img v-if="selected_artifact.image_url" v-bind:src="selected_artifact.image_url + '?updated_at=' + selected_artifact.updated_at" width="100%" style="padding-bottom:16px"/>
                @if (!isGL())
                    <img v-if="selected_artifact.gif_url" v-bind:src="selected_artifact.gif_url + '?updated_at=' + selected_artifact.updated_at" width="100%"/>
                @endif
            </div>
            <div class="column is-8">

                <div style="font-size:24px; padding-bottom:10px;">
                    @{{ selected_artifact.title }}

                    &nbsp;&nbsp;

                    <span v-bind:class="'button is-outlined is-small is-static is-' + selected_artifact.type_class">
                        @{{ selected_artifact.pretty_type }}
                    </span>
                </div>

                <div style="border-bottom: 2px #368cd5 solid; width: 50px;"></div><br/>

                <div class="content">
                    <a v-bind:href="selected_artifact.url" target="_blank" rel="nofollow" style="font-size:20px">
                        @{{ selected_artifact.url }}
                    </a>
                </div>

                <div class="content">
                    <div class="dropdown is-hoverable">
                        <div class="dropdown-trigger is-slightly-elevated">
                            <button class="button" aria-haspopup="true" aria-controls="dropdown-menu4">
                                <span>
                                    <i style="font-size: 20px" class="fa fa-share"></i> &nbsp;
                                    Share Resource
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
                            <a v-bind:href="'https://twitter.com/share?text=' + encodeURIComponent(selected_artifact.title) + '&amp;url=' + encodeURIComponent('{{ url('/flutter-artifact') }}' + '/' + selected_artifact.slug)" target="_blank" rel="nofollow">
                                <div class="dropdown-content">
                                    <div class="dropdown-item">
                                        <i style="font-size: 20px" class="fab fa-twitter"></i> &nbsp; Twitter
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>

                @if (isGL())
                    <div class="panel-block">
                        <div class="block wrap">@{{ selected_artifact.meta_description }}</div>
                    </div><br/>
                @endif

                <nav class="panel" v-if="selected_artifact.contents">
                    <p class="panel-heading">
                        Search Result
                    </p>
                    <div class="panel-block">
                        <div class="block wrap">@{{ selected_artifact.contents }}</div>
                    </div>
                </nav>


                <div>
                    <span v-if="selected_artifact.meta_author_url" class="">
                        <a target="_blank" v-bind:href="selected_artifact.meta_author_url" v-on:click.stop rel="nofollow">
                            @{{ selected_artifact.meta_author }}
                        </a>
                    </span>
                    <span v-if="!selected_artifact.meta_author_url && selected_artifact.meta_author_twitter" class="">
                        <a target="_blank" v-bind:href="'https://twitter.com/' + selected_artifact.meta_author_twitter" v-on:click.stop rel="nofollow">
                            @@{{ selected_artifact.meta_author_twitter }}
                        </a>
                    </span>

                    <span v-if="(selected_artifact.meta_author_url || selected_artifact.meta_author_twitter) && (selected_artifact.meta_publisher_twitter || selected_artifact.meta_publisher || selected_artifact.domain)" class="">
                        •
                    </span>

                    <span v-if="selected_artifact.meta_publisher_twitter" class="">
                        <a target="_blank" v-bind:href="'https://twitter.com/' + selected_artifact.meta_publisher_twitter" v-on:click.stop rel="nofollow">
                            @@{{ selected_artifact.meta_publisher_twitter }}
                        </a>
                    </span>
                    <span v-if="!selected_artifact.meta_publisher_twitter && selected_artifact.meta_publisher">
                        @{{ selected_artifact.meta_publisher }}
                    </span>
                    <span v-if="!selected_artifact.meta_publisher_twitter && !selected_artifact.meta_publisher">
                        @{{ selected_artifact.domain }}
                    </span>
                </div>

                <div style="padding-top:0px;padding-bottom:18px;">
                    <span style="padding-left:0px; padding-top:6px; color:#777; font-size:14px;">
                        Published on @{{ selected_artifact.pretty_published_date }}
                    </span>
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
<a class="button is-info is-slightly-elevated" v-on:click="adjustPage(1)" v-if="page_number < unpaginatedFilteredArtifacts.length / 50">
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
    return (isStorageSupported() ? localStorage.getItem('flutterx_sort_by') : false) || 'sort_newest';
}

var app = new Vue({
el: '#artifact',

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

    selectArtifact: function(artifact) {
        if (document.body.clientWidth < 1000) {
            if (artifact) {
                window.location = '/' + {!! isServe() ? "'flutterx/' + " : '' !!} artifact.slug;
            }
        } else {
            this.selected_artifact = artifact;
            if (history.pushState) {
                if (artifact) {
                    var route = '/' + {!! isServe() ? "'flutterx/' + " : '' !!} artifact.slug;
                    gtag('config', '{{ $tracking_id }}', {'page_path': route});
                    history.pushState(null, null, route);
                } else {
                    history.pushState(null, null, '/');
                }
            }
        }
    },

    saveFilters: function() {
        if (! isStorageSupported()) {
            return false;
        }

        localStorage.setItem('flutterx_sort_by', this.sort_by);
    },

    searchBackgroundColor: function() {
        if (! this.search) {
            return '#FFFFFF';
        } else {
            if (this.is_searching) {
                return '#FFFFBB';
            } else if (this.filteredArtifacts.length) {
                return '#FFFFBB';
            } else {
                return '#FFC9D9';
            }
        }
    },

    serverSearch: function() {
        var app = app || this;
        var searchStr = this.search;
        var artifacts = this.artifacts;

        app.$set(app, 'is_searching', true);
        if (this.bounceTimeout) clearTimeout(this.bounceTimeout);

        this.bounceTimeout = setTimeout(function() {
            if (searchStr && searchStr.length >= 3) {
                $.get('/search?search=' + encodeURIComponent(searchStr), function (data) {
                    app.$set(app, 'is_searching', false);

                    var artifactMap = {};
                    for (var i=0; i<data.length; i++) {
                        var artifact = data[i];
                        artifactMap[artifact.id] = artifact.contents;
                    }

                    for (var i=0; i<artifacts.length; i++) {
                        var artifact = artifacts[i];
                        app.$set(artifacts[i], 'contents', (artifactMap[artifact.id] || ''));
                    }
                });
            } else {
                app.$set(app, 'is_searching', false);
                for (var i=0; i<artifacts.length; i++) {
                    app.$set(artifacts[i], 'contents', '');
                }
            }

        }, 500);
    },

    moveNext() {
        var artifacts = this.filteredArtifacts;
        var index = artifacts.indexOf(this.selected_artifact);
        this.selectArtifact(artifacts[index + 1]);
    },

    movePrev() {
        var artifacts = this.filteredArtifacts;
        var index = artifacts.indexOf(this.selected_artifact);
        this.selectArtifact(artifacts[index - 1]);
    },

},

beforeMount() {
    this.serverSearch();
},

mounted () {
    window.addEventListener('keyup', function(event) {
        if (event.keyCode == 27) {
            app.selectArtifact();
        } else if (event.keyCode == 39) {
            app.moveNext();
        } else if (event.keyCode == 37) {
            app.movePrev();
        }
    });
},

data: {
    artifacts: [],
    artifact_count: {{ $artifact_count }},
    search: "{{ request()->q ?: request()->search }}",
    sort_by: getCachedSortBy(),
    selected_artifact: false,
    page_number: 1,
    filter_type: 'filter_type_all',
    is_searching: false,
},

computed: {

    modalClass() {
        if (this.selected_artifact) {
            return {'is-active': true};
        } else {
            return {};
        }
    },

    unpaginatedFilteredArtifacts() {

        var artifacts = this.artifacts;
        var search = this.search.toLowerCase().trim();
        var sort_by = this.sort_by;
        var type = this.filter_type;

        if (search || type) {
            artifacts = artifacts.filter(function(item) {

                if (type) {
                    if (type == 'filter_type_articles' && item.type != 'article') {
                        return false;
                    } else if (type == 'filter_type_videos' && item.type != 'video') {
                        return false;
                    } else if (type == 'filter_type_libraries' && item.type != 'library') {
                        return false;
                    }
                }

                var searchStr = (item.title || '')
                    + (item.comment || '')
                    + (item.contents || '')
                    + (item.meta_author || '')
                    + (item.meta_publisher || '')
                    + (item.meta_description || '')
                    + (item.meta_author_twitter || '')
                    + (item.meta_publisher_twitter || '');

                if (searchStr.toLowerCase().indexOf(search) >= 0) {
                    return true;
                }

                return false;
            });
        }

        artifacts.sort(function(itemA, itemB) {
            if (sort_by == 'sort_oldest') {
                return itemA.published_date.localeCompare(itemB.published_date);
            } else if (sort_by == 'sort_newest') {
                return itemB.published_date.localeCompare(itemA.published_date);
            } else {
                return itemB.published_date.localeCompare(itemA.published_date);
            }
        });

        return artifacts;
    },

    filteredArtifacts() {

        artifacts = this.unpaginatedFilteredArtifacts;

        var startIndex = (this.page_number - 1) * 50;
        var endIndex = startIndex + 50;
        artifacts = artifacts.slice(startIndex, endIndex);

        return artifacts;
    },
}

});

</script>


</div>

@stop

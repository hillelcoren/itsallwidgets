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

@endsection


@section('content')


    <div id="app">

        <section class="hero is-light is-small is-body-font">
            <div class="hero-body">
                <div class="container">
                    <div class="field is-grouped is-grouped-multiline is-vertical-center">
                        <p class="control is-expanded has-icons-left">

                            <input v-model="search" class="input is-medium" type="text" placeholder="SEARCH" BAK-v-bind:placeholder="'Search ' + unpaginatedFilteredApps.length + ' apps and counting.."
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
        },

        computed: {

            modalClass() {
                if (this.selected_profile) {
                    return {'is-active': true};
                } else {
                    return {};
                }
            },

            modalColumClass() {
                if (this.selected_profile.is_mobile) {
                    return 'column is-8';
                } else {
                    return 'column is-12'
                }
            },

            unpaginatedFilteredProfiles() {

                var profiles = this.profiles;
                var search = this.search.toLowerCase().trim();
                var sort_by = this.sort_by;

                if (search) {
                    apps = apps.filter(function(item) {
                        /*
                        if (item.title.toLowerCase().indexOf(search) >= 0) {
                        return true;
                    }
                    */

                    return false;
                });
            }

            apps.sort(function(itemA, itemB) {
                var timeA = false;//new Date(itemA.created_at).getTime();
                var timeB = false;//new Date(itemB.created_at).getTime();

                if (sort_by == 'sort_newest') {
                    return timeB - timeA;
                } else {
                    /*
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
*/
}
});

return apps;
},

filteredApps() {

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

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


@endsection

@extends('master')

@section('title', 'A Showcase for Passionate Flutter Developers')
@section('description', 'A Showcase for Passionate Flutter Developers')
@section('image_url', asset('images/background.jpg'))

@section('header_title', 'A Showcase for Passionate Flutter Developers')
@section('header_button_url', 'auth/google?intended_url=profile')
@section('header_button_label', 'JOIN FLUTTER PRO')

@section('header_subtitle')
    Profiles are updated automatically from
        <a href="{{ iawUrl() }}">It's All Widgets!</a>,
        <a href="{{ fxUrl() }}">FlutterX</a> and
        <a href="{{ feUrl() }}">Flutter Events</a>
@endsection

@section('head')

@endsection


@section('content')

    <center style="padding-top:150px; padding-bottom:150px; font-size:26px; color:#CCC">
        COMING SOON
    </center>

@endsection

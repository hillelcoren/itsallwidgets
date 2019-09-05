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

@section('content')

    <center>

        <iframe sandbox="allow-scripts allow-same-origin" src="{{ $user->profileUrl() }}" allowTransparency="true" scrolling="no"
            loading="lazy" width="100%" height="700" frameborder="0" style="border:none; overflow:hidden;"></iframe>

    </center>

@endsection

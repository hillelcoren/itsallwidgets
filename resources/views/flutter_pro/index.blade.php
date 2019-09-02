@extends('master')

@section('title', 'Flutter Pro: The Best Flutter Developers')
@section('description', 'An Open Group of Flutter Developers')
@section('image_url', asset('images/background.jpg'))

@section('header_title', 'The Best Flutter Developers on the Planet')
@section('header_button_url', iawUrl() . '/' . (auth()->check() ? 'flutter-event/submit' : 'auth/google?intended_url=flutter-event/submit'))
@section('header_button_label', 'JOIN FLUTTER PRO')

@section('header_subtitle')
    Profiles are updated in realtime using data from It's All Widget, FlutterX and Flutter Events
@endsection

@section('head')

@endsection


@section('content')

    <center style="padding-top:150px; padding-bottom:150px; font-size:26px; color:#CCC">
        COMING SOON...
    </center>

@endsection

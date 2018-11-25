@extends('master')

@section('title', 'Flutter Podcast')
@section('description', 'An open podcast for Flutter devlopers')

@section('header_title', 'An open podcast for Flutter devlopers')
@section('header_subtitle', 'Share your Flutter story with the community')
@section('header_button_url', url(auth()->check() ? 'flutter-podcast/create' : 'auth/google?intended_url=flutter-podcast/create')))
@section('header_button_label', 'REQUEST INTERVIEW')
@section('header_button_icon', 'fas fa-microphone')

@section('content')

Hi

@stop

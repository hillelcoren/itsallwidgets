@extends('master')

@section('title', 'Flutter Events')
@section('description', '')
@section('image_url', asset('images/background.jpg'))

@section('content')

    <style>

    .flutter-event {
        background-color: white;
        border-radius: 8px;
    }

    .column {
        padding: 1rem 1rem 4rem 1rem;
    }


    </style>

<p>&nbsp;</p>
<p>&nbsp;</p>

<div class="container">

<h2 class="title">Flutter Events</h2>

<div class="columns is-multiline is-5 is-variable">
    @foreach ($events as $event)
        <div class="column is-one-third" onclick="location.href = '{{ $event->route() }}'" style="cursor: pointer;">
            <div class="flutter-event is-hover-elevated has-text-centered">
                <header style="padding: 16px">
                    <p class="no-wrap" style="font-size:22px; padding-bottom:10px;">
                        {{ $event->event_name }}
                    </p>
                    <div style="border-bottom: 2px #368cd5 solid; margin-left:40%; margin-right: 40%;"></div>
                </header>
                <div class="content" style="padding:16px;padding-bottom:16px;padding-top:6px;">
                    <div class="short-description">
                        {!! $event->getBanner() !!}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
</div>

@stop

@extends('master')

@section('title', 'Flutter Events')
@section('description', '')
@section('image_url', asset('images/background.jpg'))

@section('content')

<p>&nbsp;</p>
<p>&nbsp;</p>

<div class="container">
    <h2 class="title">Flutter Events</h2>

    

    @foreach($events as $event)

        <div class="card">
            EVENT: {{ $event->event_name }}
        </div>

    @endforeach
</div>

@stop

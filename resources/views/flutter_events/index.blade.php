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


<div class="is-clearfix">

    <h2 class="title is-pulled-left">Flutter Events</h2>

    <div class="is-pulled-right">
        <a class="button is-light is-slightly-elevated" href="http://flutterevents.com/feed" target="_blank">
            <i style="font-size: 20px" class="fas fa-rss"></i> &nbsp;
            Event Feed
        </a> &nbsp;

        <a href="{{ url('/flutter-event/submit') }}" class="button is-info is-slightly-elevated">
            <i style="font-size: 20px" class="fas fa-cloud-upload-alt"></i> &nbsp; Submit Event
        </a>

    </div>

</div>

<br/>

<div class="columns is-multiline is-5 is-variable">
    @foreach ($events as $event)
        <div class="column is-one-third">
            <div class="flutter-event is-hover-elevated has-text-centered">

                <header style="padding: 16px">
                    <p class="no-wrap" style="font-size:22px; padding-bottom:10px;">
                        {{ $event->event_name }}
                    </p>
                    <div style="border-bottom: 2px #368cd5 solid; margin-left:40%; margin-right: 40%;"></div>
                </header>
                <div>{{ $event->prettyDate() }}</div>

                <div class="content" style="padding:16px;padding-bottom:16px;padding-top:20px;">

                    <div style="font-weight:300">
                        <i class="fas fa-eye"></i> &nbsp; {{ $event->view_count ?: '0' }} views &nbsp;&nbsp;&nbsp;
                        <i class="fas fa-user"></i> &nbsp; {{ ($event->click_count + $event->twitter_click_count) ?: '0' }} clicks
                    </div><br/>

                    <div class="short-description">
                        {!! $event->getBanner() !!}
                    </div>

                    <div style="padding-top:16px;font-weight:300">{{ $event->address }}</div><br/>

                    @if (auth()->check() && auth()->user()->is_admin)

                        @if (! $event->is_approved)
                            <a class="button is-success is-medium is-slightly-elevated" href="{{ url('flutter-event/' . $event->slug . '/approve') }}">
    							<i style="font-size: 20px" class="fas fa-check"></i> &nbsp;
    							Approve
    						</a>
                            <!--
    						<a class="button is-danger is-medium is-slightly-elevated" href="{{ url('flutter-event/' . $event->slug . '/reject') }}">
    							<i style="font-size: 20px" class="fas fa-trash"></i> &nbsp;
    							Reject
    						</a>
                            -->
                        @endif

                        <p>
                            {{ $event->user->name }}
                        </p>

                    @else

                    @endif

                    <div class="is-clearfix">
                        <div class="is-pulled-left" style="padding-left:20px;padding-top:10px;">
                            @if ($event->is_approved)
                                <div class="tag is-success">
                                    Approved
                                </div>
                            @else
                                <div class="tag is-warning">
                                    Pending
                                </div>
                            @endif
                        </div>
                        <div class="is-pulled-right" style="padding-right:20px;padding-top:10px;">
                            <a href="{{ $event->url() }}" class="button is-light is-small is-slightly-elevated">
                                <i class="fas fa-edit"></i> &nbsp; Edit
                            </a>
                            @if ($event->is_approved)
                                &nbsp;
                                <a href="{{ $event->mapUrl() }}" target="_blank" class="button is-light is-small is-slightly-elevated">
                                    <i class="fas fa-map"></i> &nbsp; Map
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
</div>

@stop

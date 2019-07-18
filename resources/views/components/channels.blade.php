@php
    $twitterColor = $isPodcast ? "#444" : "#366cb6";
    $altChannelColor = $isPodcast ? "#000" : "#366cb6";
    $altChannelIcon = $isPodcast ? "fa-mobile-alt" : "fa-microphone";
    $altChannelUrl = $isPodcast ? iawUrl() : url('/podcast');
    $altChannelTitle = $isPodcast ? "Apps" : "Podcast";
@endphp

@if (isGL())

    <a class="button is-elevated-dark"
       style="color:white; background-color:{{$twitterColor}}; border-color:{{$twitterColor}}"
       href="https://twitter.com/charlottecoren" target="_blank">
                                            <span class="icon">
                                                <i class="fab fa-twitter"></i>
                                            </span> &nbsp;
        <span>Twitter</span>
    </a>

@else

    <a class="button is-elevated-dark"
       style="color:white; background-color:{{$twitterColor}}; border-color:{{$twitterColor}}"
       href="https://twitter.com/itsallwidgets" target="_blank">
                                            <span class="icon">
                                                <i class="fab fa-twitter"></i>
                                            </span> &nbsp;
        <span>Twitter</span>
    </a> &nbsp;&nbsp;&nbsp;

    <a class="button is-elevated-dark"
       style="color:white; background-color:{{$altChannelColor}}; border-color:{{$altChannelColor}}"
       href="{{ $altChannelUrl }}">
                                            <span class="icon">
                                                <i class="fas {{$altChannelIcon}}"></i>
                                            </span> &nbsp;
        <span>{{$altChannelTitle}}</span>
    </a>
@endif

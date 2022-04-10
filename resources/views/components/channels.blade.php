@php
    $twitterColor = $isPodcast ? "#444" : "#366cb6";
    $altChannelColor = $isPodcast ? "#000" : "#366cb6";
@endphp

@if (isGL())

    <a class="button is-elevated-dark"
       style="color:white; background-color: #5b99ba; border-color: #5b99ba; margin-top:30px"
       href="https://www.youtube.com/user/ccasselson" target="_blank">
                                            <span class="icon">
                                                <i class="fab fa-youtube"></i>
                                            </span> &nbsp;
        <span>YouTube</span>
    </a> &nbsp;&nbsp;&nbsp;

    <a class="button is-elevated-dark"
       style="color:white; background-color: #5b99ba; border-color:#5b99ba; margin-top:30px"
       href="https://twitter.com/charlottecoren" target="_blank">
                                            <span class="icon">
                                                <i class="fab fa-twitter"></i>
                                            </span> &nbsp;
        <span>Twitter</span>
    </a> &nbsp;&nbsp;&nbsp;

    <a class="button is-elevated-dark"
       style="color:white; background-color: #5b99ba; border-color:#5b99ba; margin-top:30px"
       href="https://www.facebook.com/chanavered" target="_blank">
                                            <span class="icon">
                                                <i class="fab fa-facebook"></i>
                                            </span> &nbsp;
        <span>Facebook</span>
    </a>

@else

    <a class="button is-elevated-dark"
       style="color:white; background-color:{{$altChannelColor}}; border-color:{{$altChannelColor}}"
       href="https://itsallwidgets.com/podcast">
                                            <span class="icon">
                                                <i class="fas fa-microphone"></i>
                                            </span> &nbsp;
        <span>Podcast</span>
    </a> &nbsp;&nbsp;

    <a class="button is-elevated-dark"
           style="color:white; background-color:{{$twitterColor}}; border-color:{{$twitterColor}}"
           href="https://twitter.com/itsallwidgets" target="_blank">
                                                <span class="icon">
                                                    <i class="fab fa-twitter"></i>
                                                </span> &nbsp;
            <span>Twitter</span>
    </a> 

@endif

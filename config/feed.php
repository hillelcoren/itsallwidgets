<?php

return [

    'feeds' => [

        'main' => [

            'items' => 'App\Models\FlutterApp@getFeedItems',
            'url' => 'app/feed',
            'title' => 'It\'s All Widgets!',
            'view' => 'feed::feed',
        ],

        'podcast' => [

            'items' => 'App\Models\PodcastEpisode@getFeedItems',
            'url' => 'podcast/feed',
            'title' => 'It\'s All Widgets! Flutter Podcast',
            'view' => 'feed::podcast',
        ],

    ],

];

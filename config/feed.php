<?php

return [

    'feeds' => [

        'main' => [

            'items' => 'App\Models\FlutterApp@getFeedItems',
            'url' => 'feed',
            'title' => 'It\'s All Widgets!',
            'view' => 'feed::feed',

        ],

    ],

];

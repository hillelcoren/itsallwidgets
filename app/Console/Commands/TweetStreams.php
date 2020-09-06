<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FlutterStream;
use App\Models\User;
use Abraham\TwitterOAuth\TwitterOAuth;
use Carbon\Carbon;
use App\Notifications\StreamsImported;

class TweetStreams extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'itsallwidgets:tweet_streams';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Tweet live streams';

    /**
    * Create a new command instance.
    *
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * Execute the console command.
    *
    * @return mixed
    */
    public function handle()
    {
        $this->info('Running...');

        $twitter = new TwitterOAuth(
            config('services.twitter_streams.consumer_key'),
            config('services.twitter_streams.consumer_secret'),
            config('services.twitter_streams.access_token'),
            config('services.twitter_streams.access_secret')
        );

        $streams = FlutterStream::visible()
                    ->english()
                    ->where('was_tweeted', '=', false)
                    ->whereRaw('starts_at < DATE_ADD(NOW(), INTERVAL 10 MINUTE) AND starts_at >= DATE_SUB(NOW(), INTERVAL 10 MINUTE)')
                    ->with('channel.language')
                    ->orderBy('starts_at')
                    ->orderBy('id')
                    ->limit(3)
                    ->get();

        foreach ($streams as $stream) {
            $response = $twitter->upload('media/upload', [
                'media' => $stream->getThumbnailUrl(),
                'media_type' => 'image/jpg'
            ], true);

            $startsAtDate = Carbon::parse($stream->starts_at);
            $tweet = '📢 ' . $stream->channel->name . "'s";

            $user = User::where('channel_id', '=', $stream->channel_id)->first();
            if ($user && ($handle = $user->twitterHandle())) {
                $tweet .= ' (' . $handle . ')';
            }

            $tweet .= ' live stream is starting soon 🙌';
            //$tweet .= ' live stream is starting ' . $startsAtDate->diffForHumans() . ' 🙌';
            //$tweet .= ' #' . $stream->channel->language->name . "\n\n";

            $tweet .= "\n\n"
                . $stream->getVideoUrl() . "\n\n"
                . $stream->name . ': ' . $stream->description;


            if (strlen($tweet) >= 280) {
                $tweet = substr($tweet, 0, 275);
                $index = strrpos($tweet, ' ');
                $tweet = substr($tweet, 0, $index) . '...';
            }

            $parameters = [
                'status' => $tweet,
                'media_ids' => $response->media_id_string
            ];

            $response = $twitter->post('statuses/update', $parameters);

            $stream->was_tweeted = true;
            $stream->save();
        }
    }
}

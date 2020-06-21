<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FlutterStream;
use App\Models\User;
use Abraham\TwitterOAuth\TwitterOAuth;
use Carbon\Carbon;
use App\Notifications\StreamsImported;

class TweetStream extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'itsallwidgets:tweet_stream';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Tweet live stream';

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
                    //->whereRaw('starts_at < DATE_SUB(NOW(), INTERVAL 10 MINUTE) AND starts_at >= DATE_SUB(NOW(), INTERVAL 20 MINUTE)')
                    ->with('channel.language')
                    ->orderBy('starts_at')
                    ->orderBy('id')
                    //->limit(3)
                    ->limit(1)
                    ->get();

        foreach ($streams as $stream) {
            $startsAtDate = Carbon::parse($stream->starts_at);

            $tweet = $stream->channel->name;

            $user = User::where('channel_id', '=', $stream->channel_id)->first();
            if ($user && ($handle = $user->twitterHandle())) {
                $tweet .= ' (' . $handle . ')';
            }

            $tweet .= ' live stream starting ' . $startsAtDate->diffForHumans();
            //$tweet .= ' #' . $stream->channel->language->name . "\n\n";

            $tweet .= "\n\n"
                . $stream->getVideoUrl() . "\n\n"
                . $stream->name . ': ' . $stream->description;


            if (strlen($tweet) >= 280) {
                $tweet = substr($tweet, 0, 275);
                $index = strrpos($tweet, ' ');
                $tweet = substr($tweet, 0, $index) . '...';
            }

            $parameters = ['status' => $tweet];

            $this->info("TWEET:\n\n" . $tweet . "\n\n");
            //$response = $twitter->post('statuses/update', $parameters);
        }

        //User::admin()->notify(new StreamsImported);
    }
}

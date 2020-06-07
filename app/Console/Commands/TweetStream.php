<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FlutterStream;
use Abraham\TwitterOAuth\TwitterOAuth;
use Carbon\Carbon;

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

        $stream = FlutterStream::visible()
                    ->whereRaw('starts_at < NOW() AND starts_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)')
                    ->orderBy('starts_at')
                    ->orderBy('id')
                    ->first();

        if ($stream) {
            $twitter = new TwitterOAuth(
                config('services.twitter_streams.consumer_key'),
                config('services.twitter_streams.consumer_secret'),
                config('services.twitter_streams.access_token'),
                config('services.twitter_streams.access_secret')
            );

            $startsAtDate = Carbon::parse($stream->starts_at);

            $tweet = 'Starting ' . $startsAtDate->diffForHumans() . '... ' . $stream->name;

            $parameters = ['status' => $tweet];

            //$response = $twitter->post('statuses/update', $parameters);
        }
    }
}

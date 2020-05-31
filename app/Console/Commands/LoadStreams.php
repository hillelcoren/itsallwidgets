<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FlutterStream;
use App\Models\FlutterChannel;
use App\Repositories\FlutterEventRepository;

class LoadStreams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'itsallwidgets:load_streams';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load live streams from YouTube';

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

        // Load videos
        $url = 'https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=50&q=flutter&type=video&order=date&key=';
        $url .= config('services.youtube.key');

        $data = json_decode(file_get_contents($url));
        $videoIds = [];
        $videoMap = [];
        $channelIds = [];
        $channelMap = [];

        foreach ($data->items as $item) {
            $videoId = $item->id->videoId;;
            $videoIds[] = $videoId;
            $videoMap[$videoId] = $item;
            $channelIds[] = $item->snippet->channelId;
        }

        // Load channels
        $url = 'https://www.googleapis.com/youtube/v3/channels?part=snippet&key=';
        $url .= config('services.youtube.key');
        $url .= '&id=' . join(',', $channelIds);
        $this->info($url);
        $data = json_decode(file_get_contents($url));

        foreach ($data->items as $item) {
            $channel = FlutterChannel::where('channel_id', '=', $item->id)
                        ->where('source', '=', 'youtube')
                        ->first();

            if (! $channel) {
                $channel = new FlutterChannel;
                $channel->source = 'youtube';
                $channel->channel_id = $item->id;
            }

            $channel->name = $item->snippet->title;
            $channel->description = $item->snippet->description;
            $channel->custom_url = property_exists($item->snippet, 'customUrl') ? $item->snippet->customUrl : '';
            $channel->thumbnail_url = property_exists($item->snippet, 'thumbnails') ? $item->snippet->thumbnails->high->url : '';
            $channel->country = property_exists($item->snippet, 'country') ? $item->snippet->country : '';
            $channel->save();

            $channelMap[$channel->channel_id] = $channel->id;
        }

        // Load video streaming details
        $url = 'https://www.googleapis.com/youtube/v3/videos?part=liveStreamingDetails%2CcontentDetails%2Cstatistics&key=';
        $url .= config('services.youtube.key');
        $url .= '&id=' . join(',', $videoIds);

        $data = json_decode(file_get_contents($url));

        foreach ($data->items as $item) {
            $videoId = $item->id;
            $video = $videoMap[$videoId];

            if (property_exists($item, 'liveStreamingDetails') && property_exists($item->liveStreamingDetails, 'scheduledStartTime')) {
                $this->info($videoId . ' - ' . $video->snippet->title . ' - ' . $item->liveStreamingDetails->scheduledStartTime);
            } else {
                $this->info('Skipping');
                continue;
            }

            $stream = FlutterStream::where('video_id', '=', $videoId)->first();

            if (! $stream) {
                $stream = new FlutterStream;
                $stream->video_id = $videoId;
            }

            $stream->name = $video->snippet->title;
            $stream->description = $video->snippet->description;
            $stream->published_at = rtrim(str_replace('T', ' ', $video->snippet->publishedAt), 'Z');
            $stream->starts_at = rtrim(str_replace('T', ' ', $item->liveStreamingDetails->scheduledStartTime), 'Z');
            $stream->channel_id = $channelMap[$video->snippet->channelId];
            $stream->thumbnail_url = $video->snippet->thumbnails->high->url;
            $stream->view_count = $item->statistics->viewCount;
            $stream->comment_count = $item->statistics->commentCount;
            $stream->like_count = $item->statistics->likeCount;
            $stream->save();
        }
    }
}

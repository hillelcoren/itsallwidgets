<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FlutterStream;
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

        $url = 'https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=50&q=flutter&type=video&order=date&key=';
        $url .= config('services.youtube.key');

        $data = json_decode(file_get_contents($url));
        $videoIds = [];
        $videoMap = [];

        foreach ($data->items as $item) {
            $videoId = $item->id->videoId;;
            $videoIds[] = $videoId;
            $videoMap[$videoId] = $item;
        }

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

            $stream = FlutterStream::where('video_id', '=', $videoId)
                        ->where('source', '=', 'youtube')
                        ->first();

            if (! $stream) {
                $stream = new FlutterStream;
            }

            $stream->source = 'youtube';
            $stream->name = $video->snippet->title;
            $stream->description = $video->snippet->description;
            $stream->video_id = $videoId;
            $stream->published_at = rtrim(str_replace('T', ' ', $video->snippet->publishedAt), 'Z');
            $stream->starts_at = rtrim(str_replace('T', ' ', $item->liveStreamingDetails->scheduledStartTime), 'Z');
            $stream->channel_id = $video->snippet->channelId;
            $stream->channel_name = $video->snippet->channelTitle;
            $stream->thumbnail_url = $video->snippet->thumbnails->high->url;
            $stream->view_count = $item->statistics->viewCount;
            $stream->comment_count = $item->statistics->commentCount;
            $stream->like_count = $item->statistics->likeCount;
            $stream->save();
        }
    }
}

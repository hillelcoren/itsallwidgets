<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FlutterArtifact;
use App\Repositories\FlutterArtifactRepository;

class LoadArtifacts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'itsallwidgets:load_artifacts {--all} {--geula}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load artifacts from Flutter Weekly';

    protected $artifactRepo;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(FlutterArtifactRepository $artifactRepo)
    {
        parent::__construct();

        $this->artifactRepo = $artifactRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        $this->info('Running...');

        if ($this->option('geula')) {
            $this->handleGeula();
        } else {
            $this->handleFlutterX();
        }

        $this->info('Done');
    }

    public function handleGeula()
    {
        // handle blogs
        $feeds = explode(',', config('services.feeds.blogs'));

        foreach ($feeds as $feed) {
            $xml = simplexml_load_file($feed);
            foreach ($xml->channel->item as $item) {
                $data = [
                    'title' => $item->title,
                    'url' => $item->link,
                    'type' => 'article',
                    'source_url' => $feed,
                    'published_date' => date('Y-m-d', strtotime($item->pubDate)),
                    'meta_author' => $item->children('dc', true)->creator,
                    'meta_description' => $item->description,
                ];

                $this->parseResource($data);
            }
        }

        // handle videos
        $feeds = explode(',', config('services.feeds.videos'));

        foreach ($feeds as $channel) {
            $feed = 'https://www.youtube.com/feeds/videos.xml?channel_id=' . $channel;
            $xml = simplexml_load_file($feed);
            foreach ($xml->entry as $item) {
                $data = [
                    'title' => $item->title,
                    'url' => $item->link['href'],
                    'type' => 'video',
                    'source_url' => $feed,
                    'published_date' => date('Y-m-d', strtotime($item->published)),
                    'meta_author' => $item->author->name,
                    'meta_description' => $item->children('media', true)->group->description,
                    'image_url' => 'https://i1.ytimg.com/vi/' . $item->children('yt', true)->videoId . '/hqdefault.jpg',
                ];

                $this->parseResource($data);
            }
        }
    }

    public function handleFlutterX()
    {
        $data = file_get_contents('https://json.flutterweekly.net/issues.json');
        $data = json_decode($data);

        if ($this->option('all')) {
            foreach ($data->issues as $issue) {
                $this->parseIssue($issue);
            }
        } else {
            $issue = $data->issues[count($data->issues) - 1];
            $this->info($issue->file);
            $this->parseIssue($issue);
        }

        file_get_contents('https://flutterx.com/?clear_cache=true');
    }

    private function parseIssue($issue)
    {
        $link = 'https://json.flutterweekly.net/' . $issue->file;
        $artifacts = file_get_contents($link);

        $this->info('Issue: ' . $link);

        $artifacts = json_decode($artifacts);
        $publishedDate = date('Y-m-d', strtotime($issue->publishedOn));

        foreach (array_reverse($artifacts->articles) as $artifact) {
            //$this->info(json_encode($artifact));
            //$this->info('Artifact: ' . $artifact->title);

            if ($artifact->section == 'articles') {
                $type = 'article';
            } else

            if ($artifact->section == 'videos' || strpos($artifact->url, 'https://www.youtube.com') === 0) {
                $type = 'video';
            } else if ($artifact->section == 'libraries') {
                $type = 'library';
            } else {
                $type = 'article';
            }

            $data = [
                'title' => $artifact->title,
                'url' => rtrim($artifact->url, '/'),
                'type' => $type,
                'comment' => $artifact->description,
                'source_url' => $link,
                'published_date' => $publishedDate,
            ];

            $this->parseResource($data);
        }
    }

    private function parseResource($item)
    {
        $slug = str_slug($item['title'] . '-' . $item['published_date']);
        $artifact = FlutterArtifact::where('url', '=', $item['url'])
            ->orWhere('slug', '=', $slug)
            ->first();

        $item['slug'] = $slug;
        $item['is_approved'] = 1;

        // https://stackoverflow.com/a/9244634/497368
        libxml_use_internal_errors(true);

        if ($c = @file_get_contents($item['url'])) {
            $doc = new \DomDocument();
            $doc->loadHTML($c);
            $xp = new \domxpath($doc);

            if ($item['type'] == 'video') {
                $item = $this->pasreVideoMetaData($xp, $item);
            } else {
                $item = $this->pasreMetaData($xp, $item);
            }

            $item = $this->parseSchema($xp, $item);
            $item = $this->parseRepoUrl($xp, $item);
            $item = $this->parseGifUrl($xp, $item);

            if ($item['type'] == 'video') {
                // load transcript
            } else {
                $item = $this->parseContents($doc, $item);
            }

            if (! array_get($item, 'image_url')) {
                $item = $this->parseImageUrl($xp, $item);
            }

            $imageUrl = array_get($item, 'image_url');
            $gifUrl = array_get($item, 'gif_url');
            $item['image_url'] = null;
            $item['gif_url'] = null;

            $item['title'] = html_entity_decode($item['title']);

            if (isset($item['meta_description'])) {
                $item['meta_description'] = html_entity_decode($item['meta_description']);
            }

            if ($artifact) {
                $this->info('Updating: ' . $item['title']);
                $artifact = $this->artifactRepo->update($artifact, $item);
            } else {
                $this->info('Storing: ' . $item['title']);
                $artifact = $this->artifactRepo->store($item, 1);

                if ($imageUrl) {
                    $parts = explode('?', $imageUrl);
                    $imageUrl = count($parts) ? $parts[0] : '';
                    $imageUrl = rtrim($imageUrl, '/');
                    $parts = explode('.', $imageUrl);
                    $extension = count($parts) > 1 ? '.' . $parts[count($parts) - 1] : '';
                    if (strlen($extension) > 5) {
                        $extension = '';
                    }

                    if ($contents = @file_get_contents($imageUrl)) {
                        if (strlen($contents) > 10000) {
                            $url = '/thumbnails/artifact-' . $artifact->id . $extension;
                            $file = public_path($url);
                            file_put_contents($file, $contents);
                            $artifact->image_url = $url;
                        }
                    }

                    $artifact->save();
                }

                if ($gifUrl) {
                    if ($contents = @file_get_contents($gifUrl)) {
                        $url = '/thumbnails/artifact-' . $artifact->id . '.gif';
                        $file = public_path($url);
                        file_put_contents($file, $contents);
                        $artifact->gif_url = $url;
                    }

                    $artifact->save();
                }
            }
        }
    }

    private function pasreMetaData($xp, $data)
    {
        /*
        foreach ($xp->query("//meta[@property='og:title']") as $el) {
            $data['title'] = $el->getAttribute("content");
            break;
        }
        */

        foreach ($xp->query("//meta[@property='og:description']") as $el) {
            $data['meta_description'] = $el->getAttribute("content");
            break;
        }

        foreach ($xp->query("//meta[@name='author']") as $el) {
            $data['meta_author'] = $el->getAttribute("content");
            break;
        }

        foreach ($xp->query("//link[@rel='author']") as $el) {
            $url = $el->getAttribute("href");

            if (substr($url, 0, 1) == '/') {
                $parse = parse_url($data['url']);
                $url = 'https://' . $parse['host'] . $url;
            }

            $data['meta_author_url'] = $url;
            break;
        }

        foreach ($xp->query("//meta[@name='twitter:creator']") as $el) {
            $data['meta_author_twitter'] = ltrim($el->getAttribute("content"), '@');
            break;
        }

        foreach ($xp->query("//meta[@name='twitter:site']") as $el) {
            $handle = ltrim($el->getAttribute("content"), '@');
            if (! in_array(strtolower($handle), ['youtube', 'github', 'medium'])) {
                $data['meta_publisher_twitter'] = $handle;
            }
            break;
        }

        foreach ($xp->query("//meta[@property='og:image']") as $el) {
            $data['image_url'] = $el->getAttribute("content");
            break;
        }

        return $data;
    }

    private function pasreVideoMetaData($xp, $data)
    {
        $videoId = false;

        preg_match('/\?v=(.*?)&/', $data['url'], $matches);
        if (count($matches) >= 1) {
            $videoId = $matches[1];
        } else {
            preg_match('/youtu\.be\/([\w*]{11})$/', $data['url'], $matches);
            if (count($matches) >= 1) {
                $videoId = $matches[1];
            }
        }

        if (! $videoId) {
            return $data;
        }

        parse_str(file_get_contents('https://youtube.com/get_video_info?video_id=' . $matches[1]), $info);

        $player = json_decode($info['player_response']);
        $videoDetails = $player->videoDetails;

        if ($videoDetails->shortDescription) {
            $data['meta_description'] = $videoDetails->shortDescription;
        }

        if ($videoDetails->author) {
            $data['meta_author'] = $videoDetails->author;
        }

        return $data;
    }

    private function parseSchema($xp, $data)
    {
        try {
            $json = $xp->query( '//script[@type="application/ld+json"]' );
            if ($json && $json->item(0)) {
                $json = trim($json->item(0)->nodeValue);
                $json = json_decode($json);

                if ($json && ! isset($item['meta_author'])
                    && property_exists($json, 'author')
                    && property_exists($json->author, 'name')) {
                    $data['meta_author'] = $json->author->name;
                }
                if ($json && ! isset($item['meta_author_url'])
                    && property_exists($json, 'author')
                    && property_exists($json->author, 'url')) {
                    $data['meta_author_url'] = $json->author->url;
                }
                if ($json && ! isset($item['meta_publisher'])
                    && property_exists($json, 'publisher')
                    && property_exists($json->publisher, 'name')) {
                    $data['meta_publisher'] = $json->publisher->name;
                }
            }

        } catch (\Exception $e) {
            // TODO
        }

        return $data;
    }

    private function parseRepoUrl($xp, $data)
    {
        if ($data['type'] == 'library') {
            $data['repo_url'] = $data['url'];
            return $data;
        }

        $githubLinks = [];

        foreach ($xp->query("//a") as $el) {
            $url = $el->getAttribute("href");
            $matches = [];
            preg_match('/https:\/\/github.com\/([\w-]+)\/([\w-]+)/', $url, $matches);

            if (count($matches)) {
                $githubLink = $matches[0];
                if (isset($githubLinks[$githubLink])) {
                    $githubLinks[$githubLink]++;
                } else {
                    $githubLinks[$githubLink] = 1;
                }
            }
        }

        if (count($githubLinks)) {
            arsort($githubLinks);
            $url = key($githubLinks);

            if ($githubLinks[$url] >= 2) {
                $data['repo_url'] = $url;
            }
        }

        return $data;
    }

    private function parseGifUrl($xp, $data)
    {
        foreach ($xp->query("//img") as $el) {
            $url = $el->getAttribute("src");
            $matches = [];
            preg_match('(http.*\.gif)', $url, $matches);

            if (count($matches)) {
                $match = $matches[0];

                if (strpos($match, 'spinner') !== false) {
                    continue;
                }

                if (strpos($match, 'loading') !== false) {
                    continue;
                }

                if (strpos($match, 'new-icon') !== false) {
                    continue;
                }

                if (strpos($match, 'paypalobjects.com') !== false) {
                    continue;
                }

                if (strpos($match, 'miro.medium.com') !== false) {
                    continue;
                }

                $data['gif_url'] = $match;
                return $data;
            }
        }

        return $data;
    }

    private function parseImageUrl($xp, $data)
    {
        $max = 0;

        foreach ($xp->query("//img") as $el) {
            $url = $el->getAttribute("src");
            $width = intval($el->getAttribute("width"));
            $height = intval($el->getAttribute("height"));

            $size = $width * $height;

            if ($size > $max) {
                $max = $size;
                $data['image_url'] = $url;
            }
        }

        return $data;
    }

    private function parseContents($doc, $data)
    {
        while (($r = $doc->getElementsByTagName('script')) && $r->length) {
            $r->item(0)->parentNode->removeChild($r->item(0));
        }

        while (($r = $doc->getElementsByTagName('style')) && $r->length) {
            $r->item(0)->parentNode->removeChild($r->item(0));
        }

        $body = $doc->getElementsByTagName('body');
        if ($body && $body->item(0)) {
            $str = $body->item(0)->nodeValue;
            $str = trim(preg_replace('/[\t\n\r\s]+/', ' ', $str));
            $data['contents'] = $str;
        }

        return $data;
    }
}

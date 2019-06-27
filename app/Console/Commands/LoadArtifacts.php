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
    protected $signature = 'itsallwidgets:load_artifacts';

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

        $data = file_get_contents('https://json.flutterweekly.net/issues.json');
        $data = json_decode($data);

        foreach ($data->issues as $issue) {
            //$this->info(json_encode($issue));

            $link = 'https://json.flutterweekly.net/' . $issue->file;
            $artifacts = file_get_contents($link);

            $artifacts = json_decode($artifacts);
            $publishedDate = date('Y-m-d', strtotime($issue->publishedOn));

            foreach ($artifacts->articles as $artifact) {
                //$this->info(json_encode($artifact));

                $slug = str_slug($artifact->title);

                if ($artifact->section == 'articles') {
                    $type = 'article';
                } elseif ($artifact->section == 'videos') {
                    $type = 'video';
                } else {
                    $type = 'library';
                }

                $item = [
                    'title' => $artifact->title,
                    'slug' => $slug,
                    'url' => $artifact->url,
                    'comment' => $artifact->description,
                    'type' => $type,
                    'source_url' => $link,
                    'published_date' => $publishedDate,
                    'is_approved' => true,
                ];

                // https://stackoverflow.com/a/9244634/497368
                libxml_use_internal_errors(true);

                if ($c = @file_get_contents($artifact->url)) {
                    $d = new \DomDocument();
                    $d->loadHTML($c);
                    $xp = new \domxpath($d);

                    $item = $this->pasreMetaData($xp, $item);
                    $item = $this->parseSchema($xp, $item);
                    $item = $this->parseRepoUrl($xp, $item);

                    $imageUrl = array_get($item, 'image_url');
                    $item['image_url'] = null;

                    $this->info(json_encode($item));
                    $artifact = $this->artifactRepo->store($item, 1);

                    if ($imageUrl) {
                        $parts = explode('?', $imageUrl);
                        $imageUrl = count($parts) ? $parts[0] : '';
                        $imageUrl = rtrim($imageUrl, '/');
                        $parts = explode('.', $imageUrl);
                        $extension = count($parts) > 1 ? '.' . $parts[count($parts) - 1] : '';
                        if (strlen($extension) > 4) {
                            $extension = '';
                        }

                        if ($contents = @file_get_contents($imageUrl)) {
                            $url = '/thumbnails/artifact-' . $artifact->id . $extension;
                            $file = public_path($url);
                            file_put_contents($file, $contents);
                            $artifact->image_url = $url;
                        }

                        $artifact->save();
                    }
                }
            }
        }

        $this->info('Done');
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

    private function parseSchema($xp, $data)
    {
        $json = $xp->query( '//script[@type="application/ld+json"]' );
        if ($json && $json->item(0)) {
            $json = trim($json->item(0)->nodeValue);
            $json = json_decode($json);

            if (! isset($item['meta_author'])
                && property_exists($json, 'author')) {
                $data['meta_author'] = $json->author->name;
            }
            if (! isset($item['meta_author_url'])
                && property_exists($json, 'author')
                && property_exists($json->author, 'url')) {
                $data['meta_author_url'] = $json->author->url;
            }
            if (! isset($item['meta_publisher'])
                && property_exists($json, 'publisher')) {
                $data['meta_publisher'] = $json->publisher->name;
            }
        }

        return $data;
    }

    private function parseRepoUrl($xp, $data)
    {
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

            if ($githubLinks[$url] >= 3) {
                $data['repo_url'] = $url;
            }
        }

        return $data;
    }
}

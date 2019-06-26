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
                //$this->info($artifact->url);continue;

                $slug = str_slug($artifact->title);

                if ($artifact->section == 'articles') {
                    $type = 'article';
                } elseif ($artifact->section == 'video') {
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
                $c = file_get_contents($artifact->url);
                $d = new \DomDocument();
                $d->loadHTML($c);
                $xp = new \domxpath($d);

                $item = $this->pasreMetaData($xp, $item);
                $item = $this->parseSchema($xp, $item);
                $item = $this->parseRepoUrl($xp, $item);

                $this->info(json_encode($item));
                $this->artifactRepo->store($item, 1);
                //exit;
            }

            exit;
        }

        $this->info('Done');
    }

    private function pasreMetaData($xp, $data)
    {
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
                $url = $parse['host'] . $url;
            }

            $data['meta_author_url'] = $url;
            break;
        }

        foreach ($xp->query("//meta[@name='twitter:creator']") as $el) {
            $data['meta_twitter_creator'] = $el->getAttribute("content");
            break;
        }

        foreach ($xp->query("//meta[@name='twitter:site']") as $el) {
            $data['meta_twitter_site'] = $el->getAttribute("content");
            break;
        }

        foreach ($xp->query("//meta[@property='og:image']") as $el) {
            $image = $el->getAttribute("content");
            $this->info('image: ' . $image);
        }

        return $data;
    }

    private function parseSchema($xp, $data)
    {
        $json = $xp->query( '//script[@type="application/ld+json"]' );
        if ($json && $json->item(0)) {
            $json = trim($json->item(0)->nodeValue);
            $json = json_decode($json);

            if (! isset($item['meta_author'])) {
                $item['meta_author'] = $json->author->name;
            }
            if (! isset($item['meta_author_url'])) {
                $item['meta_author_url'] = $json->author->url;
            }
            if (! isset($item['meta_publisher'])) {
                $item['meta_publisher'] = $json->publisher->name;
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
            preg_match('/https:\/\/github.com\/(\w+)\/(\w+)/', $url, $matches);

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
            $data['repo_url'] = key($githubLinks);
        }

        return $data;
    }
}

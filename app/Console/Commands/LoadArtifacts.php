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
            $this->info(json_encode($issue));

            $link = 'https://json.flutterweekly.net/' . $issue->file;
            $artifacts = file_get_contents($link);
            $artifacts = json_decode($artifacts);
            $publishedDate = date('Y-m-d', strtotime($issue->publishedOn));

            foreach ($artifacts->articles as $artifact) {

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
                    'description' => $artifact->description,
                    'type' => $type,
                    'source_url' => $link,
                    'published_date' => $publishedDate,
                    'is_approved' => true,
                ];

                $this->artifactRepo->store($item, 1);
                exit;
            }
        }

        $this->info('Done');
    }
}

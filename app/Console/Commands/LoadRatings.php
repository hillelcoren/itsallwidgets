<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FlutterApp;

class LoadRatings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'itsallwidgets:load_ratings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load app ratings from the store';

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
        $apps = FlutterApp::orderBy('id')->where('google_url', '!=', '')->get();
        $this->info('Running...');

        foreach ($apps as $app) {
            try {
                $matches = [];
                $listing = file_get_contents($app->google_url);

                preg_match('/ratingValue" content="(.*?)"/', $listing, $matches);
                if (count($matches) >= 2) {
                    $app->store_rating = $matches[1];
                }

                preg_match('/reviewCount" content="(.*?)"/', $listing, $matches);
                if (count($matches) >= 2) {
                    $app->store_review_count = $matches[1];
                }

                preg_match('/Installs<.div><span class=".*?"><div class=".*?"><span class=".*?">(.*?)</', $listing, $matches);
                if (count($matches) >= 2) {
                    $app->store_download_count = preg_replace('/[\D]*/', '', $matches[1]);
                }

                preg_match('/store\/apps\/category\/.*?"  class=".*?">(.*?)</', $listing, $matches);
                if (count($matches) >= 2) {
                    $app->category = html_entity_decode($matches[1]);
                }

                $this->info($app->title . ' ' . $app->store_review_count . ' ' . $app->store_rating . ' ' . $app->store_download_count);
                $app->save();

                usleep(rand(1, 500) * 10000);
            } catch(\Exception $e) {
                $error = $e->getMessage();
                $this->info($app->google_url . ' FAILED: ' . $error);
                if (strpos($error, '404 Not Found') !== false) {
                    $app->google_url = null;
                    $app->save();
                }
            }
        }

        $this->info('Done');
    }
}

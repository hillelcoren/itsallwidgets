<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FlutterArtifact;
use App\Repositories\FlutterArtifactRepository;
use App\Models\User;

class ConvertToPro extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'itsallwidgets:convert_to_pro {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert users to pro';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($user = null)
    {
        parent::__construct();

        $this->user = $user;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        //$this->info('Running...');

        if ($this->user) {
            $this->convertUser($this->user);
        } else if ($this->option('all')) {
            foreach (User::all() as $user) {
                $this->convertUser($user);
            }
        }

        //$this->info('Done');
    }

    public function convertUser($user)
    {
        $user->is_pro = true;

        if (! $user->profile_key) {
            $user->profile_key = str_random(64);
        }

        if (! $user->handle) {
            $handle = str_slug($user->name, '');
            $counter = 1;

            if (User::whereHandle($handle)->count()) {
                while (User::whereHandle($handle . $counter)->count()) {
                    $counter++;
                }

                $handle = $handle . $counter;
            }

            $user->handle = $handle;
        }

        if (! $user->image_url) {
            $url = $user->avatar_url;

            if (strpos($url, '/photo.jpg?sz=50') != null) {
                $url = rtrim($url, '50') . '300';
            } else if (strpos($url, '/photo.jpg') != null) {
                $url = $url . '?sz=300';
            } else {
                $url = $url . '=s300-c';
            }

            if ($user->avatar_url && $contents = @file_get_contents($url)) {
                $output = public_path("avatars/{$user->profile_key}.png");
                imagepng(imagecreatefromstring($contents), $output);
                $user->image_url = "avatars/{$user->profile_key}.png";
            }
        }

        $user->save();
    }
}

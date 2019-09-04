<?php

namespace App\Console\Commands;

use App\Models\FlutterApp;
use App\Models\FlutterArtifact;
use App\Models\FlutterEvent;
use App\Models\UserActivity;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use App\Repositories\UserActivityRepository;
use Illuminate\Database\Eloquent\Model;

class LinkActivities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'itsallwidgets:link_activities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load artifacts from Flutter Weekly';

    protected $activityRepo;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserActivityRepository $activityRepo)
    {
        parent::__construct();

        $this->activityRepo = $activityRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Running...');

        $users = User::whereIsPro(true)->orderBy('id')->get();

        $this->linkEvents($users);
        $this->linkArtifacts($users);
        $this->linkApps($users);

        $this->info('Done');
    }

    private function linkEvents($users): void
    {
        $this->linkActivities(FlutterEvent::all(), 'flutter_event', $users);
    }

    private function linkApps($users): void
    {
        $this->linkActivities(FlutterApp::all(), 'flutter_app', $users);
    }

    private function linkArtifacts($users): void
    {
        $this->linkActivities(FlutterArtifact::all(), 'flutter_artifact', $users);
    }

    private function linkActivities($activities, $type, $users): void
    {
        $message = 'Linking Activities of ' . $type . '...';
        $this->info($message);

        $total = sizeOf($activities);
        $count = 0;

        foreach ($users as $user)
        {
            foreach ($activities as $activity)
            {
                //$this->info($type . ' activity ' . $activity->id);

                if (! $activity->matchesUser($user)) {
                    //$this->info($type . ' activity ' . $activity->id . ' not a match: skipping...');
                    continue;
                }

                if (UserActivity::where([
                    ['activity_type', '=', $type],
                    ['activity_id', '=', $activity->id],
                ])->first()) {
                    $this->info($type . ' activity ' . $activity->id . ' exists: skipping...');
                    continue;
                }

                try {
                    $this->info('LINKING');
                    $this->activityRepo->store($activity->user_id, $activity->id, $type);
                    $count++;
                } catch (Exception $e) {
                    $this->error('Error while linking artifact: ' . $activity->id);
                }
            }
        }

        $this->info($message . 'Done. Linked ' . $count . '/' . $total);
    }
}

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
    protected $description = 'Link user activities';

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

        $this->linkApps($users);
        $this->linkEvents($users);
        $this->linkArtifacts($users);

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
                if (! $user->isActivityTypeActive($type)) {
                    continue;
                }

                if (! $activity->matchesUser($user)) {
                    continue;
                }

                if (UserActivity::where([
                    ['user_id', '=', $user->id],
                    ['activity_type', '=', $type],
                    ['activity_id', '=', $activity->id],
                ])->first()) {
                    $this->info($type . ' activity ' . $activity->id . ' exists: skipping...');
                    continue;
                }

                $this->info('LINKING');

                if ($type == 'flutter_app') {
                    $user->count_apps++;
                } else if ($type == 'flutter_event') {
                    $user->count_events++;
                } else {
                    $user->count_artifacts++;
                }

                $user->last_activity = date('Y-m-d');
                $user->save();

                $this->activityRepo->store($user->id, $activity->id, $type);
                $count++;
            }
        }

        $this->info($message . 'Done. Linked ' . $count . '/' . $total);
    }
}

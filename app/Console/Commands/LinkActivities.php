<?php

namespace App\Console\Commands;

use App\Models\FlutterApp;
use App\Models\FlutterArtifact;
use App\Models\FlutterEvent;
use App\Models\UserActivity;
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

        $this->linkEvents();
        $this->linkArtifacts();
        $this->linkApps();

        $this->info('Done');
    }

    private function linkEvents(): void
    {
        $this->linkActivities(FlutterEvent::all(), 'flutter_event');
    }

    private function linkApps(): void
    {
        $this->linkActivities(FlutterApp::all(), 'flutter_app');
    }

    private function linkArtifacts(): void
    {
        $this->linkActivities(FlutterArtifact::all(), 'flutter_artifact');
    }

    private function linkActivities($activities, $type): void
    {
        $message = 'Linking Activities of ' . $type . '...';
        $this->info($message);

        $total = sizeOf($activities);
        $count = 0;
        foreach ($activities as $activity) {
            if (UserActivity::where([
                ['activity_type', '=', $type],
                ['activity_id', '=', $activity->id],
            ])->first()) {
                $this->info($type . ' activity ' . $activity->id . ' exists: skipping...');
                continue;
            }
            try {
                $this->activityRepo->store($activity->user_id, $activity->id, $type);
                $count++;
            } catch (Exception $e) {
                $this->error('Error while linking artifact: ' . $activity->id);
            }
        }

        $this->info($message . 'Done. Linked ' . $count . '/' . $total);
    }

}
